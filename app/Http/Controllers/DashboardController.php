<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Venta;
use App\Models\DetalleVenta;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Dashboard principal
     */
    public function index()
    {
        try {
            // Estadísticas básicas
            $ventasDelMes = Venta::whereMonth('created_at', now()->month)
                                ->whereYear('created_at', now()->year)
                                ->count();

            $ingresosMes = Venta::whereMonth('created_at', now()->month)
                               ->whereYear('created_at', now()->year)
                               ->sum('total');

            $productosVendidosMes = DetalleVenta::whereHas('venta', function($query) {
                                        $query->whereMonth('created_at', now()->month)
                                              ->whereYear('created_at', now()->year);
                                    })->sum('cantidad');

            // Productos con alertas de stock
            $productosStockBajo = Producto::where('stock', '<=', 10)
                                        ->where('stock', '>', 0)
                                        ->get();

            $productosSinStock = Producto::where('stock', 0)->get();

            // Top productos más vendidos
            $topProductos = DB::table('venta_detalles')
                ->join('productos', 'venta_detalles.producto_id', '=', 'productos.id')
                ->join('ventas', 'venta_detalles.venta_id', '=', 'ventas.id')
                ->whereMonth('ventas.created_at', now()->month)
                ->whereYear('ventas.created_at', now()->year)
                ->select(
                    'productos.id as producto_id',
                    'productos.nombre as producto_nombre',
                    DB::raw('SUM(venta_detalles.cantidad) as total_vendido'),
                    DB::raw('SUM(venta_detalles.cantidad * venta_detalles.precio_unitario) as total_ingresos')
                )
                ->groupBy('productos.id', 'productos.nombre')
                ->orderBy('total_vendido', 'desc')
                ->limit(10)
                ->get();

            // Ventas recientes
            $ventasRecientes = Venta::with('detalles')
                                   ->orderBy('created_at', 'desc')
                                   ->limit(5)
                                   ->get();

            // Datos para gráfico
            $ventasPorDia = $this->getVentasPorDia();

            return view('backend.dashboard.index', compact(
                'ventasDelMes',
                'ingresosMes', 
                'productosVendidosMes',
                'productosStockBajo',
                'productosSinStock',
                'topProductos',
                'ventasRecientes',
                'ventasPorDia'
            ));

        } catch (\Exception $e) {
            Log::error('Error en dashboard: ' . $e->getMessage());
            
            // Datos por defecto en caso de error
            return view('backend.dashboard.index', [
                'ventasDelMes' => 0,
                'ingresosMes' => 0,
                'productosVendidosMes' => 0,
                'productosStockBajo' => collect(),
                'productosSinStock' => collect(),
                'topProductos' => collect(),
                'ventasRecientes' => collect(),
                'ventasPorDia' => ['dias' => [], 'ingresos' => []]
            ]);
        }
    }

    /**
     * Reporte de ventas
     */
    public function reporteVentas(Request $request)
    {
        $mes = $request->get('mes', now()->month);
        $año = $request->get('año', now()->year);

        $ventas = Venta::with('detalles')
                      ->whereMonth('created_at', $mes)
                      ->whereYear('created_at', $año)
                      ->orderBy('created_at', 'desc')
                      ->get();

        $resumen = [
            'total_ventas' => $ventas->count(),
            'total_ingresos' => $ventas->sum('total'),
            'total_productos_vendidos' => $ventas->sum(function($venta) {
                return $venta->detalles->sum('cantidad');
            }),
            'ticket_promedio' => $ventas->count() > 0 ? $ventas->sum('total') / $ventas->count() : 0
        ];

        return view('backend.dashboard.ventas', compact('ventas', 'resumen', 'mes', 'año'));
    }

    /**
     * Reporte de productos
     */
    public function reporteProductos()
    {
        $productosReporte = DB::table('venta_detalles')
            ->join('productos', 'venta_detalles.producto_id', '=', 'productos.id')
            ->select(
                'productos.id as producto_id',
                'productos.nombre as producto_nombre',
                DB::raw('SUM(venta_detalles.cantidad) as total_vendido'),
                DB::raw('AVG(venta_detalles.precio_unitario) as precio_promedio'),
                DB::raw('SUM(venta_detalles.cantidad * venta_detalles.precio_unitario) as total_ingresos')
            )
            ->groupBy('productos.id', 'productos.nombre')
            ->orderBy('total_vendido', 'desc')
            ->get();

        return view('backend.dashboard.productos', compact('productosReporte'));
    }

    /**
     * Notificaciones AJAX
     */
    public function getNotificaciones()
    {
        $stockBajo = Producto::where('stock', '<=', 10)->where('stock', '>', 0)->count();
        $sinStock = Producto::where('stock', 0)->count();
        $ventasPendientes = Venta::where('estado', 'pendiente')->count();

        return response()->json([
            'success' => true,
            'stock_bajo' => $stockBajo,
            'sin_stock' => $sinStock,
            'ventas_pendientes' => $ventasPendientes,
            'stock_alerts' => $stockBajo + $sinStock
        ]);
    }

    /**
     * API Stats para administradores
     */
    public function apiStats()
    {
        return response()->json([
            'success' => true,
            'stats' => [
                'ventas_mes' => Venta::whereMonth('created_at', now()->month)->count(),
                'ingresos_mes' => Venta::whereMonth('created_at', now()->month)->sum('total'),
                'productos_total' => Producto::count(),
                'stock_bajo' => Producto::where('stock', '<=', 10)->count()
            ]
        ]);
    }

    /**
     * Obtener ventas por día del mes actual
     */
    private function getVentasPorDia()
    {
        $diasDelMes = now()->daysInMonth;
        $dias = [];
        $ingresos = [];

        for ($i = 1; $i <= $diasDelMes; $i++) {
            $fecha = Carbon::create(now()->year, now()->month, $i);
            $dias[] = $i;
            
            $ingresosDia = Venta::whereDate('created_at', $fecha)->sum('total');
            $ingresos[] = (float) $ingresosDia;
        }

        return [
            'dias' => $dias,
            'ingresos' => $ingresos
        ];
    }
}
