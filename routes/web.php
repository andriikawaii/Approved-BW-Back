<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin;

/*
|--------------------------------------------------------------------------
| Public redirects
|--------------------------------------------------------------------------
*/

// Logo / auth layout koristi route('home')
Route::get('/home', fn () => redirect('/dashboard'))->name('home');

// Root uvek vodi na dashboard
Route::redirect('/', '/dashboard');


/*
|--------------------------------------------------------------------------
| Authenticated dashboard
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:super_admin|editor|seo_manager'])->group(function () {

    Route::view('/dashboard', 'dashboard')->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | CMS / Admin
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->name('admin.')->group(function () {

        /*
        | Pages
        */
        Route::prefix('pages')->name('pages.')->group(function () {
            Route::get('/', Admin\Pages\Index::class)->name('index');
            Route::get('/create', Admin\Pages\Create::class)->name('create');
            Route::get('/{page}/edit', Admin\Pages\Edit::class)->name('edit');
        });

        /*
        | Services
        */
        Route::prefix('services')->name('services.')->group(function () {
            Route::get('/', Admin\Services\Index::class)->name('index');
            Route::get('/create', Admin\Services\Create::class)->name('create');
            Route::get('/{service}/edit', Admin\Services\Edit::class)->name('edit');
        });

        /*
        | Projects (Case Studies)
        */
        Route::prefix('projects')->name('projects.')->group(function () {
            Route::get('/', Admin\Projects\Index::class)->name('index');
            Route::get('/create', Admin\Projects\Create::class)->name('create');
            Route::get('/{project}/edit', Admin\Projects\Edit::class)->name('edit');
        });

        /*
        | Testimonials
        */
        Route::prefix('testimonials')->name('testimonials.')->group(function () {
            Route::get('/', Admin\Testimonials\Index::class)->name('index');
            Route::get('/create', Admin\Testimonials\Create::class)->name('create');
            Route::get('/{testimonial}/edit', Admin\Testimonials\Edit::class)->name('edit');
        });

        /*
        | Settings
        */
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/general', Admin\Settings\General::class)->name('general');
        });
                /*
        | References
        */
        Route::prefix('counties')->name('counties.')->group(function () {
            Route::get('/', Admin\Counties\Index::class)->name('index');
            Route::get('/create', Admin\Counties\Create::class)->name('create');
            Route::get('/{county}/edit', Admin\Counties\Edit::class)->name('edit');
        });

        Route::prefix('towns')->name('towns.')->group(function () {
            Route::get('/', Admin\Towns\Index::class)->name('index');
            Route::get('/create', Admin\Towns\Create::class)->name('create');
            Route::get('/{town}/edit', Admin\Towns\Edit::class)->name('edit');
        });

        Route::get('/media', Admin\Media\Index::class)->name('media.index');
        Route::get('/media/create', Admin\Media\Create::class)->name('media.create');
        Route::get('/media/{mediaAsset}/edit', Admin\Media\Edit::class)->name('media.edit');
        /*
        | SEO & Ops
        */
        Route::get('/redirects', Admin\Redirects\Index::class)->name('redirects.index');

        /*
        | System
        */
        Route::get('/rules', Admin\Rules\Index::class)->name('rules.index');


    });
});

require __DIR__ . '/settings.php';
