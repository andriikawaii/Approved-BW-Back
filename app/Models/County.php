<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class County extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'state_code',
        'is_active',
    ];

    public function towns(): HasMany
    {
        return $this->hasMany(Town::class);
    }

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class);
    }
}
