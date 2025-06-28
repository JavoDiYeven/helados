<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Insumo;
use Illuminate\View\View;

class InsumoController extends Controller
{
    public function index(){
        $insumos = Insumo::paginate(5);
        return view('backend.insumos.index', compact('insumos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:insumos',
            'descripcion' => 'nullable|string',
            'unidad_medida' => 'required|string|max:50',
            'stock_actual' => 'required|numeric|min:0',
            'stock_minimo' => 'required|numeric|min:0',
        ]);

        $insumo = new Insumo();
        $insumo->nombre = $request->nombre;
        $insumo->descripcion = $request->descripcion;
        $insumo->unidad_medida = $request->unidad_medida;
        $insumo->stock_actual = $request->stock_actual;
        $insumo->stock_minimo = $request->stock_minimo;
        $insumo->save();

        return redirect()->route('insumos.index')->with('success', 'Insumo creado exitosamente.');
    }

    public function update(Request $request, Insumo $insumo)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:insumos,nombre,' . $insumo->id,
            'descripcion' => 'nullable|string',
            'unidad_medida' => 'required|string|max:50',
            'stock_actual' => 'required|numeric|min:0',
            'stock_minimo' => 'required|numeric|min:0',
        ]);

        $insumo->nombre = $request->nombre;
        $insumo->descripcion = $request->descripcion;
        $insumo->unidad_medida = $request->unidad_medida;
        $insumo->stock_actual = $request->stock_actual;
        $insumo->stock_minimo = $request->stock_minimo;
        $insumo->save();

        return redirect()->route('insumos.index')->with('success', 'Insumo actualizado exitosamente.');
    }

    public function show(Insumo $insumo): View
    {
        return view('backend.insumos.show', compact('insumo'));
    }
    public function edit(Insumo $insumo): View
    {
        return view('backend.insumos.edit', compact('insumo'));
    }
    public function create(): View
    {
        return view('backend.insumos.create');
    }
    public function destroy(Insumo $insumo)
    {
        $insumo->delete();
        return redirect()->route('insumos.index')->with('success', 'Insumo eliminado exitosamente.');
    }
}
