<?php

// App\Observers\PageObserver.php

namespace App\Observers;

use App\Models\Page;
use App\Http\Controllers\Api\PageController as ApiPageController;
use App\Models\Redirect;
use App\Support\Paths\PathNormalizer;
use Illuminate\Support\Facades\Cache;

class PageObserver
{
    public function created(Page $page): void
    {
        ApiPageController::forgetCacheForPath($page->full_path);
        Cache::forget('seo:sitemap.xml');
    }
    public function saving(Page $page): void
    {
        // Normalize path
        $page->full_path = PathNormalizer::normalize($page->full_path);

        // Auto-assign and validate schema_type
        $this->autoAssignSchema($page);
        $this->validateSchema($page);
    }

    /**
     * Auto-assign schema_type based on page type
     *
     * @param Page $page
     * @return void
     */
    protected function autoAssignSchema(Page $page): void
    {
        // Skip if user manually set schema_type to null (intentional override)
        if ($page->isDirty('schema_type') && $page->schema_type === null) {
            return;
        }

        // Auto-assign based on page type
        $schemaType = match ($page->type) {
            'service_global', 'service_county', 'service_town' => 'Service',
            'county_hub' => 'Place',
            'office' => 'HomeAndConstructionBusiness',
            'faq' => 'FAQPage',
            default => null,
        };

        // Only set if not already set or if page type changed
        if ($schemaType && ($page->schema_type === null || $page->isDirty('type'))) {
            $page->schema_type = $schemaType;
        }
    }

    /**
     * Validate schema_type rules (BuiltWell v3.3 LOCKED)
     *
     * @param Page $page
     * @return void
     * @throws \Exception if validation fails
     */
    protected function validateSchema(Page $page): void
    {
        if (!$page->schema_type) {
            return; // No schema to validate
        }

        switch ($page->schema_type) {
            case 'FAQPage':
                $this->validateFaqPage($page);
                break;

            case 'HomeAndConstructionBusiness':
                $this->validateHomeAndConstructionBusiness($page);
                break;

            case 'Service':
                $this->validateService($page);
                break;

            case 'Place':
                $this->validatePlace($page);
                break;
        }
    }

    /**
     * Validate FAQPage schema (LOCKED: only /faq/ pages)
     */
    protected function validateFaqPage(Page $page): void
    {
        if (!str_contains($page->full_path, '/faq')) {
            throw new \Exception(
                "FAQPage schema can only be used on pages with '/faq' in the URL path. " .
                "Current path: {$page->full_path}"
            );
        }
    }

    /**
     * Validate HomeAndConstructionBusiness schema (LOCKED: only Orange office)
     */
    protected function validateHomeAndConstructionBusiness(Page $page): void
    {
        $normalizedPath = rtrim($page->full_path, '/') . '/';

        if ($normalizedPath !== '/new-haven-county/orange-ct/') {
            throw new \Exception(
                "HomeAndConstructionBusiness schema can ONLY be used on the Orange office page " .
                "(/new-haven-county/orange-ct/). Current path: {$page->full_path}"
            );
        }
    }

    /**
     * Validate Service schema (should be on service pages)
     */
    protected function validateService(Page $page): void
    {
        $validTypes = ['service_global', 'service_county', 'service_town'];

        if (!in_array($page->type, $validTypes, true)) {
            throw new \Exception(
                "Service schema should only be used on service pages " .
                "(service_global, service_county, service_town). " .
                "Current page type: {$page->type}"
            );
        }
    }

    /**
     * Validate Place schema (should be on county/town pages)
     */
    protected function validatePlace(Page $page): void
    {
        $validTypes = ['county_hub', 'service_county', 'service_town'];

        if (!in_array($page->type, $validTypes, true)) {
            throw new \Exception(
                "Place schema should only be used on location pages " .
                "(county_hub, service_county, service_town). " .
                "Current page type: {$page->type}"
            );
        }
    }
    public function updated(Page $page): void
    {
        $oldPath = $page->getOriginal('full_path');
        $newPath = $page->full_path;

        $actorId = $page->updated_by ?? auth()->id();

        if ($oldPath && $oldPath !== $newPath) {

            // ne pravi redirect ako bi napravio besmisao
            if ($oldPath !== $newPath && !Redirect::wouldCreateLoop($oldPath, $newPath)) {
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
