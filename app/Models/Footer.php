<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Footer extends Model
{
    use HasFactory;

    protected $table = 'footer';

    protected $fillable = ['direccion', 'telefono', 'instagram', 'facebook', 'email'];

    protected $dates = ['created_at', 'updated_at'];

    // Si utilizas timestamps (created_at, updated_at), asegúrate de que estén habilitados
    public $timestamps = true;
}
