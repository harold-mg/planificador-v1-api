<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unidad extends Model
{
    use HasFactory;

    protected $fillable = ['nombre'];

    // Relación con Poa
    public function poas()
    {
        return $this->hasMany(Poa::class);
    }
    // Relación con Area
    public function areas()
    {
        return $this->hasMany(Area::class);
    }
}
