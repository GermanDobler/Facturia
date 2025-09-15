<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SliderSecundario extends Model
{
    use HasFactory;

    protected $table = 'sliders_secundario';

    protected $fillable = ['imagen_url', 'orden'];
}
