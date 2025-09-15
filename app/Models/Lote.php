<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lote extends Model
{
    protected $table = 'lotes';

    protected $fillable = [
        'nombre',
        'fecha_inicio',
        'fecha_fin',
        'estado',
    ];

    // Helpers útiles
    public function isAbierto(): bool
    {
        return $this->estado === 'abierto';
    }
    public function isCerrado(): bool
    {
        return $this->estado === 'cerrado';
    }
    public function isAnulado(): bool
    {
        return $this->estado === 'anulado';
    }
}
