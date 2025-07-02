<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /**
     * Trait para facilitar a criação de factories para testes e seeders.
     *
     * @use HasFactory<\Database\Factories\UserFactory>
     */
    use HasFactory, Notifiable;

    /**
     * Os atributos que podem ser preenchidos via mass assignment.
     *
     * Protege contra atribuição em massa de campos não autorizados.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_photo',
    ];

    /**
     * Os atributos que devem ser ocultados na serialização para arrays ou JSON.
     *
     * Geralmente usados para proteger informações sensíveis.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Define os casts para atributos, transformando-os automaticamente.
     *
     * - 'email_verified_at' como objeto datetime
     * - 'password' será automaticamente hasheado ao ser atribuído
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
