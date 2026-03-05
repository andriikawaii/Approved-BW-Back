<?php

use App\Models\Page;
use App\Models\Section;
use Illuminate\Support\Facades\Cache;

$specFiles = [
    'C:\\Users\\georg\\Downloads\\builtwell-ct-content-handoff\\content\\core-pages-gotov back, treba front (home nista)\\financing.md',
    'C:\\Users\\georg\\Downloads\\builtwell-ct-content-handoff\\content\\core-pages-gotov back, treba front (home nista)\\free-consultation.md',
    'C:\\Users\\georg\\Downloads\\builtwell-ct-content-handoff\\content\\core-pages-gotov back, treba front (home nista)\\homeowner-hub.md',
    'C:\\Users\\georg\\Downloads\\builtwell-ct-content-handoff\\content\\core-pages-gotov back, treba front (home nista)\\new-haven-county-hub.md',
    'C:\\Users\\georg\\Downloads\\builtwell-ct-content-handoff\\content\\core-pages-gotov back, treba front (home nista)\\orange-office.md',
    'C:\\Users\\georg\\Downloads\\builtwell-ct-content-handoff\\content\\core-pages-gotov back, treba front (home nista)\\portfolio.md',
    'C:\\Users\\georg\\Downloads\\builtwell-ct-content-handoff\\content\\core-pages-gotov back, treba front (home nista)\\process.md',
    'C:\\Users\\georg\\Downloads\\builtwell-ct-content-handoff\\content\\core-pages-gotov back, treba front (home nista)\\reviews.md',
    'C:\\Users\\georg\\Downloads\\builtwell-ct-content-handoff\\content\\core-pages-gotov back, treba front (home nista)\\services-hub.md',
    'C:\\Users\\georg\\Downloads\\builtwell-ct-content-handoff\\content\\core-pages-gotov back, treba front (home nista)\\warranty.md',
];

$templateRegistry = config('page-template-sections', []);

function normalizeNewlinesCore(string $text): string
{
    return str_replace(["\r\n", "\r"], "\n", $text);
}

function normalizeUrlPathCore(string $url): string
{
    $url = trim($url);
    $path = parse_url($url, PHP_URL_PATH);
    $path = $path ?: $url;
    $path = '/' . trim((string) $path, '/');
    return $path === '//' ? '/' : $path;
}

function isDecorativeLineCore(string $line): bool
{
    $trimmed = trim($line);
    if ($trimmed === '') {
        return false;
    }

    if (preg_match('/[A-Za-z0-9]/u', $trimmed)) {
        return false;
    }

    if (strpos($trimmed, '/') !== false) {
        return false;
    }

    return (bool) preg_match('/^[\p{P}\p{S}\p{Z}\x{2500}-\x{257F}]+$/u', $trimmed);
}

function extractValueAfterLabelCore(string $text, string $labelPattern): ?string
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

function findSectionSegmentCore(string $text, array $startPatterns, array $endPatterns): string
{
    $firstStart = null;
    $firstStartLen = 0;

    foreach ($startPatterns as $pattern) {
        if (preg_match($pattern, $text, $m, PREG_OFFSET_CAPTURE)) {
            $pos = $m[0][1];
            if ($firstStart === null || $pos < $firstStart) {
                $firstStart = $pos;
                $firstStartLen = strlen($m[0][0]);
            }
        }
    }

    if ($firstStart === null) {
        return '';
    }

    $segmentStart = $firstStart + $firstStartLen;
    $segmentEnd = strlen($text);

    foreach ($endPatterns as $pattern) {
        if (preg_match($pattern, $text, $m, PREG_OFFSET_CAPTURE, $segmentStart)) {
            $segmentEnd = min($segmentEnd, $m[0][1]);
        }
    }

    return trim(substr($text, $segmentStart, $segmentEnd - $segmentStart));
}

