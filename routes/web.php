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

// Authentication Routes
Route::get('/backend/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/backend/login', [AuthController::class, 'login'])->name('backend.login');
Route::post('/backend/logout', [AuthController::class, 'logout'])->name('backend.logout');

// Protected Backend Routes
Route::prefix('backend')->middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect()->route('backend.dashboard');
    });
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('backend.dashboard');
    Route::get('/dashboard/notificaciones', [DashboardController::class, 'notificaciones'])->name('backend.dashboard.notificaciones');
    Route::get('/dashboard/productos', [DashboardController::class, 'productos'])->name('backend.dashboard.productos');
    Route::get('/dashboard/reporte-productos', [DashboardController::class, 'reporteProductos'])->name('backend.dashboard.reporte-productos');
    Route::get('/dashboard/reporte-ventas', [DashboardController::class, 'reporteVentas'])->name('backend.dashboard.reporte-ventas');
    Route::get('/dashboard/ventas', [DashboardController::class, 'ventas'])->name('backend.dashboard.ventas');
    
    // Resource routes
    Route::resource('productos', ProductoController::class);
    Route::resource('insumos', InsumoController::class);
});