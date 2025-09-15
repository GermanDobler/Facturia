<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatriculadoCuota extends Model
{
    protected $table = 'matriculado_cuota';

    protected $fillable = [
        'user_id',
        'cuota_id',
        'monto',
        'estado',
        'monto_pagado',
        'ultimo_pago_at',
        'personalizada'
    ];

    protected $casts = [
        'monto'         => 'decimal:2',
        'monto_pagado'  => 'decimal:2',
        'ultimo_pago_at' => 'datetime',
        'personalizada' => 'boolean',
    ];

    public function cuota()
    {
        return $this->belongsTo(Cuota::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function comprobantes()
    {
        return $this->hasMany(Comprobante::class, 'matriculado_cuota_id');
    }
}
