@extends('backend.layouts.app')
@section('title', 'Admin Productos')
@section('content')
<div class="card mt-5">
    <h2>Listado de productos</h2>
    <div class="card-body">

        @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
        @endif

        <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-3">
            <a href="{{ route('productos.create') }}" class="btn btn-success btn-sm">Crear producto</a>
        </div>

        <table class="table table-bordered table-striped mt-4">
            <thead>
                <tr>
                    <th width="80px">COD</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th width="250px">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($productos as $producto)
                <tr>
                    <td>{{ $producto->id }}</td>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->descripcion }}</td>
                    <td>{{ $producto->precio }}</td>
                    <td>{{ $producto->stock }}</td>
                    <td>
                        <form action="{{ route('productos.destroy', $producto->id) }}" method="POST">
                            <a class="btn btn-info btn-sm" href="{{ route('productos.show',$producto->id) }}"><i class="fa-solid fa-list"></i> Ver</a>
                            <a class="btn btn-primary btn-sm" href="{{ route('productos.edit',$producto->id) }}"><i class="fa-solid fa-pen-to-square"></i> Editar</a>
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i>Eliminar</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">No hay productos registrados</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        {{ $productos->links() }}
    </div>
</div>
@endsection