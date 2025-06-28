<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->string('numero_pedido')->unique();
            $table->string('cliente_nombre');
            $table->string('cliente_telefono');
            $table->string('cliente_email')->nullable();
            $table->text('direccion_entrega');
            $table->date('fecha_entrega');
            $table->string('hora_entrega');
            $table->text('instrucciones_especiales')->nullable();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('costo_envio', 10, 2)->default(25.00);
            $table->decimal('total', 10, 2);
            $table->enum('estado', ['pendiente', 'confirmado', 'en_preparacion', 'enviado', 'entregado', 'cancelado'])->default('pendiente');
            $table->timestamp('fecha_venta');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
