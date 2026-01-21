<?php

namespace App\Observers;

use App\Models\Section;
use App\Models\Page;
use App\Http\Controllers\Api\PageController as ApiPageController;
use Illuminate\Support\Facades\Cache;

class SectionObserver
{
    public function created(Section $section): void { $this->forgetForSection($section); }
    public function updated(Section $section): void { $this->forgetForSection($section); }
    public function deleted(Section $section): void { $this->forgetForSection($section); }
    public function restored(Section $section): void { $this->forgetForSection($section); }
    public function forceDeleted(Section $section): void { $this->forgetForSection($section); }

    protected function forgetForSection(Section $section): void
    {
        $fullPath = Page::query()
            ->whereKey($section->page_id)
            ->value('full_path');

        if ($fullPath) {
            ApiPageController::forgetCacheForPath($fullPath);
        }

        // nije obavezno, ali realno: content se promenio => updated_at page može da se menja,
        // a i ti želiš da sitemap lastmod prati izmene.
        Cache::forget('seo:sitemap.xml');
    }
}
