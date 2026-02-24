<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Page extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['full_path', 'template_key', 'status', 'seo_title', 'seo_description', 'canonical_url'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected $fillable = [
        'full_path',
        'type',
        'template_key',
        'status',
        'published_at',

        'seo_title',
        'seo_description',
        'canonical_url',

        'schema_type',
        'schema_overrides',

        'content',

        'service_id',
        'county_id',
        'town_id',
        'hero_media_asset_id',

        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'content' => 'array',
        'schema_overrides' => 'array',
        'published_at' => 'datetime',
    ];

    /* -----------------
     | Relations
     |-----------------*/

    public function sections()
    {
        return $this->hasMany(Section::class)->orderBy('sort_order');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function county(): BelongsTo
    {
        return $this->belongsTo(County::class);
    }

    public function town(): BelongsTo
    {
        return $this->belongsTo(Town::class);
    }

    public function heroMedia(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'hero_media_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /* -----------------
     | Scopes
     |-----------------*/
    public function scopePublicVisible($query)
    {
        return $query
            ->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeByPath($query, string $path)
    {
        return $query->where('full_path', $path);
    }
}
