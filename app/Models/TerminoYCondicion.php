<?php
// app/Models/TerminoYCondicion.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TerminoYCondicion extends Model
{
    use HasFactory;

    protected $table = 'terminos_y_condiciones'; // Nombre de la tabla
    protected $fillable = ['titulo', 'contenido']; // Campos que pueden ser llenados masivamente
}
