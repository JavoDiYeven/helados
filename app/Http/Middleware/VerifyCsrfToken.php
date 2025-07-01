<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // Solo excluir la ruta específica para procesar ventas del frontend
        'api/ventas',
        'api/productos', // Para cargar productos sin CSRF
    ];
}
