<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'display_name', 'email', 'password', 'role', 'avatar',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /**
     * Override: gunakan 'name' sebagai username untuk Auth::attempt.
     * Ini memungkinkan login menggunakan name alih-alih email.
     */
    public function getAuthIdentifierName(): string
    {
        return 'name';
    }

    /**
     * Cek apakah user adalah superAdmin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'superAdmin';
    }

    /**
     * Nama tampilan — gunakan display_name jika ada, fallback ke name.
     */
    public function getLabel(): string
    {
        return $this->display_name ?: $this->name;
    }
}
