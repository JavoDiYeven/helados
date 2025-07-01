<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes (Backend Dashboard)
|--------------------------------------------------------------------------
*/

// Ruta raíz - redirige al frontend
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Rutas del frontend público
Route::get('/tienda', function () {
    return view('welcome');
})->name('tienda');

Route::get('/login', [AuthController::class, 'showLoginPage'])->name('login');

// Rutas del Backend Dashboard (requieren autenticación web)
Route::prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard principal
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    
    // Reportes
    Route::get('/reporte-ventas', [DashboardController::class, 'reporteVentas'])->name('reporte-ventas');
    Route::get('/reporte-productos', [DashboardController::class, 'reporteProductos'])->name('reporte-productos');
    
    // CRUD de Productos
    Route::resource('productos', ProductoController::class);
    
    // Gestión de Ventas
    Route::resource('ventas', VentaController::class)->except(['store']);
    
    // Notificaciones AJAX
    Route::get('/notificaciones', [DashboardController::class, 'getNotificaciones'])->name('notificaciones');
});

// Rutas de acceso directo al dashboard (compatibilidad)
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard/reporte-ventas', [DashboardController::class, 'reporteVentas'])->name('dashboard.reporte-ventas');
Route::get('/dashboard/reporte-productos', [DashboardController::class, 'reporteProductos'])->name('dashboard.reporte-productos');
Route::resource('/productos', ProductoController::class);
Route::resource('/ventas', VentaController::class)->except(['store']);

// Rutas de prueba y utilidad
Route::get('/test-productos', function () {
    $productos = \App\Models\Producto::all();
    return response()->json([
        'total' => $productos->count(),
        'productos' => $productos->toArray()
    ]);
});

Route::get('/test-connection', function () {
    return response()->json([
        'success' => true,
        'message' => 'Conexión exitosa',
        'timestamp' => now(),
        'environment' => app()->environment()
    ]);
});