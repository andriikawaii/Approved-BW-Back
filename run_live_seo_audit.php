<?php

declare(strict_types=1);

use App\Models\Page;
use App\Models\Redirect;
use App\Services\BreadcrumbBuilder;
use App\Services\CanonicalResolver;
use App\Services\FooterTemplateResolver;
use App\Services\PhoneResolver;
use App\Services\SchemaBuilder;
use Illuminate\Contracts\Console\Kernel;

require __DIR__ . '/vendor/autoload.php';

/** @var \Illuminate\Foundation\Application $app */
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$auditDir = storage_path('app/audits');
if (!is_dir($auditDir)) {
    mkdir($auditDir, 0777, true);
}

function normalize_path(?string $path): string
{
    $path = trim((string) $path);

    if ($path === '') {
        return '/';
    }

    if (preg_match('#^https?://#i', $path)) {
        $parsedPath = (string) (parse_url($path, PHP_URL_PATH) ?? '');
        $path = $parsedPath === '' ? '/' : $parsedPath;
    }

    $path = '/' . ltrim($path, '/');

    return $path === '/' ? '/' : rtrim($path, '/');
}

function normalize_whitespace(string $value): string
{
    return trim(preg_replace('/\s+/', ' ', $value) ?? '');
}

function normalize_text(?string $value): string
{
    $value = (string) $value;
    $value = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $value = strip_tags($value);
    $value = mb_strtolower($value);
    $value = preg_replace('/[^a-z0-9\s]/u', ' ', $value) ?? '';
    $value = normalize_whitespace($value);

    return $value;
}

function text_excerpt(?string $value, int $max = 180): string
{
    $value = normalize_whitespace(strip_tags((string) $value));
    if ($value === '') {
        return '';
    }

    if (mb_strlen($value) <= $max) {
        return $value;
    }

    return rtrim(mb_substr($value, 0, $max - 3)) . '...';
}

function flatten_links(mixed $node, string $prefix = ''): array
{
    $links = [];

    if (is_array($node)) {
        foreach ($node as $key => $value) {
            $keyPath = $prefix === '' ? (string) $key : $prefix . '.' . $key;

            if (
                is_string($value)
                && in_array((string) $key, ['url', 'href', 'link'], true)
            ) {
                $candidate = trim($value);
                if ($candidate !== '' && str_starts_with($candidate, '/')) {
                    $links[] = $candidate;
                }
            }

            $links = array_merge($links, flatten_links($value, $keyPath));
        }
    }

    return $links;
}

function starts_with_any(string $value, array $prefixes): bool
{
    foreach ($prefixes as $prefix) {
        $prefix = (string) $prefix;
        if ($prefix === '') {
            continue;
        }

        if (str_starts_with($value, $prefix)) {
            return true;
        }
    }

    return false;
}

function is_path_under_base(string $path, string $base): bool
{
    $path = normalize_path($path);
    $base = normalize_path($base);

    return $path === $base || str_starts_with($path, $base . '/');
}

function is_path_under_any_base(string $path, array $bases): bool
{
    foreach ($bases as $base) {
        if (is_path_under_base($path, (string) $base)) {
            return true;
        }
    }

    return false;
}

function is_service_label(string $label): bool
{
    $labelNorm = normalize_text($label);
    if ($labelNorm === '') {
        return false;
    }

    foreach ([
        'kitchen',
        'bathroom',
        'basement',
        'flooring',
        'remodel',
        'renovation',
    ] as $keyword) {
        if (str_contains($labelNorm, $keyword)) {
            return true;
        }
    }

    return false;
}

function collect_link_entries(mixed $node, string $prefix = ''): array
{
    if (!is_array($node)) {
        return [];
    }

    $entries = [];
    $linkKeys = ['url', 'href', 'link'];
    $labelKeys = ['label', 'name', 'title', 'headline', 'text', 'cta_text', 'town', 'county', 'service', 'city'];
    $isAssoc = array_keys($node) !== range(0, count($node) - 1);

    if ($isAssoc) {
        foreach ($linkKeys as $linkKey) {
            if (!isset($node[$linkKey]) || !is_string($node[$linkKey])) {
                continue;
            }

            $candidate = trim($node[$linkKey]);
            if ($candidate === '' || !str_starts_with($candidate, '/')) {
                continue;
            }

            $label = '';
            foreach ($labelKeys as $labelKey) {
                if (isset($node[$labelKey]) && is_string($node[$labelKey]) && trim($node[$labelKey]) !== '') {
                    $label = normalize_whitespace($node[$labelKey]);
                    break;
                }
            }

            $field = $prefix === '' ? $linkKey : $prefix . '.' . $linkKey;
            $entries[] = [
                'url' => $candidate,
                'label' => $label,
                'field' => $field,
            ];
        }
    }

    foreach ($node as $key => $value) {
        if (!is_array($value)) {
            continue;
        }

        $childPrefix = $prefix === '' ? (string) $key : $prefix . '.' . $key;
        $entries = array_merge($entries, collect_link_entries($value, $childPrefix));
    }

    $unique = [];
    foreach ($entries as $entry) {
        $key = ($entry['field'] ?? '') . '|' . normalize_path((string) ($entry['url'] ?? '')) . '|' . (string) ($entry['label'] ?? '');
        $unique[$key] = $entry;
    }

    return array_values($unique);
}

function flatten_image_fields(mixed $node, string $prefix = ''): array
{
    $result = [
        'image_fields' => [],
        'alt_fields' => [],
        'missing_images' => [],
        'missing_alts' => [],
    ];

    if (!is_array($node)) {
        return $result;
    }

    foreach ($node as $key => $value) {
        $key = (string) $key;
        $keyPath = $prefix === '' ? $key : $prefix . '.' . $key;

        if (is_array($value)) {
            $child = flatten_image_fields($value, $keyPath);
            foreach ($result as $bucket => $items) {
                $result[$bucket] = array_merge($items, $child[$bucket]);
            }
            continue;
        }

        if (!is_string($value) && !is_null($value)) {
            continue;
        }

        $scalarValue = trim((string) $value);
        $isImageKey = (bool) preg_match('/(^|_)(image|logo|avatar)$/', $key)
            || in_array($key, ['before_image', 'after_image', 'background_image', 'background_video', 'office_image', 'hero_image'], true);
        $isAltKey = (bool) preg_match('/(^|_)(alt|alt_text)$/', $key);

        if ($isImageKey) {
            $result['image_fields'][] = $keyPath . '=' . $scalarValue;
            if ($scalarValue === '') {
                $result['missing_images'][] = $keyPath;
            }
        }

        if ($isAltKey) {
            $result['alt_fields'][] = $keyPath . '=' . $scalarValue;
            if ($scalarValue === '') {
                $result['missing_alts'][] = $keyPath;
            }
        }
    }

    return $result;
}

function canonicalize_for_dup(string $value, array $townNames, array $serviceNames): string
{
    $value = normalize_text($value);

    if ($value === '') {
        return '';
    }

    $replaceWords = [
        'connecticut',
        'ct',
        'fairfield county',
        'new haven county',
        'orange',
        'new haven',
    ];

    foreach ($townNames as $town) {
        $replaceWords[] = normalize_text($town);
    }
    foreach ($serviceNames as $service) {
        $replaceWords[] = normalize_text($service);
    }

    $replaceWords = array_values(array_unique(array_filter($replaceWords)));

    foreach ($replaceWords as $word) {
        if ($word === '') {
            continue;
        }

        $value = preg_replace('/\b' . preg_quote($word, '/') . '\b/u', ' {x} ', $value) ?? $value;
    }

    $value = normalize_whitespace($value);

    return $value;
}

