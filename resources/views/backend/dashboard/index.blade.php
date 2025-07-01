@extends('backend.layouts.app')

@section('title', 'Dashboard Principal')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('page-title', '📊 Dashboard Principal')
@section('page-description', 'Resumen general de tu negocio')

@section('page-actions')
    <div class="btn-toolbar">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-outline-secondary" onclick="refreshData()">
                <i class="fas fa-sync-alt"></i> Actualizar
            </button>
        </div>
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle position-relative" type="button" id="notificationsDropdown" data-bs-toggle="dropdown">
                <i class="fas fa-bell"></i> Notificaciones
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge" id="notification-count">
                    {{ $productosStockBajo->count() + $productosSinStock->count() }}
                </span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" id="notifications-list">
                @if($productosSinStock->count() > 0)
                    @foreach($productosSinStock as $producto)
                        <li>
                            <a class="dropdown-item text-danger" href="{{ route('productos.edit', $producto->id) }}">
                                <i class="fas fa-exclamation-circle"></i> {{ $producto->nombre }} - Sin stock
                            </a>
                        </li>
                    @endforeach
                @endif
                @if($productosStockBajo->count() > 0)
                    @foreach($productosStockBajo as $producto)
                        <li>
                            <a class="dropdown-item text-warning" href="{{ route('productos.edit', $producto->id) }}">
                                <i class="fas fa-exclamation-triangle"></i> {{ $producto->nombre }} - Stock: {{ $producto->stock }}
                            </a>
                        </li>
                    @endforeach
                @endif
                @if($productosStockBajo->count() == 0 && $productosSinStock->count() == 0)
                    <li><a class="dropdown-item text-success">✅ Todo el stock está bien</a></li>
                @endif
            </ul>
        </div>
    </div>
@endsection

