<?php

$created = [];
$skipped = [];

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

function ctaBanner($headline, $btnLabel) {
    return [
        'type' => 'cta_banner',
        'data' => [
            'headline' => $headline,
            'subheadline' => 'Contact us today for a free consultation.',
            'cta' => ['label' => $btnLabel, 'url' => '/contact'],
        ],
    ];
}

$textImageSplit = [
    'type' => 'text_image_split',
    'data' => [
        'title' => 'Maximize Your Home\'s Potential',
        'content' => 'Add value and usable square footage with professional basement finishing.',
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

$pages = [
    '/reviews' => [
        'template_key' => 'generic',
        'seo_title' => 'Reviews | BuiltWell',
        'content' => [
            hero('Real Reviews from Connecticut Homeowners', 'See why clients trust BuiltWell for their remodeling projects.'),
            ['type' => 'testimonials_list', 'data' => ['testimonials' => []]],
            ctaBanner('Ready to Start Your Project?', 'Get a Free Quote'),
        ],
    ],
    '/portfolio' => [
        'template_key' => 'generic',
        'seo_title' => 'Portfolio | BuiltWell',
        'content' => [
            hero('Our Remodeling Portfolio', 'Explore real transformations across Connecticut.'),
            ['type' => 'projects_grid', 'data' => ['projects' => []]],
            ctaBanner("Let's Build Something Beautiful Together", 'Schedule Consultation'),
        ],
    ],
    '/basement-finishing' => [
        'template_key' => 'service_global',
        'seo_title' => 'Basement Finishing in Connecticut | BuiltWell',
        'content' => [
            ['type' => 'hero_simple', 'data' => [
                'headline' => 'Basement Finishing in Connecticut',
                'subheadline' => 'Transform your unfinished basement into functional living space.',
                'cta_primary' => ['label' => 'Get a Free Estimate', 'url' => '/contact'],
            ]],
            $textImageSplit,
            $processSteps,
            ctaBanner('Turn Your Basement Into Living Space', 'Schedule Consultation'),
        ],
    ],
];

foreach ($pages as $path => $def) {
    $existing = \App\Models\Page::where('full_path', $path)->first();
    if ($existing) {
        $skipped[] = $path;
        echo "SKIPPED: {$path} (already exists, ID:{$existing->id})\n";
        continue;
    }
    $page = \App\Models\Page::create([
        'full_path' => $path,
        'template_key' => $def['template_key'],
        'status' => 'published',
        'published_at' => now(),
        'seo_title' => $def['seo_title'],
        'content' => $def['content'],
    ]);
    $created[] = ['path' => $path, 'id' => $page->id, 'sections' => count($def['content'])];
    echo "CREATED: {$path} (ID:{$page->id}) — " . count($def['content']) . " sections\n";
}

echo "\n========== SUMMARY ==========\n";
echo "Pages created: " . count($created) . "\n";
foreach ($created as $c) {
    echo "  + {$c['path']} (ID:{$c['id']}) — {$c['sections']} sections\n";
}
echo "Pages skipped: " . count($skipped) . "\n";
foreach ($skipped as $s) { echo "  - {$s}\n"; }
echo "Total pages now: " . \App\Models\Page::count() . "\n";

// Verify
echo "\n=== VERIFICATION ===\n";
foreach (['/reviews', '/portfolio', '/basement-finishing'] as $path) {
    $p = \App\Models\Page::where('full_path', $path)->first();
    if (!$p) { echo "MISSING: {$path}\n"; continue; }
    $s = is_array($p->content) ? count($p->content) : 0;
    echo "OK: {$path} | ID:{$p->id} | status:{$p->status} | sections:{$s}\n";
}

// Duplicate check
echo "\n=== DUPLICATE CHECK ===\n";
$dupes = \App\Models\Page::select('full_path', \Illuminate\Support\Facades\DB::raw('COUNT(*) as c'))
    ->groupBy('full_path')->having('c', '>', 1)->get();
echo $dupes->isEmpty() ? "No duplicates found.\n" : "DUPLICATES: " . $dupes->toJson() . "\n";
