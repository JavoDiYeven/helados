<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\VentaController;

/*
|--------------------------------------------------------------------------
| API Routes (Sin CSRF Token)
|--------------------------------------------------------------------------
*/

// Rutas públicas (sin autenticación)
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Productos públicos para el catálogo
Route::get('/productos', [ProductoController::class, 'apiIndex']);

// VENTAS SIN AUTENTICACIÓN (como invitado)
Route::post('/ventas', [VentaController::class, 'store']);

// Estado de la API
Route::get('/status', function () {
    return response()->json([
        'success' => true,
        'message' => 'API funcionando correctamente',
        'timestamp' => now(),
        'csrf_required' => false
    ]);
});

// Rutas protegidas con Sanctum
Route::middleware('auth:sanctum')->group(function () {
    
    // Autenticación
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::get('/check', [AuthController::class, 'check']);
    
    // Ventas (usuarios autenticados)
    Route::get('/mis-pedidos', [VentaController::class, 'misPedidos']);
    
    // Rutas de administrador
    Route::middleware('admin')->group(function () {
        Route::get('/admin/ventas', [VentaController::class, 'adminIndex']);
        Route::get('/admin/dashboard', [DashboardController::class, 'apiStats']);
        Route::get('/admin/productos', [ProductoController::class, 'adminIndex']);
    });
});
