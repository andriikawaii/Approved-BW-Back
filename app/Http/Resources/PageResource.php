<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'full_path' => $this->full_path,
            'template'  => $this->template_key,

            'seo' => [
                'title'       => $this->seo_title ?: null,
                'description' => $this->seo_description ?: null,
                'canonical'   => $this->canonical_url ?: null,
                'schema'      => $this->schema_type
                    ? ['@type' => $this->schema_type]
                    : null,
            ],

            'context' => [
                'service_id' => $this->service_id,
                'county_id'  => $this->county_id,
                'town_id'    => $this->town_id,
            ],

            'content' => [
                'sections' => $this->sections
                    ->sortBy('sort_order')
                    ->values()
                    ->map(fn ($s) => [
                        'id'        => $s->id,
                        'type'      => $s->type,
                        'data'      => $s->data ?? [],
                        'is_active' => (bool) $s->is_active,
                    ])
                    ->all(),
            ],

            'meta' => [
                'status'       => $this->status,
                'published_at' => optional($this->published_at)?->toISOString(),
                'preview'      => $request->routeIs('api.pages.preview'),
            ],
        ];
    }
}
