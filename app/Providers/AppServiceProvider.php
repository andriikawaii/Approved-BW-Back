<?php

namespace App\Providers;

use App\Models\Page;
use App\Models\Redirect;
use App\Models\Section;
use App\Observers\PageObserver;
use App\Observers\RedirectObserver;
use App\Observers\SectionObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Page::observe(PageObserver::class);
        Section::observe(SectionObserver::class);
        Redirect::observe(RedirectObserver::class);
    }
}
