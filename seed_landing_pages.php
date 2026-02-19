<?php

use App\Models\Section;
use App\Models\Page;

// ============================================================
// /services  (ID:32, template: generic)  — Master Services Hub
// Valid types: hero, rich_text, services_grid, feature_list_two_column,
//             process_steps, trust_bar, testimonials, cta_block, etc.
// ============================================================
$services = Page::find(32);
if (!$services) { echo "ERROR: Services page not found\n"; return; }

Section::where('page_id', $services->id)->delete();
echo "Cleared existing /services sections.\n";

$servicesSections = [
    // 1. HERO
    [
        'page_id' => $services->id,
        'type' => 'hero',
        'sort_order' => 0,
        'is_active' => true,
        'data' => [
            'headline' => 'Home Remodeling Services in Connecticut',
            'subheadline' => 'High-end interior renovations and additions across Fairfield & New Haven Counties.',
            'background_image' => null,
            'background_video' => null,
            'cta_primary' => ['label' => 'Schedule a Consultation', 'url' => '/free-consultation/'],
            'cta_secondary' => ['label' => 'View Our Work', 'url' => '/portfolio/'],
            'overlay_opacity' => 0.45,
        ],
    ],

    // 2. SERVICES GRID
    [
        'page_id' => $services->id,
        'type' => 'services_grid',
        'sort_order' => 1,
        'is_active' => true,
        'data' => [
            'headline' => 'Our Core Services',
            'services' => [
                ['title' => 'Kitchen Remodeling', 'description' => 'Custom cabinetry, countertops, layout redesigns, and full kitchen renovations built to last.', 'url' => '/kitchen-remodeling', 'image' => null],
                ['title' => 'Bathroom Remodeling', 'description' => 'Walk-in showers, heated floors, custom tile, and spa-inspired designs for every budget.', 'url' => '/bathroom-remodeling', 'image' => null],
                ['title' => 'Basement Finishing', 'description' => 'Turn unused square footage into living space — offices, entertainment rooms, guest suites, and more.', 'url' => '/basement-finishing', 'image' => null],
                ['title' => 'Home Additions', 'description' => 'Room additions, second stories, in-law suites, and garage conversions with seamless structural integration.', 'url' => '/home-additions', 'image' => null],
                ['title' => 'Flooring', 'description' => 'Hardwood, luxury vinyl plank, tile, and engineered wood — installed with precision and care.', 'url' => '/flooring', 'image' => null],
                ['title' => 'Roofing', 'description' => 'Roof replacement, repair, and storm damage restoration for residential properties across Connecticut.', 'url' => '/roofing', 'image' => null],
            ],
        ],
    ],

    // 3. FEATURE LIST TWO COLUMN (Why BuiltWell Is Different)
    [
        'page_id' => $services->id,
        'type' => 'feature_list_two_column',
        'sort_order' => 2,
        'is_active' => true,
        'data' => [
            'headline' => 'Why BuiltWell Is Different',
            'left_column' => [
                ['title' => 'Licensed & Insured', 'description' => 'Fully licensed and insured with all required state and local certifications.'],
                ['title' => '15+ Years Experience', 'description' => 'Over a decade of residential remodeling experience across Connecticut.'],
                ['title' => 'Written Warranty', 'description' => 'Every project backed by a written workmanship warranty for your peace of mind.'],
            ],
            'right_column' => [
                ['title' => 'Fixed-Price Proposals', 'description' => 'No hidden fees, no change-order surprises — you know the price before we start.'],
                ['title' => 'Clear Communication', 'description' => 'One project manager, daily updates, and full transparency from start to finish.'],
            ],
        ],
    ],

    // 4. PROCESS STEPS (How We Work)
    [
        'page_id' => $services->id,
        'type' => 'process_steps',
        'sort_order' => 3,
        'is_active' => true,
        'data' => [
            'headline' => 'How We Work',
            'steps' => [
                ['step_number' => 1, 'title' => 'Listen', 'description' => 'We learn about your goals, your home, and how you want to use the space.'],
                ['step_number' => 2, 'title' => 'Estimate', 'description' => 'You receive a detailed, fixed-price proposal with scope, timeline, and materials.'],
                ['step_number' => 3, 'title' => 'Schedule', 'description' => 'We lock in your start date and coordinate all materials, permits, and logistics.'],
                ['step_number' => 4, 'title' => 'Build', 'description' => 'Our crew executes with daily communication, clean work areas, and expert craftsmanship.'],
                ['step_number' => 5, 'title' => 'Walkthrough', 'description' => 'We inspect every detail together and address anything before the project closes.'],
            ],
        ],
    ],

    // 5. CTA BLOCK
    [
        'page_id' => $services->id,
        'type' => 'cta_block',
        'sort_order' => 4,
        'is_active' => true,
        'data' => [
            'headline' => 'Ready to Start Your Project?',
            'subheadline' => 'Schedule a free, no-obligation consultation with our team.',
            'cta' => ['label' => 'Book a Free Consultation', 'url' => '/free-consultation/'],
        ],
    ],
];

