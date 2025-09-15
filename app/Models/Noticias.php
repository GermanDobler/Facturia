<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Noticias extends Model
{
    protected $table = 'noticias';
    protected $fillable = ['titulo', 'subtitulo', 'contenido_html', 'imagen_url', 'pdf_url', 'estado', 'is_paid', 'featured', 'prioridad', 'created_at', 'fuente_url'];

    // Opcional: Agrega un método para los estados
    public static function getEstados()
    {
        return ['principal', 'secundaria', 'archivada'];
    }

    public function etiquetas()
    {
        return $this->belongsToMany(Etiqueta::class, 'noticia_etiqueta', 'noticia_id', 'etiqueta_id');
    }
}
