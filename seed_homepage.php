<?php

use App\Models\Page;
use App\Models\Section;
use Illuminate\Support\Facades\Cache;

$specPath = 'C:\\Users\\georg\\Downloads\\builtwell-ct-content-handoff\\content\\core-pages-gotov back, treba front (home nista)\\homepage.md';

function normalizeNewlinesHome(string $text): string
{
    return str_replace(["\r\n", "\r"], "\n", $text);
}

function normalizeUrlPathHome(string $url): string
{
    $url = trim($url);
    $path = parse_url($url, PHP_URL_PATH);
    $path = $path ?: $url;
    $path = '/' . trim((string) $path, '/');

    return $path === '//' ? '/' : $path;
}

function isDecorativeLineHome(string $line): bool
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

function cleanSegmentHome(string $segment, array $dropExactLines = []): string
{
    $dropMap = [];
    foreach ($dropExactLines as $line) {
        $dropMap[trim($line)] = true;
    }

    $lines = explode("\n", $segment);
    $clean = [];

    foreach ($lines as $line) {
        if (isDecorativeLineHome($line)) {
            continue;
        }

        $trimmed = trim($line);
        if ($trimmed !== '' && isset($dropMap[$trimmed])) {
            continue;
        }

        $clean[] = rtrim($line);
    }

    $joined = implode("\n", $clean);
    $joined = preg_replace("/\n{3,}/", "\n\n", $joined);

    return trim((string) $joined);
}

function extractValueAfterLabelHome(string $text, string $labelPattern): ?string
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

function extractBetweenMarkersHome(string $text, string $start, string $end): string
{
    $startPos = mb_stripos($text, $start, 0, 'UTF-8');
    if ($startPos === false) {
        return '';
    }

    $segmentStart = $startPos + mb_strlen($start, 'UTF-8');
    $endPos = mb_stripos($text, $end, $segmentStart, 'UTF-8');
    if ($endPos === false) {
        $endPos = mb_strlen($text, 'UTF-8');
    }

    return trim(mb_substr($text, $segmentStart, $endPos - $segmentStart, 'UTF-8'));
}

function extractH1Home(string $contentSegment): ?string
{
    if (preg_match('/^H1:\s*(.+)$/mi', $contentSegment, $m)) {
        return trim($m[1]);
    }

    if (preg_match('/^H1:\s*$/mi', $contentSegment, $m, PREG_OFFSET_CAPTURE)) {
        $start = $m[0][1] + strlen($m[0][0]);
        $tail = substr($contentSegment, $start);
        foreach (explode("\n", $tail) as $line) {
            $value = trim($line);
            if ($value !== '') {
                return $value;
            }
        }
    }

    return null;
}

function parseH3BlocksHome(string $segment): array
{
    $blocks = [];

    if (preg_match_all('/^H3:\s*([^\n]+)\n(.*?)(?=^H3:\s*|\z)/ms', $segment, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $match) {
            $blocks[] = [
                'title' => trim($match[1]),
                'body' => trim((string) $match[2]),
            ];
        }
    }

    return $blocks;
}

function firstSentenceHome(string $text): string
{
    $text = trim(preg_replace('/\s+/', ' ', $text));
    if ($text === '') {
        return '';
    }

    if (preg_match('/^(.+?[\.!?])\s/u', $text, $m)) {
        return trim($m[1]);
    }

    return $text;
}

function inferServiceUrlHome(string $title): string
{
    $map = [
        'Kitchen Remodeling' => '/kitchen-remodeling/',
        'Bathroom Remodeling' => '/bathroom-remodeling/',
        'Basement Finishing' => '/basement-finishing/',
        'Home Additions' => '/home-additions/',
        'Flooring Installation' => '/flooring/',
        'Interior Painting' => '/interior-painting/',
        'Interior Carpentry' => '/interior-carpentry/',
        'Attic Conversions' => '/attic-conversions/',
        'Decks and Porches' => '/decks-porches/',
        'Design and Planning' => '/remodeling-design-planning/',
        'Comfort and Accessibility Remodeling' => '/comfort-accessibility-remodeling/',
    ];

    return $map[$title] ?? '/services/';
}

function parseTownsHome(string $paragraph): array
{
    if (!preg_match('/We serve\s+(.+?),\s+along with/u', $paragraph, $m)) {
        return [];
    }

    $raw = trim($m[1]);
    $raw = str_replace([', and ', ' and '], ', ', $raw);

    $towns = array_values(array_filter(array_map(
        static fn ($value) => trim($value),
        explode(',', $raw)
    ), static fn ($value) => $value !== ''));

    return $towns;
}

