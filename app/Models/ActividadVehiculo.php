<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActividadVehiculo extends Model
{
    use HasFactory;
    protected $table = 'actividades_vehiculo';
    protected $fillable = [
        'poa_id',
        'detalle_operacion',
        'resultados_esperados',
        'fecha_inicio',
        'fecha_fin',
        'centro_salud_id', // Mantener solo la relación con CentroSalud
        'tecnico_a_cargo',
        'detalles_adicionales',
        'estado_aprobacion',
        'nivel_aprobacion',
        'usuario_id',
        'vehiculo_id',
    ];

    // Relación con Poa
    public function poa()
    {
        return $this->belongsTo(Poa::class);
    }

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
