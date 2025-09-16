<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\FacturaExtraccion;


class FacturaItem extends Model
{
    protected $table = 'factura_items';

    protected $fillable = [
        'factura_extraccion_id',
        'descripcion',
        'cantidad',
        'precio_unitario',
        'importe',
        'moneda',
    ];

    protected $casts = [
        'cantidad'       => 'decimal:4',
        'precio_unitario' => 'decimal:2',
        'importe'        => 'decimal:2',
    ];

    public function extraccion()
    {
        return $this->belongsTo(FacturaExtraccion::class, 'factura_extraccion_id');
    }
}
