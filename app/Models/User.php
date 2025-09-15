<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cuil',
        'matricula',
        'nombre',
        'apellido',
        'email',
        'telefono',
        'password',
        'role',
        'activo',
        'observacion'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /* ================== Relaciones ================== */

    // Cuotas del usuario (matriculado)
    public function cuotas(): HasMany
    {
        return $this->hasMany(Cuota::class, 'usuario_id');
    }

    // Pagos del usuario a través de sus cuotas
    public function pagos(): HasManyThrough
    {
        return $this->hasManyThrough(
            Pago::class,     // modelo final
            Cuota::class,    // modelo intermedio
            'usuario_id',    // FK en cuotas -> users.id
            'cuota_id',      // FK en pagos -> cuotas.id
            'id',            // PK users
            'id'             // PK cuotas
        );
    }

    public function fullName(): string
    {
        return trim("{$this->nombre} {$this->apellido}");
    }

    public function matriculadosActivosCount()
    {
        return User::where('role', 'user')->where('activo', 'si')->count();
    }

    /**
     * Obtiene la última cuota aprobada del usuario.
     */
    // public function ultimaCuotaAprobada(): ?Cuota
    // {
    //     $latestCuotaId = Cuota::select('id')
    //         ->where('usuario_id', $this->id)
    //         ->where('estado', 'aprobado')
    //         ->orderBy('id', 'desc')
    //         ->limit(1)
    //         ->value('id');

    //     return $latestCuotaId ? Cuota::find($latestCuotaId) : null;
    // }

    // Relación para la última cuota aprobada (HasOne con ofMany)
    public function ultimaCuotaAprobada(): HasOne
    {
        return $this->hasOne(Cuota::class, 'usuario_id')
            ->ofMany('id', 'MAX')  // Última por ID más alto
            ->where('estado', 'aprobado');
    }
}
