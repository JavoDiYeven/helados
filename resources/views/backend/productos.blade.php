<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Backend Productos</title>
    <link https:"//cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
</head>
<body>
    <section class="container">
        <h3>Registro de Productos</h3>

        <label>Codigo de Producto</label>
        <input type="text" name="codigo_producto" id="codigo_producto" placeholder="Codigo de Producto">

        <input type="file" name="imagen_producto" id="imagen_producto" placeholder="Imagen de Producto">

        <label>Nombre de Producto</label>
        <input type="text" name="nombre_producto" id="nombre_producto" placeholder="Nombre de Producto">

        <label>Descripcion de Producto</label>
        <input type="text" name="descripcion_producto" id="descripcion_producto" placeholder="Descripcion de Producto">

        <label>Precio de Producto</label>
        <input type="text" name="precio_producto" id="precio_producto" placeholder="Precio de Producto">

        <label>Stock de Producto</label>
        <input type="text" name="stock_producto" id="stock_producto" placeholder="Stock de Producto">

        <button id="btn-guardar">Guardar</button>
        <button id="btn-limpiar">Cancelar</button>
        <button id="btn-buscar">Buscar</button>
        <button id="btn-modificar">Modificar</button>
        <button id="btn-eliminar">Eliminar</button>
    </section>
    <section class="container">
        <h3>Lista de Productos</h3>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Codigo</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Descripcion</th>
                    <th scope="col">Precio</th>
                    <th scope="col">Stock</th>
                </tr>
            </thead>
            <tbody id="productos-list">
                <!-- Los productos se llenaran desde el script de jQuery -->
                
            </tbody>
        </table>

    </section>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $.ajaxSetup({
                headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
        });
        
        $(document).ready(function () {
                cargarProductos();
        
                function cargarProductos() {
                        $.getJSON('/api/productos', function (data) {
                                $('#productos-list').empty();
                                $.each(data, function (i, producto) {
                                        $('#productos-list').append(`
                                                <tr>
                                                        <td>${producto.codigo}</td>
                                                        <td>${producto.nombre}</td>
                                                        <td>${producto.descripcion}</td>
                                                        <td>${producto.precio}</td>
                                                        <td>${producto.stock}</td>
                                                </tr>
                                        `);
                                });
                        });
                }
        
                // Guardar producto
                $('#btn-guardar').click(function () {
                        let imagen = $('#imagen_producto')[0].files[0];
                        let formData = new FormData();
                        formData.append('codigo', $('#codigo_producto').val());
                        formData.append('nombre', $('#nombre_producto').val());
                        formData.append('descripcion', $('#descripcion_producto').val());
                        formData.append('precio', $('#precio_producto').val());
                        formData.append('stock', $('#stock_producto').val());
                        if (imagen) formData.append('imagen', imagen);
            
                        $.ajax({
                                url: '/api/productos',
                                method: 'POST',
                                data: formData,
                                contentType: false,
                                processData: false,
                                success: function () {
                                        alert('Producto guardado');
                                        cargarProductos();
                                },
                                error: function (xhr) {
                                        alert('Error al guardar: ' + xhr.responseText);
                                }
                        });
                });
        
                // Buscar producto
                $('#btn-buscar').click(function () {
                        let codigo = prompt("Ingrese el código del producto");
                        if (codigo) {
                                $.getJSON(`/api/productos/${codigo}`, function (producto) {
                                        $('#codigo_producto').val(producto.codigo);
                                        $('#nombre_producto').val(producto.nombre);
                                        $('#descripcion_producto').val(producto.descripcion);
                                        $('#precio_producto').val(producto.precio);
                                        $('#stock_producto').val(producto.stock);
                                }).fail(function () {
                                        alert('Producto no encontrado');
                                });
                        }
                });
        
                // Modificar producto
                $('#btn-modificar').click(function () {
                        let codigo = $('#codigo_producto').val();
                        let imagen = $('#imagen_producto')[0].files[0];
                        let formData = new FormData();
                        formData.append('_method', 'PUT');
                        formData.append('nombre', $('#nombre_producto').val());
                        formData.append('descripcion', $('#descripcion_producto').val());
                        formData.append('precio', $('#precio_producto').val());
                        formData.append('stock', $('#stock_producto').val());
                        if (imagen) formData.append('imagen', imagen);
            
                        $.ajax({
                                url: `/api/productos/${codigo}`,
                                method: 'POST',
                                data: formData,
                                contentType: false,
                                processData: false,
                                success: function () {
                                        alert('Producto actualizado');
                                        cargarProductos();
                                },
                                error: function (xhr) {
                                        alert('Error al actualizar: ' + xhr.responseText);
                                }
                        });
                });
        
                // Eliminar producto
                $('#btn-eliminar').click(function () {
                        let codigo = $('#codigo_producto').val();
                        if (!codigo) return alert('Ingrese un código válido primero');
            
                        if (confirm("¿Seguro que quieres eliminar el producto?")) {
                                $.ajax({
                                        url: `/api/productos/${codigo}`,
                                        method: 'DELETE',
                                        success: function () {
                                                alert('Producto eliminado');
                                                cargarProductos();
                                        },
                                        error: function (xhr) {
                                                alert('Error al eliminar: ' + xhr.responseText);
                                        }
                                });
                        }
                });
        
        });
    </script>
    
</body>
</html>