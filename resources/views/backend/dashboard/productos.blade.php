<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Productos - Amai Gelato</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light">
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="{{ route('backend.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Volver al Dashboard
                </a>
            </div>
            <div>
                <button class="btn btn-primary" onclick="window.print()">
                    <i class="fas fa-print"></i> Imprimir
                </button>
            </div>
        </div>

        <!-- Título -->
        <div class="text-center mb-4">
            <h1 class="h2">📊 Análisis de Productos</h1>
            <p class="text-muted">Rendimiento y ganancias por producto</p>
        </div>

        <!-- Gráfico de Productos Más Vendidos -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5>📈 Productos Más Vendidos</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="productosChart" height="100"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>💰 Ganancias por Categoría</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="gananciasChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de Productos -->
        <div class="card">
            <div class="card-header">
                <h5>🍦 Detalle de Productos</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Ranking</th>
                                <th>Producto</th>
                                <th>Unidades Vendidas</th>
                                <th>Precio Promedio</th>
                                <th>Ingresos Totales</th>
                                <th>% del Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $totalIngresos = $productosReporte->sum('total_ingresos'); @endphp
                            @foreach($productosReporte as $index => $producto)
                                <tr>
                                    <td>
                                        @if($index < 3)
                                            <span class="badge bg-{{ $index == 0 ? 'warning' : ($index == 1 ? 'secondary' : 'dark') }}">
                                                #{{ $index + 1 }}
                                            </span>
                                        @else
                                            <span class="badge bg-light text-dark">#{{ $index + 1 }}</span>
                                        @endif
                                    </td>
                                    <td><strong>{{ $producto->producto_nombre }}</strong></td>
                                    <td>
                                        <span class="badge bg-primary">{{ $producto->total_vendido }}</span>
                                    </td>
                                    <td>${{ number_format($producto->precio_promedio, 2) }}</td>
                                    <td class="text-success">
                                        <strong>${{ number_format($producto->total_ingresos, 2) }}</strong>
                                    </td>
                                    <td>
                                        @php $porcentaje = $totalIngresos > 0 ? ($producto->total_ingresos / $totalIngresos) * 100 : 0; @endphp
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar" role="progressbar" style="width: {{ $porcentaje }}%">
                                                {{ number_format($porcentaje, 1) }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="4" class="text-end">TOTAL:</th>
                                <th class="text-success">${{ number_format($totalIngresos, 2) }}</th>
                                <th>100%</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Gráfico de productos más vendidos
        const productosData = {!! json_encode($productosReporte->take(10)) !!};
        
        const ctx1 = document.getElementById('productosChart').getContext('2d');
        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: productosData.map(p => p.producto_nombre),
                datasets: [{
                    label: 'Unidades Vendidas',
                    data: productosData.map(p => p.total_vendido),
                    backgroundColor: 'rgba(54, 162, 235, 0.8)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Top 10 Productos por Unidades Vendidas'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Gráfico de ganancias (simulado por categorías)
        const ctx2 = document.getElementById('gananciasChart').getContext('2d');
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: ['Cremosos', 'Frutales', 'Especiales'],
                datasets: [{
                    data: [40, 35, 25], // Datos simulados
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 205, 86, 0.8)'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Distribución de Ganancias'
                    }
                }
            }
        });
    </script>
</body>
</html>