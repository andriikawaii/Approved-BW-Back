<?php

use App\Models\Page;
use App\Models\Section;
use Illuminate\Support\Facades\Cache;

$specFiles = [
    'C:\\Users\\georg\\Downloads\\builtwell-ct-content-handoff\\content\\service-town-pages\\flooring\\branford-ct.md',
    'C:\\Users\\georg\\Downloads\\builtwell-ct-content-handoff\\content\\service-town-pages\\flooring\\darien-ct.md',
    'C:\\Users\\georg\\Downloads\\builtwell-ct-content-handoff\\content\\service-town-pages\\flooring\\fairfield-ct.md',
    'C:\\Users\\georg\\Downloads\\builtwell-ct-content-handoff\\content\\service-town-pages\\flooring\\greenwich-ct.md',
    'C:\\Users\\georg\\Downloads\\builtwell-ct-content-handoff\\content\\service-town-pages\\flooring\\guilford-ct.md',
    'C:\\Users\\georg\\Downloads\\builtwell-ct-content-handoff\\content\\service-town-pages\\flooring\\hamden-ct.md',
    'C:\\Users\\georg\\Downloads\\builtwell-ct-content-handoff\\content\\service-town-pages\\flooring\\madison-ct.md',
    'C:\\Users\\georg\\Downloads\\builtwell-ct-content-handoff\\content\\service-town-pages\\flooring\\milford-ct.md',
    'C:\\Users\\georg\\Downloads\\builtwell-ct-content-handoff\\content\\service-town-pages\\flooring\\new-canaan-ct.md',
    'C:\\Users\\georg\\Downloads\\builtwell-ct-content-handoff\\content\\service-town-pages\\flooring\\new-haven-ct.md',
    'C:\\Users\\georg\\Downloads\\builtwell-ct-content-handoff\\content\\service-town-pages\\flooring\\norwalk-ct.md',
    'C:\\Users\\georg\\Downloads\\builtwell-ct-content-handoff\\content\\service-town-pages\\flooring\\orange-ct.md',
    'C:\\Users\\georg\\Downloads\\builtwell-ct-content-handoff\\content\\service-town-pages\\flooring\\ridgefield-ct.md',
    'C:\\Users\\georg\\Downloads\\builtwell-ct-content-handoff\\content\\service-town-pages\\flooring\\stamford-ct.md',
    'C:\\Users\\georg\\Downloads\\builtwell-ct-content-handoff\\content\\service-town-pages\\flooring\\westport-ct.md',
    'C:\\Users\\georg\\Downloads\\builtwell-ct-content-handoff\\content\\service-town-pages\\flooring\\woodbridge-ct.md',
];

$templateRegistry = config('page-template-sections', []);

function normalizeNewlinesBathroomTown(string $text): string
{
    return str_replace(["\r\n", "\r"], "\n", $text);
}

function normalizeUrlPathBathroomTown(string $url): string
{
    $url = trim($url);
    $path = parse_url($url, PHP_URL_PATH);
    $path = $path ?: $url;
    $path = '/' . trim((string) $path, '/');
    return $path === '//' ? '/' : $path;
}

function isDecorativeLineBathroomTown(string $line): bool
{
    $trimmed = trim($line);
    if ($trimmed === '') {
        return false;
    }

    return (bool) preg_match('/^[\-\=\_\~\*\.\s\x{2500}-\x{257F}]+$/u', $trimmed);
}

function extractValueAfterLabelBathroomTown(string $text, string $labelPattern): ?string
{
    if (!preg_match($labelPattern, $text, $m, PREG_OFFSET_CAPTURE)) {
        return null;
    }

    $start = $m[0][1] + strlen($m[0][0]);
    $tail = substr($text, $start);
    $lines = explode("\n", $tail);
    foreach ($lines as $line) {
        $value = trim($line);
        if ($value !== '') {
            return $value;
        }
    }

    return null;
}

function findSectionSegmentBathroomTown(string $text, array $startPatterns, array $endPatterns): string
{
    $startPos = null;
    $startLen = 0;

    foreach ($startPatterns as $pattern) {
        if (preg_match($pattern, $text, $m, PREG_OFFSET_CAPTURE)) {
            $startPos = $m[0][1];
            $startLen = strlen($m[0][0]);
            break;
        }
    }

    if ($startPos === null) {
        return '';
    }

    $segmentStart = $startPos + $startLen;
    $segmentEnd = strlen($text);

    foreach ($endPatterns as $pattern) {
        if (preg_match($pattern, $text, $m, PREG_OFFSET_CAPTURE, $segmentStart)) {
            $segmentEnd = min($segmentEnd, $m[0][1]);
        }
    }

    return trim(substr($text, $segmentStart, $segmentEnd - $segmentStart));
}

