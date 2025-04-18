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
        'description',
        'type',
        'entity_type'
    ];

    // Öznitelik değerlerinin tipini belirtmek için
    const TYPES = [
        'string',
        'integer',
        'boolean',
        'datetime',
        'array'
    ];

    // Hangi varlık tipine ait olduğunu belirtmek için
    const ENTITY_TYPES = [
        'user',
        'resource',
        'environment'
    ];

    /**
     * Get the user attributes for the attribute.
     */
    public function userAttributes(): HasMany
    {
        return $this->hasMany(UserAttribute::class);
    }

    public function policyAttributes(): HasMany
    {
        return $this->hasMany(PolicyAttribute::class);
    }

    public function resourceAttributes(): HasMany
    {
        return $this->hasMany(ResourceAttribute::class);
    }
}