function sectionWithoutH2Home(string $segment): array
{
    $lines = explode("\n", trim($segment));
    $title = null;
    $contentLines = [];

    foreach ($lines as $line) {
        $trimmed = trim($line);
        if ($title === null && preg_match('/^H2:\s*(.+)$/u', $trimmed, $m)) {
            $title = trim($m[1]);
            continue;
        }

        $contentLines[] = rtrim($line);
    }

    $content = trim(implode("\n", $contentLines));
    return ['title' => $title, 'content' => $content];
}

function createRichTextHome(Page $page, int &$sort, ?string $title, string $content, string $styleVariant = 'default'): void
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

if (!is_file($specPath)) {
    echo "Spec file not found: {$specPath}\n";
    return;
}

$raw = file_get_contents($specPath);
if ($raw === false) {
    echo "Unable to read spec file.\n";
    return;
}

$text = normalizeNewlinesHome($raw);
$text = preg_replace('/^\xEF\xBB\xBF/u', '', $text);

if (!preg_match('/^URL:\s*(.+)$/mi', $text, $urlMatch)) {
    echo "URL not found in spec.\n";
    return;
}
$url = normalizeUrlPathHome($urlMatch[1]);

$seoTitle = extractValueAfterLabelHome($text, '/^Meta Title[^\n]*:\s*$/mi');
$seoDescription = extractValueAfterLabelHome($text, '/^Meta Description[^\n]*:\s*$/mi');

if (!$seoTitle || !$seoDescription) {
    echo "SEO values not found in spec.\n";
    return;
}

$pageContentSegment = extractBetweenMarkersHome($text, 'PAGE CONTENT', 'CTA');
if ($pageContentSegment === '') {
    echo "PAGE CONTENT segment not found.\n";
    return;
}

$h1 = extractH1Home($pageContentSegment);
if (!$h1) {
    echo "H1 not found in PAGE CONTENT.\n";
    return;
}

$heroIntroSegment = cleanSegmentHome(
    extractBetweenMarkersHome($text, 'HERO INTRO', 'SECTION: What We Do'),
    ['HERO INTRO']
);

$whatWeDoRaw = cleanSegmentHome(
    extractBetweenMarkersHome($text, 'SECTION: What We Do', 'SECTION: Why Homeowners Choose BuiltWell CT'),
    ['SECTION: What We Do']
);
$whyRaw = cleanSegmentHome(
    extractBetweenMarkersHome($text, 'SECTION: Why Homeowners Choose BuiltWell CT', 'SECTION: How It Works'),
    ['SECTION: Why Homeowners Choose BuiltWell CT']
);
$howRaw = cleanSegmentHome(
    extractBetweenMarkersHome($text, 'SECTION: How It Works', 'SECTION: Where We Work'),
    ['SECTION: How It Works']
);
$whereRaw = cleanSegmentHome(
    extractBetweenMarkersHome($text, 'SECTION: Where We Work', 'SECTION: Recent Projects'),
    ['SECTION: Where We Work']
);
$recentRaw = cleanSegmentHome(
    extractBetweenMarkersHome($text, 'SECTION: Recent Projects', 'SECTION: Licensed and Insured in Connecticut'),
    ['SECTION: Recent Projects']
);
$licensedRaw = cleanSegmentHome(
    extractBetweenMarkersHome($text, 'SECTION: Licensed and Insured in Connecticut', 'CTA'),
    ['SECTION: Licensed and Insured in Connecticut']
);

$ctaRaw = cleanSegmentHome(
    extractBetweenMarkersHome($text, 'CTA', 'INTERNAL LINKS'),
    ['CTA']
);

$whatParsed = sectionWithoutH2Home($whatWeDoRaw);
$whyParsed = sectionWithoutH2Home($whyRaw);
$howParsed = sectionWithoutH2Home($howRaw);
$whereParsed = sectionWithoutH2Home($whereRaw);
$recentParsed = sectionWithoutH2Home($recentRaw);
$licensedParsed = sectionWithoutH2Home($licensedRaw);

$whatIntro = '';
if (preg_match('/^(.+?)(?=\n--- PRIMARY SERVICES ---)/s', $whatParsed['content'], $m)) {
    $whatIntro = trim($m[1]);
}

$primaryServicesSegment = extractBetweenMarkersHome($whatParsed['content'], '--- PRIMARY SERVICES ---', '--- SECONDARY SERVICES ---');
$secondaryServicesSegment = extractBetweenMarkersHome($whatParsed['content'], '--- SECONDARY SERVICES ---', 'View all our services.');

