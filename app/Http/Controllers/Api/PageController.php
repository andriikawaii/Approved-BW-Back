<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PageResource;
use App\Models\Page;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PageController extends Controller
{
    /**
     * GET /api/pages/{path}
     * PUBLIC endpoint
     * - samo published
     * - published_at <= now() ili null
     * - sa cache-om
     */
    public function show(string $path): JsonResponse
    {
        $fullPath = $this->normalizePath($path);
        $cacheKey = self::cacheKeyForPath($fullPath);

        $page = Cache::remember($cacheKey, now()->addMinutes((int) config('pages.cache_ttl')), function () use ($fullPath) {
            return Page::query()
                ->publicVisible()
                ->where('full_path', $fullPath)
                ->with(['sections'])
                ->first();
        });

        if (!$page) {
            return response()->json(['message' => 'Page not found'], 404);
        }

        return response()->json(
            new PageResource($page)
        );
    }

    /**
     * GET /api/preview/pages?path=...
     * PREVIEW endpoint
     * - draft + published
     * - bez cache-a
     * - signed URL
     */
    public function preview(Request $request): JsonResponse
    {
        $path = (string) $request->query('path', '');
        $fullPath = $this->normalizePath($path);

        $page = Page::query()
            ->where('full_path', $fullPath)
            ->whereIn('status', ['draft', 'published'])
            ->with(['sections'])
            ->first();

        if (!$page) {
            return response()->json(['message' => 'Page not found'], 404);
        }

        return response()->json(
            new PageResource($page)
        );
    }

    /**
     * Normalizacija path-a
     */
    protected function normalizePath(string $path): string
    {
        $path = trim($path);

        if ($path === '') {
            return '/';
        }

        $path = '/' . ltrim($path, '/');

        return $path === '/' ? '/' : rtrim($path, '/');
    }

    protected static function cacheKeyForPath(string $fullPath): string
    {
        return 'page_api:' . md5($fullPath);
    }

    /**
     * Cache invalidation helper
     */
    public static function forgetCacheForPath(string $path): void
    {
        $path = '/' . ltrim(trim($path), '/');
        $path = $path === '/' ? '/' : rtrim($path, '/');

        Cache::forget(self::cacheKeyForPath($path));
    }
}