foreach ($servicesSections as $s) {
    Section::create($s);
}
echo "Created " . count($servicesSections) . " /services sections.\n";

$services->update([
    'seo_title' => 'Home Remodeling Services in Connecticut | BUILTWELL',
    'seo_description' => 'Full-service home remodeling across Fairfield and New Haven County, CT. Kitchen, bathroom, basement, additions, flooring, and roofing — backed by a written warranty.',
]);
echo "Updated /services SEO.\n";


// ============================================================
// /fairfield-county  (ID:7, template: service_county)
// Valid types: hero, trust_bar, rich_text, local_context,
//             service_includes, testimonials, town_list, cta_block,
//             before_after, faq_list
// ============================================================
$fairfield = Page::find(7);
if (!$fairfield) { echo "ERROR: Fairfield County page not found\n"; return; }

Section::where('page_id', $fairfield->id)->delete();
echo "Cleared existing /fairfield-county sections.\n";

$fairfieldSections = [
    // 1. HERO
    [
        'page_id' => $fairfield->id,
        'type' => 'hero',
        'sort_order' => 0,
        'is_active' => true,
        'data' => [
            'headline' => 'Remodeling Services in Fairfield County, CT',
            'subheadline' => 'Trusted by homeowners in Greenwich, Stamford, Westport, Norwalk, and beyond.',
            'background_image' => null,
            'background_video' => null,
            'cta_primary' => ['label' => 'Schedule a Consultation', 'url' => '/free-consultation/'],
            'cta_secondary' => ['label' => 'Call (203) 555-0100', 'url' => 'tel:+12035550100'],
            'overlay_opacity' => 0.45,
        ],
    ],

    // 2. LOCAL CONTEXT (Local Expertise)
    [
        'page_id' => $fairfield->id,
        'type' => 'local_context',
        'sort_order' => 1,
        'is_active' => true,
        'data' => [
            'headline' => 'Local Expertise You Can Trust',
            'content' => "Fairfield County homeowners expect a higher standard — and that's exactly what BUILTWELL delivers.\n\nWe know the local building codes, historic district requirements, and permit processes across every town in the county. Whether you're renovating a pre-war Colonial in Greenwich or adding a second story in Trumbull, our team has the experience and local knowledge to get it done right.\n\nAs a Connecticut-based company, we understand the unique challenges of working with older homes, coastal properties, and tight suburban lots. We handle permitting, engineering, and construction — so you can focus on enjoying the result.",
        ],
    ],

    // 3. TOWN LIST
    [
        'page_id' => $fairfield->id,
        'type' => 'town_list',
        'sort_order' => 2,
        'is_active' => true,
        'data' => [
            'headline' => 'Areas We Serve in Fairfield County',
            'counties' => [
                [
                    'name' => 'Fairfield County',
                    'towns' => [
                        ['name' => 'Greenwich', 'url' => '/fairfield-county/greenwich'],
                        ['name' => 'Stamford', 'url' => '/fairfield-county/stamford'],
                        ['name' => 'Norwalk', 'url' => '/fairfield-county/norwalk'],
                        ['name' => 'Westport', 'url' => '/fairfield-county/westport'],
                        ['name' => 'Fairfield', 'url' => '/fairfield-county/fairfield'],
                        ['name' => 'Darien', 'url' => '/fairfield-county/darien'],
                        ['name' => 'Trumbull', 'url' => '/fairfield-county/trumbull'],
                        ['name' => 'Shelton', 'url' => '/fairfield-county/shelton'],
                    ],
                ],
            ],
        ],
    ],

    // 4. RICH TEXT (Popular Services)
    [
        'page_id' => $fairfield->id,
        'type' => 'rich_text',
        'sort_order' => 3,
        'is_active' => true,
        'data' => [
            'headline' => 'Popular Services in Fairfield County',
            'body' => "**Kitchen Remodeling** — Full kitchen renovations with custom cabinetry, countertops, and modern layouts. [Learn more →](/kitchen-remodeling)\n\n**Bathroom Remodeling** — Spa-inspired bathrooms with walk-in showers, heated floors, and designer tile. [Learn more →](/bathroom-remodeling)\n\n**Basement Finishing** — Transform your basement into a home office, entertainment room, or guest suite. [Learn more →](/basement-finishing)\n\n**Home Additions** — Room additions, second stories, and in-law suites built to match your existing home. [Learn more →](/home-additions)",
            'image' => null,
            'cta' => null,
        ],
    ],

    // 5. TESTIMONIALS (Fairfield County only)
    [
        'page_id' => $fairfield->id,
        'type' => 'testimonials',
        'sort_order' => 4,
        'is_active' => true,
        'data' => [
            'headline' => 'What Fairfield County Homeowners Say',
            'testimonials' => [
                ['name' => 'Lisa & James R.', 'city' => 'Greenwich, CT', 'rating' => 5, 'text' => 'BUILTWELL completely transformed our master bathroom. The attention to detail was incredible — heated floors, frameless glass shower, and flawless tile work.'],
                ['name' => 'Michael T.', 'city' => 'Westport, CT', 'rating' => 5, 'text' => 'They finished our basement in under 6 weeks. Entertainment room, wet bar, and a half bath — all on budget. The crew was professional and communicated daily.'],
                ['name' => 'Robert K.', 'city' => 'Fairfield, CT', 'rating' => 5, 'text' => 'We hired BUILTWELL for a kitchen remodel and the result is stunning. They managed every detail from design to final walkthrough. Highly recommend.'],
            ],
        ],
    ],

    // 6. CTA BLOCK
    [
        'page_id' => $fairfield->id,
        'type' => 'cta_block',
        'sort_order' => 5,
        'is_active' => true,
        'data' => [
            'headline' => 'Start Your Project in Fairfield County',
            'subheadline' => 'Schedule a free consultation. We\'ll visit your home, discuss your goals, and provide a detailed proposal.',
            'cta' => ['label' => 'Book a Free Consultation', 'url' => '/free-consultation/'],
        ],
    ],
];

