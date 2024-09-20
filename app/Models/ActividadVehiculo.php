<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActividadVehiculo extends Model
{
    use HasFactory;

    protected $fillable = [
        'poa_id',
        'resultado_esperado',
        'fecha_inicio',
        'fecha_fin',
        'centro_salud_id', // Mantener solo la relación con CentroSalud
        'tecnico_id',
        'detalles',
        'estado_aprobacion',
        'usuario_id',
    ];

    // Relación con Poa
    public function poa()
    {
        return $this->belongsTo(Poa::class);
    }

/*     // Relación con Coordinacion
    public function coordinacion()
    {
        return $this->belongsTo(Coordinacion::class);
    }

    // Relación con Municipio
    public function municipio()
    {
        return $this->belongsTo(Municipio::class);
    } */

    // Relación con CentroSalud
    public function centroSalud()
    {
        return $this->belongsTo(CentroSalud::class);
    }
    
    // Relación con User
    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class);
    }
}
