<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Project extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'location',
        'completed_at',
        'status',
        'featured_media_asset_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'content' => 'array',
        'completed_at' => 'date',
    ];

    /**
     * Glavna slika projekta
     */
    public function featuredMedia(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'featured_media_asset_id');
    }

    /**
     * Projekat može biti vezan za više servisa
     */
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'project_service');
    }

    /**
     * Audit
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
