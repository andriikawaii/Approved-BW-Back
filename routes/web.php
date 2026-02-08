<?php

use App\Http\Controllers\Admin\Pages\PagePreviewController;
use App\Services\Seo\SitemapGenerator;
use Illuminate\Support\Facades\Cache;
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
    $frontUrl = rtrim(config('app.frontend_url', env('FRONTEND_URL', config('app.url'))), '/');

    $txt = implode("\n", [
        "# BuiltWell Construction - robots.txt",
        "# Updated: " . now()->format('Y-m-d'),
        "",
        "# Default crawlers",
        "User-agent: *",
        "Disallow: /admin",
        "Disallow: /api",
        "Disallow: /livewire",
        "Disallow: /preview",
        "Disallow: /*?",
        "Allow: /",
        "",
        "# AI Crawlers - OpenAI",
        "User-agent: GPTBot",
        "Disallow: /admin",
        "Disallow: /api",
        "Disallow: /livewire",
        "Disallow: /preview",
        "Allow: /",
        "",
        "User-agent: ChatGPT-User",
        "Disallow: /admin",
        "Disallow: /api",
        "Disallow: /livewire",
        "Disallow: /preview",
        "Allow: /",
        "",
        "# AI Crawlers - Anthropic",
        "User-agent: Claude-Web",
        "Disallow: /admin",
        "Disallow: /api",
        "Disallow: /livewire",
        "Disallow: /preview",
        "Allow: /",
        "",
        "# AI Crawlers - Google Gemini",
        "User-agent: Google-Extended",
        "Disallow: /admin",
        "Disallow: /api",
        "Disallow: /livewire",
        "Disallow: /preview",
        "Allow: /",
        "",
        "# Sitemap",
        "Sitemap: {$frontUrl}/sitemap.xml",
        "",
    ]);

    return response($txt, 200)->header('Content-Type', 'text/plain; charset=UTF-8');
})->name('seo.robots');

Route::get('/llms.txt', function () {
    $frontUrl = rtrim(config('app.frontend_url', env('FRONTEND_URL', config('app.url'))), '/');

    // Get contact info from settings (with fallbacks)
    $phoneMain = \App\Models\Setting::where('key', 'phone_main')->value('value') ?? '(203) 555-0100';
    $companyName = \App\Models\Setting::where('key', 'company_name')->value('value') ?? 'BUILTWELL Construction';

    $txt = implode("\n", [
        "# {$companyName}",
        "# Connecticut Home Remodeling & Construction",
        "# Last updated: " . now()->format('F d, Y'),
        "",
        "## About",
        "Licensed Connecticut Home Improvement Contractor (HIC #0668405).",
        "Serving Fairfield County and New Haven County since 2009.",
        "15+ years of experience. 5-star rated contractor.",
        "",
        "## Services",
        "We specialize in residential remodeling:",
        "- Kitchen Remodeling",
        "- Bathroom Remodeling",
        "- Basement Finishing",
        "- Flooring Installation",
        "- Home Additions",
        "- General Contracting",
        "",
        "## Service Area",
        "Primary Counties:",
        "- Fairfield County, Connecticut",
        "- New Haven County, Connecticut",
        "",
        "Major Towns Served:",
        "- Greenwich, Stamford, Darien, New Canaan (Fairfield County)",
        "- Orange, Milford, West Haven, Branford (New Haven County)",
        "",
        "## Contact",
        "Phone: {$phoneMain}",
        "Office: Orange, Connecticut",
        "Website: {$frontUrl}",
        "",
        "## Process",
        "We follow a transparent 5-step process:",
        "1. Free Consultation (on-site or remote via Google Meet/Zoom)",
        "2. Detailed Planning & Proposal",
        "3. Material Selections",
        "4. Construction with Daily Updates",
        "5. Final Walkthrough",
        "",
        "## Credentials",
        "- Connecticut HIC License #0668405",
        "- Fully Insured",
        "- Background-Checked Team",
        "- 5-Star Google Rating",
        "",
        "## Resources",
        "Sitemap: {$frontUrl}/sitemap.xml",
        "FAQ: {$frontUrl}/faq/",
        "Free Consultation: {$frontUrl}/free-consultation/",
        "",
    ]);

    return response($txt, 200)->header('Content-Type', 'text/plain; charset=UTF-8');
})->name('seo.llms');

