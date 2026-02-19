<?php

use App\Models\Section;
use App\Models\Page;

// ============================================================
// HOME ADDITIONS  (ID:16  /home-additions)
// ============================================================
$additions = Page::find(16);
if (!$additions) { echo "ERROR: Home Additions page not found\n"; return; }

Section::where('page_id', $additions->id)->delete();
echo "Cleared existing home-additions sections.\n";

$additionsSections = [
    // 1. SERVICE HERO
    [
        'page_id' => $additions->id,
        'type' => 'service_hero',
        'sort_order' => 0,
        'is_active' => true,
        'data' => [
            'title' => 'Home Additions in Connecticut',
            'subtitle' => 'Expand your home with seamless design and structural precision — room additions, second stories, in-law suites, and more.',
            'background_image' => null,
            'primary_cta' => ['label' => 'Schedule a Consultation', 'url' => '/free-consultation/'],
            'secondary_cta' => ['label' => 'Call (203) 555-0100', 'url' => 'tel:+12035550100'],
            'overlay_opacity' => 0.45,
        ],
    ],

    // 2. TEXT IMAGE SPLIT
    [
        'page_id' => $additions->id,
        'type' => 'text_image_split',
        'sort_order' => 1,
        'is_active' => true,
        'data' => [
            'title' => 'When Your Home Needs More Space',
            'content' => "Whether your family is growing, you need a dedicated home office, or aging parents are moving in — an addition is the smartest way to gain space without giving up the home you love.\n\nAt BUILTWELL, we design and build additions that blend seamlessly with your existing structure. From foundation to roofline, we handle architecture, engineering, permitting, and construction — all under one roof.",
            'image' => null,
            'image_position' => 'right',
            'bullet_points' => [
                ['text' => 'Room additions'],
                ['text' => 'Second-story additions'],
                ['text' => 'In-law suites'],
                ['text' => 'Garage conversions'],
                ['text' => 'Structural reinforcements'],
            ],
        ],
    ],

    // 3. SERVICE AREA TEXT
    [
        'page_id' => $additions->id,
        'type' => 'service_area_text',
        'sort_order' => 2,
        'is_active' => true,
        'data' => [
            'title' => 'Serving Fairfield & New Haven County',
            'content' => 'BUILTWELL builds home additions throughout Fairfield and New Haven County. From Stamford to Milford, Trumbull to Shelton — we bring licensed, insured craftsmanship to every project, every time.',
            'counties' => ['Fairfield County', 'New Haven County'],
        ],
    ],

    // 4. FEATURE GRID
    [
        'page_id' => $additions->id,
        'type' => 'feature_grid',
        'sort_order' => 3,
        'is_active' => true,
        'data' => [
            'title' => 'What Makes BUILTWELL Different',
            'items' => [
                ['title' => 'In-House Project Management', 'description' => 'One dedicated project manager from start to finish — no middlemen, no confusion.', 'icon' => null],
                ['title' => 'Licensed & Insured', 'description' => 'Fully licensed and insured with all required state and local certifications.', 'icon' => null],
                ['title' => 'Written Warranty', 'description' => 'Every project backed by a written workmanship warranty for your peace of mind.', 'icon' => null],
                ['title' => 'Clear Pricing', 'description' => 'Fixed-price proposals with no hidden fees, change-order surprises, or budget overruns.', 'icon' => null],
            ],
        ],
    ],

    // 5. BEFORE/AFTER GRID
    [
        'page_id' => $additions->id,
        'type' => 'before_after_grid',
        'sort_order' => 4,
        'is_active' => true,
        'data' => [
            'title' => 'Real Home Addition Projects',
            'subtitle' => 'See how we help Connecticut homeowners expand their living space.',
            'projects' => [
                [
                    'before_image' => null,
                    'after_image' => null,
                    'location' => 'Trumbull, CT',
                    'description' => 'Two-story addition with master suite above and family room below — matched perfectly to the existing Colonial exterior.',
                    'testimonial_quote' => 'You can\'t even tell where the old house ends and the addition begins. Incredible work.',
                ],
                [
                    'before_image' => null,
                    'after_image' => null,
                    'location' => 'Shelton, CT',
                    'description' => 'In-law suite addition with private entrance, kitchenette, full bathroom, and ADA-compliant doorways.',
                    'testimonial_quote' => null,
                ],
                [
                    'before_image' => null,
                    'after_image' => null,
                    'location' => 'Milford, CT',
                    'description' => 'Garage conversion into a home office and gym with insulated walls, new HVAC, and custom built-in cabinetry.',
                    'testimonial_quote' => 'Best decision we made during COVID. The space is perfect for working from home.',
                ],
            ],
        ],
    ],

    // 6. FAQ ACCORDION
    [
        'page_id' => $additions->id,
        'type' => 'faq_accordion',
        'sort_order' => 5,
        'is_active' => true,
        'data' => [
            'title' => 'Home Additions FAQ',
            'items' => [
                [
                    'question' => 'Do I need a structural engineer for a home addition?',
                    'answer' => 'In most cases, yes. A structural engineer ensures your foundation, framing, and load paths are designed correctly. BUILTWELL coordinates all engineering as part of our service — you don\'t need to hire one separately.',
                ],
                [
                    'question' => 'How long does a home addition take?',
                    'answer' => 'Most home additions take 3–6 months depending on size and complexity. A single-room addition may take 8–12 weeks, while a second-story addition typically takes 4–6 months. We provide a detailed timeline before breaking ground.',
                ],
                [
                    'question' => 'Do you handle permits?',
                    'answer' => 'Yes. We manage all permitting — building, electrical, plumbing, zoning — so you don\'t have to deal with town offices or inspectors directly.',
                ],
                [
                    'question' => 'Can you match the existing exterior of my home?',
                    'answer' => 'Absolutely. We match siding, roofing, trim, and architectural details so the addition looks like it was always part of the original home. Our crews are experienced with Colonial, Cape, Ranch, and contemporary styles.',
                ],
                [
                    'question' => 'What is the average cost of a home addition in CT?',
                    'answer' => 'Home additions in Connecticut typically range from $80,000–$200,000+ depending on size, scope, and finishes. Second-story additions and in-law suites tend to be on the higher end. We provide a fixed-price proposal after an on-site consultation.',
                ],
                [
                    'question' => 'Do home additions increase property value?',
                    'answer' => 'Yes. Well-built additions typically return 50–70% of their cost in added home value — and they dramatically improve livability. In-law suites and extra bedrooms are especially valuable in the Connecticut market.',
                ],
            ],
        ],
    ],

    // 7. CTA SPLIT FORM
    [
        'page_id' => $additions->id,
        'type' => 'cta_split_form',
        'sort_order' => 6,
        'is_active' => true,
        'data' => [
            'title' => 'Let\'s Plan Your Home Expansion',
            'subtitle' => 'Tell us about your project and we\'ll schedule a site visit to discuss your options.',
            'steps' => [
                ['number' => 1, 'text' => 'Tell us about your project'],
                ['number' => 2, 'text' => 'Schedule a site visit'],
                ['number' => 3, 'text' => 'Receive a detailed proposal'],
            ],
            'form_id' => 'consultation',
            'background_image' => null,
        ],
    ],
];

