<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('actividades_vehiculo', function (Blueprint $table) {
            $table->enum('nivel_aprobacion', ['unidad', 'planificador'])->default('unidad');
        });
    }
    
    public function down()
    {
        Schema::table('actividades_vehiculo', function (Blueprint $table) {
            $table->dropColumn('nivel_aprobacion');
        });
    }
    
};
