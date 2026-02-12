<?php

use App\Http\Controllers\Api\PageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Page API
|--------------------------------------------------------------------------
*/

// PUBLIC (published + published_at)
Route::get('/pages/{path?}', [PageController::class, 'show'])
    ->where('path', '.*')
    ->middleware('page.redirect')
    ->name('api.pages.show');

// PREVIEW (draft + published, bez cache)
Route::get('/preview/pages', [PageController::class, 'preview'])
    ->middleware('signed')
    ->name('api.pages.preview');
