<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/*
|===========================================================================
| User Model
|===========================================================================
| Modelo de usuario con soporte para:
|   - API tokens (Sanctum)
|   - Avatar de perfil (avatar_url)
|   - Método helper updateAvatar()
|===========================================================================
*/

class User extends Authenticatable
{
    /** @use HasApiTokens — Habilita la autenticación por tokens de API (Sanctum) */
    use HasApiTokens, HasFactory, Notifiable;

    /*
    | Campos asignables masivamente (Mass Assignment)
    | avatar_url se agregó para el módulo de transferencia de archivos
    */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar_url',
    ];

    /*
    | Campos ocultos en respuestas JSON
    */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /*
    | Casting de tipos para atributos
    */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | updateAvatar()
    |--------------------------------------------------------------------------
    | Método helper para actualizar la ruta del avatar en la base de datos.
    | Encapsula la lógica de actualización para mantener el controlador limpio.
    |
    | @param string $path  Ruta relativa del archivo (ej: "avatars/uuid.jpg")
    |--------------------------------------------------------------------------
    */
    public function updateAvatar(string $path): void
    {
        $this->update(['avatar_url' => $path]);
    }
}
