<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operacion extends Model
{
    use HasFactory;
    protected $table = 'operaciones';

    protected $fillable = ['poa_id', 'descripcion'];

    // Relación con Poa
    public function poa()
    {
        return $this->belongsTo(Poa::class);
    }
}
