<?php

namespace Database\Seeders;

use App\Http\Controllers\Api\PageController as ApiPageController;
use App\Models\Page;
use DOMDocument;
use DOMXPath;
use Illuminate\Database\Seeder;

/**
 * Populates seo_title, seo_description, canonical_url, og_image_alt, robots,
 * and schema_overrides for every page that has a matching HTML file in the
 * client's static deploy folder.
 *
 * Usage:
 *   php artisan db:seed --class=SeoFromHtmlSeeder
 *
 * Safe to run multiple times — uses updateOrSkip logic (only writes if value
 * changed or was previously null).
 */
class SeoFromHtmlSeeder extends Seeder
{
    /**
     * Root folder of the client's static-deploy HTML files.
     * Each page is expected at:  {HTML_ROOT}/{slug}/index.html
     * Homepage is at:            {HTML_ROOT}/index.html
     */
    private const HTML_ROOT = 'C:/Users/georg/Downloads/BuiltWell-Deploy-Final-March-23-2026-20260324T140636Z-1-001/BuiltWell-Deploy-Final-March-23-2026';

    /**
     * Domain used in the static deploy (may differ from production URL).
     * URLs referencing this domain inside schemas will be left as-is;
     * they are not corrected here to avoid accidental data corruption.
     */
    private const DEPLOY_DOMAIN = 'buildwellct.com';

    public function run(): void
    {
        $root = rtrim(self::HTML_ROOT, '/\\');

        if (!is_dir($root)) {
            $this->command->error("HTML root not found: {$root}");
            return;
        }

        $htmlFiles = $this->findHtmlFiles($root);
        $this->command->info(sprintf('Found %d HTML files.', count($htmlFiles)));

        $updated  = 0;
        $skipped  = 0;
        $notFound = [];

        foreach ($htmlFiles as $htmlPath) {
            $fullPath = $this->htmlPathToPagePath($htmlPath, $root);
            $page = Page::where('full_path', $fullPath)->first();

            if (!$page) {
                $notFound[] = $fullPath;
                continue;
            }

            $data = $this->extractSeoData($htmlPath);

            $changes = array_filter([
                'seo_title'       => $data['title']       ?: null,
                'seo_description' => $data['description'] ?: null,
                'canonical_url'   => $data['canonical']   ?: null,
                'og_image_alt'    => $data['og_image_alt'] ?: null,
                'robots'          => $this->normalizeRobots($data['robots']),
                'schema_overrides' => !empty($data['schemas']) ? $data['schemas'] : null,
            ], fn ($v) => $v !== null);

            if (empty($changes)) {
                $skipped++;
                continue;
            }

            $page->update($changes);

            // Invalidate API cache for this page
            ApiPageController::forgetCacheForPath($fullPath);

            $this->command->line(sprintf(
                '  <info>✓</info> %s — title: "%s"  schemas: %d',
                $fullPath,
                $data['title'],
                count($data['schemas']),
            ));

            $updated++;
        }

        $this->command->newLine();
        $this->command->info("Updated: {$updated}  |  Skipped (no data): {$skipped}");

        if (!empty($notFound)) {
            $this->command->warn('Pages in HTML but NOT in database (' . count($notFound) . '):');
            foreach ($notFound as $p) {
                $this->command->line("  - {$p}");
            }
        }
    }

    // ──────────────────────────────────────────────────────────────
    // HTML discovery
    // ──────────────────────────────────────────────────────────────

    /** @return string[] Absolute paths to every index.html under $root */
    private function findHtmlFiles(string $root): array
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($root, \FilesystemIterator::SKIP_DOTS),
        );

        $files = [];
        foreach ($iterator as $file) {
            if ($file->getFilename() === 'index.html') {
                $files[] = $file->getPathname();
            }
        }

        sort($files);
        return $files;
    }

    /**
     * Convert an absolute HTML file path to the page's full_path in the DB.
     *
     * Examples:
     *   {root}/index.html                       → /
     *   {root}/about/index.html                 → /about
     *   {root}/kitchen-remodeling/stamford-ct/index.html → /kitchen-remodeling/stamford-ct
     */
    private function htmlPathToPagePath(string $htmlPath, string $root): string
    {
        $relative = str_replace(['\\', $root], ['/', ''], $htmlPath);
        $relative = ltrim($relative, '/');
        // strip trailing /index.html
        $relative = preg_replace('#/?index\.html$#i', '', $relative);
        $relative = rtrim($relative, '/');

        return '/' . $relative;   // always starts with /
    }

    // ──────────────────────────────────────────────────────────────
    // HTML parsing
    // ──────────────────────────────────────────────────────────────

    /**
     * @return array{
     *   title: string,
     *   description: string,
     *   canonical: string,
     *   og_image_alt: string,
     *   robots: string,
     *   schemas: array,
     * }
     */
    private function extractSeoData(string $htmlPath): array
    {
        $html = file_get_contents($htmlPath);

        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $dom->loadHTML($html, LIBXML_NOWARNING | LIBXML_NOERROR);
        libxml_clear_errors();

        $xpath = new DOMXPath($dom);

        return [
            'title'       => $this->extractTitle($xpath),
            'description' => $this->extractMeta($xpath, 'name', 'description'),
            'canonical'   => $this->extractCanonical($xpath),
            'og_image_alt' => $this->extractMeta($xpath, 'property', 'og:image:alt'),
            'robots'      => $this->extractMeta($xpath, 'name', 'robots'),
            'schemas'     => $this->extractSchemas($html),
        ];
    }

    private function extractTitle(DOMXPath $xpath): string
    {
        $nodes = $xpath->query('//head/title');
        return $nodes && $nodes->length > 0
            ? trim($nodes->item(0)->textContent)
            : '';
    }

    private function extractMeta(DOMXPath $xpath, string $attr, string $value): string
    {
        $nodes = $xpath->query(
            sprintf('//head/meta[@%s="%s"]', $attr, $value),
        );

        return ($nodes && $nodes->length > 0)
            ? trim($nodes->item(0)->getAttribute('content'))
            : '';
    }

    private function extractCanonical(DOMXPath $xpath): string
    {
        $nodes = $xpath->query('//head/link[@rel="canonical"]');
        return ($nodes && $nodes->length > 0)
            ? trim($nodes->item(0)->getAttribute('href'))
            : '';
    }

    /**
     * Extract all <script type="application/ld+json"> blocks and decode them.
     *
     * Returns an array of decoded schema objects (associative arrays).
     * Invalid JSON blocks are silently skipped.
     *
     * @return array<int, array>
     */
    private function extractSchemas(string $html): array
    {
        preg_match_all(
            '/<script[^>]+type=["\']application\/ld\+json["\'][^>]*>(.*?)<\/script>/si',
            $html,
            $matches,
        );

        $schemas = [];
        foreach ($matches[1] as $raw) {
            $decoded = json_decode(trim($raw), true);
            if (is_array($decoded) && !empty($decoded)) {
                $schemas[] = $decoded;
            }
        }

        return $schemas;
    }

    // ──────────────────────────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────────────────────────

    /**
     * Normalise the robots string:
     * - If blank or the generic "index, follow" → return null (use global default)
     * - Otherwise return as-is (e.g. "noindex, nofollow")
     */
    private function normalizeRobots(string $robots): ?string
    {
        $robots = trim($robots);

        if ($robots === '' || stripos($robots, 'noindex') === false && stripos($robots, 'nofollow') === false) {
            return null;
        }

        return $robots;
    }
}
