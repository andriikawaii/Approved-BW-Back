<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Project extends Model
{
    use LogsActivity;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'description',
        'client',
        'completed_at',
        'is_published',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'completed_at' => 'date',
        'is_published' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'slug', 'is_published'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'project_service');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}
