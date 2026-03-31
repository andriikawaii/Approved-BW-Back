<?php

namespace App\Http\Resources;

use App\Services\BreadcrumbBuilder;
use App\Services\CanonicalResolver;
use App\Services\FooterTemplateResolver;
use App\Services\PhoneResolver;
use App\Services\MediaUrlResolver;
use App\Services\SchemaBuilder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $sections = $this->resource->relationLoaded('sections')
            ? $this->sections
            : collect();

        $page = $this->resource;

        // Ensure relations are loaded for resolvers
        if (!$page->relationLoaded('county')) {
            $page->load('county');
        }
        if (!$page->relationLoaded('town')) {
            $page->load('town');
        }
        if (!$page->relationLoaded('service')) {
            $page->load('service');
        }

        $phones = PhoneResolver::resolve($page);
        $footer = FooterTemplateResolver::resolve($page);
        $breadcrumbs = BreadcrumbBuilder::build($page);
        $schema = SchemaBuilder::build($page);

        return [
            'id' => $this->id,
            'slug' => $this->full_path,
            'template' => $this->template_key,

            'seo' => [
                'title' => (string) ($this->seo_title ?? ''),
                'description' => (string) ($this->seo_description ?? ''),
                'canonical' => CanonicalResolver::resolve($page),
                'og_image_alt' => $this->og_image_alt,
                'robots' => $this->robots,
            ],

            'phones' => $phones,

            'footer' => $footer,

            'breadcrumbs' => $breadcrumbs['items'],

            'schema' => $schema,

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
                        'data' => MediaUrlResolver::resolveSection($data),
                        'is_active' => (bool) $section->is_active,
                    ];
                })
                ->all(),
        ];
    }
}
