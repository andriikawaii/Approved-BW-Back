<?php

declare(strict_types=1);

namespace App\Support\SeoFix;

use App\Models\Page;
use App\Models\Redirect;
use App\Models\Section;
use App\Services\CanonicalResolver;
use App\Services\SchemaBuilder;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class SeoFixRunner
{
    private const OFFICE_PATH = '/new-haven-county/orange-ct/';

    /** @var array<int, string> */
    private const AUDIT_FILES = [
        'SEO_AUDIT_REPORT.md',
        'pages.csv',
        'sections.csv',
        'page_text_extract.json',
        'sample_api_payloads.json',
    ];

    /** @var array<int, string> */
    private const LINK_KEYS = [
        'url',
        'href',
        'link',
    ];

    /** @var array<int, string> */
    private const CORE_SERVICE_ORDER = [
        'kitchen-remodeling',
        'bathroom-remodeling',
        'basement-finishing',
        'flooring',
    ];

    /** @var array<int, string> */
    private const HEADING_KEYS = [
        'headline',
        'title',
        'section_label',
        'name',
        'h1',
        'heading',
    ];

    /** @var array<int, string> */
    private const SUBHEADING_KEYS = [
        'subheadline',
        'subtitle',
        'subheading',
        'eyebrow',
    ];

    /** @var array<int, string> */
    private const INTRO_KEYS = [
        'content',
        'description',
        'body',
        'intro',
        'text',
    ];

    /** @var array<int, string> */
    private const H1_ELIGIBLE_SECTIONS = [
        'hero',
        'hero_slider',
        'hero_service_location',
        'service_hero',
        'page_hero',
        'case_study_header',
    ];

    /** @var array<int, string> */
    private const STATIC_URL_SKIP_PREFIXES = [
        '/images/',
        '/image/',
        '/img/',
        '/assets/',
        '/storage/',
        '/vendor/',
        '/build/',
    ];

    /** @var array<int, string> */
    private const STATIC_URL_SKIP_EXTENSIONS = [
        '.jpg',
        '.jpeg',
        '.png',
        '.gif',
        '.webp',
        '.svg',
        '.ico',
        '.pdf',
        '.mp4',
        '.webm',
        '.mov',
        '.avi',
        '.css',
        '.js',
        '.json',
        '.xml',
        '.txt',
        '.zip',
    ];

    /**
     * @param array<string, mixed> $opts
     * @return array<string, mixed>
     */
    public function run(array $opts = []): array
    {
        $dryRun = (bool) ($opts['dry_run'] ?? true);

        $auditInputs = $this->loadAuditInputs();
        $flaggedMeta = $this->extractFlaggedMetaFromAudit($auditInputs);

        $publishedPages = $this->loadPublishedPages();
        $publishedPathSet = $this->buildPublishedPathSet($publishedPages);
        $redirectFromSet = $this->buildActiveRedirectFromSet();

        $before = $this->computeAuditSummary($publishedPages, $publishedPathSet, $redirectFromSet);

        $changes = [];
        $counters = [
            'links_fixed' => 0,
            'links_unlinked' => 0,
            'hub_links_added' => 0,
            'service_variants_applied' => 0,
            'office_schema_fixed' => 0,
            'meta_shortened' => 0,
        ];

        $explicitMap = $this->buildExplicitLinkMap($publishedPathSet);
        $townHubByCountyTownSlug = $this->buildTownHubSlugMap($publishedPages);

        $this->fixBrokenInternalLinks(
            $publishedPages,
            $publishedPathSet,
            $redirectFromSet,
            $explicitMap,
            $townHubByCountyTownSlug,
            $changes,
            $counters
        );

        $this->fixHubLinkingCompleteness(
            $publishedPages,
            $publishedPathSet,
            $changes,
            $counters
        );

        $this->applyServiceTownCopyVariants(
            $publishedPages,
            $changes,
            $counters
        );

        $this->fixOfficeSchemaUrlMismatch(
            $publishedPages,
            $changes,
            $counters
        );

        $this->shortenFlaggedMeta(
            $publishedPages,
            $flaggedMeta,
            $changes,
            $counters
        );

        if (!$dryRun) {
            $this->persistChanges($publishedPages);
            $publishedPages = $this->loadPublishedPages();
            $publishedPathSet = $this->buildPublishedPathSet($publishedPages);
            $redirectFromSet = $this->buildActiveRedirectFromSet();
        }

        $after = $this->computeAuditSummary($publishedPages, $publishedPathSet, $redirectFromSet);

        $logPath = $this->writeFixLog(
            $dryRun,
            $auditInputs,
            $before,
            $after,
            $changes,
            $counters
        );

        $touchedPaths = array_values(array_map(
            static fn (array $change): string => (string) $change['full_path'],
            array_values($changes)
        ));
        sort($touchedPaths);

        return [
            'dry_run' => $dryRun,
            'audit_inputs_loaded' => array_keys($auditInputs),
            'before' => $before,
            'after' => $after,
            'counters' => array_merge($counters, [
                'pages_changed' => count($changes),
                'sections_changed' => array_sum(array_map(
                    static fn (array $change): int => count($change['changed_section_ids'] ?? []),
                    array_values($changes)
                )),
            ]),
            'touched_paths' => $touchedPaths,
            'log_path' => $logPath,
        ];
    }

    /**
     * @return array<string, string>
     */
    private function loadAuditInputs(): array
    {
        $auditDir = storage_path('app/audits');
        $inputs = [];

        foreach (self::AUDIT_FILES as $file) {
            $path = $auditDir . DIRECTORY_SEPARATOR . $file;
            if (!is_file($path)) {
                throw new \RuntimeException('Required audit file is missing: ' . $path);
            }

            $content = file_get_contents($path);
            if ($content === false) {
                throw new \RuntimeException('Unable to read audit file: ' . $path);
            }

            $inputs[$file] = $content;
        }

        return $inputs;
    }

    /**
     * @return Collection<int, Page>
     */
    private function loadPublishedPages(): Collection
    {
        return Page::query()
            ->where('status', 'published')
            ->where(function ($q): void {
                $q->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->with([
                'sections' => function ($q): void {
                    $q->where('is_active', true)->orderBy('sort_order');
                },
                'service',
                'town',
                'county',
            ])
            ->orderBy('full_path')
            ->get();
    }

    /**
     * @param Collection<int, Page> $publishedPages
     * @return array<string, true>
     */
    private function buildPublishedPathSet(Collection $publishedPages): array
    {
        $set = [];

        foreach ($publishedPages as $page) {
            $set[$this->normalizePath((string) $page->full_path)] = true;
        }

        return $set;
    }

    /**
     * @return array<string, true>
     */
    private function buildActiveRedirectFromSet(): array
    {
        $set = [];

        Redirect::query()
            ->where('is_active', true)
            ->pluck('from_path')
            ->each(function ($path) use (&$set): void {
                $normalized = $this->normalizePath((string) $path);
                $set[$normalized] = true;
            });

        return $set;
    }

    /**
     * @param array<string, true> $publishedPathSet
     * @return array<string, string>
     */
    private function buildExplicitLinkMap(array $publishedPathSet): array
    {
        $portfolioTarget = $this->firstExistingPath(
            [
                '/portfolio/',
                '/case-studies/',
                '/services/',
            ],
            $publishedPathSet
        );

        $kitchenStudy = $this->firstExistingPath(
            [
                '/case-studies/kitchen-remodeling-new-canaan/',
                '/case-studies/kitchen-remodeling-milford/',
                '/portfolio/',
            ],
            $publishedPathSet
        );

        $bathStudy = $this->firstExistingPath(
            [
                '/case-studies/bathroom-remodeling-westport/',
                '/portfolio/',
            ],
            $publishedPathSet
        );

        $basementStudy = $this->firstExistingPath(
            [
                '/case-studies/basement-finishing-darien/',
                '/portfolio/',
            ],
            $publishedPathSet
        );

        $servicesTarget = $this->firstExistingPath(
            [
                '/services/',
                '/portfolio/',
                '/case-studies/',
            ],
            $publishedPathSet
        );

        $map = [];

        if ($servicesTarget !== null) {
            $map['/exterior-renovations/'] = $servicesTarget;
        }

        if ($kitchenStudy !== null) {
            $map['/projects/modern-kitchen-greenwich/'] = $kitchenStudy;
        }

        if ($bathStudy !== null) {
            $map['/projects/spa-bathroom-orange/'] = $bathStudy;
        }

        if ($basementStudy !== null) {
            $map['/projects/basement-westport/'] = $basementStudy;
        }

        if ($portfolioTarget !== null) {
            $map['/projects/'] = $portfolioTarget;
        }

        return $map;
    }

    /**
     * @param array<int, string> $paths
     * @param array<string, true> $publishedPathSet
     */
    private function firstExistingPath(array $paths, array $publishedPathSet): ?string
    {
        foreach ($paths as $path) {
            $normalized = $this->normalizePath($path);
            if (isset($publishedPathSet[$normalized])) {
                return $normalized;
            }
        }

        return null;
    }

    /**
     * @param Collection<int, Page> $publishedPages
     * @return array<string, string>
     */
    private function buildTownHubSlugMap(Collection $publishedPages): array
    {
        $map = [];

        foreach ($publishedPages as $page) {
            if ($page->template_key !== 'county_hub') {
                continue;
            }
            if ($this->pathDepth((string) $page->full_path) !== 2) {
                continue;
            }
            if (!$page->county_id || !$page->town?->slug) {
                continue;
            }

            $key = $page->county_id . ':' . $page->town->slug;
            $map[$key] = $this->normalizePath((string) $page->full_path);
        }

        return $map;
    }

    /**
     * @param Collection<int, Page> $publishedPages
     * @param array<string, true> $publishedPathSet
     * @param array<string, true> $redirectFromSet
     * @param array<string, string> $explicitMap
     * @param array<string, string> $townHubByCountyTownSlug
     * @param array<int, array<string, mixed>> $changes
     * @param array<string, int> $counters
     */
    private function fixBrokenInternalLinks(
        Collection $publishedPages,
        array $publishedPathSet,
        array $redirectFromSet,
        array $explicitMap,
        array $townHubByCountyTownSlug,
        array &$changes,
        array &$counters
    ): void {
        foreach ($publishedPages as $page) {
            foreach ($page->sections as $section) {
                $before = is_array($section->data) ? $section->data : [];
                $sectionStats = [
                    'fixed' => 0,
                    'unlinked' => 0,
                ];

                $after = $this->repairNodeLinks(
                    $before,
                    $publishedPathSet,
                    $redirectFromSet,
                    $explicitMap,
                    $townHubByCountyTownSlug,
                    $sectionStats
                );

                if ($after !== $before) {
                    $section->data = $after;
                    $this->recordSectionChange($page, $section, $before, $after, $changes);

                    if ($sectionStats['fixed'] > 0 || $sectionStats['unlinked'] > 0) {
                        $bucket = &$this->ensureChangeBucket($page, $changes);
                        $bucket['counters']['links_fixed'] += $sectionStats['fixed'];
                        $bucket['counters']['links_unlinked'] += $sectionStats['unlinked'];

                        $counters['links_fixed'] += $sectionStats['fixed'];
                        $counters['links_unlinked'] += $sectionStats['unlinked'];
                    }
                }
            }
        }
    }

    /**
     * @param mixed $node
     * @param array<string, true> $publishedPathSet
     * @param array<string, true> $redirectFromSet
     * @param array<string, string> $explicitMap
     * @param array<string, string> $townHubByCountyTownSlug
     * @param array{fixed:int,unlinked:int} $stats
     * @return mixed
     */
    private function repairNodeLinks(
        mixed $node,
        array $publishedPathSet,
        array $redirectFromSet,
        array $explicitMap,
        array $townHubByCountyTownSlug,
        array &$stats
    ): mixed {
        if (!is_array($node)) {
            if (is_string($node)) {
                return $this->replaceMarkdownLinks(
                    $node,
                    $publishedPathSet,
                    $redirectFromSet,
                    $explicitMap,
                    $townHubByCountyTownSlug,
                    $stats
                );
            }

            return $node;
        }

        $result = $node;

        foreach ($result as $key => $value) {
            if (is_array($value)) {
                $result[$key] = $this->repairNodeLinks(
                    $value,
                    $publishedPathSet,
                    $redirectFromSet,
                    $explicitMap,
                    $townHubByCountyTownSlug,
                    $stats
                );
                continue;
            }

            if (!is_string($value)) {
                continue;
            }

            $stringKey = (string) $key;

            if (in_array($stringKey, self::LINK_KEYS, true)) {
                $linkDecision = $this->resolveLinkTarget(
                    $value,
                    $publishedPathSet,
                    $redirectFromSet,
                    $explicitMap,
                    $townHubByCountyTownSlug
                );

                if ($linkDecision['action'] === 'replace' && $linkDecision['value'] !== $value) {
                    $result[$key] = $linkDecision['value'];
                    $stats['fixed']++;
                } elseif ($linkDecision['action'] === 'unlink') {
                    $result[$key] = null;
                    $stats['unlinked']++;
                }

                continue;
            }

            $result[$key] = $this->replaceMarkdownLinks(
                $value,
                $publishedPathSet,
                $redirectFromSet,
                $explicitMap,
                $townHubByCountyTownSlug,
                $stats
            );
        }

        return $result;
    }

    /**
     * @param array<string, true> $publishedPathSet
     * @param array<string, true> $redirectFromSet
     * @param array<string, string> $explicitMap
     * @param array<string, string> $townHubByCountyTownSlug
     * @param array{fixed:int,unlinked:int} $stats
     */
    private function replaceMarkdownLinks(
        string $value,
        array $publishedPathSet,
        array $redirectFromSet,
        array $explicitMap,
        array $townHubByCountyTownSlug,
        array &$stats
    ): string {
        if (!str_contains($value, '](')) {
            return $value;
        }

        return (string) preg_replace_callback(
            '/\[(?<label>[^\]]+)\]\((?<url>[^)]+)\)/',
            function (array $matches) use (
                $publishedPathSet,
                $redirectFromSet,
                $explicitMap,
                $townHubByCountyTownSlug,
                &$stats
            ): string {
                $url = trim((string) ($matches['url'] ?? ''));
                $label = (string) ($matches['label'] ?? '');
                $decision = $this->resolveLinkTarget(
                    $url,
                    $publishedPathSet,
                    $redirectFromSet,
                    $explicitMap,
                    $townHubByCountyTownSlug,
                    $label
                );

                if ($decision['action'] === 'keep') {
                    return $matches[0];
                }

                if ($decision['action'] === 'replace') {
                    $replacement = '[' . $label . '](' . $decision['value'] . ')';
                    if ($replacement !== $matches[0]) {
                        $stats['fixed']++;
                    }
                    return $replacement;
                }

                if ($label !== $matches[0]) {
                    $stats['unlinked']++;
                }
                return $label;
            },
            $value
        );
    }

    /**
     * @param array<string, true> $publishedPathSet
     * @param array<string, true> $redirectFromSet
     * @param array<string, string> $explicitMap
     * @param array<string, string> $townHubByCountyTownSlug
     * @return array{action:string,value:?string}
     */
    private function resolveLinkTarget(
        string $rawUrl,
        array $publishedPathSet,
        array $redirectFromSet,
        array $explicitMap,
        array $townHubByCountyTownSlug,
        ?string $label = null
    ): array {
        $normalized = $this->normalizeInternalUrl($rawUrl);
        if ($normalized === null) {
            return [
                'action' => 'keep',
                'value' => null,
            ];
        }

        if ($this->isStaticAssetPath($normalized)) {
            return [
                'action' => 'keep',
                'value' => null,
            ];
        }

        if (isset($publishedPathSet[$normalized]) || isset($redirectFromSet[$normalized])) {
            return [
                'action' => 'replace',
                'value' => $normalized,
            ];
        }

        if (isset($explicitMap[$normalized]) && isset($publishedPathSet[$explicitMap[$normalized]])) {
            return [
                'action' => 'replace',
                'value' => $explicitMap[$normalized],
            ];
        }

        $ctCandidate = $this->appendTownCtSuffix($normalized);
        if ($ctCandidate !== null && isset($publishedPathSet[$ctCandidate])) {
            return [
                'action' => 'replace',
                'value' => $ctCandidate,
            ];
        }

        $townHubCandidate = $this->guessTownHubPathByCountyAndTownSlug($normalized, $townHubByCountyTownSlug);
        if ($townHubCandidate !== null && isset($publishedPathSet[$townHubCandidate])) {
            return [
                'action' => 'replace',
                'value' => $townHubCandidate,
            ];
        }

        if (str_starts_with($normalized, '/projects/')) {
            if (isset($explicitMap['/projects/']) && isset($publishedPathSet[$explicitMap['/projects/']])) {
                return [
                    'action' => 'replace',
                    'value' => $explicitMap['/projects/'],
                ];
            }
        }

        if ($label !== null && trim($label) !== '') {
            return [
                'action' => 'unlink',
                'value' => null,
            ];
        }

        return [
            'action' => 'unlink',
            'value' => null,
        ];
    }

    private function appendTownCtSuffix(string $path): ?string
    {
        if ($path === '/' || str_ends_with($path, '-ct/')) {
            return null;
        }

        $trimmed = trim($path, '/');
        if ($trimmed === '' || !str_contains($trimmed, '/')) {
            return null;
        }

        $parts = explode('/', $trimmed);
        $last = array_pop($parts);
        if ($last === null || $last === '') {
            return null;
        }

        $parts[] = $last . '-ct';

        return '/' . implode('/', $parts) . '/';
    }

    /**
     * @param array<string, string> $townHubByCountyTownSlug
     */
    private function guessTownHubPathByCountyAndTownSlug(string $path, array $townHubByCountyTownSlug): ?string
    {
        $trimmed = trim($path, '/');
        $parts = explode('/', $trimmed);
        if (count($parts) !== 2) {
            return null;
        }

        $countySlug = $parts[0];
        $townSlug = $parts[1];

        if ($townSlug === '') {
            return null;
        }

        $countyId = Page::query()
            ->where('status', 'published')
            ->where('template_key', 'county_hub')
            ->where('full_path', '/' . $countySlug)
            ->value('county_id');

        if (!$countyId) {
            return null;
        }

        $key = $countyId . ':' . str_replace('-ct', '', $townSlug);

        return $townHubByCountyTownSlug[$key] ?? null;
    }

    private function isStaticAssetPath(string $path): bool
    {
        foreach (self::STATIC_URL_SKIP_PREFIXES as $prefix) {
            if (str_starts_with($path, $prefix)) {
                return true;
            }
        }

        foreach (self::STATIC_URL_SKIP_EXTENSIONS as $extension) {
            $lower = strtolower($path);
            if (str_ends_with($lower, $extension . '/')
                || str_ends_with($lower, $extension)) {
                return true;
            }
        }

        return false;
    }

    private function normalizeInternalUrl(string $value): ?string
    {
        $value = trim($value);
        if ($value === '') {
            return null;
        }

        if (str_starts_with($value, 'tel:')
            || str_starts_with($value, 'mailto:')
            || str_starts_with($value, '#')
            || str_starts_with($value, 'javascript:')) {
            return null;
        }

        if (preg_match('#^https?://#i', $value) === 1) {
            $host = (string) parse_url($value, PHP_URL_HOST);
            $frontendHost = (string) parse_url((string) config('app.frontend_url'), PHP_URL_HOST);
            $appHost = (string) parse_url((string) config('app.url'), PHP_URL_HOST);

            if ($host !== '' && $frontendHost !== '' && strcasecmp($host, $frontendHost) !== 0
                && ($appHost === '' || strcasecmp($host, $appHost) !== 0)) {
                return null;
            }

            $value = (string) parse_url($value, PHP_URL_PATH);
        }

        if (!str_starts_with($value, '/')) {
            return null;
        }

        return $this->normalizePath($value);
    }

    private function normalizePath(string $path): string
    {
        $path = trim($path);

        if ($path === '') {
            return '/';
        }

        if (preg_match('#^https?://#i', $path) === 1) {
            $parsedPath = (string) parse_url($path, PHP_URL_PATH);
            $path = $parsedPath === '' ? '/' : $parsedPath;
        }

        $path = (string) parse_url($path, PHP_URL_PATH);
        $path = '/' . ltrim($path, '/');
        $path = preg_replace('#/+#', '/', $path) ?: '/';

        if ($path === '/') {
            return '/';
        }

        return rtrim($path, '/') . '/';
    }

    private function pathDepth(string $path): int
    {
        $trimmed = trim($path, '/');

        if ($trimmed === '') {
            return 0;
        }

        return count(array_filter(explode('/', $trimmed)));
    }

    /**
     * @param Collection<int, Page> $publishedPages
     * @param array<string, true> $publishedPathSet
     * @param array<int, array<string, mixed>> $changes
     * @param array<string, int> $counters
     */
    private function fixHubLinkingCompleteness(
        Collection $publishedPages,
        array $publishedPathSet,
        array &$changes,
        array &$counters
    ): void {
        $countyRoots = $publishedPages
            ->filter(fn (Page $page): bool => $page->template_key === 'county_hub'
                && $this->pathDepth((string) $page->full_path) === 1)
            ->values();

        $townHubs = $publishedPages
            ->filter(fn (Page $page): bool => $page->template_key === 'county_hub'
                && $this->pathDepth((string) $page->full_path) === 2)
            ->values();

        foreach ($countyRoots as $countyRoot) {
            $expectedTownHubs = $townHubs
                ->filter(fn (Page $townHub): bool => $townHub->county_id === $countyRoot->county_id)
                ->sortBy('full_path')
                ->values();

            if ($expectedTownHubs->isEmpty()) {
                continue;
            }

            /** @var Section|null $townListSection */
            $townListSection = $countyRoot->sections
                ->first(fn (Section $section): bool => $section->type === 'town_list');

            if (!$townListSection) {
                continue;
            }

            $before = is_array($townListSection->data) ? $townListSection->data : [];
            $after = $before;
            $countyRows = $after['counties'] ?? [];

            if (!is_array($countyRows) || !isset($countyRows[0]) || !is_array($countyRows[0])) {
                $countyRows = [
                    [
                        'name' => $countyRoot->county?->name ?? 'County',
                        'towns' => [],
                    ],
                ];
            }

            $townRows = $countyRows[0]['towns'] ?? [];
            if (!is_array($townRows)) {
                $townRows = [];
            }

            $existingPathIndex = [];
            foreach ($townRows as $index => $townRow) {
                if (!is_array($townRow)) {
                    continue;
                }
                $url = $townRow['url'] ?? null;
                if (is_string($url) && $url !== '') {
                    $existingPathIndex[$this->normalizePath($url)] = $index;
                }
            }

            foreach ($expectedTownHubs as $townHub) {
                $expectedPath = $this->normalizePath((string) $townHub->full_path);
                if (isset($existingPathIndex[$expectedPath])) {
                    $townRows[$existingPathIndex[$expectedPath]]['url'] = $expectedPath;
                    continue;
                }

                $townRows[] = [
                    'name' => $townHub->town?->name ?? $this->labelFromPath($expectedPath),
                    'url' => $expectedPath,
                ];
                $counters['hub_links_added']++;
                $bucket = &$this->ensureChangeBucket($countyRoot, $changes);
                $bucket['counters']['hub_links_added']++;
            }

            $countyRows[0]['towns'] = $townRows;
            $after['counties'] = $countyRows;

            if ($after !== $before) {
                $townListSection->data = $after;
                $this->recordSectionChange($countyRoot, $townListSection, $before, $after, $changes);
            }
        }

        $serviceTownPages = $publishedPages
            ->filter(fn (Page $page): bool => $page->template_key === 'service_town')
            ->values();

        $serviceTownByTownAndSlug = [];
        foreach ($serviceTownPages as $serviceTownPage) {
            if (!$serviceTownPage->town_id || !$serviceTownPage->service?->slug) {
                continue;
            }

            $slug = $serviceTownPage->service->slug;
            if (!in_array($slug, self::CORE_SERVICE_ORDER, true)) {
                continue;
            }

            $serviceTownByTownAndSlug[$serviceTownPage->town_id][$slug] = $this->normalizePath((string) $serviceTownPage->full_path);
        }

        foreach ($townHubs as $townHub) {
            $expected = $serviceTownByTownAndSlug[$townHub->town_id] ?? [];
            if (count($expected) === 0) {
                continue;
            }

            /** @var Section|null $servicesGrid */
            $servicesGrid = $townHub->sections
                ->first(fn (Section $section): bool => $section->type === 'services_grid');

            if ($servicesGrid) {
                $before = is_array($servicesGrid->data) ? $servicesGrid->data : [];
                $after = $this->ensureServicesGridLinks($before, $expected);

                if ($after !== $before) {
                    $servicesGrid->data = $after;
                    $this->recordSectionChange($townHub, $servicesGrid, $before, $after, $changes);
                    $added = $this->countNewServiceLinks($before, $after, $expected);
                    if ($added > 0) {
                        $counters['hub_links_added'] += $added;
                        $bucket = &$this->ensureChangeBucket($townHub, $changes);
                        $bucket['counters']['hub_links_added'] += $added;
                    }
                }

                continue;
            }

            /** @var Section|null $richText */
            $richText = $townHub->sections
                ->first(fn (Section $section): bool => $section->type === 'rich_text');

            if (!$richText) {
                continue;
            }

            $before = is_array($richText->data) ? $richText->data : [];
            $after = $this->ensureRichTextServiceLinks($before, $expected);

            if ($after !== $before) {
                $richText->data = $after;
                $this->recordSectionChange($townHub, $richText, $before, $after, $changes);

                $added = $this->countMarkdownServiceLinks($before, $after, $expected);
                if ($added > 0) {
                    $counters['hub_links_added'] += $added;
                    $bucket = &$this->ensureChangeBucket($townHub, $changes);
                    $bucket['counters']['hub_links_added'] += $added;
                }
            }

            /** @var Section|null $townList */
            $townList = $townHub->sections
                ->first(fn (Section $section): bool => $section->type === 'town_list');

            if ($townList) {
                $townBefore = is_array($townList->data) ? $townList->data : [];
                $townAfter = $this->ensureTownListContainsServiceLinks($townBefore, $expected);
                if ($townAfter !== $townBefore) {
                    $townList->data = $townAfter;
                    $this->recordSectionChange($townHub, $townList, $townBefore, $townAfter, $changes);
                    $added = $this->countNewServiceLinks($townBefore, $townAfter, $expected);
                    if ($added > 0) {
                        $counters['hub_links_added'] += $added;
                        $bucket = &$this->ensureChangeBucket($townHub, $changes);
                        $bucket['counters']['hub_links_added'] += $added;
                    }
                }
            }
        }
    }

    /**
     * @param array<string, mixed> $data
     * @param array<string, string> $expectedBySlug
     * @return array<string, mixed>
     */
    private function ensureServicesGridLinks(array $data, array $expectedBySlug): array
    {
        $after = $data;
        $key = isset($after['services']) && is_array($after['services']) ? 'services' : 'items';
        $items = $after[$key] ?? [];

        if (!is_array($items)) {
            $items = [];
        }

        $slugToIndex = [];
        foreach ($items as $index => $item) {
            if (!is_array($item)) {
                continue;
            }

            $url = $item['url'] ?? null;
            if (is_string($url) && $url !== '') {
                $urlPath = $this->normalizePath($url);
                foreach (self::CORE_SERVICE_ORDER as $serviceSlug) {
                    if (str_starts_with($urlPath, '/' . $serviceSlug . '/')) {
                        $slugToIndex[$serviceSlug] = $index;
                    }
                }
            }

            $title = strtolower((string) ($item['title'] ?? $item['name'] ?? ''));
            foreach (self::CORE_SERVICE_ORDER as $serviceSlug) {
                if (str_contains($title, str_replace('-', ' ', $serviceSlug))) {
                    $slugToIndex[$serviceSlug] = $index;
                }
            }
        }

        foreach (self::CORE_SERVICE_ORDER as $serviceSlug) {
            if (!isset($expectedBySlug[$serviceSlug])) {
                continue;
            }

            $target = $expectedBySlug[$serviceSlug];

            if (isset($slugToIndex[$serviceSlug])) {
                $index = $slugToIndex[$serviceSlug];
                if (!isset($items[$index]) || !is_array($items[$index])) {
                    $items[$index] = [];
                }
                $items[$index]['url'] = $target;
                if (!isset($items[$index]['title']) || trim((string) $items[$index]['title']) === '') {
                    $items[$index]['title'] = $this->humanizeServiceSlug($serviceSlug);
                }
                continue;
            }

            $items[] = [
                'title' => $this->humanizeServiceSlug($serviceSlug),
                'url' => $target,
                'description' => 'See scope, process, and pricing guidance for this service in your town.',
                'image' => null,
            ];
        }

        $after[$key] = $items;

        return $after;
    }

    /**
     * @param array<string, mixed> $before
     * @param array<string, mixed> $after
     * @param array<string, string> $expectedBySlug
     */
    private function countNewServiceLinks(array $before, array $after, array $expectedBySlug): int
    {
        $beforeLinks = $this->extractInternalLinksFromNode($before);
        $afterLinks = $this->extractInternalLinksFromNode($after);

        $count = 0;
        foreach ($expectedBySlug as $target) {
            if (in_array($target, $afterLinks, true) && !in_array($target, $beforeLinks, true)) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * @param array<string, mixed> $data
     * @param array<string, string> $expectedBySlug
     * @return array<string, mixed>
     */
    private function ensureRichTextServiceLinks(array $data, array $expectedBySlug): array
    {
        $after = $data;
        $bodyKey = isset($after['body']) ? 'body' : (isset($after['content']) ? 'content' : null);
        if ($bodyKey === null || !is_string($after[$bodyKey])) {
            return $after;
        }

        $body = $after[$bodyKey];
        foreach ($expectedBySlug as $serviceSlug => $target) {
            $body = (string) preg_replace(
                '#\(/' . preg_quote($serviceSlug, '#') . '/?\)#i',
                '(' . $target . ')',
                $body
            );
        }

        $existingLinks = $this->extractMarkdownLinks($body);
        $existingBySlug = [];
        foreach ($existingLinks as $link) {
            foreach (self::CORE_SERVICE_ORDER as $serviceSlug) {
                if (str_starts_with($link, '/' . $serviceSlug . '/')) {
                    $existingBySlug[$serviceSlug] = true;
                }
            }
        }

        $appendLines = [];
        foreach (self::CORE_SERVICE_ORDER as $serviceSlug) {
            if (!isset($expectedBySlug[$serviceSlug])) {
                continue;
            }
            if (isset($existingBySlug[$serviceSlug])) {
                continue;
            }

            $appendLines[] = '**' . $this->humanizeServiceSlug($serviceSlug) . '** - [Learn more](' . $expectedBySlug[$serviceSlug] . ')';
        }

        if (!empty($appendLines)) {
            $body = rtrim($body);
            $body .= PHP_EOL . PHP_EOL . implode(PHP_EOL . PHP_EOL, $appendLines);
        }

        $after[$bodyKey] = $body;

        return $after;
    }

    /**
     * @param array<string, mixed> $data
     * @param array<string, string> $expectedBySlug
     * @return array<string, mixed>
     */
    private function ensureTownListContainsServiceLinks(array $data, array $expectedBySlug): array
    {
        $after = $data;
        $counties = $after['counties'] ?? [];
        if (!is_array($counties) || !isset($counties[0]) || !is_array($counties[0])) {
            return $after;
        }

        $towns = $counties[0]['towns'] ?? [];
        if (!is_array($towns)) {
            $towns = [];
        }

        $existing = [];
        foreach ($towns as $row) {
            if (!is_array($row)) {
                continue;
            }
            $url = $row['url'] ?? null;
            if (is_string($url) && $url !== '') {
                $existing[$this->normalizePath($url)] = true;
            }
        }

        foreach (self::CORE_SERVICE_ORDER as $serviceSlug) {
            if (!isset($expectedBySlug[$serviceSlug])) {
                continue;
            }
            $target = $expectedBySlug[$serviceSlug];
            if (isset($existing[$target])) {
                continue;
            }

            $towns[] = [
                'name' => $this->humanizeServiceSlug($serviceSlug),
                'url' => $target,
            ];
            $existing[$target] = true;
        }

        $counties[0]['towns'] = $towns;
        $after['counties'] = $counties;

        return $after;
    }

    /**
     * @param array<string, mixed> $before
     * @param array<string, mixed> $after
     * @param array<string, string> $expectedBySlug
     */
    private function countMarkdownServiceLinks(array $before, array $after, array $expectedBySlug): int
    {
        $beforeLinks = $this->extractInternalLinksFromNode($before);
        $afterLinks = $this->extractInternalLinksFromNode($after);

        $count = 0;
        foreach ($expectedBySlug as $target) {
            if (in_array($target, $afterLinks, true) && !in_array($target, $beforeLinks, true)) {
                $count++;
            }
        }

        return $count;
    }

    private function humanizeServiceSlug(string $serviceSlug): string
    {
        return ucwords(str_replace('-', ' ', $serviceSlug));
    }

    /**
     * @param Collection<int, Page> $publishedPages
     * @param array<int, array<string, mixed>> $changes
     * @param array<string, int> $counters
     */
    private function applyServiceTownCopyVariants(
        Collection $publishedPages,
        array &$changes,
        array &$counters
    ): void {
        $serviceTownPages = $publishedPages
            ->filter(fn (Page $page): bool => $page->template_key === 'service_town')
            ->values();

        foreach ($serviceTownPages as $page) {
            $service = $page->service?->name ?? 'Home Remodeling';
            $serviceLower = strtolower($service);
            $town = $page->town?->name ?? $this->labelFromPath((string) $page->full_path);
            $county = $page->county?->name ?? 'Connecticut';
            $variant = ((int) sprintf('%u', crc32((string) $page->full_path))) % 3;

            $heroText = $this->variantHeroSubheadline($variant, $service, $serviceLower, $town);
            $introParagraph = $this->variantIntroParagraph($variant, $service, $serviceLower, $town, $county);
            $faq = $this->variantFaqTriplet($variant, $service, $serviceLower, $town);

            $changedThisPage = false;

            /** @var Section|null $hero */
            $hero = $page->sections->first(fn (Section $section): bool => in_array($section->type, ['hero_service_location', 'hero'], true));
            if ($hero) {
                $before = is_array($hero->data) ? $hero->data : [];
                $after = $before;
                $after['subheadline'] = $heroText;

                if ($after !== $before) {
                    $hero->data = $after;
                    $this->recordSectionChange($page, $hero, $before, $after, $changes);
                    $changedThisPage = true;
                }
            }

            $introSection = $page->sections
                ->first(fn (Section $section): bool => in_array($section->type, ['service_two_column', 'local_context', 'rich_text'], true));

            if ($introSection) {
                $before = is_array($introSection->data) ? $introSection->data : [];
                $after = $before;

                if (isset($after['description']) && is_string($after['description'])) {
                    $after['description'] = $this->rewriteFirstParagraph($after['description'], $introParagraph);
                } elseif (isset($after['content']) && is_string($after['content'])) {
                    $after['content'] = $this->rewriteFirstParagraph($after['content'], $introParagraph);
                } elseif (isset($after['body']) && is_string($after['body'])) {
                    $after['body'] = $this->rewriteFirstParagraph($after['body'], $introParagraph);
                } else {
                    $after['description'] = $introParagraph;
                }

                if ($after !== $before) {
                    $introSection->data = $after;
                    $this->recordSectionChange($page, $introSection, $before, $after, $changes);
                    $changedThisPage = true;
                }
            }

            /** @var Section|null $faqSection */
            $faqSection = $page->sections->first(fn (Section $section): bool => $section->type === 'faq_list');
            if ($faqSection) {
                $before = is_array($faqSection->data) ? $faqSection->data : [];
                $after = $before;
                $items = $after['items'] ?? [];

                if (is_array($items)) {
                    for ($i = 0; $i < 3; $i++) {
                        if (!isset($items[$i]) || !is_array($items[$i])) {
                            continue;
                        }
                        $items[$i]['question'] = $faq[$i]['question'];
                        $items[$i]['answer'] = $faq[$i]['answer'];
                    }
                    $after['items'] = $items;
                }

                if ($after !== $before) {
                    $faqSection->data = $after;
                    $this->recordSectionChange($page, $faqSection, $before, $after, $changes);
                    $changedThisPage = true;
                }
            }

            if ($changedThisPage) {
                $counters['service_variants_applied']++;
                $bucket = &$this->ensureChangeBucket($page, $changes);
                $bucket['counters']['service_variants_applied']++;
            }
        }
    }

    private function variantHeroSubheadline(int $variant, string $service, string $serviceLower, string $town): string
    {
        if ($variant === 0) {
            return 'Clear scope, code-compliant execution, and steady communication for ' . $serviceLower . ' projects in ' . $town . '.';
        }
        if ($variant === 1) {
            return $service . ' in ' . $town . ' planned around your layout, timeline, and permit requirements.';
        }

        return 'Factual planning, precise installation, and dependable follow-through for ' . $serviceLower . ' work in ' . $town . '.';
    }

    private function variantIntroParagraph(int $variant, string $service, string $serviceLower, string $town, string $county): string
    {
        $localCue = 'In ' . $town . ', permit review windows and inspection scheduling can affect start dates, so we account for that lead time in the project plan.';

        if ($variant === 0) {
            return $service . ' in ' . $town . ' starts with a practical plan for layout, materials, and code requirements. We define scope up front so timeline and cost are clear before construction begins. ' . $localCue;
        }
        if ($variant === 1) {
            return 'For ' . $town . ' homeowners, strong ' . $serviceLower . ' results come from careful prep and sequencing. We coordinate design details and trade work so each phase is completed without avoidable rework. ' . $localCue;
        }

        return $service . ' projects in ' . $town . ' often involve older framing, utility constraints, or finish matching. We resolve those conditions early so quality and schedule stay predictable across ' . $county . '. ' . $localCue;
    }

    /**
     * @return array<int, array{question:string,answer:string}>
     */
    private function variantFaqTriplet(int $variant, string $service, string $serviceLower, string $town): array
    {
        if ($variant === 0) {
            return [
                [
                    'question' => 'What is the typical timeline for ' . $service . ' in ' . $town . ', CT?',
                    'answer' => 'Most ' . $service . ' projects in ' . $town . ' run 4 to 8 weeks after planning and permit approval. We provide a written timeline before work begins.',
                ],
                [
                    'question' => 'Will my ' . $serviceLower . ' project in ' . $town . ' require permits?',
                    'answer' => 'Most ' . $serviceLower . ' scopes in ' . $town . ' require permits. BuiltWell manages permit submissions and inspection coordination on your behalf.',
                ],
                [
                    'question' => 'What budget range is typical for ' . $serviceLower . ' in ' . $town . '?',
                    'answer' => 'Budget depends on scope, materials, and layout changes. We provide a fixed-price proposal so your expected cost is clear before construction.',
                ],
            ];
        }

        if ($variant === 1) {
            return [
                [
                    'question' => 'How long do planning and construction take for ' . $serviceLower . ' in ' . $town . '?',
                    'answer' => 'Planning, selections, and permitting are scheduled first, then construction typically runs 4 to 8 weeks depending on scope. You receive milestones in writing.',
                ],
                [
                    'question' => 'Who handles permits and inspections for ' . $serviceLower . ' work in ' . $town . '?',
                    'answer' => 'BuiltWell handles permit paperwork, code coordination, and inspection scheduling so your project stays aligned with local requirements.',
                ],
                [
                    'question' => 'What factors have the biggest impact on ' . $serviceLower . ' cost in ' . $town . '?',
                    'answer' => 'The main cost drivers are layout changes, product selections, and utility or structural updates. We outline each driver in your estimate.',
                ],
            ];
        }

        return [
            [
                'question' => 'Can ' . $serviceLower . ' be phased to reduce disruption in ' . $town . ' homes?',
                'answer' => 'Yes. When scope allows, we can phase work so critical areas stay usable while construction proceeds in planned stages.',
            ],
            [
                'question' => 'What is included in your ' . $serviceLower . ' scope for ' . $town . ' projects?',
                'answer' => 'Scope can include planning, demolition, installation, code-required work, and final walkthrough. Your proposal lists inclusions line by line.',
            ],
            [
                'question' => 'How do you estimate pricing for ' . $serviceLower . ' in ' . $town . ', CT?',
                'answer' => 'Pricing is based on field measurements, scope complexity, selections, and any required code upgrades. You receive a clear fixed-price quote.',
            ],
        ];
    }

    private function rewriteFirstParagraph(string $text, string $replacementParagraph): string
    {
        $text = trim($text);
        if ($text === '') {
            return $replacementParagraph;
        }

        $paragraphs = preg_split('/\R{2,}/', $text) ?: [];
        if (count($paragraphs) === 0) {
            return $replacementParagraph;
        }

        $paragraphs[0] = $replacementParagraph;

        return implode(PHP_EOL . PHP_EOL, array_map(
            static fn (string $paragraph): string => trim($paragraph),
            $paragraphs
        ));
    }

    /**
     * @param Collection<int, Page> $publishedPages
     * @param array<int, array<string, mixed>> $changes
     * @param array<string, int> $counters
     */
    private function fixOfficeSchemaUrlMismatch(
        Collection $publishedPages,
        array &$changes,
        array &$counters
    ): void {
        /** @var Page|null $office */
        $office = $publishedPages->first(fn (Page $page): bool => $this->normalizePath((string) $page->full_path) === self::OFFICE_PATH);
        if (!$office) {
            return;
        }

        $canonical = CanonicalResolver::resolve($office);
        $beforeOverrides = is_array($office->schema_overrides) ? $office->schema_overrides : [];
        $afterOverrides = $beforeOverrides;
        $afterOverrides['url'] = $canonical;

        if ($afterOverrides === $beforeOverrides) {
            return;
        }

        $office->schema_overrides = $afterOverrides;
        $this->recordPageFieldChange(
            $office,
            'schema_overrides.url',
            $beforeOverrides['url'] ?? null,
            $canonical,
            $changes
        );

        $counters['office_schema_fixed']++;
        $bucket = &$this->ensureChangeBucket($office, $changes);
        $bucket['counters']['office_schema_fixed']++;
    }

    /**
     * @param Collection<int, Page> $publishedPages
     * @param array{titles:array<int, string>,descriptions:array<int, string>} $flaggedMeta
     * @param array<int, array<string, mixed>> $changes
     * @param array<string, int> $counters
     */
    private function shortenFlaggedMeta(
        Collection $publishedPages,
        array $flaggedMeta,
        array &$changes,
        array &$counters
    ): void {
        $pagesByPath = [];
        foreach ($publishedPages as $page) {
            $pagesByPath[$this->normalizePath((string) $page->full_path)] = $page;
        }

        foreach ($flaggedMeta['titles'] as $path) {
            $normalized = $this->normalizePath($path);
            $page = $pagesByPath[$normalized] ?? null;
            if (!$page) {
                continue;
            }

            $before = (string) $page->seo_title;
            $after = $this->shortenedTitle($normalized, $before);

            if ($after !== $before) {
                $page->seo_title = $after;
                $this->recordPageFieldChange($page, 'seo_title', $before, $after, $changes);
                $counters['meta_shortened']++;
                $bucket = &$this->ensureChangeBucket($page, $changes);
                $bucket['counters']['meta_shortened']++;
            }
        }

        foreach ($flaggedMeta['descriptions'] as $path) {
            $normalized = $this->normalizePath($path);
            $page = $pagesByPath[$normalized] ?? null;
            if (!$page) {
                continue;
            }

            $before = (string) $page->seo_description;
            $after = $this->shortenedDescription($normalized, $before);

            if ($after !== $before) {
                $page->seo_description = $after;
                $this->recordPageFieldChange($page, 'seo_description', $before, $after, $changes);
                $counters['meta_shortened']++;
                $bucket = &$this->ensureChangeBucket($page, $changes);
                $bucket['counters']['meta_shortened']++;
            }
        }
    }

    private function shortenedTitle(string $path, string $current): string
    {
        $map = [
            '/' => 'BuiltWell CT | Home Remodeling in Fairfield & New Haven',
            '/about/' => 'About BUILTWELL | Connecticut Remodeling Contractor',
        ];

        $candidate = $map[$path] ?? $current;
        if (mb_strlen($candidate) <= 60) {
            return $candidate;
        }

        return $this->trimToMax($candidate, 60);
    }

    private function shortenedDescription(string $path, string $current): string
    {
        $map = [
            '/free-consultation/' => 'Book a free remodeling consultation with BUILTWELL, a licensed CT contractor serving Fairfield and New Haven County.',
            '/case-studies/' => 'Explore BuiltWell case studies across Fairfield and New Haven County, including kitchen, bathroom, basement, and whole-home remodels.',
            '/fairfield-county/' => 'Home remodeling services across Fairfield County, CT, including kitchens, bathrooms, basements, additions, and flooring.',
            '/bathroom-remodeling/' => 'Bathroom remodeling in Fairfield and New Haven County, CT, including walk-in showers, custom tile, and heated floors.',
            '/new-haven-county/' => 'Home remodeling services across New Haven County, CT, including kitchens, bathrooms, basements, additions, and flooring.',
            '/home-additions/' => 'Home additions in Fairfield and New Haven County, CT, including room additions, second stories, and in-law suites.',
            '/kitchen-remodeling/' => 'Kitchen remodeling in Fairfield and New Haven County, CT, including cabinetry, countertops, flooring, and layout upgrades.',
            '/services/' => 'BuiltWell provides kitchen, bathroom, basement, additions, and flooring services across Fairfield and New Haven County, CT.',
            '/flooring/' => 'Flooring installation in Fairfield and New Haven County, CT, including hardwood, LVP, tile, engineered wood, and subfloor prep.',
        ];

        $candidate = $map[$path] ?? $current;
        if (mb_strlen($candidate) <= 160) {
            return $candidate;
        }

        return $this->trimToMax($candidate, 160);
    }

    private function trimToMax(string $value, int $max): string
    {
        $value = trim(preg_replace('/\s+/', ' ', $value) ?? $value);

        if (mb_strlen($value) <= $max) {
            return $value;
        }

        $slice = mb_substr($value, 0, $max);
        $cutAt = mb_strrpos($slice, ' ');
        if ($cutAt !== false && $cutAt >= (int) ($max * 0.6)) {
            $slice = mb_substr($slice, 0, $cutAt);
        }

        return rtrim($slice, " \t\n\r\0\x0B,;:.!");
    }

    /**
     * @param array<string, string> $auditInputs
     * @return array{titles:array<int, string>,descriptions:array<int, string>}
     */
    private function extractFlaggedMetaFromAudit(array $auditInputs): array
    {
        $report = $auditInputs['SEO_AUDIT_REPORT.md'] ?? '';
        $titles = [];
        $descriptions = [];

        $mode = null;
        $lines = preg_split('/\R/', $report) ?: [];

        foreach ($lines as $line) {
            $trimmed = trim($line);

            if ($trimmed === '- Worst title lengths (top 10):') {
                $mode = 'title';
                continue;
            }

            if ($trimmed === '- Worst description lengths (top 10):') {
                $mode = 'description';
                continue;
            }

            if (str_starts_with($trimmed, '- Largest near-duplicate ')
                || str_starts_with($trimmed, '- Largest near-duplicate')
                || str_starts_with($trimmed, '#### ')) {
                $mode = null;
            }

            if ($mode === null) {
                continue;
            }

            if (preg_match('/^-\s+(\/\S+)\s+\((\d+)\):/u', $trimmed, $matches) !== 1) {
                continue;
            }

            $path = $this->normalizePath($matches[1]);
            $length = (int) $matches[2];

            if ($mode === 'title' && $length > 60) {
                $titles[$path] = true;
            }

            if ($mode === 'description' && $length > 160) {
                $descriptions[$path] = true;
            }
        }

        if (empty($titles) && empty($descriptions) && isset($auditInputs['pages.csv'])) {
            $rows = $this->parseCsv($auditInputs['pages.csv']);
            foreach ($rows as $row) {
                $path = $this->normalizePath((string) ($row['full_path'] ?? '/'));
                $titleLen = mb_strlen((string) ($row['seo_title'] ?? ''));
                $descLen = mb_strlen((string) ($row['seo_description'] ?? ''));

                if ($titleLen > 60) {
                    $titles[$path] = true;
                }
                if ($descLen > 160) {
                    $descriptions[$path] = true;
                }
            }
        }

        return [
            'titles' => array_values(array_keys($titles)),
            'descriptions' => array_values(array_keys($descriptions)),
        ];
    }

    /**
     * @return array<int, array<string, string>>
     */
    private function parseCsv(string $csvContent): array
    {
        $lines = preg_split('/\R/', trim($csvContent)) ?: [];
        if (count($lines) < 2) {
            return [];
        }

        $header = str_getcsv(array_shift($lines));
        if ($header === false) {
            return [];
        }

        $rows = [];
        foreach ($lines as $line) {
            if (trim($line) === '') {
                continue;
            }
            $values = str_getcsv($line);
            if ($values === false) {
                continue;
            }

            $row = [];
            foreach ($header as $index => $key) {
                $row[$key] = $values[$index] ?? '';
            }
            $rows[] = $row;
        }

        return $rows;
    }

    /**
     * @param Collection<int, Page> $publishedPages
     */
    private function persistChanges(Collection $publishedPages): void
    {
        foreach ($publishedPages as $page) {
            foreach ($page->sections as $section) {
                if ($section->isDirty()) {
                    $section->save();
                }
            }

            if ($page->isDirty()) {
                $page->save();
            }
        }
    }

    /**
     * @param Collection<int, Page> $publishedPages
     * @param array<string, true> $publishedPathSet
     * @param array<string, true> $redirectFromSet
     * @return array<string, mixed>
     */
    private function computeAuditSummary(
        Collection $publishedPages,
        array $publishedPathSet,
        array $redirectFromSet
    ): array {
        $pageTextExtract = $this->buildPageTextExtract($publishedPages);

        $linkGraph = [];
        $allInternalEdges = [];

        foreach ($publishedPages as $page) {
            $path = $this->normalizePath((string) $page->full_path);
            $links = $pageTextExtract[$path]['internal_links'] ?? [];
            $links = array_values(array_unique(array_map(fn (string $value): string => $this->normalizePath($value), $links)));

            $linkGraph[$path] = $links;

            foreach ($links as $target) {
                $allInternalEdges[] = [
                    'source' => $path,
                    'target' => $target,
                ];
            }
        }

        $brokenLinks = [];
        foreach ($allInternalEdges as $edge) {
            $target = $edge['target'];
            if (isset($publishedPathSet[$target]) || isset($redirectFromSet[$target])) {
                continue;
            }

            $brokenLinks[$target]['target'] = $target;
            $brokenLinks[$target]['sources'][] = $edge['source'];
        }

        foreach ($brokenLinks as &$row) {
            $row['sources'] = array_values(array_unique($row['sources'] ?? []));
        }
        unset($row);
        $brokenLinks = array_values($brokenLinks);
        usort($brokenLinks, static fn (array $a, array $b): int => count($b['sources']) <=> count($a['sources']));

        $countyRoots = $publishedPages
            ->filter(fn (Page $page): bool => $page->template_key === 'county_hub'
                && $this->pathDepth((string) $page->full_path) === 1)
            ->values();

        $townHubs = $publishedPages
            ->filter(fn (Page $page): bool => $page->template_key === 'county_hub'
                && $this->pathDepth((string) $page->full_path) === 2)
            ->values();

        $missingCountyToTown = [];
        foreach ($countyRoots as $countyRoot) {
            $rootPath = $this->normalizePath((string) $countyRoot->full_path);
            $expected = [];

            foreach ($townHubs as $townHub) {
                if ($townHub->county_id !== $countyRoot->county_id) {
                    continue;
                }
                $expected[] = $this->normalizePath((string) $townHub->full_path);
            }

            $actual = $linkGraph[$rootPath] ?? [];
            $missing = array_values(array_diff($expected, $actual));
            if (!empty($missing)) {
                $missingCountyToTown[] = [
                    'county_hub' => $rootPath,
                    'missing_town_hub_links' => $missing,
                ];
            }
        }

        $serviceTownPages = $publishedPages
            ->filter(fn (Page $page): bool => $page->template_key === 'service_town')
            ->values();

        $serviceTownByTown = [];
        foreach ($serviceTownPages as $serviceTownPage) {
            if (!$serviceTownPage->town_id || !$serviceTownPage->service?->slug) {
                continue;
            }
            if (!in_array($serviceTownPage->service->slug, self::CORE_SERVICE_ORDER, true)) {
                continue;
            }

            $serviceTownByTown[$serviceTownPage->town_id][] = $this->normalizePath((string) $serviceTownPage->full_path);
        }
        foreach ($serviceTownByTown as &$paths) {
            sort($paths);
        }
        unset($paths);

        $townHubMissing = [];
        foreach ($townHubs as $townHub) {
            $townPath = $this->normalizePath((string) $townHub->full_path);
            $expected = $serviceTownByTown[$townHub->town_id] ?? [];
            $actual = $linkGraph[$townPath] ?? [];
            $missing = array_values(array_diff($expected, $actual));

            if (count($expected) > 0 && !empty($missing)) {
                $townHubMissing[] = [
                    'town_hub' => $townPath,
                    'missing_links' => $missing,
                ];
            }
        }

        $schemaMismatches = [];
        foreach ($publishedPages as $page) {
            $path = $this->normalizePath((string) $page->full_path);
            $canonical = CanonicalResolver::resolve($page);
            $schemaItems = SchemaBuilder::build($page);

            foreach ($schemaItems as $schemaItem) {
                if (!is_array($schemaItem)) {
                    continue;
                }
                if (!isset($schemaItem['url'])) {
                    continue;
                }

                $schemaUrl = trim((string) $schemaItem['url']);
                if ($schemaUrl === '') {
                    continue;
                }

                if (rtrim($schemaUrl, '/') . '/' !== rtrim($canonical, '/') . '/') {
                    $schemaMismatches[] = [
                        'path' => $path,
                        'schema_type' => (string) ($schemaItem['@type'] ?? ''),
                        'schema_url' => $schemaUrl,
                        'canonical' => $canonical,
                    ];
                }
            }
        }

        $townNames = $publishedPages->pluck('town.name')->filter()->map(fn ($name): string => (string) $name)->values()->all();
        $serviceNames = $publishedPages->pluck('service.name')->filter()->map(fn ($name): string => (string) $name)->values()->all();

        $clusterMap = [];
        foreach ($serviceTownPages as $page) {
            $path = $this->normalizePath((string) $page->full_path);
            $pageExtract = $pageTextExtract[$path] ?? null;
            if (!$pageExtract) {
                continue;
            }

            $localText = implode(' ', array_merge(
                $pageExtract['h1_candidates'],
                $pageExtract['subheadings'],
                $pageExtract['intro_texts'],
                $pageExtract['faq_questions']
            ));

            $normalized = $this->canonicalizeForDup($localText, $townNames, $serviceNames);
            $fingerprint = sha1($normalized);
            $clusterMap[$fingerprint][] = $path;
        }

        $duplicateClusters = array_values(array_filter(
            $clusterMap,
            static fn (array $paths): bool => count($paths) > 1
        ));
        usort($duplicateClusters, static fn (array $a, array $b): int => count($b) <=> count($a));

        $largestCluster = 0;
        foreach ($clusterMap as $clusterPaths) {
            $largestCluster = max($largestCluster, count($clusterPaths));
        }

        return [
            'published_pages' => $publishedPages->count(),
            'service_town_pages' => $serviceTownPages->count(),
            'broken_internal_links_count' => count($brokenLinks),
            'broken_internal_links' => $brokenLinks,
            'county_hub_missing_count' => count($missingCountyToTown),
            'county_hub_missing' => $missingCountyToTown,
            'town_hub_missing_count' => count($townHubMissing),
            'town_hub_missing' => $townHubMissing,
            'hub_missing_total' => count($missingCountyToTown) + count($townHubMissing),
            'office_schema_mismatch_count' => count(array_values(array_filter(
                $schemaMismatches,
                fn (array $row): bool => $row['path'] === self::OFFICE_PATH
            ))),
            'office_schema_mismatch' => array_values(array_filter(
                $schemaMismatches,
                fn (array $row): bool => $row['path'] === self::OFFICE_PATH
            )),
            'service_town_duplicate_cluster_count' => count($duplicateClusters),
            'largest_service_town_duplicate_cluster' => $largestCluster,
        ];
    }

    /**
     * @param Collection<int, Page> $publishedPages
     * @return array<string, array<string, array<int, string>>>
     */
    private function buildPageTextExtract(Collection $publishedPages): array
    {
        $extract = [];

        foreach ($publishedPages as $page) {
            $path = $this->normalizePath((string) $page->full_path);
            $extract[$path] = [
                'h1_candidates' => [],
                'subheadings' => [],
                'intro_texts' => [],
                'faq_questions' => [],
                'internal_links' => [],
            ];

            foreach ($page->sections as $section) {
                $data = is_array($section->data) ? $section->data : [];
                $sectionExtract = $this->extractSectionText((string) $section->type, $data);

                $extract[$path]['h1_candidates'] = array_values(array_unique(array_merge(
                    $extract[$path]['h1_candidates'],
                    $sectionExtract['h1_candidates']
                )));
                $extract[$path]['subheadings'] = array_values(array_unique(array_merge(
                    $extract[$path]['subheadings'],
                    $sectionExtract['subheadings']
                )));
                $extract[$path]['intro_texts'] = array_values(array_unique(array_merge(
                    $extract[$path]['intro_texts'],
                    $sectionExtract['intro_texts']
                )));
                $extract[$path]['faq_questions'] = array_values(array_unique(array_merge(
                    $extract[$path]['faq_questions'],
                    $sectionExtract['faq_questions']
                )));
                $extract[$path]['internal_links'] = array_values(array_unique(array_merge(
                    $extract[$path]['internal_links'],
                    $sectionExtract['links']
                )));
            }
        }

        return $extract;
    }

    /**
     * @param array<string, mixed> $data
     * @return array{
     *   h1_candidates:array<int, string>,
     *   subheadings:array<int, string>,
     *   intro_texts:array<int, string>,
     *   faq_questions:array<int, string>,
     *   links:array<int, string>
     * }
     */
    private function extractSectionText(string $type, array $data): array
    {
        $h1Candidates = [];
        $subheadings = [];
        $introTexts = [];
        $faqQuestions = [];

        $headings = [];
        foreach (self::HEADING_KEYS as $key) {
            if (isset($data[$key]) && is_string($data[$key]) && trim($data[$key]) !== '') {
                $headings[] = $this->normalizeWhitespace($data[$key]);
            }
        }

        if (in_array($type, self::H1_ELIGIBLE_SECTIONS, true)) {
            $h1Candidates = $headings;
        }

        foreach (self::SUBHEADING_KEYS as $key) {
            if (isset($data[$key]) && is_string($data[$key]) && trim($data[$key]) !== '') {
                $subheadings[] = $this->normalizeWhitespace($data[$key]);
            }
        }

        foreach (self::INTRO_KEYS as $key) {
            if (isset($data[$key]) && is_string($data[$key]) && trim($data[$key]) !== '') {
                $introTexts[] = $this->normalizeWhitespace(strip_tags($data[$key]));
            }
        }

        if (in_array($type, ['faq_list', 'faq_accordion'], true) && isset($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $faq) {
                if (!is_array($faq)) {
                    continue;
                }
                $question = isset($faq['question']) ? trim((string) $faq['question']) : '';
                if ($question !== '') {
                    $faqQuestions[] = $this->normalizeWhitespace($question);
                }
            }
        }

        $links = $this->extractInternalLinksFromNode($data);

        return [
            'h1_candidates' => array_values(array_unique($h1Candidates)),
            'subheadings' => array_values(array_unique($subheadings)),
            'intro_texts' => array_values(array_unique($introTexts)),
            'faq_questions' => array_values(array_unique($faqQuestions)),
            'links' => array_values(array_unique($links)),
        ];
    }

    /**
     * @param mixed $node
     * @return array<int, string>
     */
    private function extractInternalLinksFromNode(mixed $node): array
    {
        $links = [];

        if (!is_array($node)) {
            if (is_string($node)) {
                $links = array_merge($links, $this->extractMarkdownLinks($node));
            }
            return array_values(array_unique($links));
        }

        foreach ($node as $key => $value) {
            if (is_array($value)) {
                $links = array_merge($links, $this->extractInternalLinksFromNode($value));
                continue;
            }

            if (!is_string($value)) {
                continue;
            }

            if (in_array((string) $key, self::LINK_KEYS, true)) {
                $normalized = $this->normalizeInternalUrl($value);
                if ($normalized !== null && !$this->isStaticAssetPath($normalized)) {
                    $links[] = $normalized;
                }
            }

            $links = array_merge($links, $this->extractMarkdownLinks($value));
        }

        return array_values(array_unique($links));
    }

    /**
     * @return array<int, string>
     */
    private function extractMarkdownLinks(string $value): array
    {
        if (!str_contains($value, '](')) {
            return [];
        }

        $links = [];
        if (preg_match_all('/\[[^\]]+\]\(([^)]+)\)/', $value, $matches) !== false) {
            foreach ($matches[1] ?? [] as $url) {
                $normalized = $this->normalizeInternalUrl((string) $url);
                if ($normalized !== null && !$this->isStaticAssetPath($normalized)) {
                    $links[] = $normalized;
                }
            }
        }

        return array_values(array_unique($links));
    }

    private function normalizeWhitespace(string $value): string
    {
        return trim((string) preg_replace('/\s+/', ' ', $value));
    }

    /**
     * @param array<int, string> $townNames
     * @param array<int, string> $serviceNames
     */
    private function canonicalizeForDup(string $value, array $townNames, array $serviceNames): string
    {
        $value = $this->normalizeText($value);

        $replaceWords = [
            'connecticut',
            'ct',
            'fairfield county',
            'new haven county',
            'orange',
            'new haven',
        ];

        foreach ($townNames as $town) {
            $replaceWords[] = $this->normalizeText($town);
        }

        foreach ($serviceNames as $service) {
            $replaceWords[] = $this->normalizeText($service);
        }

        $replaceWords = array_values(array_unique(array_filter($replaceWords)));

        foreach ($replaceWords as $word) {
            if ($word === '') {
                continue;
            }
            $value = preg_replace('/\b' . preg_quote($word, '/') . '\b/u', ' {x} ', $value) ?? $value;
        }

        return $this->normalizeWhitespace($value);
    }

    private function normalizeText(?string $value): string
    {
        $value = (string) $value;
        $value = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $value = strip_tags($value);
        $value = mb_strtolower($value);
        $value = preg_replace('/[^a-z0-9\s]/u', ' ', $value) ?? '';

        return $this->normalizeWhitespace($value);
    }

    /**
     * @param array<int, array<string, mixed>> $changes
     * @return array<string, mixed>
     */
    private function &ensureChangeBucket(Page $page, array &$changes): array
    {
        if (!isset($changes[$page->id])) {
            $changes[$page->id] = [
                'page_id' => $page->id,
                'full_path' => (string) $page->full_path,
                'changed_section_ids' => [],
                'page_fields' => [],
                'section_fields' => [],
                'counters' => [
                    'links_fixed' => 0,
                    'links_unlinked' => 0,
                    'hub_links_added' => 0,
                    'service_variants_applied' => 0,
                    'office_schema_fixed' => 0,
                    'meta_shortened' => 0,
                ],
            ];
        }

        return $changes[$page->id];
    }

    /**
     * @param array<int, array<string, mixed>> $changes
     * @param array<string, mixed> $before
     * @param array<string, mixed> $after
     */
    private function recordSectionChange(
        Page $page,
        Section $section,
        array $before,
        array $after,
        array &$changes
    ): void {
        $diff = $this->diffNodes($before, $after);
        if (empty($diff)) {
            return;
        }

        $bucket = &$this->ensureChangeBucket($page, $changes);
        $bucket['changed_section_ids'][] = $section->id;
        $bucket['changed_section_ids'] = array_values(array_unique($bucket['changed_section_ids']));

        if (!isset($bucket['section_fields'][$section->id])) {
            $bucket['section_fields'][$section->id] = [
                'section_id' => $section->id,
                'type' => (string) $section->type,
                'fields' => [],
            ];
        }

        foreach ($diff as $path => $snapshot) {
            $bucket['section_fields'][$section->id]['fields'][$path] = $snapshot;
        }
    }

    /**
     * @param array<int, array<string, mixed>> $changes
     */
    private function recordPageFieldChange(
        Page $page,
        string $fieldPath,
        mixed $before,
        mixed $after,
        array &$changes
    ): void {
        if ($before === $after) {
            return;
        }

        $bucket = &$this->ensureChangeBucket($page, $changes);
        $bucket['page_fields'][$fieldPath] = [
            'before' => $before,
            'after' => $after,
        ];
    }

    /**
     * @param mixed $before
     * @param mixed $after
     * @return array<string, array{before:mixed,after:mixed}>
     */
    private function diffNodes(mixed $before, mixed $after, string $prefix = ''): array
    {
        if (is_array($before) && is_array($after)) {
            $diff = [];
            $keys = array_values(array_unique(array_merge(array_keys($before), array_keys($after))));
            foreach ($keys as $key) {
                $path = $prefix === '' ? (string) $key : $prefix . '.' . $key;
                $childBefore = $before[$key] ?? null;
                $childAfter = $after[$key] ?? null;
                $childDiff = $this->diffNodes($childBefore, $childAfter, $path);
                $diff = array_merge($diff, $childDiff);
            }
            return $diff;
        }

        if ($before !== $after) {
            return [
                $prefix => [
                    'before' => $before,
                    'after' => $after,
                ],
            ];
        }

        return [];
    }

    private function labelFromPath(string $path): string
    {
        $trimmed = trim($path, '/');
        if ($trimmed === '') {
            return 'Home';
        }

        $last = explode('/', $trimmed);
        $last = end($last);
        if ($last === false) {
            return $trimmed;
        }

        return ucwords(str_replace('-', ' ', preg_replace('/-ct$/', '', $last) ?? $last));
    }

    /**
     * @param array<string, string> $auditInputs
     * @param array<string, mixed> $before
     * @param array<string, mixed> $after
     * @param array<int, array<string, mixed>> $changes
     * @param array<string, int> $counters
     */
    private function writeFixLog(
        bool $dryRun,
        array $auditInputs,
        array $before,
        array $after,
        array $changes,
        array $counters
    ): string {
        $timestamp = Carbon::now()->format('Ymd-His');
        $relative = 'seo_fixes/' . $timestamp . '.json';
        $absolute = storage_path('app/' . $relative);

        $dir = dirname($absolute);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $payload = [
            'generated_at' => Carbon::now()->toDateTimeString(),
            'dry_run' => $dryRun,
            'audit_inputs_loaded' => array_keys($auditInputs),
            'before' => $before,
            'after' => $after,
            'global_counters' => $counters,
            'page_changes' => array_values($changes),
        ];

        file_put_contents(
            $absolute,
            json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL
        );

        return $absolute;
    }
}
