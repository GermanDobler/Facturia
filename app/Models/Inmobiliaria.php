<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inmobiliaria extends Model
{
    use SoftDeletes;

    protected $table = 'inmobiliarias';

    protected $fillable = [
        'nombre',
        'telefono',
        'email',
        'direccion',
        'localidad',
        'url_web',
        'activo',
        'instagram',
        'facebook',
        'whatsapp',
        'logo_url',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Accesor “linkeable”: asegura que URLs vengan con http(s)
    public function getUrlWebAttribute($value)
    {
        return $this->normalizeUrl($value);
    }
    public function getInstagramAttribute($value)
    {
        return $this->normalizeUrl($value);
    }
    public function getFacebookAttribute($value)
    {
        return $this->normalizeUrl($value);
    }
    protected function normalizeUrl(?string $value): ?string
    {
        if (!$value) return $value;
        if (preg_match('~^https?://~i', $value)) return $value;
        return 'https://' . ltrim($value, '/');
    }

    // Helper para mostrar teléfono/whatsapp “clickeable”
    public function telLink(): ?string
    {
        return $this->telefono ? 'tel:' . preg_replace('/\s+/', '', $this->telefono) : null;
    }

    public function waLink(): ?string
    {
        if (!$this->whatsapp) return null;
        $digits = preg_replace('/\D+/', '', $this->whatsapp);
        return "https://wa.me/{$digits}";
    }

    public function inmobiliariasRegistradasCount(): int
    {
        return Inmobiliaria::count();
    }
}
