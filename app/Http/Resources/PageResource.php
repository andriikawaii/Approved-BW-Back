<?php

namespace App\Http\Resources;

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
        // Build schema object
        $baseSchema = $this->schema_type
            ? ['@type' => $this->schema_type]
            : [];

        $schemaOverrides = is_array($this->schema_overrides)
            ? $this->schema_overrides
            : [];

        $schema = array_replace_recursive($baseSchema, $schemaOverrides);

        // ===== BUILTWELL v3.3 CONTRACT-COMPLIANT RESPONSE =====
        return [
            'id'       => $this->id,
            'slug'     => $this->full_path,  // ✅ FIXED: renamed from full_path
            'template' => $this->template_key,

            'seo' => [
                'title'       => $this->seo_title ?: null,
                'description' => $this->seo_description ?: null,
                'canonical'   => $this->canonical_url ?: null,
                'schema'      => $schema !== [] ? $schema : null,
            ],

            // ✅ FIXED: sections at root level, filtered & sorted
            'sections' => $this->sections
                ->filter(fn ($s) => $s->is_active === true)  // Only active sections
                ->sortBy('sort_order')                       // Ordered by sort_order
                ->values()                                    // Re-index array
                ->map(fn ($s) => [
                    'id'        => $s->id,
                    'type'      => $s->type,
                    'data'      => $s->data ?? [],           // Raw data (no transformation)
                    'is_active' => true,                      // Always true (filtered above)
                ])
                ->all(),
        ];
    }
}
