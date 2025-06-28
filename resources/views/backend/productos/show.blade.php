@extends('backend.productos.layout')

@section('content')

<div class="card mt-5">
  <h2 class="card-header">Ver Producto</h2>
  <div class="card-body">

    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        <a class="btn btn-primary btn-sm" href="{{ route('productos.index') }}"><i class="fa fa-arrow-left"></i> volver</a>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Nombre:</strong> <br/>
                {{ $producto->nombre }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Imagen:</strong> <br/>
                @if($producto->imagen)
                    <img src="{{ asset('storage/' . $producto->imagen) }}" alt="Product Image" class="img-thumbnail mb-2" style="max-width: 200px;">
                @else
                    <p>No image available</p>
                @endif
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
            <div class="form-group">
                <strong>Descripción:</strong> <br/>
                {{ $producto->descripcion }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
            <div class="form-group">
                <strong>Precio:</strong> <br/>
                {{ $producto->precio }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
            <div class="form-group">
                <strong>Stock:</strong> <br/>
                {{ $producto->stock }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
            <div class="form-group">
                <strong>Creado:</strong> <br/>
                {{ $producto->created_at }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
            <div class="form-group">
                <strong>Ultimo cambio:</strong> <br/>
                {{ $producto->updated_at }}
            </div>
        </div>
    </div>

  </div>
</div>
@endsection