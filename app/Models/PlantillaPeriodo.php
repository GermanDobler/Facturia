<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlantillaPeriodo extends Model
{
    protected $table = 'plantillas_periodo';

    protected $fillable = ['frecuencia', 'monto_defecto', 'dia_vencimiento', 'activo'];

    protected $casts = [
        'monto_defecto' => 'decimal:2',
        'activo'        => 'boolean',
    ];
}