@section('content')
    <!-- Cards de estadísticas -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card card-stats border-primary h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Ventas del Mes</h5>
                            <span class="h2 font-weight-bold mb-0">{{ $ventasDelMes }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-primary text-white rounded-circle shadow d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="fas fa-shopping-cart fa-2x"></i>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-muted text-sm">
                        <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 12%</span>
                        <span class="text-nowrap">Desde el mes pasado</span>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card card-stats border-success h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Ingresos del Mes</h5>
                            <span class="h2 font-weight-bold mb-0">${{ number_format($ingresosMes, 2) }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-success text-white rounded-circle shadow d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="fas fa-dollar-sign fa-2x"></i>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-muted text-sm">
                        <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 8%</span>
                        <span class="text-nowrap">Desde el mes pasado</span>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card card-stats border-info h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Productos Vendidos</h5>
                            <span class="h2 font-weight-bold mb-0">{{ $productosVendidosMes }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-info text-white rounded-circle shadow d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="fas fa-ice-cream fa-2x"></i>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-muted text-sm">
                        <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 15%</span>
                        <span class="text-nowrap">Desde el mes pasado</span>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card card-stats border-warning h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Alertas de Stock</h5>
                            <span class="h2 font-weight-bold mb-0">{{ $productosStockBajo->count() + $productosSinStock->count() }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-warning text-white rounded-circle shadow d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="fas fa-exclamation-triangle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-muted text-sm">
                        <span class="text-danger mr-2"><i class="fa fa-arrow-down"></i> 3</span>
                        <span class="text-nowrap">Productos críticos</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos y contenido principal -->
    <div class="row">
        <!-- Gráfico de Ventas -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">📈 Ingresos Diarios del Mes</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end shadow">
                            <div class="dropdown-header">Opciones de Reporte:</div>
                            <a class="dropdown-item" href="{{ route('admin.reporte-ventas') }}">
                                <i class="fas fa-file-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                Ver Reporte Completo
                            </a>
                            <a class="dropdown-item" href="{{ route('admin.reporte-ventas', ['mes' => now()->subMonth()->month]) }}">
                                <i class="fas fa-calendar fa-sm fa-fw mr-2 text-gray-400"></i>
                                Mes Anterior
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#" onclick="exportChart()">
                                <i class="fas fa-download fa-sm fa-fw mr-2 text-gray-400"></i>
                                Exportar Gráfico
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="ventasChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel de Alertas de Stock -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">🚨 Centro de Alertas</h6>
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    @if($productosSinStock->count() > 0)
                        <div class="alert alert-danger alert-no-stock">
                            <h6 class="alert-heading">❌ Productos Sin Stock</h6>
                            @foreach($productosSinStock as $producto)
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <strong>{{ $producto->nombre }}</strong><br>
                                        <small class="text-muted">Stock: {{ $producto->stock }} unidades</small>
                                    </div>
                                    <a href="{{ route('productos.edit', $producto->id) }}" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                                @if(!$loop->last)<hr class="my-2">@endif
                            @endforeach
                        </div>
                    @endif

                    @if($productosStockBajo->count() > 0)
                        <div class="alert alert-warning alert-stock">
                            <h6 class="alert-heading">⚠️ Stock Bajo (≤10 unidades)</h6>
                            @foreach($productosStockBajo as $producto)
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <strong>{{ $producto->nombre }}</strong><br>
                                        <small class="text-muted">Stock: {{ $producto->stock }} unidades</small>
                                    </div>
                                    <a href="{{ route('productos.edit', $producto->id) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                                @if(!$loop->last)<hr class="my-2">@endif
                            @endforeach
                        </div>
                    @endif

                    @if($productosStockBajo->count() == 0 && $productosSinStock->count() == 0)
                        <div class="text-center text-success py-4">
                            <i class="fas fa-check-circle fa-3x mb-3"></i>
                            <h5>¡Excelente!</h5>
                            <p class="mb-0">Todo el inventario está en niveles óptimos</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Segunda fila de contenido -->
    <div class="row">
        <!-- Top 10 Productos Más Vendidos -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">🏆 Top 10 Productos Más Vendidos</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Producto</th>
                                    <th>Vendidos</th>
                                    <th>Ingresos</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topProductos as $index => $producto)
                                    <tr>
                                        <td>
                                            @if($index < 3)
                                                <span class="badge bg-{{ $index == 0 ? 'warning' : ($index == 1 ? 'secondary' : 'dark') }}">
                                                    {{ $index + 1 }}
                                                </span>
                                            @else
                                                <span class="badge bg-light text-dark">{{ $index + 1 }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $producto->producto_nombre }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $producto->total_vendido }}</span>
                                        </td>
                                        <td class="text-success font-weight-bold">
                                            ${{ number_format($producto->total_ingresos, 2) }}
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-info" onclick="verDetalleProducto({{ $producto->producto_id ?? 0 }})">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.reporte-productos') }}" class="btn btn-primary">
                            <i class="fas fa-chart-bar"></i> Ver Reporte Completo
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ventas Recientes -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">📋 Ventas Recientes</h6>
                </div>
                <div class="card-body">
                    @foreach($ventasRecientes as $venta)
                        <div class="d-flex align-items-center border-bottom pb-3 mb-3">
                            <div class="flex-shrink-0">
                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fas fa-shopping-cart text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="d-flex justify-content-between">
                                    <h6 class="mb-0">{{ $venta->numero_pedido }}</h6>
                                    <span class="badge bg-{{ $venta->estado == 'entregado' ? 'success' : ($venta->estado == 'pendiente' ? 'warning' : 'info') }}">
                                        {{ ucfirst(str_replace('_', ' ', $venta->estado)) }}
                                    </span>
                                </div>
                                <p class="text-muted mb-0">{{ $venta->cliente_nombre }}</p>
                                <small class="text-muted">
                                    ${{ number_format($venta->total, 2) }} • {{ $venta->created_at->diffForHumans() }}
                                </small>
                            </div>
                        </div>
                    @endforeach
                    <div class="text-center">
                        <button class="btn btn-outline-primary btn-sm" onclick="showVentas()">
                            <i class="fas fa-list"></i> Ver Todas las Ventas
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Panel de Acciones Rápidas -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">⚡ Acciones Rápidas</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('productos.create') }}" class="btn btn-success btn-lg w-100">
                                <i class="fas fa-plus-circle"></i><br>
                                <small>Nuevo Producto</small>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.reporte-ventas') }}" class="btn btn-info btn-lg w-100">
                                <i class="fas fa-chart-line"></i><br>
                                <small>Reporte de Ventas</small>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.reporte-productos') }}" class="btn btn-warning btn-lg w-100">
                                <i class="fas fa-ice-cream"></i><br>
                                <small>Análisis de Productos</small>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <button class="btn btn-secondary btn-lg w-100" onclick="exportarDatos()">
                                <i class="fas fa-download"></i><br>
                                <small>Exportar Datos</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // Configuración del gráfico de ventas
    const ctx = document.getElementById('ventasChart').getContext('2d');
    const ventasChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($ventasPorDia['dias']) !!},
            datasets: [{
                label: 'Ingresos Diarios ($)',
                data: {!! json_encode($ventasPorDia['ingresos']) !!},
                borderColor: 'rgb(54, 162, 235)',
                backgroundColor: 'rgba(54, 162, 235, 0.1)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: 'rgb(54, 162, 235)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Tendencia de Ingresos - {{ now()->format("F Y") }}',
                    font: {
                        size: 16
                    }
                },
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    },
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });

    // Funciones JavaScript
    function refreshData() {
        location.reload();
    }

    function showVentas() {
        showNotification('Módulo de ventas en desarrollo', 'info');
    }

    function verDetalleProducto(id) {
        if (id > 0) {
            window.location.href = `/productos/${id}`;
        } else {
            showNotification('Producto no encontrado', 'warning');
        }
    }

    function exportChart() {
        const link = document.createElement('a');
        link.download = 'grafico-ventas.png';
        link.href = ventasChart.toBase64Image();
        link.click();
        showNotification('Gráfico exportado exitosamente', 'success');
    }

    function exportarDatos() {
        showNotification('Función de exportación en desarrollo', 'info');
    }

    // Animación de entrada para las cards
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            setTimeout(() => {
                card.style.transition = 'all 0.5s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    });
</script>
@endsection