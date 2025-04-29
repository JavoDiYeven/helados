@extends('layouts.app')

@section('title', 'Catálogo de Helados')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="display-4 text-primary">Nuestros Deliciosos Helados</h1>
            <p class="lead">Descubre nuestra variedad de sabores y productos</p>
        </div>
        <div class="col-md-4">
            <div class="input-group mt-3">
                <input type="text" id="searchInput" class="form-control" placeholder="Buscar productos...">
                <button class="btn btn-primary" type="button">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-primary active filter-btn" data-filter="all">Todos</button>
                @foreach($categories as $category)
                    <button type="button" class="btn btn-outline-primary filter-btn" data-filter="{{ $category->id }}">
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    <div class="row" id="products-container">
        @forelse($products as $product)
            <div class="col-md-6 col-lg-4 mb-4 product-item" data-category="{{ $product->category_id }}">
                <div class="card product-card h-100">
                    <div class="product-img-container">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" class="product-img" alt="{{ $product->name }}">
                        @else
                            <img src="https://via.placeholder.com/300x200?text=Sin+Imagen" class="product-img" alt="{{ $product->name }}">
                        @endif
                        <div class="product-price">${{ number_format($product->price, 2) }}</div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text">{{ $product->description }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">Categoría: {{ $product->category->name }}</small>
                            <span class="badge bg-success">Disponible</span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    No hay productos disponibles en este momento.
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Filtrado por categoría
        $('.filter-btn').click(function() {
            $('.filter-btn').removeClass('active');
            $(this).addClass('active');
            
            var filterValue = $(this).data('filter');
            
            if (filterValue === 'all') {
                $('.product-item').show();
            } else {
                $('.product-item').hide();
                $('.product-item[data-category="' + filterValue + '"]').show();
            }
        });
        
        // Búsqueda
        $('#searchInput').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('.product-item').filter(function() {
                var text = $(this).text().toLowerCase();
                $(this).toggle(text.indexOf(value) > -1);
            });
        });
        
        // Animación al cargar la página
        $('.product-card').each(function(index) {
            $(this).delay(100 * index).animate({opacity: 1}, 500);
        });
    });
</script>
@endsection
