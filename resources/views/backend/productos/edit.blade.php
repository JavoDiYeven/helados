@extends('backend.productos.layout')

@section('content')

<div class="card mt-5">
  <h2 class="card-header">Edit Product</h2>
  <div class="card-body">

    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        <a class="btn btn-primary btn-sm" href="{{ route('productos.index') }}"><i class="fa fa-arrow-left"></i> Volver</a>
    </div>

    <form action="{{ route('productos.update', $producto->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @if($producto->imagen)
            <div class="mb-3">
                <img src="{{ asset('storage/' . $producto->imagen) }}" alt="Imagen del producto" class="img-thumbnail mb-2" style="max-width: 200px;">
            </div>
        @endif
        <label for="nombre" class="form-label"><strong>Nombre:</strong></label>
        <input type="text" name="nombre" class="form-control" value="{{ $producto->nombre }}" required>
        <label for="imagen" class="form-label"><strong>Imagen:</strong></label>
        <input type="file" name="imagen" class="form-control" value="{{ $producto->imagen }}" required>
        <label for="descripcion" class="form-label"><strong>Descripción:</strong></label>
        <input type="text" name="descripcion" class="form-control" value="{{ $producto->descripcion }}" required>
        <label for="precio" class="form-label"><strong>Precio:</strong></label>
        <input type="number" name="precio" class="form-control" value="{{ $producto->precio }}" required>
        <label for="stock" class="form-label"><strong>Stock:</strong></label>
        <input type="number" name="stock" class="form-control" value="{{ $producto->stock }}" required>
        <button type="submit" class="btn btn-success"><i class="fa-solid fa-floppy-disk"></i> Actualizar</button>
    </form>

  </div>
</div>
@endsection