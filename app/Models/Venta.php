<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Venta extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_pedido',
        'cliente_nombre',
        'cliente_telefono',
        'cliente_email',
        'direccion_entrega',
        'fecha_entrega',
        'hora_entrega',
        'instrucciones_especiales',
        'subtotal',
        'costo_envio',
        'total',
        'estado',
        'fecha_venta'
    ];

    protected $casts = [
        'fecha_entrega' => 'date',
        'fecha_venta' => 'datetime',
        'subtotal' => 'decimal:2',
        'costo_envio' => 'decimal:2',
        'total' => 'decimal:2'
    ];

    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class);
    }

    // Scopes para filtros
    public function scopeDelMes($query, $mes = null, $año = null)
    {
        $mes = $mes ?? now()->month;
        $año = $año ?? now()->year;
        
        return $query->whereMonth('fecha_venta', $mes)
                    ->whereYear('fecha_venta', $año);
    }

    public function scopeDelMesAnterior($query)
    {
        $mesAnterior = now()->subMonth();
        return $query->whereMonth('fecha_venta', $mesAnterior->month)
                    ->whereYear('fecha_venta', $mesAnterior->year);
    }

    public function scopeEntregadas($query)
    {
        return $query->where('estado', 'entregado');
    }
}
