<?php

use App\Models\Section;
use App\Models\Page;

// ============================================================
// BATHROOM REMODELING  (ID:6  /bathroom-remodeling)
// Premium / Luxury Feel — emotional, design-focused
// ============================================================
$bathroom = Page::find(6);
if (!$bathroom) { echo "ERROR: Bathroom page not found\n"; return; }

Section::where('page_id', $bathroom->id)->delete();
echo "Cleared existing bathroom sections.\n";

$bathroomSections = [
    // 1. SERVICE HERO
    [
        'page_id' => $bathroom->id,
        'type' => 'service_hero',
        'sort_order' => 0,
        'is_active' => true,
        'data' => [
            'title' => 'Bathroom Remodeling in Connecticut',
            'subtitle' => 'Transform your bathroom into a personal retreat — spa-inspired showers, custom vanities, heated floors, and designer tile work crafted to perfection.',
            'background_image' => null,
            'primary_cta' => ['label' => 'Schedule a Free Consultation', 'url' => '/free-consultation/'],
            'secondary_cta' => ['label' => 'Call (203) 555-0100', 'url' => 'tel:+12035550100'],
            'overlay_opacity' => 0.45,
        ],
    ],

    // 2. FULL WIDTH TEXT DARK (moved to #2 — strong emotional statement)
    [
        'page_id' => $bathroom->id,
        'type' => 'full_width_text_dark',
        'sort_order' => 1,
        'is_active' => true,
        'data' => [
            'title' => 'Your Bathroom, Your Way',
            'subtitle' => 'Every bathroom we build starts with your vision — not a cookie-cutter template. Whether you want a minimalist spa or a luxurious master suite, we design around how you live.',
            'alignment' => 'center',
        ],
    ],

    // 3. BEFORE/AFTER GRID (moved higher — show work early)
    [
        'page_id' => $bathroom->id,
        'type' => 'before_after_grid',
        'sort_order' => 2,
        'is_active' => true,
        'data' => [
            'title' => 'Real Bathroom Transformations',
            'subtitle' => 'See what happens when quality craftsmanship meets thoughtful design.',
            'projects' => [
                [
                    'before_image' => null,
                    'after_image' => null,
                    'location' => 'Greenwich, CT',
                    'description' => 'Complete master bathroom renovation — walk-in shower with frameless glass, heated porcelain floors, and custom double vanity.',
                    'testimonial_quote' => 'Our bathroom feels like a five-star hotel. BUILTWELL nailed every detail — the tile work is absolutely flawless.',
                ],
                [
                    'before_image' => null,
                    'after_image' => null,
                    'location' => 'Westport, CT',
                    'description' => 'Tub-to-shower conversion with floor-to-ceiling marble tile, recessed niche, and rainfall showerhead.',
                    'testimonial_quote' => null,
                ],
                [
                    'before_image' => null,
                    'after_image' => null,
                    'location' => 'Orange, CT',
                    'description' => 'Hall bathroom update — new vanity, subway tile shower surround, modern fixtures, and fresh flooring in under 3 weeks.',
                    'testimonial_quote' => 'Fast, clean, and the result is beautiful. Best contractor experience we\'ve had.',
                ],
            ],
        ],
    ],

    // 4. SERVICE INTRO SPLIT (moved below projects)
    [
        'page_id' => $bathroom->id,
        'type' => 'service_intro_split',
        'sort_order' => 3,
        'is_active' => true,
        'data' => [
            'title' => 'A Bathroom Designed for Comfort and Function',
            'content' => "Your bathroom should be more than just functional — it should feel like a retreat.\n\nAt BUILTWELL, we handle every detail of your bathroom renovation. Custom tile, walk-in showers, freestanding tubs, double vanities, heated floors, and modern fixtures — all managed by one team with one point of contact.\n\nNo subcontractors disappearing. No budget surprises. Just clear communication, quality materials, and craftsmanship that lasts.",
            'image_main' => null,
            'image_secondary' => null,
            'bullet_points' => [
                ['text' => 'Walk-in showers and tub-to-shower conversions'],
                ['text' => 'Custom tile work (floor, walls, niches)'],
                ['text' => 'Double vanities and custom cabinetry'],
                ['text' => 'Heated flooring systems'],
                ['text' => 'Plumbing relocation and fixture upgrades'],
                ['text' => 'Lighting design and ventilation'],
                ['text' => 'Waterproofing and moisture protection'],
                ['text' => 'Written warranty on all workmanship'],
            ],
        ],
    ],

    // 5. FEATURE GRID (NEW — replaces service_process, logo_strip, service_area_text)
    [
        'page_id' => $bathroom->id,
        'type' => 'feature_grid',
        'sort_order' => 4,
        'is_active' => true,
        'data' => [
            'title' => 'Why Homeowners Trust BUILTWELL',
            'items' => [
                ['title' => 'Waterproofing Expertise', 'description' => 'Every shower, floor, and wet area is fully waterproofed using industry-leading systems — no shortcuts, no callbacks.', 'icon' => null],
                ['title' => 'Precision Tile Installation', 'description' => 'Our tile setters are specialists. Subway, mosaic, large-format, natural stone — installed with laser-straight lines and flawless grout.', 'icon' => null],
                ['title' => 'In-House Coordination', 'description' => 'One project manager, one team. Plumbing, electrical, tile, cabinetry — all coordinated under one roof with daily updates.', 'icon' => null],
                ['title' => 'Written Warranty', 'description' => 'Every BUILTWELL bathroom comes with a written workmanship warranty. If something isn\'t right, we come back and fix it.', 'icon' => null],
            ],
        ],
    ],

    // 6. TESTIMONIAL SLIDER (reduced to 3)
    [
        'page_id' => $bathroom->id,
        'type' => 'testimonial_slider_small',
        'sort_order' => 5,
        'is_active' => true,
        'data' => [
            'title' => 'What Homeowners Say About Their New Bathrooms',
            'testimonials' => [
                ['name' => 'Lisa & James R.', 'city' => 'Orange, CT', 'rating' => 5, 'text' => 'BUILTWELL turned our dated master bath into a spa. The heated floors and walk-in shower were exactly what we wanted. Incredible attention to detail.'],
                ['name' => 'Amanda P.', 'city' => 'Stamford, CT', 'rating' => 5, 'text' => 'They guided us through every tile and fixture decision. The process was smooth and stress-free, and the result exceeded our expectations.'],
                ['name' => 'Robert K.', 'city' => 'Fairfield, CT', 'rating' => 5, 'text' => 'Professional, on time, and the craftsmanship is outstanding. Our guest bathroom looks like it belongs in a design magazine.'],
            ],
        ],
    ],

    // 7. FAQ ACCORDION (reduced to 5)
    [
        'page_id' => $bathroom->id,
        'type' => 'faq_accordion',
        'sort_order' => 6,
        'is_active' => true,
        'data' => [
            'title' => 'Bathroom Remodeling FAQ',
            'items' => [
                [
                    'question' => 'How long does a bathroom remodel take?',
                    'answer' => 'Most bathroom remodels take 3–6 weeks depending on scope. A simple refresh (vanity, fixtures, paint) can be done in 2–3 weeks. Full gut renovations with custom tile and layout changes typically take 4–6 weeks. We\'ll provide a clear timeline before any work begins.',
                ],
                [
                    'question' => 'How much does a bathroom remodel cost in Connecticut?',
                    'answer' => 'Bathroom remodels in CT typically range from $25,000–$50,000 for a standard renovation. High-end master bathrooms with premium tile, heated floors, and custom features can range from $50,000–$100,000+. We provide fixed-price proposals so there are no surprises.',
                ],
                [
                    'question' => 'Can you convert my bathtub to a walk-in shower?',
                    'answer' => 'Yes — tub-to-shower conversions are one of our most popular projects. We handle the plumbing relocation, waterproofing, custom tile work, glass enclosure, and all finishing details.',
                ],
                [
                    'question' => 'Do you handle permits for bathroom remodeling?',
                    'answer' => 'Yes. We manage all necessary permits for your bathroom remodel, including plumbing and electrical permits required by your town.',
                ],
                [
                    'question' => 'Do you offer a warranty?',
                    'answer' => 'Yes. Every BUILTWELL project comes with a written workmanship warranty. If anything isn\'t right after completion, we come back and fix it.',
                ],
            ],
        ],
    ],

    // 8. CTA SPLIT FORM
    [
        'page_id' => $bathroom->id,
        'type' => 'cta_split_form',
        'sort_order' => 7,
        'is_active' => true,
        'data' => [
            'title' => 'Ready to Start Your Bathroom Remodel?',
            'subtitle' => 'Schedule your free, no-obligation consultation. We\'ll visit your home, discuss your vision, and provide a detailed proposal with a fixed price.',
            'steps' => [
                ['number' => 1, 'text' => 'Tell us about your bathroom project'],
                ['number' => 2, 'text' => 'We\'ll schedule a site visit'],
                ['number' => 3, 'text' => 'Receive your detailed proposal'],
            ],
            'form_id' => 'consultation',
            'background_image' => null,
        ],
    ],
];

