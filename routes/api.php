<?php

use App\Http\Controllers\Api\PageController;
use Illuminate\Support\Facades\Route;

// LIVE page fetch (published + cache u controlleru)
// Bitno: where('.*') da podrži /a/b/c
Route::get('/pages/{path}', [PageController::class, 'show'])
    ->where('path', '.*')
    ->middleware('page.redirect')
    ->name('api.pages.show');

// PREVIEW (draft + published, bez cache) - signed URL
Route::get('/preview/pages', [PageController::class, 'preview'])
    ->middleware('signed')
    ->name('api.pages.preview');
