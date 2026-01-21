<?php

namespace App\Observers;

use App\Models\Page;
use App\Http\Controllers\Api\PageController as ApiPageController;
use Illuminate\Support\Facades\Cache;

class PageObserver
{
    public function created(Page $page): void
    {
        ApiPageController::forgetCacheForPath($page->full_path);
        Cache::forget('seo:sitemap.xml');
    }

    public function updated(Page $page): void
    {
        $oldPath = $page->getOriginal('full_path');
        $newPath = $page->full_path;

        if ($oldPath && $oldPath !== $newPath) {
            ApiPageController::forgetCacheForPath($oldPath);
        }

        ApiPageController::forgetCacheForPath($newPath);

        // ✅ sitemap treba da se rebuild-uje (status/path/seo/canonical/updated_at)
        Cache::forget('seo:sitemap.xml');
    }

    public function deleted(Page $page): void
    {
        ApiPageController::forgetCacheForPath($page->full_path);
        Cache::forget('seo:sitemap.xml');
    }

    public function restored(Page $page): void
    {
        ApiPageController::forgetCacheForPath($page->full_path);
        Cache::forget('seo:sitemap.xml');
    }

    public function forceDeleted(Page $page): void
    {
        ApiPageController::forgetCacheForPath($page->full_path);
        Cache::forget('seo:sitemap.xml');
    }
}
