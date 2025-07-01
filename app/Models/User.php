<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'telefono',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
        ];
    }

     protected $dates = ['deleted_at'];

    // Roles del sistema
    const ROLE_ADMIN = 'admin';
    const ROLE_MANAGER = 'manager';
    const ROLE_EMPLOYEE = 'employee';
    const ROLE_CUSTOMER = 'customer';

    //verifica si el usuario es administrador
    protected function isAdmin(){
        return $this->role === 'admin';
    }
    //verifica si el usuario es cliente
    protected function isCliente(){
        return $this->role === 'cliente';
    }

    //relacion con ventas
    public function ventas()
    {
        return $this->hasMany(Venta::class, 'user_id');
    }

    public function canAccessBackend(): bool
    {
        return in_array($this->role, [
            self::ROLE_ADMIN, 
            self::ROLE_MANAGER, 
            self::ROLE_EMPLOYEE
        ]) && $this->is_active;
    }

    public function updateLastLogin(): void
    {
        $this->update(['last_login_at' => now()]);
    }
}