/*
|--------------------------------------------------------------------------
| Authenticated area (ALL ADMINS)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:super_admin|editor|seo_manager'])->group(function () {

    Route::view('/dashboard', 'dashboard')->name('dashboard');

    Route::prefix('admin')->name('admin.')->group(function () {

        /*
        |--------------------------------------------------------------------------
        | CONTENT
        |--------------------------------------------------------------------------
        */
        Route::prefix('pages')->name('pages.')->group(function () {
            Route::get('/', Admin\Pages\Index::class)->name('index');
            Route::get('/create', Admin\Pages\Create::class)->name('create');
            Route::get('/{page}/edit', Admin\Pages\Edit::class)->name('edit');
        });

        // SECTION LIBRARY (novo)
        Route::prefix('sections')->name('sections.')->group(function () {
            Route::get('/', Admin\Sections\Index::class)->name('index');
        });

        /*
        |--------------------------------------------------------------------------
        | REFERENCES
        |--------------------------------------------------------------------------
        */
        Route::prefix('services')->name('services.')->group(function () {
            Route::get('/', Admin\Services\Index::class)->name('index');
            Route::get('/create', Admin\Services\Create::class)->name('create');
            Route::get('/{service}/edit', Admin\Services\Edit::class)->name('edit');
        });

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

        Route::prefix('media')->name('media.')->group(function () {
            Route::get('/', Admin\Media\Index::class)->name('index');
            Route::get('/create', Admin\Media\Create::class)->name('create');
            Route::get('/{mediaAsset}/edit', Admin\Media\Edit::class)->name('edit');
        });

        /*
        |--------------------------------------------------------------------------
        | SOCIAL PROOF
        |--------------------------------------------------------------------------
        */
        Route::prefix('testimonials')->name('testimonials.')->group(function () {
            Route::get('/', Admin\Testimonials\Index::class)->name('index');
            Route::get('/create', Admin\Testimonials\Create::class)->name('create');
            Route::get('/{testimonial}/edit', Admin\Testimonials\Edit::class)->name('edit');
        });

        Route::prefix('projects')->name('projects.')->group(function () {
            Route::get('/', Admin\Projects\Index::class)->name('index');
            Route::get('/create', Admin\Projects\Create::class)->name('create');
            Route::get('/{project}/edit', Admin\Projects\Edit::class)->name('edit');
        });

        /*
        |--------------------------------------------------------------------------
        | SEO & OPS (seo_manager + super_admin)
        |--------------------------------------------------------------------------
        */
        Route::middleware('role:seo_manager|super_admin')->group(function () {

            // Redirects
            Route::prefix('redirects')->name('redirects.')->group(function () {
                Route::get('/', Admin\Redirects\Index::class)->name('index');
                Route::get('/create', Admin\Redirects\Create::class)->name('create');
                Route::get('/{redirect}/edit', Admin\Redirects\Edit::class)->name('edit');
            });

            // SEO tools (novo)
            Route::prefix('seo')->name('seo.')->group(function () {
                Route::get('/sitemap', Admin\Seo\Sitemap::class)->name('sitemap');
                Route::get('/html-sitemap', \App\Livewire\Admin\Seo\HtmlSitemap::class)
                    ->name('html-sitemap');
                Route::get('/robots', Admin\Seo\Robots::class)->name('robots');
                Route::get('/llms', Admin\Seo\Llms::class)->name('llms');
                Route::get('/settings', Admin\Seo\Settings::class)->name('settings');
            });
        });

        /*
        |--------------------------------------------------------------------------
        | SETTINGS (seo_manager + super_admin) - zadrži kako si imao u sidebaru
        |--------------------------------------------------------------------------
        */
        Route::middleware('role:seo_manager|super_admin')->group(function () {
            Route::prefix('settings')->name('settings.')->group(function () {
                Route::get('/general', Admin\Settings\General::class)->name('general');
            });
        });

        /*
        |--------------------------------------------------------------------------
        | SYSTEM (super_admin only)
        |--------------------------------------------------------------------------
        */
        Route::middleware('role:super_admin')->group(function () {

            // Admin Users (Livewire) (postojeće)
            Route::prefix('users')->name('users.')->group(function () {
                Route::get('/', Admin\Users\Index::class)->name('index');
                Route::get('/create', Admin\Users\Create::class)->name('create');
                Route::get('/{user}/edit', Admin\Users\Edit::class)->name('edit');
            });

            // Roles screen (novo)
            Route::prefix('system')->name('system.')->group(function () {
                Route::get('/roles', Admin\System\Roles::class)->name('roles');
            });

            // Ops (novo)
            Route::prefix('ops')->name('ops.')->group(function () {
                Route::get('/cache', Admin\Ops\Cache::class)->name('cache');
                Route::get('/activity', Admin\Ops\Activity::class)->name('activity');
            });

            // Rules (Locked) (postojeće)
            Route::get('/rules', Admin\Rules\Index::class)->name('rules.index');
        });
    });
});

/*
|--------------------------------------------------------------------------
| Auth / profile / passwords
|--------------------------------------------------------------------------
*/
require __DIR__ . '/settings.php';

