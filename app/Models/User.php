<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
        ];
    }

    public function userAttributes(): HasMany
    {
        return $this->hasMany(UserAttribute::class);
    }

    public function attributes(): HasMany
    {
        return $this->hasMany(UserAttribute::class);
    }

    /**
     * Check if the user has a specific role.
     *
     * @param string $role
     * @return bool
     */
    public function hasRole($role)
    {
        return $this->userAttributes()
            ->whereHas('attribute', function ($query) use ($role) {
                $query->where('name', 'role')
                    ->where('value', $role);
            })
            ->exists();
    }

    // Öznitelik değerini almak için yardımcı metod
    public function getAttribute($attributeName)
    {
        return $this->userAttributes()
            ->whereHas('attribute', function ($query) use ($attributeName) {
                $query->where('name', $attributeName);
            })
            ->first()?->value;
    }
}
