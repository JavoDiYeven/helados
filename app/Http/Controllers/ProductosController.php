<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Productos;
use Illuminate\Support\Facades\Storage;

class ProductosController extends Controller
{
    Public function index()
    {
        //Backend
        return view('backend.productos', compact('productos'));

    }

    Public function create()
    {
        //Backend
        return view('backend.productos.create');
    }

    Public function store(Request $request)
    {
        $data = $request->validate([
            'codigo' => 'required|string|unique:productos,codigo',
            'nombre' => 'required|string',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric',
            'stock' => 'required|integer',
            'imagen' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $producto = Productos::create($data);
        return response()->json($producto);
    }

    Public function show(Productos $producto){
        return view('backend.productos.show', compact('producto'));
    }

    Public function edit(Productos $producto)
    {
        return view('backend.productos', compact('producto'));
    }

    Public function update(Request $request, Productos $producto)
    {
        $producto = Productos::findOrFail($id);
        $data = $request->validate([
            'codigo' => 'required|string|unique:productos,codigo,' . $id,
            'nombre' => 'required|string',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric',
            'stock' => 'required|integer',
            'imagen' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('imagen')) {
            Storage::delete('public/' . $producto->imagen);
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $producto->update($data);
        return response()->json($producto);
    }

    Public function destroy(Productos $producto)
    {
        $producto = Productos::findOrFail($id);
        if ($producto->imagen) {
            Storage::delete('public/' . $producto->imagen);
        }
        $producto->delete();
        return response()->json(['message' => 'Producto eliminado']);
    }


}
