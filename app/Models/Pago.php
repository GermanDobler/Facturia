<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pago extends Model
{
    protected $table = 'pagos';

    protected $fillable = [
        'cuota_id',
        'metodo',
        'pagado_en',
        'referencia',
        'notas',
        'estado'
    ];

    protected $casts = [
        'pagado_en' => 'datetime',
    ];

    public function cuota(): BelongsTo
    {
        return $this->belongsTo(Cuota::class, 'cuota_id');
    }

    public function archivos(): HasMany
    {
        return $this->hasMany(ArchivoPago::class, 'pago_id');
    }
}
