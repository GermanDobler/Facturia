<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Periodo extends Model
{
    protected $table = 'periodos';

    protected $fillable = [
        'nombre',
        'anio',
        'mes',
        'fecha_vencimiento',
        'monto',
        'estado',
        'es_visible',
        'creado_por'
    ];

    protected $casts = [
        'fecha_vencimiento' => 'date',
        'monto'             => 'decimal:2',
        'es_visible'        => 'boolean',
    ];

    public function cuotas(): HasMany
    {
        return $this->hasMany(Cuota::class, 'periodo_id');
    }
}
