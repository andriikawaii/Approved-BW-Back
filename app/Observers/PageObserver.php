<?php

// App\Observers\PageObserver.php

namespace App\Observers;

use App\Models\Page;
use App\Http\Controllers\Api\PageController as ApiPageController;
use App\Models\Redirect;
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

        $actorId = $page->updated_by ?? auth()->id();

        if ($oldPath && $oldPath !== $newPath) {

            // ne pravi redirect ako bi napravio besmisao
            if ($oldPath !== $newPath) {
                Redirect::updateOrCreate(
                    ['from_path' => $oldPath],
                    [
                        'to_path'     => $newPath,
                        'status_code' => 301,
                        'is_active'   => true,
                        'updated_by'  => $actorId,
                        'created_by'  => $page->created_by ?? $actorId,
                    ]
                );
            }

            ApiPageController::forgetCacheForPath($oldPath);
        }

        ApiPageController::forgetCacheForPath($newPath);
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
