<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PageController extends Controller
{
    /**
     * GET /api/pages/{path}
     * LIVE: samo published + cache 10min
     */
    public function show(string $path): JsonResponse
    {
        $fullPath = $this->normalizePath($path);

        // stabilan cache key (ne zavisi od / ili bez /)
        $cacheKey = $this->cacheKeyForPath($fullPath);

        $data = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($fullPath) {

            $page = Page::query()
                ->where('full_path', $fullPath)
                ->where('status', 'published')
                ->with(['sections' => function ($q) {
                    $q->where('is_active', true)
                        ->orderBy('sort_order');
                }])
                ->first();

            if (!$page) {
                return null;
            }

            return $this->toDto($page, false);
        });

        if (!$data) {
            return response()->json([
                'message' => 'Page not found',
            ], 404);
        }

        return response()->json($data);
    }

    /**
     * GET /api/preview/pages?path=...
     * PREVIEW: draft + published, bez cache
     * (signed middleware mora da prođe)
     */
    public function preview(Request $request): JsonResponse
    {
        $path = (string) $request->query('path', '');
        $fullPath = $this->normalizePath($path);

        $page = Page::query()
            ->where('full_path', $fullPath)
            ->with(['sections' => function ($q) {
                $q->where('is_active', true)
                    ->orderBy('sort_order');
            }])
            ->first();

        if (!$page) {
            return response()->json(['message' => 'Page not found'], 404);
        }

        return response()->json($this->toDto($page, true));
    }

    /**
     * Transform Page → Frontend DTO
     */
    protected function toDto(Page $page, bool $isPreview): array
    {
        return [
            'id'       => $page->id,
            'slug'     => ltrim($page->full_path, '/'),
            'template' => $page->template_key,
            'status'   => $page->status,
            'preview'  => $isPreview,

            'seo' => [
                'title'       => $page->seo_title ?? '',
                'description' => $page->seo_description ?? '',
                'canonical'   => $page->canonical_url,
            ],

            // već su filtrirane is_active i sortirane u query-u
            'sections' => $page->sections->map(fn ($section) => [
                'id'        => $section->id,
                'type'      => $section->type,
                'data'      => $section->data ?? new \stdClass(),
                'is_active' => (bool) $section->is_active,
            ])->values()->toArray(),
        ];
    }

    /**
     * /a/b/  -> /a/b
     * a/b    -> /a/b
     * /      -> /
     */
    protected function normalizePath(string $path): string
    {
        $path = trim($path);
        $path = '/' . ltrim($path, '/');
        return $path === '/' ? '/' : rtrim($path, '/');
    }

    protected function cacheKeyForPath(string $fullPath): string
    {
        // md5 je sigurniji za duge path-ove + nema specijalnih karaktera
        return 'page_api:' . md5($fullPath);
    }

    /**
     * Helper da drugi delovi sistema mogu da obrišu cache po path-u.
     */
    public static function forgetCacheForPath(string $path): void
    {
        $fullPath = '/' . ltrim(trim($path), '/');
        $fullPath = $fullPath === '/' ? '/' : rtrim($fullPath, '/');

        Cache::forget('page_api:' . md5($fullPath));
    }
}
