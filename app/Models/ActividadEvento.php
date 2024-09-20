<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActividadEvento extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_actividad',
        'tipo_actividad',
        'resultado_esperado',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'tecnico_a_cargo',
        'participantes',
        'lugar',
        'estado_aprobacion',
        'usuario_id',
    ];

    // Relación con User
    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
}
