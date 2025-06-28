<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\InsumoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\AuthController; // Asegúrate de tener este controlador

// Página inicial
Route::get('/', function () {
    return view('welcome');
});

//Prevencion del favicon
Route::get('favicon.ico', function () {
    return response()->noContent();
});

// API para el frontend (público)
Route::get('api/productos', [ProductoController::class, 'apiIndex']);
Route::post('api/ventas', [VentaController::class, 'store']);

// CRUD productos (si es accesible fuera del admin)
Route::resource('productos', ProductoController::class);

// Grupo backend
Route::prefix('backend')->group(function () {
    // Al acceder a /backend, redirige al login
    Route::get('/', function () {
        return redirect()->route('backend.login');
    });

    // Login (puedes usar un controlador o una vista directa)
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('backend.login');
    Route::post('/login', [AuthController::class, 'login'])->name('backend.login.submit');

    // Si quieres un logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('backend.logout');

    // Dashboard y otros, protegidos por middleware auth
    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('backend.dashboard');

        Route::get('/dashboard/ventas', [DashboardController::class, 'ventas'])->name('backend.dashboard.ventas');
        Route::get('/dashboard/productos', [DashboardController::class, 'reporteProductos'])->name('backend.dashboard.productos');
        Route::get('/dashboard/reporte-ventas', [DashboardController::class, 'reporteVentasMes'])->name('backend.dashboard.ventas');
        Route::get('/dashboard/reporte-productos', [DashboardController::class, 'reporteProductos'])->name('backend.dashboard.productos');
        Route::get('/dashboard/notificaciones', [DashboardController::class, 'getNotificaciones']);

        // Admin CRUD
        Route::resource('productos', ProductoController::class);
        Route::resource('insumos', InsumoController::class);

        // API exclusiva del admin
        Route::post('/api/ventas', [VentaController::class, 'store']);
    });
});