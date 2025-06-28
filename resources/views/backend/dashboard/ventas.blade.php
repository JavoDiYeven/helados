<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Ventas - Helados Delicia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print { display: none !important; }
            .card { border: 1px solid #ddd !important; }
        }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 no-print">
            <div>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Volver al Dashboard
                </a>
            </div>
            <div>
                <button class="btn btn-primary" onclick="window.print()">
                    <i class="fas fa-print"></i> Imprimir Reporte
                </button>
                <button class="btn btn-success" onclick="exportToExcel()">
                    <i class="fas fa-file-excel"></i> Exportar Excel
                </button>
            </div>
        </div>

        <!-- Título del Reporte -->
        <div class="text-center mb-4">
            <h1 class="h2">🍦 Helados Delicia</h1>
            <h2 class="h4">Reporte de Ventas - {{ \Carbon\Carbon::create(null, $mes)->format('F') }} {{ $año }}</h2>
            <p class="text-muted">Generado el {{ now()->format('d/m/Y H:i') }}</p>
        </div>

        <!-- Filtros -->
        <div class="card mb-4 no-print">
            <div class="card-body">
                <form method="GET" action="{{ route('dashboard.reporte-ventas') }}" class="row g-3">
                    <div class="col-md-4">
                        <label for="mes" class="form-label">Mes</label>
                        <select name="mes" id="mes" class="form-select">
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ $mes == $i ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create(null, $i)->format('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="año" class="form-label">Año</label>
                        <select name="año" id="año" class="form-select">
                            @for($i = now()->year; $i >= 2020; $i--)
                                <option value="{{ $i }}" {{ $año == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary d-block">
                            <i class="fas fa-search"></i> Filtrar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Resumen Ejecutivo -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-center border-primary">
                    <div class="card-body">
                        <h5 class="card-title text-primary">Total de Ventas</h5>
                        <h2 class="text-primary">{{ $resumen['total_ventas'] }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center border-success">
                    <div class="card-body">
                        <h5 class="card-title text-success">Ingresos Totales</h5>
                        <h2 class="text-success">${{ number_format($resumen['total_ingresos'], 2) }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center border-info">
                    <div class="card-body">
                        <h5 class="card-title text-info">Productos Vendidos</h5>
                        <h2 class="text-info">{{ $resumen['total_productos_vendidos'] }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center border-warning">
                    <div class="card-body">
                        <h5 class="card-title text-warning">Ticket Promedio</h5>
                        <h2 class="text-warning">${{ number_format($resumen['ticket_promedio'], 2) }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de Ventas -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">📋 Detalle de Ventas</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Fecha</th>
                                <th>N° Pedido</th>
                                <th>Cliente</th>
                                <th>Productos</th>
                                <th>Subtotal</th>
                                <th>Envío</th>
                                <th>Total</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ventas as $venta)
                                <tr>
                                    <td>{{ $venta->fecha_venta->format('d/m/Y') }}</td>
                                    <td><strong>{{ $venta->numero_pedido }}</strong></td>
                                    <td>{{ $venta->cliente_nombre }}</td>
                                    <td>
                                        <small>
                                            @foreach($venta->detalles as $detalle)
                                                {{ $detalle->producto_nombre }} ({{ $detalle->cantidad }})<br>
                                            @endforeach
                                        </small>
                                    </td>
                                    <td>${{ number_format($venta->subtotal, 2) }}</td>
                                    <td>${{ number_format($venta->costo_envio, 2) }}</td>
                                    <td><strong>${{ number_format($venta->total, 2) }}</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $venta->estado == 'entregado' ? 'success' : ($venta->estado == 'pendiente' ? 'warning' : 'info') }}">
                                            {{ ucfirst(str_replace('_', ' ', $venta->estado)) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="6" class="text-end">TOTAL:</th>
                                <th>${{ number_format($ventas->sum('total'), 2) }}</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Footer del Reporte -->
        <div class="text-center mt-4 text-muted">
            <p>Reporte generado automáticamente por el sistema de Helados Delicia</p>
            <p>{{ now()->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function exportToExcel() {
            // Implementar exportación a Excel
            alert('Función de exportación a Excel en desarrollo');
        }
    </script>
</body>
</html>