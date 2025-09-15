<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Auditoria extends Model
{
    protected $table = 'auditorias';

    protected $fillable = ['entidad_tipo', 'entidad_id', 'accion', 'actor_id', 'meta'];

    protected $casts = [
        'meta' => 'array',
    ];

    public function actor(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'actor_id');
    }

    // Acceso polimórfico manual
    public function entidad(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'entidad_tipo', 'entidad_id');
    }
}
