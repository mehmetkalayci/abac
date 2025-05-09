<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'description'
    ];

    public function resourceAttributes(): HasMany
    {
        return $this->hasMany(ResourceAttribute::class);
    }
} 