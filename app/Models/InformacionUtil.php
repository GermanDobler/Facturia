<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InformacionUtil extends Model
{
    use HasFactory;

    protected $table = 'informacion_util'; // Nombre de la tabla

    protected $fillable = ['titulo', 'contenido']; // Campos que se pueden llenar

    public $timestamps = true; // Laravel manejará created_at y updated_at
}
