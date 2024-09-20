<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CentroSalud extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre', 
        'municipio_id'
    ];

    // Relación con Municipio
    public function municipio()
    {
        return $this->belongsTo(Municipio::class);
    }
}
