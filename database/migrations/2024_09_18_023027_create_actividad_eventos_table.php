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
        Schema::create('actividades_evento', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo_actividad', ['auditorio', 'virtual', 'externo']);
            $table->string('nombre_actividad');
            $table->text('resultados_esperados');
            $table->date('fecha');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->string('tecnico_a_cargo');
            $table->text('participantes')->nullable();
            $table->string('lugar')->nullable(); // Solo para actividades externas
            $table->enum('estado_aprobacion', ['pendiente', 'aprobado', 'rechazado'])->default('pendiente');
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actividad_eventos');
    }
};
