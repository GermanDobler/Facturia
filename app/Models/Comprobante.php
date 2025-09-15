<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Comprobante extends Model
{
    protected $fillable = [
        'matriculado_cuota_id',
        'monto_declarado',
        'fecha_transferencia',
        'referencia',
        'archivo_path',
        'estado',
        'revisado_por',
        'revisado_at',
        'notas_admin'
    ];

    protected $casts = [
        'monto_declarado'    => 'decimal:2',
        'fecha_transferencia' => 'date',
        'revisado_at'        => 'datetime',
    ];

    protected $appends = ['archivo_url'];

    public function instancia()
    {
        return $this->belongsTo(MatriculadoCuota::class, 'matriculado_cuota_id');
    }

    public function revisor()
    {
        return $this->belongsTo(\App\Models\User::class, 'revisado_por');
    }

    public function getArchivoUrlAttribute(): ?string
    {
        return $this->archivo_path ? Storage::url($this->archivo_path) : null;
    }
}
