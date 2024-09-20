<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $fillable = [
        'nombre',
        'apellido',
        'cedula_identidad',
        'nombre_usuario',
        'password',
        'telefono',
        'rol',
        'area_id'
    ];
    
    // Relación con ActividadesConVehiculo
    public function actividadeVehiculo()
    {
        return $this->hasMany(ActividadVehiculo::class);
    }
    // Relación con Area
    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    // Acceso a Unidad a través de Area
    public function unidad()
    {
        return $this->area->unidad();
    }
}

