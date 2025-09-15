<?php

// app/Models/Etiqueta.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etiqueta extends Model
{
    use HasFactory;

    protected $fillable = ['nombre'];

    // Relación muchos a muchos con Noticias
    public function noticias()
    {
        return $this->belongsToMany(Noticias::class, 'noticia_etiqueta', 'etiqueta_id', 'noticia_id');
    }
}
