<?php

namespace App\Models;

use App\Services\MediaUrlResolver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MediaAsset extends Model
{
    protected $table = 'media';

    protected $fillable = [
        'file_name',
        'file_path',
        'mime_type',
        'size',
        'alt_text',
        'title',
    ];

    protected $appends = ['url'];

    public function getUrlAttribute(): string
    {
        return MediaUrlResolver::url($this->file_path) ?? '';
    }

    public function isImage(): bool
    {
        return $this->mime_type
            && str_starts_with($this->mime_type, 'image/');
    }

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class, 'hero_media_id');
    }
}