function parseContentBlocksCore(string $contentSegment): array
{
    $lines = explode("\n", $contentSegment);
    $filtered = [];
    $h1 = null;
    $pendingH1Line = false;

    foreach ($lines as $line) {
        $line = rtrim($line, "\n");
        $trimmed = trim($line);

        if (isDecorativeLineCore($line)) {
            continue;
        }

        if (preg_match('/^SECTION:\s*/u', $trimmed)) {
            continue;
        }

        if (preg_match('/^H1:\s*(.+)$/u', $trimmed, $m)) {
            $h1 = trim($m[1]);
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
            $pendingH1Line = false;
            continue;
        }

        $filtered[] = $line;
    }

    $blocks = [];
    $currentTitle = 'Intro';
    $currentLines = [];
    $seenH2 = false;

    foreach ($filtered as $line) {
        $trimmed = trim($line);

        if (preg_match('/^H2:\s*(.+)$/u', $trimmed, $m)) {
            $content = trim(implode("\n", $currentLines));
            if ($content !== '') {
                $blocks[] = [
                    'title' => $currentTitle,
                    'content' => $content,
                ];
            }

            $currentTitle = trim($m[1]);
            $currentLines = [];
            $seenH2 = true;
            continue;
        }

        if (!$seenH2 && strcasecmp($trimmed, 'Intro') === 0 && trim(implode("\n", $currentLines)) === '') {
            $currentTitle = 'Intro';
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

function inferFallbackUrlCore(string $label, string $fallback): string
{
    $normalized = mb_strtolower($label, 'UTF-8');

    if (str_contains($normalized, 'service')) {
        return '/services/';
    }
    if (str_contains($normalized, 'career') || str_contains($normalized, 'apply')) {
        return '/careers';
    }
    if (str_contains($normalized, 'consultation')) {
        return '/free-consultation/';
    }
    if (str_contains($normalized, 'contact')) {
        return '/contact/';
    }

    return $fallback;
}

function parseButtonSpecCore(string $rawValue, string $fallbackUrl = '/contact/'): array
{
    $value = trim($rawValue);

    if (preg_match('/^\[(.+?)\]\((\/[^)]+)\)$/u', $value, $m)) {
        return ['label' => trim($m[1]), 'url' => trim($m[2])];
    }

    if (preg_match('/^(.+?)\s*[—-]\s*(\/[^\s]+)$/u', $value, $m)) {
        return ['label' => trim($m[1]), 'url' => trim($m[2])];
    }

    if (preg_match('/(\/[A-Za-z0-9\-\/]+\/?)/u', $value, $m)) {
        $url = trim($m[1]);
        $label = trim(str_replace($m[0], '', $value), " \t\n\r\0\x0B-—");
        if ($label === '') {
            $label = $value;
        }
        return ['label' => $label, 'url' => $url];
    }

    return [
        'label' => $value,
        'url' => inferFallbackUrlCore($value, $fallbackUrl),
    ];
}

function parseCtaCore(string $ctaSegment): array
{
    $lines = explode("\n", $ctaSegment);
    $clean = [];

    foreach ($lines as $line) {
        if (isDecorativeLineCore($line)) {
            continue;
        }
        $clean[] = trim($line);
    }

    $heading = null;
    $body = null;
    $buttonLabel = null;
    $buttonUrl = null;
    $buttonExplicit = false;
    $secondaryLabel = null;
    $secondaryUrl = null;
    $subtext = null;
    $phones = [];
    $extra = [];

    foreach ($clean as $line) {
        if ($line === '') {
            continue;
        }

        if (strcasecmp($line, 'CTA') === 0 || strcasecmp($line, 'FINAL CTA') === 0 || $line === '[CTA Block]') {
            continue;
        }

        if (preg_match('/^(Heading|Headline):\s*(.+)$/ui', $line, $m)) {
            $heading = trim($m[2]);
            continue;
        }

        if (preg_match('/^Body:\s*(.+)$/ui', $line, $m)) {
            $body = trim($m[1]);
            continue;
        }

        if (preg_match('/^(Button|Primary CTA Button):\s*(.+)$/ui', $line, $m)) {
            $parsed = parseButtonSpecCore(trim($m[2]), '/contact/');
            $buttonLabel = $parsed['label'];
            $buttonUrl = $parsed['url'];
            $buttonExplicit = true;
            continue;
        }

        if (preg_match('/^Secondary CTA:\s*(.+)$/ui', $line, $m)) {
            $parsed = parseButtonSpecCore(trim($m[1]), '/services/');
            $secondaryLabel = $parsed['label'];
            $secondaryUrl = $parsed['url'];
            continue;
        }

        if (preg_match('/^Subtext:\s*(.+)$/ui', $line, $m)) {
            $subtext = trim($m[1]);
            continue;
        }

        if (preg_match('/^(Fairfield County|New Haven County):\s*(.+)$/ui', $line)) {
            $phones[] = $line;
            continue;
        }

        if (preg_match('/^NOTE:/ui', $line)) {
            continue;
        }

        $extra[] = $line;
    }

    if (!$heading && count($extra) > 0) {
        $fallbackLine = rtrim($extra[0]);
        if (str_contains($fallbackLine, '?')) {
            $parts = explode('?', $fallbackLine, 2);
            $heading = trim($parts[0]) . '?';
            $tail = trim((string) ($parts[1] ?? ''));
            if ($tail !== '') {
                $body = $body ? ($body . ' ' . $tail) : $tail;
            }
        } elseif (mb_strlen($fallbackLine, 'UTF-8') > 140 && str_contains($fallbackLine, '.')) {
            $parts = explode('.', $fallbackLine, 2);
            $heading = trim($parts[0]) . '.';
            $tail = trim((string) ($parts[1] ?? ''));
            if ($tail !== '') {
                $body = $body ? ($tail . ' ' . $body) : $tail;
            }
        } else {
            $heading = $fallbackLine;
        }
    }

    if (!$buttonLabel) {
        if ($buttonExplicit) {
            $fallbackLabel = 'Schedule a Free Consultation';
            $parsed = parseButtonSpecCore($fallbackLabel, '/free-consultation/');
            $buttonLabel = $parsed['label'];
            $buttonUrl = $parsed['url'];
        } else {
            $buttonLabel = 'Contact Us';
            $buttonUrl = '/contact/';
        }
    }

    return [
        'heading' => $heading,
        'body' => $body,
        'button_label' => $buttonLabel,
        'button_url' => $buttonUrl,
        'secondary_label' => $secondaryLabel,
        'secondary_url' => $secondaryUrl,
        'subtext' => $subtext,
        'phones' => $phones,
        'extra' => $extra,
    ];
}

function parseSpecCore(string $filePath): array
{
    if (!is_file($filePath)) {
        throw new RuntimeException("Spec file not found: {$filePath}");
    }

    $raw = file_get_contents($filePath);
    if ($raw === false) {
        throw new RuntimeException("Unable to read spec file: {$filePath}");
    }

    $text = normalizeNewlinesCore($raw);
    $text = preg_replace('/^\xEF\xBB\xBF/u', '', $text);

    $url = null;
    if (preg_match('/^URL:\s*(.+)$/mi', $text, $m)) {
        $url = normalizeUrlPathCore($m[1]);
    }
    if (!$url && preg_match('/^URL\s*:\s*(.+)$/mi', $text, $m)) {
        $url = normalizeUrlPathCore($m[1]);
    }
    if (!$url) {
        throw new RuntimeException("URL not found in spec: {$filePath}");
    }

    $seoTitle = extractValueAfterLabelCore($text, '/^Meta Title[^\n]*:\s*$/mi');
    if (!$seoTitle && preg_match('/^Title:\s*(.+)$/mi', $text, $m)) {
        $seoTitle = trim($m[1]);
    }
    if (!$seoTitle) {
        throw new RuntimeException("SEO title not found in spec: {$filePath}");
    }

    $seoDescription = extractValueAfterLabelCore($text, '/^Meta Description[^\n]*:\s*$/mi');
    if (!$seoDescription && preg_match('/^Description:\s*(.+)$/mi', $text, $m)) {
        $seoDescription = trim($m[1]);
    }
    if (!$seoDescription) {
        throw new RuntimeException("SEO description not found in spec: {$filePath}");
    }

    $contentSegment = findSectionSegmentCore(
        $text,
        ['/^PAGE CONTENT\s*$/mi', '/^\[CONTENT\]\s*$/mi'],
        ['/^CTA\s*$/mi', '/^FINAL CTA\s*$/mi', '/^\[CTA\]\s*$/mi', '/^INTERNAL LINKS\s*$/mi', '/^IMAGE RECOMMENDATIONS\s*$/mi', '/^FOOTER\s*$/mi']
    );

    if ($contentSegment === '') {
        throw new RuntimeException("PAGE CONTENT section not found in spec: {$filePath}");
    }

    $content = parseContentBlocksCore($contentSegment);
    if (!$content['h1']) {
        throw new RuntimeException("H1 not found in PAGE CONTENT: {$filePath}");
    }
    if (count($content['blocks']) === 0) {
        throw new RuntimeException("No content blocks parsed in PAGE CONTENT: {$filePath}");
    }

    $ctaSegment = findSectionSegmentCore(
        $text,
        ['/^CTA\s*$/mi', '/^FINAL CTA\s*$/mi', '/^\[CTA\]\s*$/mi'],
        ['/^INTERNAL LINKS\s*$/mi', '/^IMAGE RECOMMENDATIONS\s*$/mi', '/^FOOTER\s*$/mi']
    );

    $cta = $ctaSegment !== '' ? parseCtaCore($ctaSegment) : null;

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

function chooseSectionTypeCore(array $allowed, array $candidates): ?string
{
    foreach ($candidates as $candidate) {
        if (in_array($candidate, $allowed, true)) {
            return $candidate;
        }
    }

    return null;
}

function createRichTextCore(Page $page, int &$sort, ?string $title, string $content, string $styleVariant = 'default'): void
{
    Section::create([
        'page_id' => $page->id,
        'type' => 'rich_text',
        'sort_order' => $sort++,
        'is_active' => true,
        'data' => [
            'eyebrow' => null,
            'title' => $title,
            'content' => $content,
            'image' => null,
            'image_alt' => null,
            'image_position' => 'right',
            'cta' => ['label' => '', 'url' => ''],
            'align' => 'left',
            'variant' => 'default',
            'style_variant' => $styleVariant,
            'surface' => 'default',
            'container_width' => 'default',
            'spacing' => 'normal',
            'anchor_id' => null,
        ],
    ]);
}

$processed = 0;

foreach ($specFiles as $filePath) {
    echo "============================================================\n";
    echo "Processing spec: {$filePath}\n";

    $spec = parseSpecCore($filePath);
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

    $heroType = chooseSectionTypeCore($allowed, ['page_hero', 'hero', 'service_hero', 'hero_service_location']);
    if (!$heroType) {
        throw new RuntimeException("No supported hero section type allowed for template {$page->template_key}");
    }

    if (!in_array('rich_text', $allowed, true) && $spec['url'] !== '/faq') {
        throw new RuntimeException("Template {$page->template_key} does not allow rich_text; cannot map content safely.");
    }

    $deleted = Section::where('page_id', $page->id)->delete();
    echo "Deleted {$deleted} existing sections.\n";

    $sort = 0;

    if ($heroType === 'page_hero') {
        Section::create([
            'page_id' => $page->id,
            'type' => 'page_hero',
            'sort_order' => $sort++,
            'is_active' => true,
            'data' => [
                'eyebrow' => null,
                'title' => $spec['h1'],
                'subtitle' => null,
                'background_image' => null,
                'overlay_opacity' => 0.45,
                'alignment' => 'left',
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
    } elseif ($heroType === 'hero_service_location') {
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
    } else {
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
    }
    echo "Created: {$heroType}\n";

    if ($spec['url'] === '/faq' && in_array('faq_list', $allowed, true)) {
        $introBlock = null;
        $faqItems = [];

        foreach ($spec['blocks'] as $block) {
            if ($block['title'] === 'Intro' && $introBlock === null) {
                $introBlock = $block['content'];
                continue;
            }

            $faqItems[] = [
                'question' => $block['title'],
                'answer' => $block['content'],
            ];
        }

        if ($introBlock && in_array('rich_text', $allowed, true)) {
            createRichTextCore($page, $sort, null, $introBlock, 'faq');
            echo "Created: intro rich_text\n";
        }

        Section::create([
            'page_id' => $page->id,
            'type' => 'faq_list',
            'sort_order' => $sort++,
            'is_active' => true,
            'data' => [
                'title' => 'Frequently Asked Questions',
                'subtitle' => null,
                'items' => $faqItems,
            ],
        ]);
        echo "Created: faq_list (" . count($faqItems) . " items)\n";
    } else {
        $blocksByTitle = [];

        foreach ($spec['blocks'] as $block) {
            $title = $block['title'] === 'Intro' ? null : $block['title'];
            $blocksByTitle[$block['title']] = $block['content'];

            $styleVariant = 'default';
            if ($page->full_path === '/process' && preg_match('/^Step\s*\d+/i', $block['title'])) {
                $styleVariant = 'process';
            } elseif (in_array($page->full_path, ['/financing', '/warranty'], true)) {
                $styleVariant = 'legal';
            }

            createRichTextCore($page, $sort, $title, $block['content'], $styleVariant);
            echo "Created: rich_text (" . ($title ?? 'Intro') . ")\n";

            if (
                $spec['url'] === '/new-haven-county' &&
                in_array('town_list', $allowed, true) &&
                $block['title'] === 'Towns We Serve in New Haven County'
            ) {
                Section::create([
                    'page_id' => $page->id,
                    'type' => 'town_list',
                    'sort_order' => $sort++,
                    'is_active' => true,
                    'data' => [
                        'county' => 'new_haven',
                        'title' => 'Towns We Serve in New Haven County',
                        'tier1' => [
                            ['label' => 'Orange (Our Office)', 'url' => '/new-haven-county/orange-ct/'],
                            ['label' => 'New Haven', 'url' => '/new-haven-county/new-haven-ct/'],
                            ['label' => 'Madison', 'url' => '/new-haven-county/madison-ct/'],
                        ],
                        'tier2' => ['Hamden', 'Branford', 'Guilford', 'Woodbridge', 'Milford'],
                    ],
                ]);
                echo "Created: town_list\n";
            }
        }

        if ($page->full_path === '/free-consultation' && in_array('lead_form', $allowed, true)) {
            $leadSubtitle = $blocksByTitle['Book Your Consultation'] ?? "Select a time that works for you. We'll confirm shortly.";
            if (mb_strlen($leadSubtitle, 'UTF-8') > 255) {
                $leadSubtitle = mb_substr($leadSubtitle, 0, 252, 'UTF-8') . '...';
            }

            Section::create([
                'page_id' => $page->id,
                'type' => 'lead_form',
                'sort_order' => $sort++,
                'is_active' => true,
                'data' => [
                    'title' => 'Schedule Your Free Consultation',
                    'subtitle' => $leadSubtitle,
                    'steps' => [
                        ['number' => 1, 'text' => 'Tell us about your project'],
                        ['number' => 2, 'text' => 'Schedule a site visit'],
                        ['number' => 3, 'text' => 'Get your detailed proposal'],
                    ],
                    'fields' => [
                        ['name' => 'full_name', 'label' => 'Full Name', 'type' => 'text', 'required' => true],
                        ['name' => 'phone', 'label' => 'Phone Number', 'type' => 'tel', 'required' => true],
                        ['name' => 'contact_preference', 'label' => 'Contact Preference', 'type' => 'select', 'required' => false, 'options' => [
                            ['label' => 'Call', 'value' => 'call'],
                            ['label' => 'Text', 'value' => 'text'],
                            ['label' => 'Email', 'value' => 'email'],
                        ]],
                        ['name' => 'email', 'label' => 'Email Address', 'type' => 'email', 'required' => true],
                        ['name' => 'project_type', 'label' => 'Project Type', 'type' => 'select', 'required' => true, 'options' => [
                            ['label' => 'Kitchen Remodeling', 'value' => 'kitchen'],
                            ['label' => 'Bathroom Remodeling', 'value' => 'bathroom'],
                            ['label' => 'Basement Finishing', 'value' => 'basement'],
                            ['label' => 'Flooring', 'value' => 'flooring'],
                            ['label' => 'Other', 'value' => 'other'],
                        ]],
                        ['name' => 'zip', 'label' => 'Zip Code', 'type' => 'text', 'required' => true],
                        ['name' => 'street_address', 'label' => 'Street Address (Optional)', 'type' => 'text', 'required' => false],
                        ['name' => 'photos', 'label' => 'Upload Project Photos (Optional)', 'type' => 'file', 'required' => false],
                        ['name' => 'details', 'label' => 'Tell us a bit about your project (optional)...', 'type' => 'textarea', 'required' => false],
                    ],
                    'submit_label' => 'Book Free Consultation',
                    'consent_text' => 'By submitting, you agree to receive calls or texts from BUILTWELL.',
                ],
            ]);
            echo "Created: lead_form\n";
        }

        if ($page->full_path === '/new-haven-county/orange-ct' && in_array('map_embed', $allowed, true)) {
            Section::create([
                'page_id' => $page->id,
                'type' => 'map_embed',
                'sort_order' => $sort++,
                'is_active' => true,
                'data' => [
                    'title' => 'BuiltWell CT Office Location',
                    'embed_url' => 'https://www.google.com/maps?q=206A+Boston+Post+Road+Orange+CT+06477&output=embed',
                    'height' => 420,
                ],
            ]);
            echo "Created: map_embed\n";
        }
    }

    if ($spec['cta'] && in_array('cta_block', $allowed, true)) {
        $cta = $spec['cta'];

        $ctaTitle = $cta['heading'] ?: 'Ready to Start Your Project?';
        if (mb_strlen($ctaTitle, 'UTF-8') > 140) {
            if (str_contains($ctaTitle, '?')) {
                $parts = explode('?', $ctaTitle, 2);
                $shortTitle = trim($parts[0]) . '?';
                $tail = trim((string) ($parts[1] ?? ''));
                $ctaTitle = mb_strlen($shortTitle, 'UTF-8') <= 140 ? $shortTitle : mb_substr($ctaTitle, 0, 137, 'UTF-8') . '...';
                if ($tail !== '') {
                    $cta['body'] = $cta['body'] ? ($tail . ' ' . $cta['body']) : $tail;
                }
            } else {
                $ctaTitle = mb_substr($ctaTitle, 0, 137, 'UTF-8') . '...';
            }
        }

        $extraLines = [];
        if ($cta['body']) {
            $extraLines[] = $cta['body'];
        }
        if ($cta['secondary_label']) {
            $line = 'Secondary CTA: ' . $cta['secondary_label'];
            if ($cta['secondary_url']) {
                $line .= ' — ' . $cta['secondary_url'];
            }
            $extraLines[] = $line;
        }
        foreach ($cta['phones'] as $phoneLine) {
            $extraLines[] = $phoneLine;
        }

        $subtitle = null;
        if ($cta['body'] && mb_strlen($cta['body'], 'UTF-8') <= 255) {
            $subtitle = $cta['body'];
            $extraLines = array_values(array_filter(
                $extraLines,
                static fn ($line) => $line !== $cta['body']
            ));
        }

        $subtext = $cta['subtext'];
        if ($subtext && mb_strlen($subtext, 'UTF-8') > 80) {
            $extraLines[] = 'Subtext: ' . $subtext;
            $subtext = null;
        }

        if (count($extraLines) > 0 && in_array('rich_text', $allowed, true)) {
            createRichTextCore($page, $sort, null, implode("\n", $extraLines), 'links');
            echo "Created: CTA supporting rich_text\n";
        }

        Section::create([
            'page_id' => $page->id,
            'type' => 'cta_block',
            'sort_order' => $sort++,
            'is_active' => true,
            'data' => [
                'eyebrow' => null,
                'title' => $ctaTitle,
                'subtitle' => $subtitle,
                'button' => [
                    'label' => $cta['button_label'] ?: 'Contact Us',
                    'url' => $cta['button_url'] ?: '/contact/',
                ],
                'subtext' => $subtext,
                'variant' => 'default',
            ],
        ]);
        echo "Created: cta_block\n";
    }

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