foreach ($fairfieldSections as $s) {
    Section::create($s);
}
echo "Created " . count($fairfieldSections) . " /fairfield-county sections.\n";

$fairfield->update([
    'seo_title' => 'Home Remodeling in Fairfield County, CT | BUILTWELL',
    'seo_description' => 'Professional home remodeling services across Fairfield County, CT. Kitchen, bathroom, basement, additions, and more — serving Greenwich, Stamford, Westport, Norwalk, and beyond.',
]);
echo "Updated /fairfield-county SEO.\n";


// ============================================================
// /new-haven-county  (ID:8, template: service_county)
// Same allowed types as fairfield
// ============================================================
$newHaven = Page::find(8);
if (!$newHaven) { echo "ERROR: New Haven County page not found\n"; return; }

Section::where('page_id', $newHaven->id)->delete();
echo "Cleared existing /new-haven-county sections.\n";

$newHavenSections = [
    // 1. HERO
    [
        'page_id' => $newHaven->id,
        'type' => 'hero',
        'sort_order' => 0,
        'is_active' => true,
        'data' => [
            'headline' => 'Remodeling Services in New Haven County, CT',
            'subheadline' => 'Quality home renovations for homeowners in Orange, Milford, Branford, Hamden, and throughout the county.',
            'background_image' => null,
            'background_video' => null,
            'cta_primary' => ['label' => 'Schedule a Consultation', 'url' => '/free-consultation/'],
            'cta_secondary' => ['label' => 'Call (203) 555-0100', 'url' => 'tel:+12035550100'],
            'overlay_opacity' => 0.45,
        ],
    ],

    // 2. LOCAL CONTEXT
    [
        'page_id' => $newHaven->id,
        'type' => 'local_context',
        'sort_order' => 1,
        'is_active' => true,
        'data' => [
            'headline' => 'Your Neighborhood Remodeling Team',
            'content' => "New Haven County is home to some of Connecticut's most charming residential neighborhoods — from the tree-lined streets of Orange and Milford to the coastal character of Branford and Guilford.\n\nBUILTWELL understands the homes here. Ranch-style layouts that need more space. Split-levels that deserve a modern kitchen. Capes with untapped second-floor potential. We work with the architecture you have and build the home you want.\n\nWe're familiar with local building departments, permit timelines, and inspection processes across every town in New Haven County. That local knowledge means fewer delays and a smoother project from start to finish.",
        ],
    ],

    // 3. TOWN LIST
    [
        'page_id' => $newHaven->id,
        'type' => 'town_list',
        'sort_order' => 2,
        'is_active' => true,
        'data' => [
            'headline' => 'Areas We Serve in New Haven County',
            'counties' => [
                [
                    'name' => 'New Haven County',
                    'towns' => [
                        ['name' => 'Orange', 'url' => '/new-haven-county/orange'],
                        ['name' => 'New Haven', 'url' => '/new-haven-county/new-haven'],
                        ['name' => 'Branford', 'url' => '/new-haven-county/branford'],
                        ['name' => 'Milford', 'url' => '/new-haven-county/milford'],
                        ['name' => 'Hamden', 'url' => '/new-haven-county/hamden'],
                        ['name' => 'Guilford', 'url' => '/new-haven-county/guilford'],
                        ['name' => 'Wallingford', 'url' => '/new-haven-county/wallingford'],
                        ['name' => 'North Haven', 'url' => '/new-haven-county/north-haven'],
                    ],
                ],
            ],
        ],
    ],

    // 4. RICH TEXT (Popular Services)
    [
        'page_id' => $newHaven->id,
        'type' => 'rich_text',
        'sort_order' => 3,
        'is_active' => true,
        'data' => [
            'headline' => 'Popular Services in New Haven County',
            'body' => "**Kitchen Remodeling** — Modern kitchen designs with custom cabinetry, stone countertops, and smart layouts. [Learn more →](/kitchen-remodeling)\n\n**Bathroom Remodeling** — Complete bathroom renovations with custom tile, walk-in showers, and heated floors. [Learn more →](/bathroom-remodeling)\n\n**Basement Finishing** — Convert your unfinished basement into usable living space — offices, playrooms, and more. [Learn more →](/basement-finishing)\n\n**Home Additions** — Expand your home with room additions, in-law suites, and second-story builds. [Learn more →](/home-additions)",
            'image' => null,
            'cta' => null,
        ],
    ],

    // 5. TESTIMONIALS (New Haven County only)
    [
        'page_id' => $newHaven->id,
        'type' => 'testimonials',
        'sort_order' => 4,
        'is_active' => true,
        'data' => [
            'headline' => 'What New Haven County Homeowners Say',
            'testimonials' => [
                ['name' => 'Karen M.', 'city' => 'Orange, CT', 'rating' => 5, 'text' => 'We had our entire first floor refloored with LVP and the kitchen remodeled. BUILTWELL managed both projects seamlessly — on time and on budget.'],
                ['name' => 'David & Sarah L.', 'city' => 'Milford, CT', 'rating' => 5, 'text' => 'They added a beautiful in-law suite for my mother. Private entrance, full bathroom, kitchenette — everything she needs. The craftsmanship is outstanding.'],
                ['name' => 'Jennifer R.', 'city' => 'Branford, CT', 'rating' => 5, 'text' => 'Our basement went from a damp storage area to a finished family room with a half bath. BUILTWELL handled the moisture issues and built a space we use every day.'],
            ],
        ],
    ],

    // 6. CTA BLOCK
    [
        'page_id' => $newHaven->id,
        'type' => 'cta_block',
        'sort_order' => 5,
        'is_active' => true,
        'data' => [
            'headline' => 'Start Your Project in New Haven County',
            'subheadline' => 'Schedule a free consultation. We\'ll visit your home, discuss your vision, and provide a clear, fixed-price proposal.',
            'cta' => ['label' => 'Book a Free Consultation', 'url' => '/free-consultation/'],
        ],
    ],
];

foreach ($newHavenSections as $s) {
    Section::create($s);
}
echo "Created " . count($newHavenSections) . " /new-haven-county sections.\n";

$newHaven->update([
    'seo_title' => 'Home Remodeling in New Haven County, CT | BUILTWELL',
    'seo_description' => 'Professional home remodeling services across New Haven County, CT. Kitchen, bathroom, basement, additions, and more — serving Orange, Milford, Branford, Hamden, and beyond.',
]);
echo "Updated /new-haven-county SEO.\n";


// ============================================================
// FINAL SUMMARY
// ============================================================
echo "\n========== SUMMARY ==========\n";

foreach ([32 => '/services', 7 => '/fairfield-county', 8 => '/new-haven-county'] as $id => $path) {
    $page = Page::find($id);
    $secs = Section::where('page_id', $id)->orderBy('sort_order')->get();
    echo "{$path} (ID:{$id}) — Status: {$page->status} — Sections: {$secs->count()}\n";
    echo "  Types: " . $secs->pluck('type')->implode(' → ') . "\n";
}

echo "\nDone! All 3 landing pages rebuilt with valid section types.\n";
