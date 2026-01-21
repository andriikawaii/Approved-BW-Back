<?php

use App\Http\Controllers\Admin\Pages\PagePreviewController;
use App\Services\Seo\SitemapGenerator;
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

Route::get('/sitemap.xml', function (SitemapGenerator $gen) {
    $xml = Cache::remember('seo:sitemap.xml', now()->addHours(6), function () use ($gen) {
        return $gen->generate();
    });

    return response($xml, 200)
        ->header('Content-Type', 'application/xml; charset=UTF-8');
})->name('seo.sitemap');

Route::get('/robots.txt', function () {
    $appUrl = rtrim(config('app.url'), '/');

    $txt = implode("\n", [
        "User-agent: *",
        "Allow: /",
        "",
        "Sitemap: {$appUrl}/sitemap.xml",
        "",
    ]);

    return response($txt, 200)
        ->header('Content-Type', 'text/plain; charset=UTF-8');
})->name('seo.robots');
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
        Route::get('/pages/{page}/preview', [PagePreviewController::class, '__invoke'])
            ->name('pages.preview');
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
        Route::prefix('redirects')->name('redirects.')->group(function () {
            Route::get('/', Admin\Redirects\Index::class)->name('index');
            Route::get('/create', Admin\Redirects\Create::class)->name('create');
            Route::get('/{redirect}/edit', Admin\Redirects\Edit::class)->name('edit');
        });
        /*
        | System
        */
        Route::get('/rules', Admin\Rules\Index::class)->name('rules.index');


    });
});

require __DIR__ . '/settings.php';
