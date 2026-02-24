<?php

namespace App\Services;

use App\Models\Page;

class CanonicalResolver
{
    /**
     * Build the canonical URL for a page.
     *
     * Uses FRONTEND_URL (the public-facing domain) + full_path.
     * Trailing slash: always append (consistent with sitemap & schema).
     */
    public static function resolve(Page $page): string
    {
        $base = rtrim(config('app.frontend_url', config('app.url')), '/');
        $path = $page->full_path ?? '/';

        // Homepage
        if ($path === '/' || $path === '') {
            return $base . '/';
        }

        // Ensure leading slash, strip trailing, then add one trailing slash
        $path = '/' . ltrim($path, '/');
        $path = rtrim($path, '/') . '/';

        return $base . $path;
    }
}