function parseContentBlocksBathroomTown(string $contentSegment): array
{
    $lines = explode("\n", $contentSegment);
    $h1 = null;
    $pendingH1Line = false;
    $foundH1 = false;
    $contentLines = [];

    foreach ($lines as $line) {
        $line = rtrim($line, "\n");
        $trimmed = trim($line);

        if (isDecorativeLineBathroomTown($line)) {
            continue;
        }

        if (!$foundH1) {
            if (preg_match('/^H1:\s*(.+)$/u', $trimmed, $m)) {
                $h1 = trim($m[1]);
                $foundH1 = true;
                continue;
            }

            if ($trimmed === 'H1:') {
                $pendingH1Line = true;
                continue;
            }

            if ($pendingH1Line) {
                if ($trimmed === '') {
                    continue;
                }
                $h1 = $trimmed;
                $foundH1 = true;
                $pendingH1Line = false;
                continue;
            }

            continue;
        }

        $contentLines[] = $line;
    }

    if (!$h1) {
        return ['h1' => null, 'blocks' => []];
    }

    $blocks = [];
    $currentTitle = 'Intro';
    $currentLines = [];

    foreach ($contentLines as $line) {
        $trimmed = trim($line);

        if (preg_match('/^##\s+(.+)$/u', $trimmed, $m) || preg_match('/^H2:\s*(.+)$/u', $trimmed, $m)) {
            $content = trim(implode("\n", $currentLines));
            if ($content !== '') {
                $blocks[] = [
                    'title' => $currentTitle,
                    'content' => $content,
                ];
            }

            $currentTitle = trim($m[1]);
            $currentLines = [];
            continue;
        }

        $currentLines[] = $line;
    }

    $lastContent = trim(implode("\n", $currentLines));
    if ($lastContent !== '') {
        $blocks[] = [
            'title' => $currentTitle,
            'content' => $lastContent,
        ];
    }

    return ['h1' => $h1, 'blocks' => $blocks];
}

function parseCtaBathroomTown(string $ctaSegment): array
{
    $lines = explode("\n", $ctaSegment);
    $clean = [];

    foreach ($lines as $line) {
        if (isDecorativeLineBathroomTown($line)) {
            continue;
        }
        $clean[] = trim($line);
    }

    $heading = null;
    $button = null;
    $subtext = null;
    $extra = [];

    foreach ($clean as $line) {
        if ($line === '') {
            continue;
        }

        if ($line === '[CTA]' || $line === '[CTA Block]' || strcasecmp($line, 'CTA') === 0 || strcasecmp($line, 'FINAL CTA') === 0) {
            continue;
        }

        if (preg_match('/^Heading:\s*(.+)$/u', $line, $m)) {
            $heading = trim($m[1]);
            continue;
        }

        if (preg_match('/^Button:\s*(.+)$/u', $line, $m)) {
            $button = trim($m[1]);
            continue;
        }

        if (preg_match('/^Subtext:\s*(.+)$/u', $line, $m)) {
            $subtext = trim($m[1]);
            continue;
        }

        $extra[] = $line;
    }

    if (!$heading && count($extra) > 0) {
        $heading = $extra[0];
    }
    if (!$button && count($extra) > 0) {
        $button = $extra[0];
    }
    if (!$subtext && count($extra) > 1) {
        $subtext = $extra[1];
    }

    return [
        'heading' => $heading,
        'button' => $button,
        'subtext' => $subtext,
        'extra' => $extra,
    ];
}

