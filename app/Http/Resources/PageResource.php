<?php

namespace App\Http\Resources;

use App\Services\BreadcrumbBuilder;
use App\Services\CtaResolver;
use App\Services\FooterTemplateResolver;
use App\Services\PhoneResolver;
use App\Services\SchemaBuilder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Page API Resource
 *
 * BUILTWELL v3.3 Contract-Compliant Response
 *
 * Returns ONLY the fields specified in the contract:
 * - id, slug, template, seo, sections
 *
 * Section filtering:
 * - Only is_active === true sections are returned
 * - Sorted by sort_order
 * - data is returned raw (no transformation)
 */
class PageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $breadcrumbs = BreadcrumbBuilder::build($this->resource);
        $schemas = SchemaBuilder::build($this->resource);

        // ===== BUILTWELL v3.3 CONTRACT-COMPLIANT RESPONSE =====
        return [
            'id'       => $this->id,
            'slug'     => $this->full_path,
            'template' => $this->template_key,

            'phones' => PhoneResolver::resolve($this->resource),
            'footer' => FooterTemplateResolver::resolve($this->resource),
            'breadcrumbs' => $breadcrumbs,

            'seo' => [
                'title'       => $this->seo_title ?: null,
                'description' => $this->seo_description ?: null,
                'canonical'   => $this->canonical_url ?: null,
                'schemas'     => $schemas,
            ],

            // ✅ FIXED: sections at root level, filtered & sorted
            'sections' => $this->sections
                ->filter(fn ($s) => $s->is_active === true)  // Only active sections
                ->sortBy('sort_order')                       // Ordered by sort_order
                ->values()                                    // Re-index array
                ->map(fn ($s) => [
                    'id'        => $s->id,
                    'type'      => $s->type,
                    'data'      => CtaResolver::resolve($this->resource, $s->data ?? [], $s->type),
                    'is_active' => true,
                ])
                ->all(),
        ];
    }
}
