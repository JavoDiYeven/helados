<nav class="col-md-3 col-lg-2 d-md-block sidebar collapse" id="sidebar">
    <div class="position-sticky pt-3">
        <!-- Logo -->
        <div class="text-center mb-4">
            <div class="d-flex align-items-center justify-content-center">
                <span class="text-4xl me-2">🍦</span>
                <div class="text-start">
                    <h4 class="text-white mb-0">Amai Gelato</h4>
                    <small class="text-white-50">Panel Admin</small>
                </div>
            </div>
        </div>
        
        <!-- Navigation Menu -->
        <ul class="nav flex-column">
            <!-- Dashboard -->
            <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('dashboard') ? 'active' : '' }}" 
                   href="{{ route('backend.dashboard') }}">
                    <i class="fas fa-tachometer-alt me-2"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Productos -->
            <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('productos.*') ? 'active' : '' }}" 
                   href="{{ route('productos.index') }}">
                    <i class="fas fa-ice-cream me-2"></i>
                    <span>Productos</span>
                </a>
            </li>

            <!-- Ventas -->
            <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('ventas.*') ? 'active' : '' }}" 
                   href="#" onclick="showVentas()">
                    <i class="fas fa-shopping-cart me-2"></i>
                    <span>Ventas</span>
                    <span class="badge bg-danger ms-auto" id="ventas-pendientes">3</span>
                </a>
            </li>

            <!-- Reportes -->
            <li class="nav-item dropdown">
                <a class="nav-link text-white dropdown-toggle" href="#" id="reportesDropdown" role="button" data-bs-toggle="collapse" data-bs-target="#reportesSubmenu">
                    <i class="fas fa-chart-line me-2"></i>
                    <span>Reportes</span>
                </a>
                <div class="collapse" id="reportesSubmenu">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item">
                            <a class="nav-link text-white-75 {{ request()->routeIs('dashboard.reporte-ventas') ? 'active' : '' }}" 
                               href="{{ route('backend.dashboard.ventas') }}">
                                <i class="fas fa-chart-bar me-2"></i>
                                <span>Ventas</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-75 {{ request()->routeIs('dashboard.reporte-productos') ? 'active' : '' }}" 
                               href="{{ route('backend.dashboard.productos') }}">
                                <i class="fas fa-ice-cream me-2"></i>
                                <span>Productos</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-75" href="#" onclick="showReporteClientes()">
                                <i class="fas fa-users me-2"></i>
                                <span>Clientes</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- Inventario -->
            <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('inventario.*') ? 'active' : '' }}" 
                   href="#" onclick="showInventario()">
                    <i class="fas fa-warehouse me-2"></i>
                    <span>Inventario</span>
                    <span class="badge bg-warning ms-auto" id="stock-alerts">5</span>
                </a>
            </li>

            <!-- Clientes -->
            <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('clientes.*') ? 'active' : '' }}" 
                   href="#" onclick="showClientes()">
                    <i class="fas fa-users me-2"></i>
                    <span>Clientes</span>
                </a>
            </li>

            <!-- Configuración -->
            <li class="nav-item mt-3">
                <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-white-50">
                    <span>Configuración</span>
                </h6>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('configuracion.*') ? 'active' : '' }}" 
                   href="#" onclick="showConfiguracion()">
                    <i class="fas fa-cog me-2"></i>
                    <span>Configuración</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('usuarios.*') ? 'active' : '' }}" 
                   href="#" onclick="showUsuarios()">
                    <i class="fas fa-user-cog me-2"></i>
                    <span>Usuarios</span>
                </a>
            </li>

            <!-- Ayuda -->
            <li class="nav-item mt-3">
                <a class="nav-link text-white-50" href="#" onclick="showAyuda()">
                    <i class="fas fa-question-circle me-2"></i>
                    <span>Ayuda</span>
                </a>
            </li>
        </ul>

        <!-- Notifications Panel -->
        <div class="mt-4 p-3">
            <div class="card bg-white bg-opacity-10 border-0">
                <div class="card-body p-3">
                    <h6 class="text-white mb-2">
                        <i class="fas fa-bell me-1"></i>
                        Notificaciones
                    </h6>
                    <div id="sidebar-notifications" class="text-white-75 small">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Stock bajo:</span>
                            <span class="badge bg-warning">5</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>Pedidos pendientes:</span>
                            <span class="badge bg-info">3</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Sin stock:</span>
                            <span class="badge bg-danger">2</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-auto p-3 text-center">
            <small class="text-white-50">
                © 2024 Helados Delicia<br>
                v1.0.0
            </small>
        </div>
    </div>
</nav>

<script>
    // Sidebar navigation functions
    function showVentas() {
        showNotification('Módulo de ventas en desarrollo', 'info');
    }

    function showInventario() {
        window.location.href = '{{ route("productos.index") }}';
    }

    function showClientes() {
        showNotification('Módulo de clientes en desarrollo', 'info');
    }

    function showConfiguracion() {
        showNotification('Módulo de configuración en desarrollo', 'info');
    }

    function showUsuarios() {
        showNotification('Módulo de usuarios en desarrollo', 'info');
    }

    function showAyuda() {
        showNotification('Sistema de ayuda en desarrollo', 'info');
    }

    function showReporteClientes() {
        showNotification('Reporte de clientes en desarrollo', 'info');
    }

    // Update notifications periodically
    function updateSidebarNotifications() {
        fetch('/api/dashboard/notifications')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('ventas-pendientes').textContent = data.ventas_pendientes || 0;
                    document.getElementById('stock-alerts').textContent = data.stock_alerts || 0;
                    
                    // Update sidebar notifications
                    const notificationsHtml = `
                        <div class="d-flex justify-content-between mb-1">
                            <span>Stock bajo:</span>
                            <span class="badge bg-warning">${data.stock_bajo || 0}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>Pedidos pendientes:</span>
                            <span class="badge bg-info">${data.ventas_pendientes || 0}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Sin stock:</span>
                            <span class="badge bg-danger">${data.sin_stock || 0}</span>
                        </div>
                    `;
                    document.getElementById('sidebar-notifications').innerHTML = notificationsHtml;
                }
            })
            .catch(error => console.error('Error updating notifications:', error));
    }

    // Update notifications every 30 seconds
    setInterval(updateSidebarNotifications, 30000);
    
    // Initial load
    document.addEventListener('DOMContentLoaded', updateSidebarNotifications);
</script>