function parseSpecBathroomTown(string $filePath): array
{
    if (!is_file($filePath)) {
        throw new RuntimeException("Spec file not found: {$filePath}");
    }

    $raw = file_get_contents($filePath);
    if ($raw === false) {
        throw new RuntimeException("Unable to read spec file: {$filePath}");
    }

    $text = normalizeNewlinesBathroomTown($raw);
    $text = preg_replace('/^\xEF\xBB\xBF/u', '', $text);

    $url = null;
    if (preg_match('/^URL:\s*(.+)$/mi', $text, $m)) {
        $url = normalizeUrlPathBathroomTown($m[1]);
    }
    if (!$url && preg_match('/^URL\s*:\s*(.+)$/mi', $text, $m)) {
        $url = normalizeUrlPathBathroomTown($m[1]);
    }
    if (!$url) {
        throw new RuntimeException("URL not found in spec: {$filePath}");
    }

    $seoTitle = extractValueAfterLabelBathroomTown($text, '/^Meta Title[^\n]*:\s*$/mi');
    if (!$seoTitle && preg_match('/^Title:\s*(.+)$/mi', $text, $m)) {
        $seoTitle = trim($m[1]);
    }
    if (!$seoTitle) {
        throw new RuntimeException("Meta Title not found in spec: {$filePath}");
    }

    $seoDescription = extractValueAfterLabelBathroomTown($text, '/^Meta Description[^\n]*:\s*$/mi');
    if (!$seoDescription && preg_match('/^Description:\s*(.+)$/mi', $text, $m)) {
        $seoDescription = trim($m[1]);
    }
    if (!$seoDescription) {
        throw new RuntimeException("Meta Description not found in spec: {$filePath}");
    }

    $contentSegment = findSectionSegmentBathroomTown(
        $text,
        ['/^PAGE CONTENT\s*$/mi', '/^\[CONTENT\]\s*$/mi'],
        ['/^CTA\s*$/mi', '/^FINAL CTA\s*$/mi', '/^\[CTA\]\s*$/mi']
    );

    if ($contentSegment === '') {
        throw new RuntimeException("PAGE CONTENT section not found in spec: {$filePath}");
    }

    $content = parseContentBlocksBathroomTown($contentSegment);
    if (!$content['h1']) {
        throw new RuntimeException("H1 not found in PAGE CONTENT: {$filePath}");
    }
    if (count($content['blocks']) === 0) {
        throw new RuntimeException("No content blocks parsed in PAGE CONTENT: {$filePath}");
    }

    $ctaSegment = findSectionSegmentBathroomTown(
        $text,
        ['/^CTA\s*$/mi', '/^FINAL CTA\s*$/mi', '/^\[CTA\]\s*$/mi'],
        ['/^INTERNAL LINKS\s*$/mi', '/^\[INTERNAL LINKS\]\s*$/mi', '/^IMAGE RECOMMENDATIONS\s*$/mi', '/^\[IMAGE RECOMMENDATIONS\]\s*$/mi', '/^FOOTER\s*$/mi', '/^\[FOOTER TEMPLATE\]\s*$/mi']
    );

    if ($ctaSegment === '') {
        throw new RuntimeException("CTA section not found in spec: {$filePath}");
    }

    $cta = parseCtaBathroomTown($ctaSegment);
    if (!$cta['heading'] || !$cta['button']) {
        throw new RuntimeException("CTA heading/button missing in spec: {$filePath}");
    }

    return [
        'file' => $filePath,
        'url' => $url,
        'seo_title' => $seoTitle,
        'seo_description' => $seoDescription,
        'h1' => $content['h1'],
        'blocks' => $content['blocks'],
        'cta' => $cta,
    ];
}

function chooseSectionTypeBathroomTown(array $allowed, array $candidates): ?string
{
    foreach ($candidates as $candidate) {
        if (in_array($candidate, $allowed, true)) {
            return $candidate;
        }
    }
    return null;
}

$processed = 0;

