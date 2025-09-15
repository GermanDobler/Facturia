<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Archivo extends Model
{
    protected $fillable = [
        'user_id',
        'nombre_original',
        'ruta',
        'tipo',
        'carpeta',
        'tamano',
    ];
}