$primaryServices = parseH3BlocksHome($primaryServicesSegment);
$secondaryServices = parseH3BlocksHome($secondaryServicesSegment);

$makeServiceItems = static function (array $blocks): array {
    $items = [];

    foreach ($blocks as $block) {
        $cleanBody = trim(preg_replace('/\s+/', ' ', str_replace("\n", ' ', $block['body'])));
        $summaryCandidate = preg_replace('/\s*Learn more[^.]*\.?$/ui', '', $cleanBody) ?? $cleanBody;
        $summary = firstSentenceHome(trim($summaryCandidate));
        if (mb_strlen($summary, 'UTF-8') > 160) {
            $summary = '';
        }

        $items[] = [
            'title' => $block['title'],
            'summary' => $summary !== '' ? $summary : null,
            'image' => null,
            'url' => inferServiceUrlHome($block['title']),
            'cta_label' => 'Learn More',
        ];
    }

    return $items;
};

$primaryServiceItems = $makeServiceItems($primaryServices);
$secondaryServiceItems = $makeServiceItems($secondaryServices);

$howStepBlocks = parseH3BlocksHome($howParsed['content']);
$howIntro = '';
if (preg_match('/^(.+?)(?=\nH3: Step 1: Consultation)/s', $howParsed['content'], $m)) {
    $howIntro = trim($m[1]);
}

$processSteps = [];
foreach ($howStepBlocks as $stepBlock) {
    $titleRaw = $stepBlock['title'];
    $title = trim($titleRaw);

    $description = trim(preg_replace('/\s+/', ' ', $stepBlock['body']));
    $short = firstSentenceHome($description);
    if (mb_strlen($short, 'UTF-8') > 120) {
        $short = $title;
    }

    $processSteps[] = [
        'title' => $title,
        'short' => $short,
        'description' => $description,
    ];
}

if (count($processSteps) !== 5) {
    echo "Expected 5 process steps, parsed " . count($processSteps) . ".\n";
    return;
}

$whereBlocks = parseH3BlocksHome($whereParsed['content']);
$whereIntro = '';
if (preg_match('/^(.+?)(?=\nH3: Fairfield County)/s', $whereParsed['content'], $m)) {
    $whereIntro = trim($m[1]);
}

$fairfieldParagraph = '';
$newHavenParagraph = '';
foreach ($whereBlocks as $block) {
    if ($block['title'] === 'Fairfield County') {
        $fairfieldParagraph = trim(preg_replace('/\s+/', ' ', str_replace("\n", ' ', $block['body'])));
    }
    if ($block['title'] === 'New Haven County') {
        $newHavenParagraph = trim(preg_replace('/\s+/', ' ', str_replace("\n", ' ', $block['body'])));
    }
}

$fairfieldTowns = parseTownsHome($fairfieldParagraph);
$newHavenTowns = parseTownsHome($newHavenParagraph);

$recentBlocks = parseH3BlocksHome($recentParsed['content']);
$recentIntro = '';
if (preg_match('/^(.+?)(?=\nH3: Kitchen Remodel in New Canaan, CT)/s', $recentParsed['content'], $m)) {
    $recentIntro = trim($m[1]);
}

$projectUrlMap = [
    'Kitchen Remodel in New Canaan, CT' => '/case-studies/kitchen-remodeling-new-canaan/',
    'Basement Finishing in Darien, CT' => '/case-studies/basement-finishing-darien/',
    'Bathroom Remodel in Westport, CT' => '/case-studies/bathroom-remodeling-westport/',
];

$projectImageMap = [
    'Kitchen Remodel in New Canaan, CT' => 'builtwell-ct-before-after-kitchen-new-canaan.webp',
    'Basement Finishing in Darien, CT' => 'builtwell-ct-finished-basement-darien.webp',
    'Bathroom Remodel in Westport, CT' => 'builtwell-ct-bathroom-remodel-westport.webp',
];

