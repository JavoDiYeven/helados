@extends('backend.layouts.app')
@section('title', 'Admin - Crear Productos')

@section('content')

<div class="card mt-5">
  <h2 class="card-header">Crear producto</h2>
  <div class="card-body">

    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        <a class="btn btn-primary btn-sm" href="{{ route('productos.index') }}"><i class="fa fa-arrow-left"></i> Volver</a>
    </div>

    <form action="{{ route('productos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="nombre" class="form-label"><strong>Nombre:</strong></label>
        <input type="text" name="nombre" class="form-control" required>
        <label for="imagen" class="form-label"><strong>Imagen:</strong></label>
        <input type="file" name="imagen" class="form-control" required>
        <label for="descripcion" class="form-label"><strong>Descripción:</strong></label>
        <input type="text" name="descripcion" class="form-control" required>
        <label for="precio" class="form-label"><strong>Precio:</strong></label>
        <input type="number" name="precio" class="form-control" required>
        <label for="stock" class="form-label"><strong>Stock:</strong></label>
        <input type="number" name="stock" class="form-control" required>
        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
  </div>
</div>
@endsection