foreach ($additionsSections as $s) {
    Section::create($s);
}
echo "Created " . count($additionsSections) . " home-additions sections.\n";

$additions->update([
    'seo_title' => 'Home Additions in Connecticut | BUILTWELL',
    'seo_description' => 'Professional home additions in Fairfield and New Haven County, CT. Room additions, second stories, in-law suites, and garage conversions — backed by a written warranty.',
]);
echo "Updated home-additions SEO.\n";


// ============================================================
// FLOORING  (ID:31  /flooring)
// ============================================================
$flooring = Page::find(31);
if (!$flooring) { echo "ERROR: Flooring page not found\n"; return; }

Section::where('page_id', $flooring->id)->delete();
echo "Cleared existing flooring sections.\n";

$flooringSections = [
    // 1. SERVICE HERO
    [
        'page_id' => $flooring->id,
        'type' => 'service_hero',
        'sort_order' => 0,
        'is_active' => true,
        'data' => [
            'title' => 'Professional Flooring Installation in Connecticut',
            'subtitle' => 'Hardwood, LVP, tile, and more — installed with precision and backed by a written warranty.',
            'background_image' => null,
            'primary_cta' => ['label' => 'Get a Free Estimate', 'url' => '/free-consultation/'],
            'secondary_cta' => ['label' => 'Call (203) 555-0100', 'url' => 'tel:+12035550100'],
            'overlay_opacity' => 0.45,
        ],
    ],

    // 2. LOGO STRIP
    [
        'page_id' => $flooring->id,
        'type' => 'logo_strip',
        'sort_order' => 1,
        'is_active' => true,
        'data' => [
            'title' => 'Brands We Work With',
            'subtitle' => null,
            'items' => [
                ['name' => 'Shaw', 'logo' => '', 'url' => null],
                ['name' => 'COREtec', 'logo' => '', 'url' => null],
                ['name' => 'Mohawk', 'logo' => '', 'url' => null],
                ['name' => 'Daltile', 'logo' => '', 'url' => null],
                ['name' => 'Armstrong', 'logo' => '', 'url' => null],
            ],
        ],
    ],

    // 3. SERVICE PROCESS (4 steps)
    [
        'page_id' => $flooring->id,
        'type' => 'service_process',
        'sort_order' => 2,
        'is_active' => true,
        'data' => [
            'title' => 'How Flooring Installation Works',
            'steps' => [
                ['step_number' => 1, 'title' => 'Consultation', 'description' => 'We visit your home, measure the space, and discuss your flooring preferences, lifestyle needs, and budget.'],
                ['step_number' => 2, 'title' => 'Material Selection', 'description' => 'We help you choose the right flooring — hardwood, LVP, tile, or engineered wood — based on your rooms, traffic, and style.'],
                ['step_number' => 3, 'title' => 'Installation', 'description' => 'Our crew handles subfloor prep, moisture testing, and precision installation with clean, efficient workmanship.'],
                ['step_number' => 4, 'title' => 'Final Inspection', 'description' => 'We walk through every room together to make sure every detail meets our standards and yours.'],
            ],
        ],
    ],

    // 4. TEXT IMAGE SPLIT
    [
        'page_id' => $flooring->id,
        'type' => 'text_image_split',
        'sort_order' => 3,
        'is_active' => true,
        'data' => [
            'title' => 'Flooring That Fits Your Lifestyle',
            'content' => "Your floors take more abuse than any other surface in your home — kids, pets, spills, and daily traffic. That\'s why choosing the right material matters.\n\nAt BUILTWELL, we help you select flooring that balances beauty, durability, and maintenance. Whether you want the warmth of hardwood, the resilience of luxury vinyl, or the clean look of tile — we install it right the first time.",
            'image' => null,
            'image_position' => 'right',
            'bullet_points' => [
                ['text' => 'Hardwood flooring'],
                ['text' => 'Luxury vinyl plank (LVP)'],
                ['text' => 'Tile flooring'],
                ['text' => 'Engineered wood'],
                ['text' => 'Subfloor repair'],
            ],
        ],
    ],

    // 5. TESTIMONIAL SLIDER (3 only)
    [
        'page_id' => $flooring->id,
        'type' => 'testimonial_slider_small',
        'sort_order' => 4,
        'is_active' => true,
        'data' => [
            'title' => 'What Homeowners Say About Their New Floors',
            'testimonials' => [
                ['name' => 'Karen M.', 'city' => 'Trumbull, CT', 'rating' => 5, 'text' => 'We had LVP installed throughout our entire first floor. The team was fast, clean, and the floors look incredible. Highly recommend BUILTWELL.'],
                ['name' => 'David & Sarah L.', 'city' => 'Milford, CT', 'rating' => 5, 'text' => 'Beautiful hardwood floors in our living room and hallway. The crew was professional and finished ahead of schedule.'],
                ['name' => 'Jennifer R.', 'city' => 'Shelton, CT', 'rating' => 5, 'text' => 'They repaired our subfloor and installed new tile in the kitchen and bathrooms. Everything looks perfect and the price was fair.'],
            ],
        ],
    ],

    // 6. FULL WIDTH TEXT DARK
    [
        'page_id' => $flooring->id,
        'type' => 'full_width_text_dark',
        'sort_order' => 5,
        'is_active' => true,
        'data' => [
            'title' => 'Built to Last',
            'subtitle' => 'Every floor we install is backed by quality materials, proper subfloor preparation, and a written workmanship warranty. No shortcuts — just floors that stand up to real life.',
            'alignment' => 'center',
        ],
    ],

    // 7. CTA SPLIT FORM
    [
        'page_id' => $flooring->id,
        'type' => 'cta_split_form',
        'sort_order' => 6,
        'is_active' => true,
        'data' => [
            'title' => 'Upgrade Your Floors Today',
            'subtitle' => 'Get a free estimate for your flooring project. We\'ll help you choose the right material and deliver a flawless installation.',
            'steps' => [
                ['number' => 1, 'text' => 'Tell us about your flooring project'],
                ['number' => 2, 'text' => 'We\'ll schedule a site visit'],
                ['number' => 3, 'text' => 'Receive your free estimate'],
            ],
            'form_id' => 'consultation',
            'background_image' => null,
        ],
    ],
];

