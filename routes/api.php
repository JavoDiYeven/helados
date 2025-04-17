<?php
Use App\Http\Controllers\ProductosController;
use Illuminate\Support\Facades\Route;

Route::get('/productos', [ProductosController::class, 'index']);
Route::post('/productos', [ProductosController::class, 'store']);
Route::get('/productos/{id}', [ProductosController::class, 'show']);
Route::put('/productos/{id}', [ProductosController::class, 'update']);
Route::delete('/productos/{id}', [ProductosController::class, 'destroy']);
Route::apiResource('productos', [ProductosController::class]);
