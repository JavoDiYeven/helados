<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Inventario - Ami Gelato</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #FF85A2;
            margin-bottom: 5px;
        }
        .header p {
            color: #666;
            margin-top: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .low-stock {
            background-color: #fff3cd;
        }
        .out-of-stock {
            background-color: #f8d7da;
        }
        .summary {
            margin-bottom: 20px;
        }
        .summary h2 {
            color: #FF85A2;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .summary-item {
            display: inline-block;
            margin-right: 30px;
            margin-bottom: 10px;
        }
        .summary-item strong {
            display: block;
            font-size: 18px;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Ami Gelato - Reporte de Inventario</h1>
        <p>Generado el {{ date('d/m/Y H:i') }}</p>
    </div>
    
    <div class="summary">
        <h2>Resumen</h2>
        <div class="summary-item">
            <strong>{{ $products->count() }}</strong>
            Total de Productos
        </div>
        <div class="summary-item">
            <strong>{{ $products->where('stock', '<=', 0)->count() }}</strong>
            Productos Agotados
        </div>
        <div class="summary-item">
            <strong>{{ $products->whereRaw('stock <= min_stock AND stock > 0')->count() }}</strong>
            Productos con Bajo Stock
        </div>
        <div class="summary-item">
            <strong>{{ $products->where('stock', '>', 0)->sum('stock') }}</strong>
            Unidades en Inventario
        </div>
    </div>
    
    <h2>Listado de Productos</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Producto</th>
                <th>Categoría</th>
                <th>Precio</th>
                <th>Stock Actual</th>
                <th>Stock Mínimo</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr class="{{ $product->stock <= 0 ? 'out-of-stock' : ($product->hasLowStock() ? 'low-stock' : '') }}">
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category->name }}</td>
                    <td>${{ number_format($product->price, 2) }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>{{ $product->min_stock }}</td>
                    <td>
                        @if($product->stock <= 0)
                            Agotado
                        @elseif($product->hasLowStock())
                            Bajo Stock
                        @else
                            Disponible
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        <p>© {{ date('Y') }} Ami Gelato - Sistema de Control de Inventario</p>
    </div>
</body>
</html>
