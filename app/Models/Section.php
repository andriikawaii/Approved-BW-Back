<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Section extends Model
{
    use LogsActivity;

    protected $table = 'sections';

    protected $fillable = [
        'page_id',
        'type',
        'data',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'data' => 'array',
        'is_active' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['type', 'page_id', 'data', 'sort_order', 'is_active'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
}