foreach ($bathroomSections as $s) {
    Section::create($s);
}
echo "Created " . count($bathroomSections) . " bathroom-remodeling sections.\n";

$bathroom->update([
    'seo_title' => 'Bathroom Remodeling in Connecticut | BUILTWELL',
    'seo_description' => 'Full-service bathroom remodeling in Fairfield and New Haven County, CT. Walk-in showers, custom tile, heated floors, and expert craftsmanship — backed by a written warranty.',
]);
echo "Updated bathroom SEO.\n";


// ============================================================
// BASEMENT FINISHING  (ID:36  /basement-finishing)
// Practical / Value-Driven — trustworthy, structurally engineered
// ============================================================
$basement = Page::find(36);
if (!$basement) { echo "ERROR: Basement page not found\n"; return; }

Section::where('page_id', $basement->id)->delete();
echo "Cleared existing basement sections.\n";

$basementSections = [
    // 1. SERVICE HERO
    [
        'page_id' => $basement->id,
        'type' => 'service_hero',
        'sort_order' => 0,
        'is_active' => true,
        'data' => [
            'title' => 'Basement Finishing in Connecticut',
            'subtitle' => 'Turn unused square footage into living space — home offices, entertainment rooms, guest suites, and more — backed by a written warranty.',
            'background_image' => null,
            'primary_cta' => ['label' => 'Schedule a Free Consultation', 'url' => '/free-consultation/'],
            'secondary_cta' => ['label' => 'Call (203) 555-0100', 'url' => 'tel:+12035550100'],
            'overlay_opacity' => 0.45,
        ],
    ],

    // 2. SERVICE PROCESS (moved early — practical, step-by-step)
    [
        'page_id' => $basement->id,
        'type' => 'service_process',
        'sort_order' => 1,
        'is_active' => true,
        'data' => [
            'title' => 'How Your Basement Finishing Project Works',
            'steps' => [
                ['step_number' => 1, 'title' => 'Consultation', 'description' => 'We assess your basement, discuss how you want to use the space, and review any moisture or structural considerations.'],
                ['step_number' => 2, 'title' => 'Planning', 'description' => 'You receive a detailed proposal with layout, scope, timeline, and a fixed price — everything spelled out clearly.'],
                ['step_number' => 3, 'title' => 'Selections', 'description' => 'We guide you through flooring, lighting, paint colors, fixtures, and any custom features like built-ins or a wet bar.'],
                ['step_number' => 4, 'title' => 'Build', 'description' => 'Our crew handles framing, electrical, plumbing, insulation, drywall, flooring, and trim — with daily communication.'],
                ['step_number' => 5, 'title' => 'Walkthrough', 'description' => 'We inspect every detail together and address anything that needs attention before the project closes.'],
            ],
        ],
    ],

    // 3. TEXT IMAGE SPLIT (moisture, insulation, code compliance focus)
    [
        'page_id' => $basement->id,
        'type' => 'text_image_split',
        'sort_order' => 2,
        'is_active' => true,
        'data' => [
            'title' => 'Built Right from the Ground Up',
            'content' => "Basements present unique challenges — moisture, insulation, egress, and code compliance. At BUILTWELL, we address every one of them before a single wall goes up.\n\nWe start with a thorough assessment of your basement's condition: water intrusion, humidity levels, foundation integrity, and drainage. Then we build with proper vapor barriers, insulation, and ventilation to create a dry, comfortable, code-compliant living space that lasts.",
            'image' => null,
            'image_position' => 'right',
            'bullet_points' => [
                ['text' => 'Moisture management and waterproofing'],
                ['text' => 'Proper insulation and vapor barriers'],
                ['text' => 'Egress windows and code compliance'],
                ['text' => 'Framing, drywall, and finishing'],
                ['text' => 'Electrical, plumbing, and HVAC'],
                ['text' => 'Flooring (LVP, tile, carpet, epoxy)'],
            ],
        ],
    ],

    // 4. LOGO STRIP (materials credibility)
    [
        'page_id' => $basement->id,
        'type' => 'logo_strip',
        'sort_order' => 3,
        'is_active' => true,
        'data' => [
            'title' => 'Premium Materials We Work With',
            'subtitle' => null,
            'items' => [
                ['name' => 'Shaw Floors', 'logo' => '', 'url' => null],
                ['name' => 'COREtec', 'logo' => '', 'url' => null],
                ['name' => 'Owens Corning', 'logo' => '', 'url' => null],
                ['name' => 'Kohler', 'logo' => '', 'url' => null],
                ['name' => 'Lutron', 'logo' => '', 'url' => null],
                ['name' => 'DRIcore', 'logo' => '', 'url' => null],
            ],
        ],
    ],

    // 5. SERVICE AREA TEXT
    [
        'page_id' => $basement->id,
        'type' => 'service_area_text',
        'sort_order' => 4,
        'is_active' => true,
        'data' => [
            'title' => 'Basement Finishing Across Connecticut',
            'content' => 'BUILTWELL provides full-service basement finishing throughout Fairfield County and New Haven County. Whether you\'re in Greenwich, Stamford, Westport, or Orange — our team brings the same level of craftsmanship and accountability to every project.',
            'counties' => ['Fairfield County', 'New Haven County'],
        ],
    ],

    // 6. TESTIMONIAL SLIDER (4 testimonials)
    [
        'page_id' => $basement->id,
        'type' => 'testimonial_slider_small',
        'sort_order' => 5,
        'is_active' => true,
        'data' => [
            'title' => 'What Homeowners Say About Their Finished Basements',
            'testimonials' => [
                ['name' => 'Michael T.', 'city' => 'Westport, CT', 'rating' => 5, 'text' => 'Our basement went from an unusable storage space to a beautiful family room. The whole process was smooth and the team was a pleasure to work with.'],
                ['name' => 'Brian & Julie H.', 'city' => 'Fairfield, CT', 'rating' => 5, 'text' => 'We needed a home office and guest room. BUILTWELL designed the perfect layout and delivered exactly what they promised — on time and on budget.'],
                ['name' => 'Danielle S.', 'city' => 'Stamford, CT', 'rating' => 5, 'text' => 'The kids finally have their own space and we have a real entertainment area. Best investment we\'ve made in this house.'],
                ['name' => 'Tom W.', 'city' => 'Trumbull, CT', 'rating' => 5, 'text' => 'They handled the moisture issues, framing, electrical, everything. Professional crew, clean work, and they communicated every single day.'],
            ],
        ],
    ],

    // 7. FAQ ACCORDION (6 FAQs)
    [
        'page_id' => $basement->id,
        'type' => 'faq_accordion',
        'sort_order' => 6,
        'is_active' => true,
        'data' => [
            'title' => 'Basement Finishing FAQ',
            'items' => [
                [
                    'question' => 'How long does it take to finish a basement?',
                    'answer' => 'Most basement finishing projects take 4–8 weeks depending on size and complexity. Adding a bathroom or wet bar can extend the timeline slightly. We\'ll give you a clear schedule before work begins.',
                ],
                [
                    'question' => 'How much does basement finishing cost in Connecticut?',
                    'answer' => 'Basement finishing in CT typically ranges from $35,000–$70,000 for a standard build-out. Projects with bathrooms, wet bars, or custom features can range from $70,000–$120,000+. We provide fixed-price proposals so there are no surprises.',
                ],
                [
                    'question' => 'Do I need to worry about moisture in my basement?',
                    'answer' => 'Moisture management is critical for any basement project. We assess your space during the consultation and address any water intrusion, humidity, or drainage issues before finishing begins. Proper waterproofing and insulation are built into every project.',
                ],
                [
                    'question' => 'Can I add a bathroom to my basement?',
                    'answer' => 'Yes — basement bathrooms are one of our most common add-ons. We handle plumbing rough-in, waterproofing, tile, fixtures, and all finishing work. If your home has a sewer ejector or gravity feed, we\'ll design around it.',
                ],
                [
                    'question' => 'Do you handle permits for basement finishing?',
                    'answer' => 'Yes. We manage all required permits including building, electrical, plumbing, and egress window permits as needed by your municipality.',
                ],
                [
                    'question' => 'Do you offer a warranty?',
                    'answer' => 'Yes. Every BUILTWELL project comes with a written workmanship warranty. If anything isn\'t right after completion, we come back and fix it.',
                ],
            ],
        ],
    ],

    // 8. CTA SPLIT FORM
    [
        'page_id' => $basement->id,
        'type' => 'cta_split_form',
        'sort_order' => 7,
        'is_active' => true,
        'data' => [
            'title' => 'Ready to Finish Your Basement?',
            'subtitle' => 'Schedule your free, no-obligation consultation. We\'ll visit your home, assess your basement, and provide a detailed proposal with a fixed price.',
            'steps' => [
                ['number' => 1, 'text' => 'Tell us about your basement project'],
                ['number' => 2, 'text' => 'We\'ll schedule a site visit'],
                ['number' => 3, 'text' => 'Receive your detailed proposal'],
            ],
            'form_id' => 'consultation',
            'background_image' => null,
        ],
    ],
];

