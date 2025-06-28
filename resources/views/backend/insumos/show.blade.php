@extends('backend.insumos.layout')

@section('content')

<div class="card mt-5">
  <h2 class="card-header">Ver insumo</h2>
  <div class="card-body">

    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        <a class="btn btn-primary btn-sm" href="{{ route('insumos.index') }}"><i class="fa fa-arrow-left"></i> volver</a>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Nombre:</strong> <br/>
                {{ $insumo->nombre }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
            <div class="form-group">
                <strong>Descripción:</strong> <br/>
                {{ $insumo->descripcion }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
            <div class="form-group">
                <strong>Unidad de medida:</strong> <br/>
                {{ $insumo->unidad_medida }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
            <div class="form-group">
                <strong>Stock:</strong> <br/>
                {{ $insumo->stock }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
            <div class="form-group">
                <strong>Creado:</strong> <br/>
                {{ $insumo->created_at }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
            <div class="form-group">
                <strong>Ultimo cambio:</strong> <br/>
                {{ $insumo->updated_at }}
            </div>
        </div>
    </div>

  </div>
</div>
@endsection