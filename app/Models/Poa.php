<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poa extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo_poa',
        'operacion',
        'area_id',
    ];

    // Relación con ActividadesConVehiculo
    public function actividadesConVehiculo()
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
    /* // Relación con Unidad
    public function unidad()
    {
        return $this->belongsTo(Unidad::class);
    } */
}
