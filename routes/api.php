<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\VentaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Rutas públicas (no requieren autenticación)
Route::get('/status', function () {
    return response()->json([
        'success' => true,
        'message' => 'API funcionando correctamente',
        'timestamp' => now(),
    ]);
});
Route::middleware([])->post('/ventas', [VentaController::class, 'store']);
Route::post('/ventas', [VentaController::class, 'store']);
Route::get('/mis-pedidos', [VentaController::class, 'misPedidos']);

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/productos', [ProductoController::class, 'apiIndex']);

// Rutas protegidas (requieren autenticación)
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::get('/check', [AuthController::class, 'check']);

    // Admin
    Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
        Route::get('/ventas', [VentaController::class, 'index']);
        Route::get('/dashboard', [VentaController::class, 'dashboard']);
    });
});
