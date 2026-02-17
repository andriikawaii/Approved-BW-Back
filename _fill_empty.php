<?php

$updated = [];

// ============================================================
// HELPER
// ============================================================
function hero($headline, $sub) {
    return [
        'type' => 'hero_simple',
        'data' => [
            'headline' => $headline,
            'subheadline' => $sub,
            'cta_primary' => ['label' => 'Schedule a Consultation', 'url' => '/contact'],
        ],
    ];
}

$ctaBanner = [
    'type' => 'cta_banner',
    'data' => [
        'headline' => 'Ready to Get Started?',
        'subheadline' => 'Contact us today for a free consultation.',
        'cta' => ['label' => 'Contact Us', 'url' => '/contact'],
    ],
];

$textImageSplit = [
    'type' => 'text_image_split',
    'data' => [
        'title' => 'Built for Quality',
        'content' => 'We provide expert service with attention to detail and long-term durability.',
        'image' => null,
    ],
];

$processSteps = [
    'type' => 'process_steps',
    'data' => [
        'steps' => [
            ['title' => 'Consultation', 'description' => 'We discuss your vision and goals.'],
            ['title' => 'Planning', 'description' => 'Our team creates a detailed project plan.'],
            ['title' => 'Design', 'description' => 'We finalize design selections and materials.'],
            ['title' => 'Construction', 'description' => 'Expert craftsmen bring the plan to life.'],
            ['title' => 'Completion', 'description' => 'Final walkthrough and quality assurance.'],
        ],
    ],
];

// ============================================================
// 1) HOME PAGE  (ID:2  /  home template)
// ============================================================
$home = \App\Models\Page::find(2);
if ($home && empty($home->content)) {
    $sections = [
        hero('The Last Contractor You\'ll Hire', 'Quality remodeling services across Connecticut.'),
        [
            'type' => 'services_grid',
            'data' => [
                'services' => [
                    ['title' => 'Kitchen Remodeling', 'url' => '/kitchen-remodeling', 'description' => 'Transform your kitchen into a modern masterpiece.'],
                    ['title' => 'Bathroom Remodeling', 'url' => '/bathroom-remodeling', 'description' => 'Beautiful bathrooms designed for comfort.'],
                    ['title' => 'Basement Remodeling', 'url' => '/basement-remodeling', 'description' => 'Unlock the potential of your basement.'],
                    ['title' => 'Flooring', 'url' => '/flooring', 'description' => 'Professional flooring installation.'],
                    ['title' => 'Home Additions', 'url' => '/home-additions', 'description' => 'Expand your living space.'],
                    ['title' => 'Roofing', 'url' => '/roofing', 'description' => 'Reliable roofing solutions.'],
                ],
            ],
        ],
        $textImageSplit,
        [
            'type' => 'testimonials_preview',
            'data' => [
                'headline' => 'What Our Clients Say',
                'testimonials' => [],
            ],
        ],
        $ctaBanner,
    ];
    $home->update(['content' => $sections]);
    $updated[] = ['path' => '/', 'id' => 2, 'sections' => count($sections), 'types' => array_column($sections, 'type')];
    echo "UPDATED: / (home) — " . count($sections) . " sections\n";
} else {
    echo "SKIPPED: / (home) — already has content\n";
}

// ============================================================
// 2) ROOFING  (ID:17  /roofing  service_global)
// ============================================================
$roofing = \App\Models\Page::find(17);
if ($roofing && empty($roofing->content)) {
    $sections = [
        hero('Roofing Services in Connecticut', 'Expert craftsmanship and transparent pricing.'),
        $textImageSplit,
        $processSteps,
        $ctaBanner,
    ];
    $roofing->update(['content' => $sections]);
    $updated[] = ['path' => '/roofing', 'id' => 17, 'sections' => count($sections), 'types' => array_column($sections, 'type')];
    echo "UPDATED: /roofing (service_global) — " . count($sections) . " sections\n";
} else {
    echo "SKIPPED: /roofing — already has content\n";
}

// ============================================================
// 3) OFFICE PAGE  (ID:28  /new-haven-county/orange-ct  office)
// ============================================================
$office = \App\Models\Page::find(28);
if ($office && empty($office->content)) {
    $sections = [
        hero('BuiltWell in Orange, CT', 'Serving the Orange community with quality remodeling.'),
        [
            'type' => 'office_info',
            'data' => [
                'city' => 'Orange',
                'state' => 'CT',
                'county' => 'New Haven County',
                'phone' => '',
                'email' => '',
                'address' => '',
            ],
        ],
        [
            'type' => 'services_grid',
            'data' => [
                'services' => [
                    ['title' => 'Kitchen Remodeling', 'url' => '/kitchen-remodeling', 'description' => 'Expert kitchen renovations.'],
                    ['title' => 'Bathroom Remodeling', 'url' => '/bathroom-remodeling', 'description' => 'Beautiful bathroom upgrades.'],
                ],
            ],
        ],
        $ctaBanner,
    ];
    $office->update(['content' => $sections]);
    $updated[] = ['path' => '/new-haven-county/orange-ct', 'id' => 28, 'sections' => count($sections), 'types' => array_column($sections, 'type')];
    echo "UPDATED: /new-haven-county/orange-ct (office) — " . count($sections) . " sections\n";
} else {
    echo "SKIPPED: /new-haven-county/orange-ct — already has content\n";
}

// ============================================================
// SUMMARY
// ============================================================
echo "\n========== SUMMARY ==========\n";
echo "Pages updated: " . count($updated) . "\n";
$totalSections = 0;
foreach ($updated as $u) {
    $types = implode(', ', $u['types']);
    echo "  {$u['path']} (ID:{$u['id']}) — {$u['sections']} sections: [{$types}]\n";
    $totalSections += $u['sections'];
}
echo "Total sections created: {$totalSections}\n";
echo "service_town pages modified: 0\n";
echo "service_county pages modified: 0\n";

// Final verify — any remaining empty non-protected pages?
echo "\n=== REMAINING EMPTY PAGES (non service_town/service_county) ===\n";
$remaining = \App\Models\Page::where('status', 'published')
    ->whereNotIn('template_key', ['service_town', 'service_county'])
    ->orderBy('full_path')
    ->get(['id','full_path','template_key','content']);
$stillEmpty = 0;
foreach ($remaining as $p) {
    $s = is_array($p->content) ? count($p->content) : 0;
    if ($s === 0) {
        echo "  STILL EMPTY: ID:{$p->id} | {$p->full_path} | {$p->template_key}\n";
        $stillEmpty++;
    }
}
if ($stillEmpty === 0) {
    echo "  None! All non-protected pages have sections.\n";
}
