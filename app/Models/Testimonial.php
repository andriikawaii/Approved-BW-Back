<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Testimonial extends Model
{
    use LogsActivity;

    protected $fillable = [
        'author_name',
        'author_position',
        'company',
        'content',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['author_name', 'content', 'is_active', 'sort_order'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
