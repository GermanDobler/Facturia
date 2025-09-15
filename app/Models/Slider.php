<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    // Especificamos la tabla si no sigue el nombre plural
    protected $table = 'sliders';

    // Los campos que son asignables masivamente
    protected $fillable = [
        'imagen_url',
        'titulo',
        'subtitulo',
        'boton_titulo',
        'boton_url',
        'boton_color',
        'boton_target',
        'orden'
    ];

    // Si se usan fechas automáticas
    public $timestamps = true;
}
