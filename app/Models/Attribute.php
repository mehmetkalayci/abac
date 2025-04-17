<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attribute extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'value',
    ];

    /**
     * Get the user attributes for the attribute.
     */
    public function userAttributes()
    {
        return $this->hasMany(UserAttribute::class);
    }

    public function policyAttributes(): HasMany
    {
        return $this->hasMany(PolicyAttribute::class);
    }
}