function token_set(string $value): array
{
    $tokens = preg_split('/\s+/', normalize_text($value)) ?: [];
    $tokens = array_values(array_filter($tokens, function (string $token): bool {
        return mb_strlen($token) >= 3
            && !in_array($token, ['the', 'and', 'for', 'with', 'your', 'from', 'this', 'that', 'our', 'you', 'are'], true);
    }));

    return array_values(array_unique($tokens));
}

function jaccard_similarity(string $a, string $b): float
{
    $aTokens = token_set($a);
    $bTokens = token_set($b);

    if (empty($aTokens) && empty($bTokens)) {
        return 1.0;
    }
    if (empty($aTokens) || empty($bTokens)) {
        return 0.0;
    }

    $aMap = array_fill_keys($aTokens, true);
    $bMap = array_fill_keys($bTokens, true);
    $intersection = count(array_intersect_key($aMap, $bMap));
    $union = count($aMap) + count($bMap) - $intersection;

    return $union === 0 ? 0.0 : $intersection / $union;
}

function path_depth(string $path): int
{
    $path = trim($path, '/');
    if ($path === '') {
        return 0;
    }

    return count(array_filter(explode('/', $path)));
}

function extract_section_text(string $type, array $data): array
{
    $h1Candidates = [];
    $headings = [];
    $subheadings = [];
    $introTexts = [];
    $faqQuestions = [];
    $faqAnswers = [];

    $headingKeys = ['headline', 'title', 'section_label', 'name', 'h1'];
    $subheadingKeys = ['subheadline', 'subtitle', 'eyebrow'];
    $introKeys = ['content', 'description', 'body', 'intro', 'text'];

    foreach ($headingKeys as $key) {
        if (isset($data[$key]) && is_string($data[$key]) && trim($data[$key]) !== '') {
            $headings[] = normalize_whitespace($data[$key]);
        }
    }
    foreach ($subheadingKeys as $key) {
        if (isset($data[$key]) && is_string($data[$key]) && trim($data[$key]) !== '') {
            $subheadings[] = normalize_whitespace($data[$key]);
        }
    }
    foreach ($introKeys as $key) {
        if (isset($data[$key]) && is_string($data[$key]) && trim($data[$key]) !== '') {
            $introTexts[] = normalize_whitespace($data[$key]);
        }
    }

    $h1Eligible = [
        'hero',
        'hero_slider',
        'hero_service_location',
        'service_hero',
        'page_hero',
        'case_study_header',
    ];
    if (in_array($type, $h1Eligible, true)) {
        foreach ($headings as $heading) {
            $h1Candidates[] = $heading;
        }
    }

    if (in_array($type, ['faq_list', 'faq_accordion'], true) && isset($data['items']) && is_array($data['items'])) {
        foreach ($data['items'] as $faq) {
            if (!is_array($faq)) {
                continue;
            }
            $question = isset($faq['question']) && is_string($faq['question']) ? normalize_whitespace($faq['question']) : '';
            $answer = isset($faq['answer']) && is_string($faq['answer']) ? normalize_whitespace(strip_tags($faq['answer'])) : '';

            if ($question !== '') {
                $faqQuestions[] = $question;
            }
            if ($answer !== '') {
                $faqAnswers[] = $answer;
            }
        }
    }

    $links = array_values(array_unique(array_map('normalize_path', flatten_links($data))));
    $images = flatten_image_fields($data);

    return [
        'h1_candidates' => array_values(array_unique($h1Candidates)),
        'headings' => array_values(array_unique($headings)),
        'subheadings' => array_values(array_unique($subheadings)),
        'intro_texts' => array_values(array_unique($introTexts)),
        'faq_questions' => array_values(array_unique($faqQuestions)),
        'faq_answers' => array_values(array_unique($faqAnswers)),
        'links' => $links,
        'image_fields' => array_values(array_unique($images['image_fields'])),
        'alt_fields' => array_values(array_unique($images['alt_fields'])),
        'missing_images' => array_values(array_unique($images['missing_images'])),
        'missing_alts' => array_values(array_unique($images['missing_alts'])),
    ];
}

function csv_value(array $values): string
{
    $values = array_values(array_filter(array_map(static fn ($v) => trim((string) $v), $values), static fn ($v) => $v !== ''));
    return implode(' | ', $values);
}

$publishedQueryString = "Page::query()"
    . "->where('status','published')"
    . "->where(function (\$q) { \$q->whereNull('published_at')->orWhere('published_at','<=', now()); })";

$publishedPages = Page::query()
    ->where('status', 'published')
    ->where(function ($q) {
        $q->whereNull('published_at')
            ->orWhere('published_at', '<=', now());
    })
    ->with([
        'sections' => function ($q) {
            $q->orderBy('sort_order');
        },
        'county',
        'town',
        'service',
    ])
    ->orderBy('full_path')
    ->get();

$publishedCount = $publishedPages->count();
$publishedPagesWithoutActiveSections = $publishedPages
    ->filter(static fn (Page $page) => $page->sections->where('is_active', true)->isEmpty())
    ->values();

// Step 1A - pages.csv
$pagesCsvPath = $auditDir . DIRECTORY_SEPARATOR . 'pages.csv';
$pagesCsv = fopen($pagesCsvPath, 'wb');
fputcsv($pagesCsv, [
    'id',
    'full_path',
    'template_key',
    'seo_title',
    'seo_description',
    'canonical_url',
    'published_at',
    'county_id',
    'town_id',
    'service_id',
]);

// Step 1B - sections.csv and page_text_extract.json
$sectionsCsvPath = $auditDir . DIRECTORY_SEPARATOR . 'sections.csv';
$sectionsCsv = fopen($sectionsCsvPath, 'wb');
fputcsv($sectionsCsv, [
    'page_id',
    'page_path',
    'template_key',
    'section_id',
    'type',
    'sort_order',
    'is_active',
    'hero_headline',
    'hero_subheadline',
    'section_title',
    'intro_excerpt',
    'faq_questions',
    'internal_urls',
    'missing_image_fields',
    'missing_alt_fields',
]);

$pageTextExtract = [];

$publishedPathSet = [];
foreach ($publishedPages as $page) {
    /** @var Page $page */
    $publishedPathSet[normalize_path($page->full_path)] = true;

    fputcsv($pagesCsv, [
        $page->id,
        $page->full_path,
        $page->template_key,
        (string) ($page->seo_title ?? ''),
        (string) ($page->seo_description ?? ''),
        (string) ($page->canonical_url ?? ''),
        optional($page->published_at)->toDateTimeString(),
        $page->county_id,
        $page->town_id,
        $page->service_id,
    ]);

    $activeSections = $page->sections->where('is_active', true)->sortBy('sort_order')->values();

    $pageAgg = [
        'id' => $page->id,
        'full_path' => $page->full_path,
        'template_key' => $page->template_key,
        'seo_title' => (string) ($page->seo_title ?? ''),
        'seo_description' => (string) ($page->seo_description ?? ''),
        'canonical_stored' => (string) ($page->canonical_url ?? ''),
        'canonical_effective' => CanonicalResolver::resolve($page),
        'published_at' => optional($page->published_at)->toDateTimeString(),
        'county_id' => $page->county_id,
        'town_id' => $page->town_id,
        'service_id' => $page->service_id,
        'sections' => [],
        'h1_candidates' => [],
        'headings' => [],
        'subheadings' => [],
        'intro_texts' => [],
        'faq_questions' => [],
        'faq_answers' => [],
        'internal_links' => [],
        'image_fields' => [],
        'alt_fields' => [],
        'missing_images' => [],
        'missing_alts' => [],
    ];

    foreach ($activeSections as $section) {
        $data = is_array($section->data) ? $section->data : [];
        $extract = extract_section_text($section->type, $data);

        $heroHeadline = '';
        $heroSubheadline = '';
        if (in_array($section->type, ['hero', 'hero_slider', 'hero_service_location', 'service_hero', 'page_hero'], true)) {
            $heroHeadline = $extract['headings'][0] ?? '';
            $heroSubheadline = $extract['subheadings'][0] ?? '';
        }

        fputcsv($sectionsCsv, [
            $page->id,
            $page->full_path,
            $page->template_key,
            $section->id,
            $section->type,
            (int) $section->sort_order,
            $section->is_active ? 1 : 0,
            $heroHeadline,
            $heroSubheadline,
            $extract['headings'][0] ?? '',
            text_excerpt($extract['intro_texts'][0] ?? ''),
            csv_value($extract['faq_questions']),
            csv_value($extract['links']),
            csv_value($extract['missing_images']),
            csv_value($extract['missing_alts']),
        ]);

        $pageAgg['sections'][] = [
            'id' => $section->id,
            'type' => $section->type,
            'sort_order' => (int) $section->sort_order,
            'extract' => $extract,
        ];

        foreach ([
            'h1_candidates',
            'headings',
            'subheadings',
            'intro_texts',
            'faq_questions',
            'faq_answers',
            'internal_links' => 'links',
            'image_fields',
            'alt_fields',
            'missing_images',
            'missing_alts',
        ] as $target => $source) {
            if (is_int($target)) {
                $target = $source;
            }
            $source = is_string($source) ? $source : $target;

            $pageAgg[$target] = array_values(array_unique(array_merge($pageAgg[$target], $extract[$source])));
        }
    }

    $pageTextExtract[$page->full_path] = $pageAgg;
}

