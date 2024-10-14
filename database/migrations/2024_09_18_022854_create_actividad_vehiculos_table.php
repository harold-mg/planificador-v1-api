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
            //$table->enum('tipo_actividad', ['con_vehiculo', 'sin_vehiculo']);
            $table->foreignId('poa_id')->constrained('poas')->onDelete('cascade');
            $table->text('detalle_operacion')->nullable();
            $table->text('resultados_esperados');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            //$table->foreignId('coordinacion_id')->constrained('coordinaciones')->onDelete('cascade');
            //$table->foreignId('municipio_id')->constrained('municipios')->onDelete('cascade');
            $table->foreignId('centro_salud_id')->constrained('centros_salud')->onDelete('cascade');
            $table->string('tecnico_a_cargo');
            $table->text('detalles_adicionales')->nullable();
            $table->enum('estado_aprobacion', ['pendiente', 'aprobado', 'rechazado'])->default('pendiente');
            $table->enum('nivel_aprobacion', ['unidad', 'planificador'])->default('unidad');
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->foreignId('vehiculo_id')->nullable()->constrained('vehiculos')->onDelete('cascade'); // Relación con vehículo
            
            /* $table->unsignedBigInteger('usuario_id');
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
            $table->unsignedBigInteger('vehiculo_id');
            $table->foreign('vehiculo_id')->references('id')->on('vehiculos')->onDelete('cascade'); */

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actividades_vehiculo');
    }
};
