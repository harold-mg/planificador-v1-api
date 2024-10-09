<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poa extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo_poa',
        //'operacion',
        'area_id',
        'unidad_id',
    ];
    // Relación con operaciones
    public function operaciones()
    {
        return $this->hasMany(Operacion::class);
    }
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

    public function unidad()
    {
        return $this->belongsTo(Unidad::class);
    }

    // Si "operacion" es una columna de texto o JSON que guarda las operaciones
/*     public function getOperacionAttribute($value)
    {
        return json_decode($value);  // Decodifica si está guardado como JSON
    } */
    // Acceso a Unidad a través de Area
    /* public function unidad()
    {
        return $this->area->unidad();
    } */
    /* // Relación con Unidad
    public function unidad()
    {
        return $this->belongsTo(Unidad::class);
    } */
}
