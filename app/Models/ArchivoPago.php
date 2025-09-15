<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArchivoPago extends Model
{
    protected $table = 'archivos_pago';

    protected $fillable = ['pago_id', 'ruta', 'mime', 'tamano'];

    public function pago(): BelongsTo
    {
        return $this->belongsTo(Pago::class, 'pago_id');
    }
}
