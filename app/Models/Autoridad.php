<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Autoridad extends Model
{
    use SoftDeletes;

    protected $table = 'autoridades';

    protected $fillable = [
        'nombre',
        'apellido',
        'cargo',
        'orden',
    ];

    protected $appends = ['nombre_completo'];

    public function getNombreCompletoAttribute(): string
    {
        return trim("{$this->nombre} {$this->apellido}");
    }
}
