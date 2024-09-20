<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    use HasFactory;

    protected $fillable = [
        'placa',
        'modelo',
        'disponible',
    ];

    // Relación uno a muchos con actividades_vehiculo
    public function actividadVehiculo()
    {
        return $this->hasMany(ActividadVehiculo::class);
    }
}
