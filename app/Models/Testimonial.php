<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Testimonial extends Model
{
    protected $fillable = [
        'author',
        'text',
        'rating',
        'service_id',
        'town_id',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function town(): BelongsTo
    {
        return $this->belongsTo(Town::class);
    }
}
