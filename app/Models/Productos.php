<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Productos extends Model
{
    use HasFactory;
    protected $table='productos';
    protected $fillable = ['codigo', 'nombre', 'descripcion', 'precio', 'stock', 'imagen'];
}
