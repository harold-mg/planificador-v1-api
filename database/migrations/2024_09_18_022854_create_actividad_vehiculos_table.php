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
        Schema::create('actividades_vehiculo', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo_actividad', ['con_vehiculo', 'sin_vehiculo']);
            $table->foreignId('poa_id')->constrained('poas')->onDelete('cascade');
            $table->text('detalle_operacion');
            $table->text('resultados_esperados');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            //$table->foreignId('coordinacion_id')->constrained('coordinaciones')->onDelete('cascade');
            //$table->foreignId('municipio_id')->constrained('municipios')->onDelete('cascade');
            $table->foreignId('centro_salud_id')->constrained('centros_salud')->onDelete('cascade');
            $table->string('tecnico_a_cargo');
            $table->text('detalles_adicionales')->nullable();
            $table->enum('estado_aprobacion', ['pendiente', 'aprobado', 'rechazado'])->default('pendiente');
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->foreignId('vehiculo_id')->constrained('vehiculos')->onDelete('cascade'); // Relación con vehículo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actividad_vehiculos');
    }
};
