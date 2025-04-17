<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\BackController;

Route::get('/', function () {
    return view('welcome');
});

//Frontend
Route::get('inicio', [FrontController::class, 'inicio'])->name('inicio');
Route::get('productos', [FrontController::class, 'productos'])->name('productos');
Route::get('contacto', [FrontController::class, 'contacto'])->name('contacto');
Route::get('nosotros', [FrontController::class, 'nosotros'])->name('nosotros');

//Backend
Route::get('/backend', [BackController::class, 'backend'])->name('backend');
Route::get('/backend/inicio', [BackController::class, 'inicio'])->name('backend.inicio');
Route::get('/backend/productos', [BackController::class, 'productos'])->name('backend.productos');
Route::get('/backend/reportes', [BackController::class, 'reportes'])->name('backend.reportes');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
