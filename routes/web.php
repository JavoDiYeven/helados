<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;

// Rutas públicas
Route::get('/', [ProductController::class, 'catalog'])->name('catalog');

// Auth routes
Auth::routes(['register' => false]); // Registro deshabilitado

// Rutas admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    
    Route::get('/reports/inventory', [ProductController::class, 'generateReport'])->name('reports.inventory');
});
