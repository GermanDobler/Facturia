<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cuota extends Model
{
    protected $table = 'cuotas';

    protected $fillable = [
        'periodo_id',
        'usuario_id',
        'monto',
        'recargo',
        'descuento',
        'monto_final',
        'estado',
        'aprobado_en',
        'aprobado_por'
    ];

    protected $casts = [
        'monto'        => 'decimal:2',
        'recargo'      => 'decimal:2',
        'descuento'    => 'decimal:2',
        'monto_final'  => 'decimal:2',
        'aprobado_en'  => 'datetime',
    ];

    public function periodo(): BelongsTo
    {
        return $this->belongsTo(Periodo::class, 'periodo_id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function aprobador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'aprobado_por');
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class, 'cuota_id');
    }

    // Helper: último pago en revisión
    public function ultimoPagoEnRevision()
    {
        return $this->hasOne(Pago::class, 'cuota_id')
            ->latestOfMany()
            ->where('estado', 'en_revision');
    }
}
