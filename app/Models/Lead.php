<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'town',
        'zip',
        'consultation_type',
        'contact_method',
        'best_time',
        'services',
        'message',
        'source_page',
        'source_page_path',
        'ip_address',
        'user_agent',
        'status',
        'emailed_at',
    ];

    protected $casts = [
        'services' => 'array',
        'emailed_at' => 'datetime',
    ];
}
