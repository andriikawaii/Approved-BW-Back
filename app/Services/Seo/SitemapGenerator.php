<?php

namespace App\Services\Seo;

use App\Models\Page;
use App\Services\CanonicalResolver;

class SitemapGenerator
{
    public function generate(): string
    {
        $pages = Page::query()
            ->select(['id', 'full_path', 'updated_at'])
            ->publicVisible()
            ->orderBy('full_path')
            ->get();

        $urls = [];

        foreach ($pages as $page) {
            $loc = CanonicalResolver::resolve($page);

            $urls[] = [
                'loc' => $loc,
                'lastmod' => optional($page->updated_at)->toAtomString(),
            ];
        }

        return $this->renderXml($urls);
    }


    private function fullUrlFromPath(string $appUrl, string $fullPath): string
    {
        $fullPath = trim($fullPath);

        // "/" -> base url
        if ($fullPath === '/' || $fullPath === '') {
            return $appUrl . '/';
        }

        return $appUrl . '/' . ltrim($fullPath, '/');
    }

    private function renderXml(array $urls): string
    {
        $xml = [];
        $xml[] = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml[] = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ($urls as $u) {
            $loc = $this->xmlEscape($u['loc']);
            $xml[] = '  <url>';
            $xml[] = "    <loc>{$loc}</loc>";

            if (!empty($u['lastmod'])) {
                $xml[] = "    <lastmod>{$this->xmlEscape($u['lastmod'])}</lastmod>";
            }

            $xml[] = '  </url>';
        }

        $xml[] = '</urlset>';

        return implode("\n", $xml);
    }

    private function xmlEscape(string $value): string
    {
        return htmlspecialchars($value, ENT_XML1 | ENT_COMPAT, 'UTF-8');
    }
}
