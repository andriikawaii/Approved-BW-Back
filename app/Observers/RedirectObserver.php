<?php

namespace App\Observers;

use App\Http\Controllers\Api\PageController as ApiPageController;
use App\Models\Redirect;
use App\Support\Paths\RedirectPathNormalizer;

class RedirectObserver
{
    public function saving(Redirect $redirect): void
    {
        $redirect->from_path = RedirectPathNormalizer::from($redirect->from_path);
        $redirect->to_path   = RedirectPathNormalizer::to($redirect->to_path);
    }

    public function created(Redirect $redirect): void
    {
        $this->forgetCache($redirect);
    }

    public function updated(Redirect $redirect): void
    {
        $dirty = array_keys($redirect->getDirty());
        sort($dirty);

        if ($dirty === ['hits']) {
            return;
        }

        $this->forgetCache($redirect);

        $originalFrom = $redirect->getOriginal('from_path');
        $originalTo = $redirect->getOriginal('to_path');

        if ($originalFrom && $originalFrom !== $redirect->from_path) {
            ApiPageController::forgetCacheForPath($originalFrom);
        }

        if (
            $originalTo &&
            $originalTo !== $redirect->to_path &&
            !preg_match('#^https?://#i', $originalTo)
        ) {
            ApiPageController::forgetCacheForPath($originalTo);
        }
    }

    public function deleted(Redirect $redirect): void
    {
        $this->forgetCache($redirect);
    }

    private function forgetCache(Redirect $redirect): void
    {
        ApiPageController::forgetCacheForPath($redirect->from_path);

        if (!preg_match('#^https?://#i', $redirect->to_path)) {
            ApiPageController::forgetCacheForPath($redirect->to_path);
        }
    }
}
