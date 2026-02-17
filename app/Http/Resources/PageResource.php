<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $sections = $this->resource->relationLoaded('sections')
            ? $this->sections
            : collect();

        return [
            'id' => $this->id,
            'slug' => $this->full_path,
            'template' => $this->template_key,
            'seo' => [
                'title' => (string) ($this->seo_title ?? ''),
                'description' => (string) ($this->seo_description ?? ''),
            ],
            'sections' => $sections
                ->values()
                ->map(function ($section) {
                    $data = $section->data;

                    if (!is_array($data) && !is_object($data)) {
                        $data = (object) [];
                    }

                    return [
                        'id' => $section->id,
                        'type' => $section->type,
                        'data' => $data,
                        'is_active' => (bool) $section->is_active,
                    ];
                })
                ->all(),
        ];
    }
}
