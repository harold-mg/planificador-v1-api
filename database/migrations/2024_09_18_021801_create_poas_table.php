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
        Schema::create('poas', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_poa');//->unique();
            $table->string('operacion');
            $table->foreignId('area_id')->nullable()->constrained('areas')->onDelete('cascade'); // Permitir null en area_id
        $table->foreignId('unidad_id')->constrained('unidades')->onDelete('cascade'); // unidad_id sigue siendo obligatorio
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('poas');
    }
};
