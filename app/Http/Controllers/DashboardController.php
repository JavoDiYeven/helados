<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Venta;
use App\Models\DetalleVenta;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Datos generales
        $ventasDelMes = Venta::delMes()->count();
        $ingresosMes = Venta::delMes()->entregadas()->sum('total');
        $productosVendidosMes = DetalleVenta::whereHas('venta', function($query) {
            $query->delMes()->entregadas();
        })->sum('cantidad');

        // Productos con stock bajo (≤ 10)
        $productosStockBajo = Producto::where('stock', '<=', 10)->where('stock', '>', 0)->get();
        $productosSinStock = Producto::where('stock', 0)->get();

        // Gráfico de ventas del mes (por días)
        $ventasPorDia = $this->getVentasPorDia();

        // Top 10 productos más vendidos
        $topProductos = $this->getTopProductos();

        // Ventas recientes
        $ventasRecientes = Venta::with('detalles')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('backend.dashboard.index', compact(
            'ventasDelMes',
            'ingresosMes',
            'productosVendidosMes',
            'productosStockBajo',
            'productosSinStock',
            'ventasPorDia',
            'topProductos',
            'ventasRecientes'
        ));
    }

    private function getVentasPorDia()
    {
        $inicioMes = now()->startOfMonth();
        $finMes = now()->endOfMonth();
        
        $ventas = Venta::selectRaw('DATE(fecha_venta) as fecha, COUNT(*) as total_ventas, SUM(total) as total_ingresos')
            ->whereBetween('fecha_venta', [$inicioMes, $finMes])
            ->where('estado', 'entregado')
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();

        // Llenar días sin ventas con 0
        $diasDelMes = [];
        $ingresosDelMes = [];
        
        for ($dia = 1; $dia <= now()->daysInMonth; $dia++) {
            $fecha = now()->startOfMonth()->addDays($dia - 1)->format('Y-m-d');
            $ventaDelDia = $ventas->firstWhere('fecha', $fecha);
            
            $diasDelMes[] = $dia;
            $ingresosDelMes[] = $ventaDelDia ? $ventaDelDia->total_ingresos : 0;
        }

        return [
            'dias' => $diasDelMes,
            'ingresos' => $ingresosDelMes
        ];
    }

    private function getTopProductos()
    {
        return DetalleVenta::select('producto_nombre', DB::raw('SUM(cantidad) as total_vendido'), DB::raw('SUM(subtotal) as total_ingresos'))
            ->whereHas('venta', function($query) {
                $query->where('estado', 'entregado');
            })
            ->groupBy('producto_id', 'producto_nombre')
            ->orderBy('total_vendido', 'desc')
            ->limit(10)
            ->get();
    }

    public function reporteVentasMes(Request $request)
    {
        $mes = $request->get('mes', now()->month);
        $año = $request->get('año', now()->year);
        
        $ventas = Venta::with('detalles')
            ->delMes($mes, $año)
            ->entregadas()
            ->orderBy('fecha_venta', 'desc')
            ->get();

        $resumen = [
            'total_ventas' => $ventas->count(),
            'total_ingresos' => $ventas->sum('total'),
            'total_productos_vendidos' => $ventas->sum(function($venta) {
                return $venta->detalles->sum('cantidad');
            }),
            'ticket_promedio' => $ventas->count() > 0 ? $ventas->avg('total') : 0
        ];

        return view('backend.dashboard.ventas', compact('ventas', 'resumen', 'mes', 'año'));
    }

    public function reporteProductos()
    {
        $productosReporte = DetalleVenta::select(
                'producto_id',
                'producto_nombre',
                DB::raw('SUM(cantidad) as total_vendido'),
                DB::raw('SUM(subtotal) as total_ingresos'),
                DB::raw('AVG(precio_unitario) as precio_promedio')
            )
            ->whereHas('venta', function($query) {
                $query->where('estado', 'entregado');
            })
            ->groupBy('producto_id', 'producto_nombre')
            ->orderBy('total_ingresos', 'desc')
            ->get();

        return view('backend.dashboard.productos', compact('productosReporte'));
    }

    public function getNotificaciones()
    {
        $productosStockBajo = Producto::where('stock', '<=', 10)->where('stock', '>', 0)->count();
        $productosSinStock = Producto::where('stock', 0)->count();
        $ventasPendientes = Venta::where('estado', 'pendiente')->count();

        return response()->json([
            'stock_bajo' => $productosStockBajo,
            'sin_stock' => $productosSinStock,
            'ventas_pendientes' => $ventasPendientes
        ]);
    }
}