$projectItems = [];
foreach ($recentBlocks as $block) {
    $body = trim($block['body']);
    $summarySource = trim(preg_replace('/\s*"[^"]+"\s*-\s*[^\n]+/u', '', $body));
    $summarySource = preg_replace('/\s*Read the full[^.]*\.?/ui', '', (string) $summarySource);
    $summary = firstSentenceHome(trim(preg_replace('/\s+/', ' ', (string) $summarySource)));

    if (mb_strlen($summary, 'UTF-8') > 255) {
        $summary = mb_substr($summary, 0, 252, 'UTF-8') . '...';
    }

    $tag = null;
    if (preg_match('/"[^"]+"\s*-\s*(.+)$/mu', $body, $q)) {
        $tag = trim($q[1]);
    }

    $projectItems[] = [
        'title' => $block['title'],
        'description' => $summary,
        'image' => $projectImageMap[$block['title']] ?? null,
        'url' => $projectUrlMap[$block['title']] ?? '/case-studies/',
        'tag' => $tag,
    ];
}

if (count($projectItems) !== 3) {
    echo "Expected 3 project highlights, parsed " . count($projectItems) . ".\n";
    return;
}

$ctaHeading = null;
$ctaButtonLabel = null;
$ctaSubtext = null;
$ctaPhones = [];

foreach (explode("\n", $ctaRaw) as $line) {
    $trimmed = trim($line);
    if ($trimmed === '') {
        continue;
    }

    if (preg_match('/^Heading:\s*(.+)$/ui', $trimmed, $m)) {
        $ctaHeading = trim($m[1]);
        continue;
    }
    if (preg_match('/^Button:\s*(.+)$/ui', $trimmed, $m)) {
        $ctaButtonLabel = trim($m[1]);
        continue;
    }
    if (preg_match('/^Subtext:\s*(.+)$/ui', $trimmed, $m)) {
        $ctaSubtext = trim($m[1]);
        continue;
    }
    if (preg_match('/^(Fairfield County|New Haven County):\s*(.+)$/ui', $trimmed)) {
        $ctaPhones[] = $trimmed;
        continue;
    }
}

if (!$ctaHeading || !$ctaButtonLabel) {
    echo "CTA heading/button not parsed.\n";
    return;
}

$page = Page::where('full_path', $url)->first();
if (!$page) {
    echo "Page not found for URL: {$url}\n";
    return;
}

echo "Found page ID: {$page->id} ({$page->full_path})\n";
echo "Template key: {$page->template_key}\n";

$templateDef = config('page-template-sections.' . $page->template_key);
if (!$templateDef) {
    echo "Template config not found: {$page->template_key}\n";
    return;
}

$allowed = $templateDef['allowed'] ?? [];
$requiredTypes = ['hero_slider', 'trust_bar', 'rich_text', 'services_grid', 'process_steps', 'areas_served', 'project_highlights', 'cta_block'];

foreach ($requiredTypes as $requiredType) {
    if (!in_array($requiredType, $allowed, true)) {
        echo "Template {$page->template_key} does not allow required type: {$requiredType}\n";
        return;
    }
}

$deleted = Section::where('page_id', $page->id)->delete();
echo "Deleted {$deleted} existing sections.\n";

$sort = 0;

Section::create([
    'page_id' => $page->id,
    'type' => 'hero_slider',
    'sort_order' => $sort++,
    'is_active' => true,
    'data' => [
        'headline' => $h1,
        'subheadline' => firstSentenceHome($heroIntroSegment),
        'slides' => [
            [
                'image' => 'builtwell-ct-hero-connecticut-home-remodel.webp',
                'alt' => 'Finished kitchen remodel in a Connecticut home with white shaker cabinets and quartz countertops',
                'caption' => null,
            ],
            [
                'image' => 'builtwell-ct-crew-on-site-connecticut.webp',
                'alt' => 'BuiltWell CT remodeling crew working on a residential project in Fairfield County Connecticut',
                'caption' => null,
            ],
            [
                'image' => 'builtwell-ct-before-after-kitchen-new-canaan.webp',
                'alt' => 'Before and after photos of a kitchen remodel in New Canaan CT with island and hardwood flooring',
                'caption' => null,
            ],
        ],
        'cta_primary' => [
            'label' => 'Schedule a Free Consultation',
            'url' => '/free-consultation/',
        ],
        'cta_secondary' => [
            'label' => 'Call Now',
            'url' => 'tel:+12039199616',
        ],
        'badges' => [
            ['label' => 'CT HIC License', 'value' => '#0668405'],
            ['label' => 'Fairfield County', 'value' => '(203) 919-9616'],
            ['label' => 'New Haven County', 'value' => '(203) 466-9148'],
        ],
    ],
]);
echo "Created: hero_slider\n";