foreach ($flooringSections as $s) {
    Section::create($s);
}
echo "Created " . count($flooringSections) . " flooring sections.\n";

$flooring->update([
    'seo_title' => 'Flooring Installation in Connecticut | BUILTWELL',
    'seo_description' => 'Professional flooring installation in Fairfield and New Haven County, CT. Hardwood, LVP, tile, engineered wood, and subfloor repair — backed by a written warranty.',
]);
echo "Updated flooring SEO.\n";


// ============================================================
// FINAL SUMMARY
// ============================================================
echo "\n========== SUMMARY ==========\n";

$additionsCheck = Page::find(16);
$flooringCheck = Page::find(31);

$addSections = Section::where('page_id', 16)->get();
$flrSections = Section::where('page_id', 31)->get();

echo "Home Additions (ID:{$additionsCheck->id}) — Status: {$additionsCheck->status} — Sections: {$addSections->count()}\n";
echo "  Types: " . $addSections->pluck('type')->implode(', ') . "\n";

echo "Flooring (ID:{$flooringCheck->id}) — Status: {$flooringCheck->status} — Sections: {$flrSections->count()}\n";
echo "  Types: " . $flrSections->pluck('type')->implode(', ') . "\n";

echo "\nDone! Both pages seeded with unique layouts.\n";
