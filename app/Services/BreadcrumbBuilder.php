<?php

namespace App\Services;

use App\Models\Page;

class BreadcrumbBuilder
{
    private const SITE_URL = 'https://www.builtwellct.com';

    /**
     * Build breadcrumb trail and BreadcrumbList JSON-LD for a page.
     *
     * @return array{items: array, schema: array}
     */
    public static function build(Page $page): array
    {
        $crumbs = self::buildTrail($page);

        return [
            'items'  => $crumbs,
            'schema' => self::toJsonLd($crumbs),
        ];
    }

    private static function buildTrail(Page $page): array
    {
        $trail = [self::crumb('Home', '/')];

        $template = $page->template_key;
        $county   = $page->county;
        $service  = $page->service;
        $town     = $page->town;

        // Home page — just "Home"
        if ($template === 'home') {
            return $trail;
        }

        // County hub: Home > County
        if ($template === 'county_hub') {
            $trail[] = self::crumb($county?->name ?? 'County', $page->full_path);
            return $trail;
        }

        // Office page: Home > County > Town
        if ($template === 'office') {
            if ($county) {
                $trail[] = self::crumb($county->name, '/' . $county->slug . '/');
            }
            $trail[] = self::crumb($town?->name ?? 'Office', $page->full_path);
            return $trail;
        }

        // Service global: Home > Service
        if ($template === 'service_global') {
            $trail[] = self::crumb($service?->name ?? 'Service', $page->full_path);
            return $trail;
        }

        // Service county: Home > County > Service
        if ($template === 'service_county') {
            if ($county) {
                $trail[] = self::crumb($county->name, '/' . $county->slug . '/');
            }
            $trail[] = self::crumb($service?->name ?? 'Service', $page->full_path);
            return $trail;
        }

        // Service town: Home > County > Service in County > Town
        if ($template === 'service_town') {
            if ($county) {
                $trail[] = self::crumb($county->name, '/' . $county->slug . '/');
            }
            if ($service && $county) {
                $trail[] = self::crumb(
                    $service->name . ' in ' . $county->name,
                    '/' . $county->slug . '/' . $service->slug . '/',
                );
            }
            $trail[] = self::crumb($town?->name ?? 'Town', $page->full_path);
            return $trail;
        }

        // Generic pages (about, faq, consultation, case_study):
        // Home > Page Title (derived from seo_title or path)
        $label = self::labelFromPath($page);
        $trail[] = self::crumb($label, $page->full_path);

        return $trail;
    }

    private static function crumb(string $label, string $path): array
    {
        return [
            'label' => $label,
            'url'   => $path,
        ];
    }

    /**
     * Derive a readable label from the page path for generic pages.
     */
    private static function labelFromPath(Page $page): string
    {
        if ($page->seo_title) {
            // Use the portion before any pipe/dash separator
            $title = preg_split('/\s*[|–—-]\s*/', $page->seo_title, 2);
            return trim($title[0]);
        }

        // Fallback: humanize the last path segment
        $segment = trim($page->full_path ?? '', '/');
        $segment = basename($segment);

        return str_replace('-', ' ', ucfirst($segment));
    }

    /**
     * Convert crumbs to BreadcrumbList JSON-LD (schema.org).
     */
    private static function toJsonLd(array $crumbs): array
    {
        $items = [];

        foreach ($crumbs as $i => $crumb) {
            $items[] = [
                '@type'    => 'ListItem',
                'position' => $i + 1,
                'name'     => $crumb['label'],
                'item'     => self::SITE_URL . $crumb['url'],
            ];
        }

        return [
            '@context'        => 'https://schema.org',
            '@type'           => 'BreadcrumbList',
            'itemListElement' => $items,
        ];
    }
}
