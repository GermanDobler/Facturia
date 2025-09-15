<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Imagen extends Model
{
    use HasFactory;

    protected $table = 'imagenes';
    protected $fillable = ['producto_id', 'url']; // Asegúrate de que 'url' es el campo correcto

    // Relación con Producto
    public function producto()
    {
        return $this->belongsTo(Producto::class); // Relación inversa con Producto
    }
}
