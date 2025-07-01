<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Producto;
use App\Models\Venta;
use App\Models\DetalleVenta;

class VentaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'numero_pedido' => 'required|string|max:255',
            'cliente_nombre' => 'required|string|max:255',
            'cliente_telefono' => 'required|string|max:20',
            'cliente_email' => 'nullable|email',
            'direccion_entrega' => 'required|string',
            'fecha_entrega' => 'required|date',
            'hora_entrega' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:productos,id',
            'items.*.quantity' => 'required|integer|min:1',
            'subtotal' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();

            // Crear la venta
            $venta = Venta::create([
                'numero_pedido' => $request->numero_pedido,
                'cliente_nombre' => $request->cliente_nombre,
                'cliente_telefono' => $request->cliente_telefono,
                'cliente_email' => $request->cliente_email,
                'direccion_entrega' => $request->direccion_entrega,
                'fecha_entrega' => $request->fecha_entrega,
                'hora_entrega' => $request->hora_entrega,
                'instrucciones_especiales' => $request->instrucciones_especiales,
                'subtotal' => $request->subtotal,
                'costo_envio' => 25.00,
                'total' => $request->total,
                'fecha_venta' => now()
            ]);

            // Crear los detalles y actualizar stock
            foreach ($request->items as $item) {
                $producto = Producto::findOrFail($item['id']);
                
                // Verificar stock disponible
                if ($producto->stock < $item['quantity']) {
                    throw new \Exception("Stock insuficiente para {$producto->nombre}");
                }

                // Crear detalle de venta
                DetalleVenta::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $producto->id,
                    'producto_nombre' => $producto->nombre,
                    'cantidad' => $item['quantity'],
                    'precio_unitario' => $producto->precio,
                    'subtotal' => $producto->precio * $item['quantity']
                ]);

                // Actualizar stock
                $producto->decrement('stock', $item['quantity']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Venta registrada exitosamente',
                'venta_id' => $venta->id
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la venta: ' . $e->getMessage()
            ], 400);
        }
    }
}