foreach ($basementSections as $s) {
    Section::create($s);
}
echo "Created " . count($basementSections) . " basement-finishing sections.\n";

$basement->update([
    'seo_title' => 'Basement Finishing in Connecticut | BUILTWELL',
    'seo_description' => 'Professional basement finishing in Fairfield and New Haven County, CT. Home offices, entertainment rooms, guest suites, and more — backed by a written warranty.',
]);
echo "Updated basement SEO.\n";


// ============================================================
// VALIDATION
// ============================================================
echo "\n========== VALIDATION ==========\n";

$bathCheck = Page::find(6);
$baseCheck = Page::find(36);

$bathSecs = Section::where('page_id', 6)->orderBy('sort_order')->get();
$baseSecs = Section::where('page_id', 36)->orderBy('sort_order')->get();

$bathTypes = $bathSecs->pluck('type')->toArray();
$baseTypes = $baseSecs->pluck('type')->toArray();

echo "Bathroom (ID:{$bathCheck->id}) — Status: {$bathCheck->status} — Sections: {$bathSecs->count()}\n";
echo "  Order: " . implode(' → ', $bathTypes) . "\n";

echo "Basement (ID:{$baseCheck->id}) — Status: {$baseCheck->status} — Sections: {$baseSecs->count()}\n";
echo "  Order: " . implode(' → ', $baseTypes) . "\n";

$identical = ($bathTypes === $baseTypes);
echo "\nLayouts identical? " . ($identical ? "YES ⚠️" : "NO ✓") . "\n";

echo "\nDone! Both pages restructured with unique layouts.\n";
