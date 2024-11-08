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
        Schema::table('actividades_vehiculo', function (Blueprint $table) {
            $table->text('observaciones')->nullable()->after('estado_aprobacion');        });
    }
    
    public function down(): void
    {
        Schema::table('actividades_vehiculo', function (Blueprint $table) {
            $table->dropColumn('observaciones');
        });
    }
};