fclose($pagesCsv);
fclose($sectionsCsv);

$pageTextExtractPath = $auditDir . DIRECTORY_SEPARATOR . 'page_text_extract.json';
file_put_contents(
    $pageTextExtractPath,
    json_encode(array_values($pageTextExtract), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
);

// Step 1C - representative payloads
$pickBy = static function (callable $predicate) use ($publishedPages): ?Page {
    foreach ($publishedPages as $page) {
        if ($predicate($page)) {
            return $page;
        }
    }
    return null;
};

$samples = [
    'home' => $pickBy(fn (Page $p) => normalize_path($p->full_path) === '/'),
    'generic' => $pickBy(fn (Page $p) => $p->template_key === 'generic'),
    'county_hub' => $pickBy(fn (Page $p) => $p->template_key === 'county_hub' && path_depth(normalize_path($p->full_path)) === 1),
    'town_hub' => $pickBy(fn (Page $p) => $p->template_key === 'county_hub' && path_depth(normalize_path($p->full_path)) === 2),
    'service_global' => $pickBy(fn (Page $p) => $p->template_key === 'service_global'),
    'service_town' => $pickBy(fn (Page $p) => $p->template_key === 'service_town'),
    'office' => $pickBy(fn (Page $p) => normalize_path($p->full_path) === '/new-haven-county/orange-ct'),
    'case_study' => $pickBy(fn (Page $p) => $p->template_key === 'case_study'),
    'faq' => $pickBy(fn (Page $p) => normalize_path($p->full_path) === '/faq'),
    'contact' => $pickBy(fn (Page $p) => normalize_path($p->full_path) === '/contact'),
];

$samplePayloads = [
    'generated_at' => now()->toDateTimeString(),
    'published_query' => $publishedQueryString,
    'published_count' => $publishedCount,
    'samples' => [],
];

foreach ($samples as $label => $page) {
    if (!$page) {
        continue;
    }

    $page->loadMissing(['sections', 'county', 'town', 'service']);
    $breadcrumbs = BreadcrumbBuilder::build($page);
    $schema = SchemaBuilder::build($page);

    $samplePayloads['samples'][$label] = [
        'id' => $page->id,
        'full_path' => $page->full_path,
        'template_key' => $page->template_key,
        'seo_title' => $page->seo_title,
        'seo_description' => $page->seo_description,
        'canonical' => CanonicalResolver::resolve($page),
        'phones' => PhoneResolver::resolve($page),
        'footer' => FooterTemplateResolver::resolve($page),
        'breadcrumbs' => $breadcrumbs,
        'schema' => $schema,
        'section_types' => $page->sections->where('is_active', true)->sortBy('sort_order')->pluck('type')->values()->all(),
    ];
}

$samplePayloadsPath = $auditDir . DIRECTORY_SEPARATOR . 'sample_api_payloads.json';
file_put_contents(
    $samplePayloadsPath,
    json_encode($samplePayloads, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
);

// ----- Step 2: SEO audit checks -----
$townNames = $publishedPages->map(fn (Page $p) => $p->town?->name)->filter()->unique()->values()->all();
$serviceNames = $publishedPages->map(fn (Page $p) => $p->service?->name)->filter()->unique()->values()->all();

$metaMissing = [
    'title' => [],
    'description' => [],
    'effective_canonical' => [],
    'stored_canonical' => [],
];
$metaOverlong = [
    'titles' => [],
    'descriptions' => [],
];

$titleGroups = [];
$descGroups = [];
$titleNearGroups = [];
$descNearGroups = [];

foreach ($publishedPages as $page) {
    $path = normalize_path($page->full_path);
    $title = trim((string) ($page->seo_title ?? ''));
    $desc = trim((string) ($page->seo_description ?? ''));
    $storedCanonical = trim((string) ($page->canonical_url ?? ''));
    $effectiveCanonical = CanonicalResolver::resolve($page);

    if ($title === '') {
        $metaMissing['title'][] = $path;
    }
    if ($desc === '') {
        $metaMissing['description'][] = $path;
    }
    if (trim($effectiveCanonical) === '') {
        $metaMissing['effective_canonical'][] = $path;
    }
    if ($storedCanonical === '') {
        $metaMissing['stored_canonical'][] = $path;
    }

    $titleLen = mb_strlen($title);
    $descLen = mb_strlen($desc);
    if ($titleLen > 60) {
        $metaOverlong['titles'][] = ['path' => $path, 'length' => $titleLen, 'value' => $title];
    }
    if ($descLen > 160) {
        $metaOverlong['descriptions'][] = ['path' => $path, 'length' => $descLen, 'value' => $desc];
    }

    $titleNorm = normalize_text($title);
    $descNorm = normalize_text($desc);

    if ($titleNorm !== '') {
        $titleGroups[$titleNorm][] = $path;
        $titleNearKey = canonicalize_for_dup($title, $townNames, $serviceNames);
        $titleNearGroups[$titleNearKey][] = $path;
    }
    if ($descNorm !== '') {
        $descGroups[$descNorm][] = $path;
        $descNearKey = canonicalize_for_dup($desc, $townNames, $serviceNames);
        $descNearGroups[$descNearKey][] = $path;
    }
}

usort($metaOverlong['titles'], static fn ($a, $b) => $b['length'] <=> $a['length']);
usort($metaOverlong['descriptions'], static fn ($a, $b) => $b['length'] <=> $a['length']);

$duplicateTitles = array_values(array_filter($titleGroups, static fn ($paths) => count($paths) > 1));
$duplicateDescriptions = array_values(array_filter($descGroups, static fn ($paths) => count($paths) > 1));
$nearDuplicateTitles = array_values(array_filter($titleNearGroups, static fn ($paths) => count($paths) > 1));
$nearDuplicateDescriptions = array_values(array_filter($descNearGroups, static fn ($paths) => count($paths) > 1));

usort($nearDuplicateTitles, static fn ($a, $b) => count($b) <=> count($a));
usort($nearDuplicateDescriptions, static fn ($a, $b) => count($b) <=> count($a));

// service_town uniqueness
$serviceTownPages = $publishedPages->filter(fn (Page $p) => $p->template_key === 'service_town')->values();
$serviceTownUniqMap = [];
$serviceTownFaqMap = [];
$serviceTownLocalText = [];

foreach ($serviceTownPages as $page) {
    $pageData = $pageTextExtract[$page->full_path] ?? null;
    if (!$pageData) {
        continue;
    }

    $localText = implode(' ', array_merge(
        $pageData['h1_candidates'],
        $pageData['subheadings'],
        $pageData['intro_texts'],
        $pageData['faq_questions']
    ));

    $normalized = canonicalize_for_dup($localText, $townNames, $serviceNames);
    $serviceTownLocalText[$page->full_path] = $normalized;

    $fingerprint = sha1($normalized);
    $serviceTownUniqMap[$fingerprint][] = $page->full_path;

    $faqNormalized = array_map(
        static fn ($q) => canonicalize_for_dup($q, $townNames, $serviceNames),
        $pageData['faq_questions']
    );
    sort($faqNormalized);
    $faqFingerprint = sha1(implode(' || ', $faqNormalized));
    $serviceTownFaqMap[$faqFingerprint][] = $page->full_path;
}

$serviceTownDuplicateClusters = array_values(array_filter(
    $serviceTownUniqMap,
    static fn ($paths) => count($paths) > 1
));
usort($serviceTownDuplicateClusters, static fn ($a, $b) => count($b) <=> count($a));

$serviceTownFaqClusters = array_values(array_filter(
    $serviceTownFaqMap,
    static fn ($paths) => count($paths) > 1
));
usort($serviceTownFaqClusters, static fn ($a, $b) => count($b) <=> count($a));

$serviceTownSimilarityPairs = [];
$serviceTownPaths = array_keys($serviceTownLocalText);
for ($i = 0; $i < count($serviceTownPaths); $i++) {
    for ($j = $i + 1; $j < count($serviceTownPaths); $j++) {
        $aPath = $serviceTownPaths[$i];
        $bPath = $serviceTownPaths[$j];
        $score = jaccard_similarity($serviceTownLocalText[$aPath], $serviceTownLocalText[$bPath]);

        if ($score >= 0.82) {
            $serviceTownSimilarityPairs[] = [
                'a' => $aPath,
                'b' => $bPath,
                'score' => round($score, 3),
            ];
        }
    }
}
usort($serviceTownSimilarityPairs, static fn ($a, $b) => $b['score'] <=> $a['score']);
$serviceTownSimilarityPairs = array_slice($serviceTownSimilarityPairs, 0, 30);

// Heading structure
$missingH1 = [];
$multipleH1 = [];
$weakSubHeadings = [];

foreach ($publishedPages as $page) {
    $pageData = $pageTextExtract[$page->full_path] ?? null;
    if (!$pageData) {
        continue;
    }

    $h1Candidates = array_values(array_unique(array_filter(array_map('trim', $pageData['h1_candidates']))));

    if (count($h1Candidates) === 0) {
        $missingH1[] = $page->full_path;
    }
    if (count($h1Candidates) > 1) {
        $multipleH1[] = [
            'path' => $page->full_path,
            'h1_candidates' => $h1Candidates,
        ];
    }

    $secondary = array_values(array_filter($pageData['headings'], static function ($heading) use ($h1Candidates): bool {
        return !in_array($heading, $h1Candidates, true);
    }));

    if (count($secondary) === 0) {
        $weakSubHeadings[] = $page->full_path;
    }
}

// Internal links
$activeRedirectFrom = Redirect::query()
    ->where('is_active', true)
    ->pluck('from_path')
    ->map(fn ($p) => normalize_path((string) $p))
    ->flip()
    ->all();

$linkGraph = [];
$inbound = [];
$allInternalLinks = [];

foreach ($publishedPages as $page) {
    $path = normalize_path($page->full_path);
    $links = $pageTextExtract[$page->full_path]['internal_links'] ?? [];
    $links = array_values(array_unique(array_map('normalize_path', $links)));
    $linkGraph[$path] = $links;

    foreach ($links as $target) {
        $inbound[$target][] = $path;
        $allInternalLinks[] = [
            'source' => $path,
            'target' => $target,
        ];
    }
}

$hubPages = [];
foreach ($publishedPages as $page) {
    $path = normalize_path($page->full_path);
    if (
        $page->template_key === 'county_hub'
        || in_array($path, ['/areas-we-serve', '/services', '/case-studies', '/fairfield-county', '/new-haven-county'], true)
    ) {
        $hubPages[$path] = true;
    }
}

$noInboundFromHubs = [];
foreach ($publishedPages as $page) {
    $path = normalize_path($page->full_path);
    if (isset($hubPages[$path]) || $path === '/') {
        continue;
    }

    $sources = $inbound[$path] ?? [];
    $fromHubs = array_values(array_filter($sources, static fn ($src) => isset($hubPages[$src])));

    if (empty($fromHubs)) {
        $noInboundFromHubs[] = $path;
    }
}

$brokenLinks = [];
foreach ($allInternalLinks as $edge) {
    $target = $edge['target'];
    if (isset($publishedPathSet[$target])) {
        continue;
    }
    if (isset($activeRedirectFrom[$target])) {
        continue;
    }

    $brokenLinks[$target]['target'] = $target;
    $brokenLinks[$target]['sources'][] = $edge['source'];
}

foreach ($brokenLinks as &$row) {
    $row['sources'] = array_values(array_unique($row['sources']));
}
unset($row);
$brokenLinks = array_values($brokenLinks);
usort($brokenLinks, static fn ($a, $b) => count($b['sources']) <=> count($a['sources']));

// County hub -> town hub validation
$countyRoots = $publishedPages->filter(fn (Page $p) => $p->template_key === 'county_hub' && path_depth(normalize_path($p->full_path)) === 1)->values();
$townHubs = $publishedPages->filter(fn (Page $p) => $p->template_key === 'county_hub' && path_depth(normalize_path($p->full_path)) === 2)->values();

$missingCountyToTownLinks = [];
foreach ($countyRoots as $root) {
    $rootPath = normalize_path($root->full_path);
    $expected = [];
    foreach ($townHubs as $townHub) {
        $townPath = normalize_path($townHub->full_path);
        if (str_starts_with($townPath, $rootPath . '/')) {
            $expected[] = $townPath;
        }
    }

    $actual = $linkGraph[$rootPath] ?? [];
    $missing = array_values(array_diff($expected, $actual));

    if (!empty($missing)) {
        $missingCountyToTownLinks[] = [
            'county_hub' => $rootPath,
            'missing_town_hub_links' => $missing,
        ];
    }
}

// Town hub -> service_town(4) validation
$serviceTownByTown = [];
foreach ($serviceTownPages as $page) {
    $townId = $page->town_id;
    if (!$townId) {
        continue;
    }
    $serviceTownByTown[$townId][] = normalize_path($page->full_path);
}
foreach ($serviceTownByTown as &$paths) {
    sort($paths);
}
unset($paths);

$townHubMissingServiceLinks = [];
foreach ($townHubs as $townHub) {
    $townPath = normalize_path($townHub->full_path);
    $expected = $serviceTownByTown[$townHub->town_id] ?? [];
    $actual = $linkGraph[$townPath] ?? [];
    $missing = array_values(array_diff($expected, $actual));

    if (count($expected) > 0 && !empty($missing)) {
        $townHubMissingServiceLinks[] = [
            'town_hub' => $townPath,
            'expected_service_town_links' => $expected,
            'missing_links' => $missing,
        ];
    }
}

// Areas-served link validation (county/town/office links only)
$countyBases = ['/fairfield-county', '/new-haven-county'];
$serviceBases = ['/kitchen-remodeling', '/bathroom-remodeling', '/basement-finishing', '/flooring'];
foreach ($serviceTownPages as $serviceTownPage) {
    $serviceTownPath = trim(normalize_path($serviceTownPage->full_path), '/');
    if ($serviceTownPath === '') {
        continue;
    }

    $slug = explode('/', $serviceTownPath)[0] ?? '';
    if ($slug !== '') {
        $serviceBases[] = '/' . $slug;
    }
}
$serviceBases = array_values(array_unique(array_map('normalize_path', $serviceBases)));

$allowedAreaTargets = [];
foreach ($publishedPages as $publishedPage) {
    if (!in_array((string) $publishedPage->template_key, ['town_hub', 'office', 'county_hub'], true)) {
        continue;
    }

    $allowedAreaTargets[normalize_path($publishedPage->full_path)] = true;
}

$areasServedCandidateTypes = ['areas_served', 'town_list', 'service_areas'];
$areasServedLinkViolations = [];
$areasServedLinkViolationsLegacy = [];
$legacyAllowedTownTargets = [];
foreach ($townHubs as $townHub) {
    $legacyAllowedTownTargets[normalize_path($townHub->full_path)] = true;
}

foreach ($publishedPages as $page) {
    $pagePath = normalize_path($page->full_path);
    $pageDepth = path_depth($pagePath);
    $activeSections = $page->sections->where('is_active', true);

    foreach ($activeSections as $section) {
        if (!in_array((string) $section->type, $areasServedCandidateTypes, true)) {
            continue;
        }

        $sectionData = is_array($section->data) ? $section->data : [];
        $entries = collect_link_entries($sectionData);
        if (empty($entries)) {
            continue;
        }

        foreach ($entries as $entry) {
            $target = normalize_path((string) ($entry['url'] ?? ''));
            $label = (string) ($entry['label'] ?? '');
            $field = (string) ($entry['field'] ?? '');
            if ($target === '/' || $target === '') {
                continue;
            }

            $isCountyPath = is_path_under_any_base($target, $countyBases);
            $isServicePath = is_path_under_any_base($target, $serviceBases);

            // Legacy bucket preserved for before/after evidence.
            if (($isCountyPath || $isServicePath) && !isset($legacyAllowedTownTargets[$target])) {
                $areasServedLinkViolationsLegacy[] = [
                    'page_id' => $page->id,
                    'full_path' => $pagePath,
                    'section_id' => $section->id,
                    'section_type' => $section->type,
                    'field' => $field,
                    'label' => $label,
                    'target' => $target,
                ];
            }

            if (!$isCountyPath) {
                continue;
            }

            // Service targets are required in town-hub service lists and must be ignored.
            if ($isServicePath) {
                continue;
            }

            // Skip town-hub service list blocks and service-labeled entries.
            if ($section->type === 'town_list' && $page->template_key === 'county_hub' && $pageDepth === 2) {
                continue;
            }
            if (is_service_label($label)) {
                continue;
            }

            if (!isset($allowedAreaTargets[$target])) {
                $areasServedLinkViolations[] = [
                    'page_id' => $page->id,
                    'full_path' => $pagePath,
                    'section_id' => $section->id,
                    'section_type' => $section->type,
                    'field' => $field,
                    'label' => $label,
                    'target' => $target,
                ];
            }
        }
    }
}

$areasServedLinkViolationsLegacy = array_values(array_reduce(
    $areasServedLinkViolationsLegacy,
    static function (array $carry, array $row): array {
        $key = implode('|', [
            (string) ($row['page_id'] ?? ''),
            (string) ($row['section_id'] ?? ''),
            (string) ($row['field'] ?? ''),
            (string) ($row['target'] ?? ''),
        ]);
        $carry[$key] = $row;
        return $carry;
    },
    []
));

$areasServedLinkViolations = array_values(array_reduce(
    $areasServedLinkViolations,
    static function (array $carry, array $row): array {
        $key = implode('|', [
            (string) ($row['page_id'] ?? ''),
            (string) ($row['section_id'] ?? ''),
            (string) ($row['field'] ?? ''),
            (string) ($row['target'] ?? ''),
        ]);
        $carry[$key] = $row;
        return $carry;
    },
    []
));

// Schema checks
$schemaIssues = [
    'missing_breadcrumb' => [],
    'home_missing_organization' => [],
    'service_missing_service_schema' => [],
    'office_missing_hcb' => [],
    'hcb_on_non_office' => [],
    'schema_url_canonical_mismatch' => [],
    'breadcrumb_item_not_trailing_slash' => [],
];

$officePath = '/new-haven-county/orange-ct';

foreach ($publishedPages as $page) {
    $path = normalize_path($page->full_path);
    $canonical = CanonicalResolver::resolve($page);
    $schema = SchemaBuilder::build($page);
    $types = [];
    foreach ($schema as $item) {
        if (!is_array($item)) {
            continue;
        }
        $type = (string) ($item['@type'] ?? '');
        if ($type !== '') {
            $types[] = $type;
        }

        if (isset($item['url']) && is_string($item['url']) && trim($item['url']) !== '') {
            $schemaUrl = trim($item['url']);
            if (rtrim($schemaUrl, '/') . '/' !== rtrim($canonical, '/') . '/') {
                $schemaIssues['schema_url_canonical_mismatch'][] = [
                    'path' => $path,
                    'schema_type' => $type,
                    'schema_url' => $schemaUrl,
                    'canonical' => $canonical,
                ];
            }
        }

        if (($item['@type'] ?? '') === 'BreadcrumbList' && isset($item['itemListElement']) && is_array($item['itemListElement'])) {
            foreach ($item['itemListElement'] as $crumb) {
                if (!is_array($crumb)) {
                    continue;
                }
                $crumbItem = (string) ($crumb['item'] ?? '');
                if ($crumbItem === '') {
                    continue;
                }

                $crumbPath = (string) parse_url($crumbItem, PHP_URL_PATH);
                if ($crumbPath !== '/' && !str_ends_with($crumbPath, '/')) {
                    $schemaIssues['breadcrumb_item_not_trailing_slash'][] = [
                        'path' => $path,
                        'breadcrumb_item' => $crumbItem,
                    ];
                }
            }
        }
    }

    $types = array_values(array_unique($types));

    if (!in_array('BreadcrumbList', $types, true)) {
        $schemaIssues['missing_breadcrumb'][] = $path;
    }
    if ($path === '/' && !in_array('Organization', $types, true)) {
        $schemaIssues['home_missing_organization'][] = $path;
    }
    if (in_array($page->template_key, ['service_global', 'service_town', 'service_county'], true) && !in_array('Service', $types, true)) {
        $schemaIssues['service_missing_service_schema'][] = $path;
    }
    if ($path === $officePath && !in_array('HomeAndConstructionBusiness', $types, true)) {
        $schemaIssues['office_missing_hcb'][] = $path;
    }
    if ($path !== $officePath && in_array('HomeAndConstructionBusiness', $types, true)) {
        $schemaIssues['hcb_on_non_office'][] = $path;
    }
}

// Media / alt checks
$imageMissingByField = [];
$altMissingByField = [];
$imageIssuesByPage = [];

foreach ($publishedPages as $page) {
    $pageData = $pageTextExtract[$page->full_path] ?? null;
    if (!$pageData) {
        continue;
    }

    $path = normalize_path($page->full_path);
    $pageMissingImages = $pageData['missing_images'] ?? [];
    $pageMissingAlts = $pageData['missing_alts'] ?? [];

    if (!empty($pageMissingImages) || !empty($pageMissingAlts)) {
        $imageIssuesByPage[] = [
            'path' => $path,
            'template_key' => $page->template_key,
            'missing_images' => $pageMissingImages,
            'missing_alts' => $pageMissingAlts,
        ];
    }

    foreach ($pageMissingImages as $field) {
        $imageMissingByField[$field] = ($imageMissingByField[$field] ?? 0) + 1;
    }
    foreach ($pageMissingAlts as $field) {
        $altMissingByField[$field] = ($altMissingByField[$field] ?? 0) + 1;
    }
}

arsort($imageMissingByField);
arsort($altMissingByField);

// LOCKED rule checks via resolvers
$lockedChecks = [
    'global_pages_have_both_phones' => true,
    'county_pages_have_single_phone' => true,
    'address_only_on_office_page' => true,
    'footer_variant_d_only_office' => true,
];

$nonOfficeWithAddress = [];
$officeWithoutAddress = [];
$footerDNonOffice = [];
$globalPhoneIssues = [];
$countyPhoneIssues = [];

foreach ($publishedPages as $page) {
    $path = normalize_path($page->full_path);
    $phones = PhoneResolver::resolve($page);
    $footer = FooterTemplateResolver::resolve($page);

    $isOffice = $path === $officePath;
    $hasCounty = !is_null($page->county_id);

    if (!$hasCounty) {
        if (($phones['mode'] ?? '') !== 'both') {
            $lockedChecks['global_pages_have_both_phones'] = false;
            $globalPhoneIssues[] = $path;
        }
    } else {
        if (($phones['mode'] ?? '') !== 'single') {
            $lockedChecks['county_pages_have_single_phone'] = false;
            $countyPhoneIssues[] = $path;
        }
    }

    $hasAddress = isset($footer['address']) && is_array($footer['address']);
    if ($isOffice && !$hasAddress) {
        $lockedChecks['address_only_on_office_page'] = false;
        $officeWithoutAddress[] = $path;
    }
    if (!$isOffice && $hasAddress) {
        $lockedChecks['address_only_on_office_page'] = false;
        $nonOfficeWithAddress[] = $path;
    }

    $variant = (string) ($footer['template'] ?? '');
    if ($variant === 'D' && !$isOffice) {
        $lockedChecks['footer_variant_d_only_office'] = false;
        $footerDNonOffice[] = $path;
    }
}

// ----- Step 3: report -----
$reportPath = $auditDir . DIRECTORY_SEPARATOR . 'SEO_AUDIT_REPORT.md';

$titleNearTop = array_slice($nearDuplicateTitles, 0, 6);
$descNearTop = array_slice($nearDuplicateDescriptions, 0, 6);
$serviceTownClusterTop = array_slice($serviceTownDuplicateClusters, 0, 6);
$faqClusterTop = array_slice($serviceTownFaqClusters, 0, 6);
$brokenLinksTop = array_slice($brokenLinks, 0, 15);
$noInboundFromHubsTop = array_slice($noInboundFromHubs, 0, 25);
$imageIssuesTop = array_slice($imageIssuesByPage, 0, 20);
$altMissingTop = array_slice($altMissingByField, 0, 15, true);
$imageMissingTop = array_slice($imageMissingByField, 0, 15, true);

$report = [];
$report[] = '# BuiltWell CT SEO QA Audit (Live DB)';
$report[] = '';
$report[] = 'Generated at: ' . now()->toDateTimeString();
$report[] = '';
$report[] = 'Source of truth: **live database content** loaded through Laravel Eloquent/Resolvers.';
$report[] = '';
$report[] = '## Executive Summary';
$report[] = '';
$report[] = '- Published query used: `' . $publishedQueryString . '`';
$report[] = '- Published pages found: **' . $publishedCount . '** (spec target: 106)';
$report[] = '- Published pages without active sections: **' . $publishedPagesWithoutActiveSections->count() . '**';
$report[] = '- service_town pages found: **' . $serviceTownPages->count() . '** (spec focus target: 64)';
$report[] = '- Overlong titles (>60): **' . count($metaOverlong['titles']) . '**';
$report[] = '- Overlong descriptions (>160): **' . count($metaOverlong['descriptions']) . '**';
$report[] = '- Exact duplicate titles: **' . count($duplicateTitles) . ' groups**';
$report[] = '- Exact duplicate descriptions: **' . count($duplicateDescriptions) . ' groups**';
$report[] = '- Near-duplicate service_town content clusters: **' . count($serviceTownDuplicateClusters) . '**';
$report[] = '- Broken internal links (no published target + no active redirect): **' . count($brokenLinks) . '**';
$report[] = '- Areas-served link violations (county/town/office only): **' . count($areasServedLinkViolations) . '**';
$report[] = '';
$report[] = '## PASS/FAIL Checklist (Spec Constraints)';
$report[] = '';
$report[] = '| Check | Result | Evidence |';
$report[] = '|---|---|---|';
$report[] = '| Exactly 106 published pages | ' . ($publishedCount === 106 ? 'PASS' : 'FAIL') . ' | Count=' . $publishedCount . ' |';
$report[] = '| No published pages without active sections | ' . ($publishedPagesWithoutActiveSections->isEmpty() ? 'PASS' : 'FAIL') . ' | Count=' . $publishedPagesWithoutActiveSections->count() . ' |';
$report[] = '| Areas-served links target only published county/town/office pages | ' . (empty($areasServedLinkViolations) ? 'PASS' : 'FAIL') . ' | Violations=' . count($areasServedLinkViolations) . ' |';
$report[] = '| Home has Organization schema | ' . (empty($schemaIssues['home_missing_organization']) ? 'PASS' : 'FAIL') . ' | Missing=' . count($schemaIssues['home_missing_organization']) . ' |';
$report[] = '| Office has HomeAndConstructionBusiness | ' . (empty($schemaIssues['office_missing_hcb']) ? 'PASS' : 'FAIL') . ' | Missing=' . count($schemaIssues['office_missing_hcb']) . ' |';
$report[] = '| HomeAndConstructionBusiness only on office page | ' . (empty($schemaIssues['hcb_on_non_office']) ? 'PASS' : 'FAIL') . ' | Violations=' . count($schemaIssues['hcb_on_non_office']) . ' |';
$report[] = '| Service templates emit Service schema | ' . (empty($schemaIssues['service_missing_service_schema']) ? 'PASS' : 'FAIL') . ' | Missing=' . count($schemaIssues['service_missing_service_schema']) . ' |';
$report[] = '| BreadcrumbList exists on every page | ' . (empty($schemaIssues['missing_breadcrumb']) ? 'PASS' : 'FAIL') . ' | Missing=' . count($schemaIssues['missing_breadcrumb']) . ' |';
$report[] = '| Global pages show both phones (LOCKED) | ' . ($lockedChecks['global_pages_have_both_phones'] ? 'PASS' : 'FAIL') . ' | Issues=' . count($globalPhoneIssues) . ' |';
$report[] = '| County pages show single phone (LOCKED) | ' . ($lockedChecks['county_pages_have_single_phone'] ? 'PASS' : 'FAIL') . ' | Issues=' . count($countyPhoneIssues) . ' |';
$report[] = '| Address shown only on office page (LOCKED) | ' . ($lockedChecks['address_only_on_office_page'] ? 'PASS' : 'FAIL') . ' | Non-office with address=' . count($nonOfficeWithAddress) . ' |';
$report[] = '| Footer variant D only on office page (LOCKED) | ' . ($lockedChecks['footer_variant_d_only_office'] ? 'PASS' : 'FAIL') . ' | Violations=' . count($footerDNonOffice) . ' |';
$report[] = '';
$report[] = '## Findings by Severity';
$report[] = '';

$report[] = '### P0 - Critical';
$report[] = '';
if ($publishedCount !== 106) {
    $report[] = '#### P0-01 Published page count mismatch';
    $report[] = '- **Evidence:** expected 106, found ' . $publishedCount . '.';
    $report[] = '- **Why it matters:** breaks spec-locked sitemap scope.';
    $report[] = '- **Minimal safe fix:** move extra pages to draft or publish missing pages to restore exactly 106.';
    $report[] = '';
} else {
    $report[] = '- No P0 issues detected from live DB audit scope.';
    $report[] = '';
}

$report[] = '### P1 - High';
$report[] = '';

$report[] = '#### P1-01 service_town near-duplicate risk';
$report[] = '- **Evidence:** ' . count($serviceTownDuplicateClusters) . ' canonicalized content clusters across 64 service_town pages.';
$report[] = '- Largest clusters (paths):';
foreach ($serviceTownClusterTop as $cluster) {
    $report[] = '  - (' . count($cluster) . ' pages) ' . implode(', ', array_slice($cluster, 0, 8));
}
$report[] = '- FAQ duplicate clusters: ' . count($serviceTownFaqClusters) . ' (top shown below).';
foreach ($faqClusterTop as $cluster) {
    $report[] = '  - (' . count($cluster) . ' pages) ' . implode(', ', array_slice($cluster, 0, 8));
}
$report[] = '- **Why it matters:** high near-duplication increases doorway/thin-content risk, especially in town-level pages.';
$report[] = '- **Minimal safe fix:** keep existing URL structure/templates, vary text by pattern using existing section types only:';
$report[] = '  - Pattern A (education-first): `hero_service_location` -> `service_two_column` -> `local_context` -> `process_steps` -> `faq_list` -> `consultation_cta_split`.';
$report[] = '  - Pattern B (proof-first): `hero_service_location` -> `before_after_showcase` -> `testimonials` -> `service_two_column` -> `faq_list` -> `consultation_cta_split`.';
$report[] = '  - Pattern C (scope-first): `hero_service_location` -> `service_includes` -> `pricing_table` -> `timeline_block` -> `service_area_highlight` -> `faq_list`.';
$report[] = '  - For each page, minimally update these fields: hero subheadline, first intro paragraph, first 3 FAQ questions, one local proof line (permit/cost/timeline cue).';
$report[] = '';

$report[] = '#### P1-02 Internal linking gaps and broken targets';
$report[] = '- **Evidence:** pages with no inbound links from hub pages: ' . count($noInboundFromHubs) . '.';
$report[] = '- Sample pages without hub inbound links: ' . implode(', ', $noInboundFromHubsTop);
$report[] = '- Broken links found: ' . count($brokenLinks) . '.';
foreach ($brokenLinksTop as $row) {
    $report[] = '  - target `' . $row['target'] . '` referenced by ' . implode(', ', $row['sources']);
}
$report[] = '- County hub missing town hub links: ' . count($missingCountyToTownLinks) . '.';
foreach ($missingCountyToTownLinks as $row) {
    $report[] = '  - `' . $row['county_hub'] . '` missing: ' . implode(', ', $row['missing_town_hub_links']);
}
$report[] = '- Town hub missing service_town links: ' . count($townHubMissingServiceLinks) . '.';
foreach (array_slice($townHubMissingServiceLinks, 0, 10) as $row) {
    $report[] = '  - `' . $row['town_hub'] . '` missing: ' . implode(', ', $row['missing_links']);
}
$report[] = '- Areas-served link violations: ' . count($areasServedLinkViolations) . '.';
foreach (array_slice($areasServedLinkViolations, 0, 15) as $row) {
    $report[] = '  - `' . $row['full_path'] . '` section#' . $row['section_id'] . ' field `' . $row['field'] . '` -> `' . $row['target'] . '`';
}
$report[] = '- **Why it matters:** weaker crawl paths and topical graph; broken links leak relevance and trust.';
$report[] = '- **Minimal safe fix:** update existing hub sections (`town_list`, `service_area_links`, CTA link fields) to published targets only.';
$report[] = '';

$report[] = '#### P1-03 Schema URL/canonical mismatch';
$report[] = '- **Evidence:** schema objects with `url` not matching canonical: ' . count($schemaIssues['schema_url_canonical_mismatch']) . '.';
foreach (array_slice($schemaIssues['schema_url_canonical_mismatch'], 0, 15) as $row) {
    $report[] = '  - `' . $row['path'] . '` [' . $row['schema_type'] . '] schema_url=' . $row['schema_url'] . ' canonical=' . $row['canonical'];
}
$report[] = '- **Why it matters:** inconsistent canonical-vs-schema URLs reduce clarity for search parsers.';
$report[] = '- **Minimal safe fix:** set schema `url` fields to the canonical URL for page-level schema objects, keep trailing slash.';
$report[] = '';

$report[] = '### P2 - Medium/Low';
$report[] = '';

$report[] = '#### P2-01 Meta length and duplication quality risks';
$report[] = '- **Evidence:**';
$report[] = '  - Missing titles: ' . count($metaMissing['title']);
$report[] = '  - Missing descriptions: ' . count($metaMissing['description']);
$report[] = '  - Missing effective canonical: ' . count($metaMissing['effective_canonical']);
$report[] = '  - Empty stored canonical_url field: ' . count($metaMissing['stored_canonical']);
$report[] = '  - Overlong titles: ' . count($metaOverlong['titles']);
$report[] = '  - Overlong descriptions: ' . count($metaOverlong['descriptions']);
$report[] = '  - Near-duplicate title groups: ' . count($nearDuplicateTitles);
$report[] = '  - Near-duplicate description groups: ' . count($nearDuplicateDescriptions);
$report[] = '- Worst title lengths (top 10):';
foreach (array_slice($metaOverlong['titles'], 0, 10) as $item) {
    $report[] = '  - ' . $item['path'] . ' (' . $item['length'] . '): ' . $item['value'];
}
$report[] = '- Worst description lengths (top 10):';
foreach (array_slice($metaOverlong['descriptions'], 0, 10) as $item) {
    $report[] = '  - ' . $item['path'] . ' (' . $item['length'] . '): ' . $item['value'];
}
$report[] = '- Largest near-duplicate title groups:';
foreach ($titleNearTop as $group) {
    $report[] = '  - (' . count($group) . ' pages) ' . implode(', ', array_slice($group, 0, 8));
}
$report[] = '- Largest near-duplicate description groups:';
foreach ($descNearTop as $group) {
    $report[] = '  - (' . count($group) . ' pages) ' . implode(', ', array_slice($group, 0, 8));
}
$report[] = '- **Why it matters:** lower SERP differentiation and CTR; potential quality clustering.';
$report[] = '- **Minimal safe fix:** adjust only page-level SEO fields (`seo_title`, `seo_description`) for flagged groups. Keep URLs unchanged.';
$report[] = '';

$report[] = '#### P2-02 Heading structure consistency';
$report[] = '- **Evidence:**';
$report[] = '  - Missing H1-equivalent pages: ' . count($missingH1);
$report[] = '  - Multiple H1-equivalent pages: ' . count($multipleH1);
$report[] = '  - Pages without clear secondary headings: ' . count($weakSubHeadings);
if (!empty($missingH1)) {
    $report[] = '  - Missing H1 paths: ' . implode(', ', array_slice($missingH1, 0, 20));
}
if (!empty($multipleH1)) {
    $report[] = '  - Multiple H1 sample:';
    foreach (array_slice($multipleH1, 0, 10) as $row) {
        $report[] = '    - ' . $row['path'] . ' => ' . implode(' || ', $row['h1_candidates']);
    }
}
$report[] = '- **Why it matters:** weaker semantic hierarchy for crawlers and assistive tech.';
$report[] = '- **Minimal safe fix:** keep one primary hero headline per page, move additional top-level claims into section titles/subheadings.';
$report[] = '';

$report[] = '#### P2-03 Media completeness (image + alt fields)';
$report[] = '- **Evidence:** pages with missing image/alt fields: ' . count($imageIssuesByPage);
$report[] = '- Top missing image fields:';
foreach ($imageMissingTop as $field => $count) {
    $report[] = '  - `' . $field . '` on ' . $count . ' sections';
}
$report[] = '- Top missing alt fields:';
foreach ($altMissingTop as $field => $count) {
    $report[] = '  - `' . $field . '` on ' . $count . ' sections';
}
$report[] = '- Sample affected pages:';
foreach ($imageIssuesTop as $item) {
    $report[] = '  - ' . $item['path'] . ' | missing_images=' . implode(',', array_slice($item['missing_images'], 0, 5)) . ' | missing_alts=' . implode(',', array_slice($item['missing_alts'], 0, 5));
}
$report[] = '- **Why it matters:** image discoverability/accessibility and richer SERP image context.';
$report[] = '- **Minimal safe fix:** fill existing alt fields first (`slides.*.alt`, `items.*.alt`, `image_alt`), then fill empty image slots for above-the-fold sections.';
$report[] = '- Alt text rule: concise factual pattern `"[Service] project in [Town], [space/element], before/after context"` (no keyword stuffing).';
$report[] = '';

$report[] = '## Action Plan';
$report[] = '';
$report[] = '### Fix now (high impact, low risk)';
$report[] = '1. Resolve broken internal links to non-published/non-redirect targets.';
$report[] = '2. Add missing town-hub -> 4 service_town links where absent.';
$report[] = '3. Apply service_town copy variation patterns (A/B/C) to largest duplicate clusters first.';
$report[] = '4. Align schema `url` with canonical for mismatched page-level schemas.';
$report[] = '';
$report[] = '### Fix later (quality hardening)';
$report[] = '1. Reduce near-duplicate title/description groups while keeping concise, factual copy.';
$report[] = '2. Normalize heading depth consistency where secondary headings are absent.';
$report[] = '3. Fill remaining image and alt gaps by section priority (hero, gallery, before/after, highlights).';
$report[] = '';

$report[] = '## Verification Checklist (DB + HTTP)';
$report[] = '';
$report[] = 'DB queries (Laravel):';
$report[] = '';
$report[] = '```php';
$report[] = "// Published pages (spec must be 106)";
$report[] = "Page::query()";
$report[] = "  ->where('status','published')";
$report[] = "  ->where(function (\$q) { \$q->whereNull('published_at')->orWhere('published_at','<=', now()); })";
$report[] = "  ->count();";
$report[] = '';
$report[] = "// service_town count";
$report[] = "Page::query()->where('status','published')->where('template_key','service_town')->count();";
$report[] = '```';
$report[] = '';
$report[] = 'HTTP checks (replace `api.DOMAIN`):';
$report[] = '';
$report[] = '```bash';
$report[] = '# 1) sitemap URL count';
$report[] = 'curl -s https://api.DOMAIN/sitemap.xml | grep -o "<url>" | wc -l';
$report[] = '';
$report[] = '# 2) home schema type';
$report[] = "curl -s https://api.DOMAIN/api/pages | jq '.schema[0][\"@type\"]'";
$report[] = '';
$report[] = '# 3) office schema type';
$report[] = "curl -s https://api.DOMAIN/api/pages/new-haven-county/orange-ct | jq '.schema[0][\"@type\"]'";
$report[] = '';
$report[] = '# 4) service_town payload quality';
$report[] = "curl -s https://api.DOMAIN/api/pages/kitchen-remodeling/greenwich-ct | jq '.seo,.phones,.breadcrumbs'";
$report[] = '';
$report[] = '# 5) redirect behavior';
$report[] = 'curl -si https://api.DOMAIN/api/pages/subcontractors';
$report[] = '```';
$report[] = '';
$report[] = '## Files Generated';
$report[] = '';
$report[] = '- `storage/app/audits/pages.csv`';
$report[] = '- `storage/app/audits/sections.csv`';
$report[] = '- `storage/app/audits/page_text_extract.json`';
$report[] = '- `storage/app/audits/sample_api_payloads.json`';
$report[] = '- `storage/app/audits/SEO_AUDIT_REPORT.md`';

file_put_contents($reportPath, implode(PHP_EOL, $report) . PHP_EOL);

$largestDuplicateClusterSize = 1;
if (!empty($serviceTownDuplicateClusters)) {
    $largestDuplicateClusterSize = max(array_map('count', $serviceTownDuplicateClusters));
}

$prodChecks = [
    'published_pages_exactly_106' => $publishedCount === 106,
    'published_pages_without_active_sections' => $publishedPagesWithoutActiveSections->count() === 0,
    'broken_internal_links' => count($brokenLinks) === 0,
    'county_hub_missing_town_hub_links' => count($missingCountyToTownLinks) === 0,
    'town_hub_missing_service_town_links' => count($townHubMissingServiceLinks) === 0,
    'schema_url_canonical_mismatch' => count($schemaIssues['schema_url_canonical_mismatch']) === 0,
    'overlong_titles' => count($metaOverlong['titles']) === 0,
    'overlong_descriptions' => count($metaOverlong['descriptions']) === 0,
    'areas_served_link_violations' => count($areasServedLinkViolations) === 0,
    'largest_duplicate_cluster_size' => $largestDuplicateClusterSize <= 4,
];

$prodReady = !in_array(false, $prodChecks, true);

echo "DB AUDIT COMPLETE\n";
echo "published_query={$publishedQueryString}\n";
echo "published_pages={$publishedCount}\n";
echo "published_pages_without_active_sections=" . $publishedPagesWithoutActiveSections->count() . "\n";
echo "published_sample_ids=" . implode(',', $publishedPages->take(10)->pluck('id')->all()) . "\n";
echo "service_town_pages=" . $serviceTownPages->count() . "\n";
echo "broken_internal_links=" . count($brokenLinks) . "\n";
echo "county_hub_missing_town_hub_links=" . count($missingCountyToTownLinks) . "\n";
echo "town_hub_missing_service_town_links=" . count($townHubMissingServiceLinks) . "\n";
echo "schema_url_canonical_mismatch=" . count($schemaIssues['schema_url_canonical_mismatch']) . "\n";
echo "overlong_titles=" . count($metaOverlong['titles']) . "\n";
echo "overlong_descriptions=" . count($metaOverlong['descriptions']) . "\n";
echo "largest_duplicate_cluster_size={$largestDuplicateClusterSize}\n";
echo "areas_served_link_violations_before_legacy=" . count($areasServedLinkViolationsLegacy) . "\n";
echo "areas_served_link_violations=" . count($areasServedLinkViolations) . "\n";

if (!empty($areasServedLinkViolations)) {
    echo "areas_served_link_violations_list:\n";
    foreach ($areasServedLinkViolations as $row) {
        echo ' - page_id=' . $row['page_id']
            . ' full_path=' . $row['full_path']
            . ' section_id=' . $row['section_id']
            . ' section_type=' . $row['section_type']
            . ' field=' . $row['field']
            . ' target=' . $row['target']
            . "\n";
    }
}

echo "PROD READINESS SUMMARY: " . ($prodReady ? 'PASS' : 'FAIL') . "\n";
if (!$prodReady) {
    echo "PROD READINESS ISSUES:\n";
    foreach ($prodChecks as $check => $pass) {
        if (!$pass) {
            echo " - {$check}=FAIL\n";
        }
    }
}

echo "exports_written:\n";
echo " - {$pagesCsvPath}\n";
echo " - {$sectionsCsvPath}\n";
echo " - {$pageTextExtractPath}\n";
echo " - {$samplePayloadsPath}\n";
echo " - {$reportPath}\n";
