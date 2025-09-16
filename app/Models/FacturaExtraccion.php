<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Archivo;
use App\Models\FacturaItem;


class FacturaExtraccion extends Model
{
    protected $table = 'factura_extracciones';

    protected $fillable = [
        'archivo_id',
        'raw_json',
        'moneda',
        'base_imponible',
        'iva',
        'total',
        'subtotal',
        'fecha_factura',
        'nro_factura',
        'nombre_persona',
        'extracted_at',
    ];

    protected $casts = [
        'raw_json' => 'array',
        'fecha_factura' => 'date',
        'extracted_at' => 'datetime',
        'base_imponible' => 'decimal:2',
        'iva' => 'decimal:2',
        'total' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function archivo()
    {
        return $this->belongsTo(Archivo::class);
    }

    public function items()
    {
        // 👇 clave foránea explícita por las dudas
        return $this->hasMany(FacturaItem::class, 'factura_extraccion_id');
    }
}
