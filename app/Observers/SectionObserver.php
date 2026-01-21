<?php
// App\Observers\SectionObserver.php

namespace App\Observers;

use App\Models\Section;
use App\Models\Page;
use App\Http\Controllers\Api\PageController as ApiPageController;
use Illuminate\Support\Facades\Cache;

class SectionObserver
{
    public function created(Section $section): void { $this->handle($section); }
    public function updated(Section $section): void { $this->handle($section); }
    public function deleted(Section $section): void { $this->handle($section); }
    public function restored(Section $section): void { $this->handle($section); }
    public function forceDeleted(Section $section): void { $this->handle($section); }

    protected function handle(Section $section): void
    {
        $page = Page::find($section->page_id);

        if ($page) {
            // ✅ da sitemap lastmod prati promene sekcija
            $page->touch();

            // ✅ bust page cache
            ApiPageController::forgetCacheForPath($page->full_path);
        }

        Cache::forget('seo:sitemap.xml');
    }
}