Section::create([
    'page_id' => $page->id,
    'type' => 'trust_bar',
    'sort_order' => $sort++,
    'is_active' => true,
    'data' => [
        'items' => [
            ['icon' => 'shield', 'label' => 'CT HIC License #0668405', 'value' => null],
            ['icon' => 'phone', 'label' => 'Fairfield County: (203) 919-9616', 'value' => null],
            ['icon' => 'phone', 'label' => 'New Haven County: (203) 466-9148', 'value' => null],
            ['icon' => 'map', 'label' => 'Fairfield County & New Haven County, Connecticut', 'value' => null],
        ],
    ],
]);
echo "Created: trust_bar\n";

createRichTextHome($page, $sort, 'Hero Intro', $heroIntroSegment, 'default');
echo "Created: rich_text (Hero Intro)\n";

Section::create([
    'page_id' => $page->id,
    'type' => 'services_grid',
    'sort_order' => $sort++,
    'is_active' => true,
    'data' => [
        'title' => 'Primary Services',
        'subtitle' => $whatIntro,
        'items' => $primaryServiceItems,
    ],
]);
echo "Created: services_grid (Primary Services)\n";

Section::create([
    'page_id' => $page->id,
    'type' => 'services_grid',
    'sort_order' => $sort++,
    'is_active' => true,
    'data' => [
        'title' => 'Secondary Services',
        'subtitle' => null,
        'items' => $secondaryServiceItems,
    ],
]);
echo "Created: services_grid (Secondary Services)\n";

createRichTextHome($page, $sort, $whatParsed['title'], $whatParsed['content'], 'cards');
echo "Created: rich_text (What We Do details)\n";

createRichTextHome($page, $sort, $whyParsed['title'], $whyParsed['content'], 'default');
echo "Created: rich_text (Why Homeowners Choose BuiltWell CT)\n";

Section::create([
    'page_id' => $page->id,
    'type' => 'process_steps',
    'sort_order' => $sort++,
    'is_active' => true,
    'data' => [
        'title' => $howParsed['title'],
        'subtitle' => $howIntro,
        'steps' => $processSteps,
    ],
]);
echo "Created: process_steps\n";

createRichTextHome($page, $sort, $whereParsed['title'], $whereParsed['content'], 'links');
echo "Created: rich_text (Where We Work details)\n";

Section::create([
    'page_id' => $page->id,
    'type' => 'areas_served',
    'sort_order' => $sort++,
    'is_active' => true,
    'data' => [
        'title' => 'Areas We Serve',
        'subtitle' => $whereIntro,
        'counties' => [
            [
                'name' => 'Fairfield County',
                'towns' => $fairfieldTowns,
                'image' => null,
                'url' => '/fairfield-county/',
            ],
            [
                'name' => 'New Haven County',
                'towns' => $newHavenTowns,
                'image' => null,
                'url' => '/new-haven-county/',
            ],
        ],
        'show_map' => true,
    ],
]);
echo "Created: areas_served\n";

createRichTextHome($page, $sort, $recentParsed['title'], $recentParsed['content'], 'links');
echo "Created: rich_text (Recent Projects details)\n";

Section::create([
    'page_id' => $page->id,
    'type' => 'project_highlights',
    'sort_order' => $sort++,
    'is_active' => true,
    'data' => [
        'eyebrow' => 'Recent Projects',
        'title' => 'Recent Projects',
        'items' => $projectItems,
    ],
]);
echo "Created: project_highlights\n";

createRichTextHome($page, $sort, $licensedParsed['title'], $licensedParsed['content'], 'default');
echo "Created: rich_text (Licensed and Insured in Connecticut)\n";

$ctaSubtitle = null;
if ($ctaSubtext && mb_strlen($ctaSubtext, 'UTF-8') <= 255) {
    $ctaSubtitle = null;
}

Section::create([
    'page_id' => $page->id,
    'type' => 'cta_block',
    'sort_order' => $sort++,
    'is_active' => true,
    'data' => [
        'eyebrow' => null,
        'title' => $ctaHeading,
        'subtitle' => $ctaSubtitle,
        'button' => [
            'label' => $ctaButtonLabel,
            'url' => '/free-consultation/',
        ],
        'subtext' => $ctaSubtext,
        'variant' => 'default',
    ],
]);
echo "Created: cta_block\n";

if (!empty($ctaPhones)) {
    createRichTextHome($page, $sort, null, implode("\n", $ctaPhones), 'links');
    echo "Created: CTA phones rich_text\n";
}

$page->update([
    'seo_title' => $seoTitle,
    'seo_description' => $seoDescription,
]);
echo "Updated SEO.\n";

Cache::flush();
echo "Cache flushed.\n";

echo "Done! {$sort} sections created.\n";
