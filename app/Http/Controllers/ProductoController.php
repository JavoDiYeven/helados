<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use Illuminate\View\View;

class ProductoController extends Controller
{
    public function index(): View
    {
        $productos = Producto::paginate(10);
        return view('backend.productos.index', compact('productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric',
            'stock' => 'required|integer',
            'categoria' => 'required|string|max:255',
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $producto = new Producto();
        $producto->nombre = $request->nombre;
        $producto->descripcion = $request->descripcion;
        $producto->precio = $request->precio;
        $producto->stock = $request->stock;
        $producto->categoria = $request->categoria ?? 'cremoso'; // Valor por defecto
        $producto->stock_minimo = $request->stock_minimo ?? 10; 

        if ($request->hasFile('imagen')) {
            $producto->imagen = $request->file('imagen')->store('productos', 'public');
        }

        $producto->save();

        return redirect()->route('productos.index')->with('success', 'Producto creado exitosamente.');
    }

    public function show(Producto $producto): View
    {
        return view('backend.productos.show', compact('producto'));
    }

    public function edit(Producto $producto): View
    {
        return view('backend.productos.edit', compact('producto'));
    }

    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric',
            'stock' => 'required|integer',
            'categoria' => 'required|string|max:255',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $producto->nombre = $request->nombre;
        $producto->descripcion = $request->descripcion;
        $producto->precio = $request->precio;
        $producto->stock = $request->stock;
        $producto->categoria = $request->categoria;

        if ($request->hasFile('imagen')) {
            $producto->imagen = $request->file('imagen')->store('productos', 'public');
        }

        $producto->save();

        return redirect()->route('productos.index')->with('success', 'Producto actualizado correctamente');
    }

    public function destroy(Producto $producto)
    {
        $producto->delete();
        return redirect()->route('productos.index')->with('success', 'Producto eliminado exitosamente.');
    }

    public function create(): View
    {
        return view('backend.productos.create');
    }

    // API para el frontend - CORREGIDA
    public function apiIndex()
    {
        try {
            $productos = Producto::all();
            
            $productosTransformados = $productos->map(function ($producto) {
                return [
                    'id' => $producto->id,
                    'nombre' => $producto->nombre,
                    'descripcion' => $producto->descripcion,
                    'precio' => (float) $producto->precio,
                    'stock' => (int) $producto->stock,
                    'categoria' => $producto->categoria ?? 'cremoso',
                    'imagen' => $producto->imagen 
                        ? asset('storage/' . $producto->imagen)
                        : '/placeholder.svg?height=300&width=300',
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Productos obtenidos correctamente',
                'data' => $productosTransformados
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener productos: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }
}