foreach ($specFiles as $filePath) {
    echo "============================================================\n";
    echo "Processing spec: {$filePath}\n";

    $spec = parseSpecBathroomTown($filePath);
    $page = Page::where('full_path', $spec['url'])->first();

    if (!$page) {
        throw new RuntimeException("Page not found for URL: {$spec['url']}");
    }

    echo "Found page ID: {$page->id} ({$page->full_path})\n";
    echo "Template key: {$page->template_key}\n";

    $templateDef = $templateRegistry[$page->template_key] ?? null;
    if (!$templateDef) {
        throw new RuntimeException("Template not found in config/page-template-sections.php: {$page->template_key}");
    }

    $allowed = $templateDef['allowed'] ?? [];

    $heroType = chooseSectionTypeBathroomTown($allowed, ['hero_service_location', 'hero', 'service_hero', 'page_hero']);
    if (!$heroType) {
        throw new RuntimeException("No supported hero section type allowed for template {$page->template_key}");
    }

    if (!in_array('rich_text', $allowed, true)) {
        throw new RuntimeException("Template {$page->template_key} does not allow rich_text; cannot map content safely.");
    }

    $ctaType = chooseSectionTypeBathroomTown($allowed, ['cta_block', 'cta_dark_band']);
    if (!$ctaType) {
        throw new RuntimeException("No supported CTA section type allowed for template {$page->template_key}");
    }

    $deleted = Section::where('page_id', $page->id)->delete();
    echo "Deleted {$deleted} existing sections.\n";

    $sort = 0;

    if ($heroType === 'hero_service_location') {
        Section::create([
            'page_id' => $page->id,
            'type' => 'hero_service_location',
            'sort_order' => $sort++,
            'is_active' => true,
            'data' => [
                'headline' => $spec['h1'],
                'subheadline' => null,
                'background_image' => null,
                'primary_cta' => ['label' => 'Schedule a Free Consultation', 'url' => '/free-consultation/'],
                'secondary_cta' => ['label' => 'Call Now', 'url' => 'tel:'],
            ],
        ]);
    } elseif ($heroType === 'hero') {
        Section::create([
            'page_id' => $page->id,
            'type' => 'hero',
            'sort_order' => $sort++,
            'is_active' => true,
            'data' => [
                'eyebrow' => null,
                'headline' => $spec['h1'],
                'subheadline' => null,
                'background_image' => null,
                'background_video' => null,
                'overlay' => ['opacity' => 0.45],
                'cta_primary' => ['label' => 'Schedule a Free Consultation', 'url' => '/free-consultation/'],
                'cta_secondary' => ['label' => 'Call Now', 'url' => 'tel:'],
                'badges' => [],
            ],
        ]);
    } elseif ($heroType === 'service_hero') {
        Section::create([
            'page_id' => $page->id,
            'type' => 'service_hero',
            'sort_order' => $sort++,
            'is_active' => true,
            'data' => [
                'title' => $spec['h1'],
                'subtitle' => null,
                'background_image' => null,
                'primary_cta' => ['label' => 'Schedule a Free Consultation', 'url' => '/free-consultation/'],
                'secondary_cta' => ['label' => 'Call Now', 'url' => 'tel:'],
                'overlay_opacity' => 0.45,
            ],
        ]);
    } else {
        Section::create([
            'page_id' => $page->id,
            'type' => 'page_hero',
            'sort_order' => $sort++,
            'is_active' => true,
            'data' => [
                'title' => $spec['h1'],
                'subtitle' => null,
                'background_image' => null,
            ],
        ]);
    }
    echo "Created: {$heroType}\n";

    foreach ($spec['blocks'] as $block) {
        Section::create([
            'page_id' => $page->id,
            'type' => 'rich_text',
            'sort_order' => $sort++,
            'is_active' => true,
            'data' => [
                'eyebrow' => null,
                'title' => $block['title'],
                'content' => $block['content'],
                'image' => null,
                'image_alt' => null,
                'image_position' => 'right',
                'cta' => ['label' => '', 'url' => ''],
                'align' => 'left',
                'variant' => 'default',
            ],
        ]);
    }
    echo "Created: " . count($spec['blocks']) . " rich_text blocks\n";

    $ctaSubtitle = null;
    if (count($spec['cta']['extra']) > 0) {
        $ctaSubtitle = implode(' | ', $spec['cta']['extra']);
    }

    if ($ctaType === 'cta_block') {
        Section::create([
            'page_id' => $page->id,
            'type' => 'cta_block',
            'sort_order' => $sort++,
            'is_active' => true,
            'data' => [
                'eyebrow' => null,
                'title' => $spec['cta']['heading'],
                'subtitle' => $ctaSubtitle,
                'button' => [
                    'label' => $spec['cta']['button'],
                    'url' => '/free-consultation/',
                ],
                'subtext' => $spec['cta']['subtext'],
                'variant' => 'default',
            ],
        ]);
    } else {
        Section::create([
            'page_id' => $page->id,
            'type' => 'cta_dark_band',
            'sort_order' => $sort++,
            'is_active' => true,
            'data' => [
                'title' => $spec['cta']['heading'],
                'subtitle' => $ctaSubtitle,
                'button' => [
                    'label' => $spec['cta']['button'],
                    'url' => '/free-consultation/',
                ],
                'subtext' => $spec['cta']['subtext'],
            ],
        ]);
    }
    echo "Created: {$ctaType}\n";

    $updatedSeo = Page::where('id', $page->id)->update([
        'seo_title' => $spec['seo_title'],
        'seo_description' => $spec['seo_description'],
    ]);
    echo "Updated SEO rows: {$updatedSeo}\n";

    Cache::flush();
    echo "Cache flushed.\n";
    echo "Done! {$sort} sections created.\n";

    $processed++;
}

echo "============================================================\n";
echo "Batch complete. Processed {$processed} pages.\n";
