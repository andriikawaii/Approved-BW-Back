<?php

use App\Models\Section;
use App\Models\Page;

// ============================================================
// HELPER: Create or find page, clear sections, rebuild
// ============================================================
function seedPage(string $path, string $templateKey, string $type, array $seo, array $sections): void
{
    $page = Page::where('full_path', $path)->first();

    if (!$page) {
        $page = Page::create([
            'full_path'     => $path,
            'type'          => $type,
            'template_key'  => $templateKey,
            'status'        => 'published',
            'published_at'  => now(),
            'seo_title'     => $seo['title'],
            'seo_description' => $seo['description'],
        ]);
        echo "Created page {$path} (ID:{$page->id})\n";
    } else {
        Section::where('page_id', $page->id)->delete();
        $page->update([
            'template_key'    => $templateKey,
            'status'          => 'published',
            'published_at'    => $page->published_at ?? now(),
            'seo_title'       => $seo['title'],
            'seo_description' => $seo['description'],
        ]);
        echo "Found existing page {$path} (ID:{$page->id}) — cleared sections\n";
    }

    foreach ($sections as $i => $s) {
        Section::create([
            'page_id'    => $page->id,
            'type'       => $s['type'],
            'sort_order' => $i,
            'is_active'  => true,
            'data'       => $s['data'],
        ]);
    }

    echo "  → Created " . count($sections) . " sections\n";
    $types = collect($sections)->pluck('type')->implode(' → ');
    echo "  → Order: {$types}\n\n";
}

// ============================================================
// 1. /projects (PORTFOLIO PAGE)
// ============================================================
seedPage(
    '/projects',
    'portfolio',
    'portfolio',
    [
        'title'       => 'Recent Remodeling Projects in Connecticut | BUILTWELL',
        'description' => 'Browse our portfolio of completed home remodeling projects across Fairfield and New Haven County, CT. Kitchen, bathroom, basement, and addition transformations.',
    ],
    [
        // 1. portfolio_hero
        [
            'type' => 'hero',
            'data' => [
                'headline'         => 'Recent Remodeling Projects in Connecticut',
                'subheadline'      => 'Real homes. Real craftsmanship. Real results.',
                'background_image' => null,
                'background_video' => null,
                'cta_primary'      => ['label' => 'Schedule a Consultation', 'url' => '/free-consultation/'],
                'cta_secondary'    => ['label' => 'View Services', 'url' => '/services/'],
                'overlay_opacity'  => 0.5,
            ],
        ],
        // 2. project_category_filter
        [
            'type' => 'project_category_filter',
            'data' => [
                'headline' => 'Browse by Project Type',
                'filters'  => [
                    ['label' => 'All',        'value' => 'all'],
                    ['label' => 'Kitchen',    'value' => 'kitchen'],
                    ['label' => 'Bathroom',   'value' => 'bathroom'],
                    ['label' => 'Basement',   'value' => 'basement'],
                    ['label' => 'Additions',  'value' => 'additions'],
                ],
            ],
        ],
        // 3. projects_masonry_grid
        [
            'type' => 'projects_masonry_grid',
            'data' => [
                'headline' => 'Our Work Speaks for Itself',
                'projects' => [
                    ['title' => 'Modern Farmhouse Kitchen',        'location' => 'Westport, CT',    'description' => 'Complete kitchen gut renovation with custom white oak cabinetry, quartzite countertops, and a 10-foot island with integrated seating.',               'service_type' => 'kitchen',    'image' => null],
                    ['title' => 'Luxury Master Bathroom Suite',    'location' => 'Greenwich, CT',   'description' => 'Spa-inspired master bath featuring a freestanding soaking tub, curbless walk-in shower with dual rain heads, and heated marble floors.',             'service_type' => 'bathroom',   'image' => null],
                    ['title' => 'Full Basement Entertainment Area', 'location' => 'Orange, CT',     'description' => 'Transformed an unfinished basement into a 1,200 sq ft entertainment space with wet bar, home theater, and a full bathroom.',                        'service_type' => 'basement',   'image' => null],
                    ['title' => 'Two-Story Colonial Addition',     'location' => 'Fairfield, CT',   'description' => 'Added a two-story wing including a master suite above and open-concept family room below, seamlessly matching the existing Colonial architecture.',  'service_type' => 'additions',  'image' => null],
                    ['title' => 'Coastal Kitchen Renovation',      'location' => 'Milford, CT',     'description' => 'Light and airy coastal kitchen with shaker cabinets, butcher block island, and subway tile backsplash. Completed in 5 weeks.',                      'service_type' => 'kitchen',    'image' => null],
                    ['title' => 'Guest Bathroom Remodel',          'location' => 'Darien, CT',      'description' => 'Compact guest bath updated with floor-to-ceiling tile, floating vanity, and frameless glass shower enclosure.',                                     'service_type' => 'bathroom',   'image' => null],
                    ['title' => 'Basement Home Office & Gym',      'location' => 'Hamden, CT',      'description' => 'Converted a damp, unused basement into a bright home office with built-in shelving and an adjacent workout room with rubber flooring.',             'service_type' => 'basement',   'image' => null],
                    ['title' => 'In-Law Suite Addition',           'location' => 'Branford, CT',    'description' => 'Ground-level addition with private entrance, full kitchen, bedroom, and ADA-compliant bathroom for aging-in-place comfort.',                       'service_type' => 'additions',  'image' => null],
                    ['title' => 'Chef\'s Kitchen with Butler\'s Pantry', 'location' => 'Stamford, CT', 'description' => 'High-performance kitchen with professional-grade appliances, custom pantry storage, and hand-finished cabinetry throughout.',                   'service_type' => 'kitchen',    'image' => null],
                    ['title' => 'Primary Bath with Steam Shower',  'location' => 'Trumbull, CT',    'description' => 'Full primary bathroom renovation featuring a custom steam shower, double vanity with quartz tops, and radiant floor heating.',                     'service_type' => 'bathroom',   'image' => null],
                ],
            ],
        ],
        // 4. case_study_highlight
        [
            'type' => 'case_study_highlight',
            'data' => [
                'headline'  => 'Featured Project: Full Home Renovation in Westport',
                'image'     => null,
                'challenge' => 'A 1970s split-level home with an outdated layout, poor natural light, and a kitchen that hadn\'t been touched in 30 years. The homeowners wanted a modern open-concept living space without losing the home\'s character.',
                'solution'  => 'BUILTWELL removed a load-bearing wall between the kitchen and living room, installed a structural steel beam, and rebuilt the space with custom cabinetry, wide-plank hardwood floors, and floor-to-ceiling windows facing the backyard.',
                'outcome'   => 'The renovation was completed in 10 weeks, on budget. The home\'s appraised value increased by over $120,000. The clients called it "the best investment we\'ve ever made."',
                'project_details' => [
                    'location'     => 'Westport, CT',
                    'duration'     => '10 weeks',
                    'service_type' => 'Kitchen + Living Room Renovation',
                ],
            ],
        ],
        // 5. stats_bar
        [
            'type' => 'stats_bar',
            'data' => [
                'stats' => [
                    ['value' => '100+', 'label' => 'Projects Completed'],
                    ['value' => '15+',  'label' => 'Years Experience'],
                    ['value' => '5.0',  'label' => 'Star Rating'],
                    ['value' => '100%', 'label' => 'Licensed & Insured'],
                ],
            ],
        ],
        // 6. cta_dark_band
        [
            'type' => 'cta_dark_band',
            'data' => [
                'headline' => 'Want Results Like These?',
                'subheadline' => 'Let\'s discuss your project. Every consultation is free, detailed, and obligation-free.',
                'cta' => ['label' => 'Schedule Consultation', 'url' => '/free-consultation/'],
            ],
        ],
    ]
);


// ============================================================
// 2. /testimonials (REVIEWS PAGE)
// ============================================================
seedPage(
    '/testimonials',
    'testimonials_page',
    'testimonials',
    [
        'title'       => 'Customer Reviews & Testimonials | BUILTWELL Connecticut',
        'description' => 'Read what homeowners across Fairfield and New Haven County say about BUILTWELL. 5-star Google reviews, BBB A+ rated, and 100% client satisfaction.',
    ],
    [
        // 1. reviews_hero
        [
            'type' => 'hero',
            'data' => [
                'headline'         => 'What Homeowners Say About BUILTWELL',
                'subheadline'      => 'Our reputation is built one project at a time. Here\'s what our clients have to say.',
                'background_image' => null,
                'background_video' => null,
                'cta_primary'      => ['label' => 'Get a Free Estimate', 'url' => '/free-consultation/'],
                'overlay_opacity'  => 0.45,
            ],
        ],
        // 2. review_rating_summary
        [
            'type' => 'review_rating_summary',
            'data' => [
                'headline' => 'Our Track Record',
                'ratings'  => [
                    ['platform' => 'Google',                 'rating' => '5.0 Stars',              'icon' => 'google'],
                    ['platform' => 'Better Business Bureau', 'rating' => 'A+ Rating',              'icon' => 'bbb'],
                    ['platform' => 'Client Satisfaction',    'rating' => '100% Satisfaction Rate',  'icon' => 'check-circle'],
                ],
            ],
        ],
        // 3. testimonial_grid_large
        [
            'type' => 'testimonial_grid_large',
            'data' => [
                'headline'     => 'Real Reviews From Real Homeowners',
                'testimonials' => [
                    ['name' => 'Lisa & James R.',   'town' => 'Greenwich, CT',   'rating' => 5, 'review' => "We hired BUILTWELL to remodel our master bathroom and the experience exceeded every expectation. From the initial consultation to the final walkthrough, the team was professional, communicative, and meticulous.\n\nThe heated marble floors, frameless glass shower, and freestanding tub turned our outdated bathroom into a spa retreat. They finished on time, on budget, and left the job site spotless every single day.\n\nWe've already booked them for our kitchen renovation next spring."],
                    ['name' => 'Michael T.',        'town' => 'Westport, CT',    'rating' => 5, 'review' => "BUILTWELL finished our basement in under six weeks — entertainment room, wet bar, half bath, and a small office nook. The project was seamless from start to finish.\n\nWhat impressed me most was the communication. Our project manager sent daily photo updates and was always available to answer questions. The crew showed up on time every morning and treated our home with respect.\n\nThe finished space has become the most-used room in our house. Worth every penny."],
                    ['name' => 'Karen M.',          'town' => 'Orange, CT',      'rating' => 5, 'review' => "We had our entire first floor refloored with luxury vinyl plank and the kitchen completely remodeled. BUILTWELL managed both projects simultaneously without a single hiccup.\n\nThe attention to detail was remarkable — perfectly aligned transitions between rooms, custom trim work, and cabinetry that looks like it belongs in a magazine. The pricing was transparent and there were zero surprise charges."],
                    ['name' => 'David & Sarah L.',  'town' => 'Milford, CT',     'rating' => 5, 'review' => "BUILTWELL added a beautiful in-law suite for my mother — private entrance, full kitchen, bedroom, and an ADA-compliant bathroom. The craftsmanship is outstanding and the design perfectly matches our existing home.\n\nThey handled all the permits, engineering, and inspections. We didn't have to chase down a single detail. My mother loves her new space and we have peace of mind knowing she's close by."],
                    ['name' => 'Robert K.',         'town' => 'Fairfield, CT',   'rating' => 5, 'review' => "We chose BUILTWELL for a full kitchen remodel after getting three other quotes. Their proposal was the most detailed and transparent — we knew exactly what we were getting and what it would cost.\n\nThe result is stunning. Custom shaker cabinetry, quartzite countertops, and a layout that finally makes sense for how we cook and entertain. The foreman was on-site daily and nothing slipped through the cracks."],
                    ['name' => 'Jennifer R.',       'town' => 'Branford, CT',    'rating' => 5, 'review' => "Our basement went from a damp, neglected storage area to a beautifully finished family room with a half bath. BUILTWELL handled the moisture mitigation, framing, electrical, plumbing, and finish work — all under one roof.\n\nWe use this space every single day now. Movie nights, work-from-home days, and weekend hangouts. It's added real livable square footage to our home."],
                    ['name' => 'Steve & Diane P.',  'town' => 'Stamford, CT',    'rating' => 5, 'review' => "We had BUILTWELL install a chef's kitchen with professional-grade appliances, a butler's pantry, and custom cabinetry throughout. The project took eight weeks and the quality is exceptional.\n\nEvery tradesperson who walked through our door was professional, clean, and clearly skilled. This was the smoothest renovation we've ever been through — and we've done four over the years."],
                    ['name' => 'Amanda G.',         'town' => 'Hamden, CT',      'rating' => 5, 'review' => "I was nervous about hiring a contractor after a bad experience years ago. BUILTWELL changed my perspective entirely. They converted our unfinished basement into a home office and workout room.\n\nThe process was organized, the pricing was locked in before they started, and the crew was respectful and tidy. I recommend them to everyone I know."],
                    ['name' => 'Thomas & Mary C.',  'town' => 'Darien, CT',      'rating' => 5, 'review' => "BUILTWELL remodeled two bathrooms in our Colonial — the master suite and the hall bath. Both came out beautifully with custom tile work, frameless glass, and modern fixtures that complement the home's traditional character.\n\nTheir design sense was excellent. They suggested layouts and materials we hadn't considered, and the results speak for themselves. Professional from quote to completion."],
                    ['name' => 'Rachel & Dan W.',   'town' => 'Trumbull, CT',    'rating' => 5, 'review' => "We hired BUILTWELL for a two-story addition — master suite upstairs, family room below. The structural work was complex but they handled every detail, from steel beam installation to matching the existing roofline.\n\nThe addition looks like it was always part of the house. Our home feels twice as large and the craftsmanship is evident in every corner. Outstanding experience from start to finish."],
                ],
            ],
        ],
        // 4. video_testimonial_placeholder
        [
            'type' => 'video_testimonial_placeholder',
            'data' => [
                'headline'    => 'Hear It Directly From Our Clients',
                'subheadline' => 'Video testimonials coming soon. In the meantime, our written reviews speak for themselves.',
                'videos'      => [],
                'placeholder' => true,
            ],
        ],
        // 5. service_area_trust
        [
            'type' => 'service_area_trust',
            'data' => [
                'headline' => 'Trusted Across Fairfield & New Haven Counties',
                'content'  => "BUILTWELL proudly serves homeowners throughout Fairfield County — including Greenwich, Stamford, Westport, Norwalk, Darien, Fairfield, Trumbull, and Shelton — and New Haven County — including Orange, Milford, Branford, Hamden, Guilford, Wallingford, and North Haven.\n\nEvery project is backed by our written workmanship warranty, full licensing and insurance, and a commitment to transparent, fixed-price proposals. We don't cut corners and we don't disappear after the final payment. Our reputation depends on every single project.",
            ],
        ],
        // 6. cta_split_form
        [
            'type' => 'cta_split_form',
            'data' => [
                'headline'    => 'Ready to Work With a 5-Star Contractor?',
                'subheadline' => 'Tell us about your project and we\'ll schedule a free, no-obligation consultation.',
                'form_fields' => ['name', 'email', 'phone', 'project_type', 'message'],
                'cta'         => ['label' => 'Request Consultation', 'url' => '/free-consultation/'],
            ],
        ],
    ]
);


// ============================================================
// 3. /contact
// ============================================================
seedPage(
    '/contact',
    'contact',
    'contact',
    [
        'title'       => 'Contact BUILTWELL | Connecticut Home Remodeling',
        'description' => 'Get in touch with BUILTWELL for a free remodeling consultation. Offices in Fairfield and New Haven County, CT. Licensed, insured, and ready to build.',
    ],
    [
        // 1. contact_hero
        [
            'type' => 'hero',
            'data' => [
                'headline'         => 'Let\'s Talk About Your Project',
                'subheadline'      => 'Whether you\'re planning a kitchen remodel, bathroom renovation, or full home addition — we\'re here to help.',
                'background_image' => null,
                'background_video' => null,
                'overlay_opacity'  => 0.45,
            ],
        ],
        // 2. contact_split_layout
        [
            'type' => 'contact_split_layout',
            'data' => [
                'left' => [
                    'headline' => 'Why Homeowners Trust BUILTWELL',
                    'content'  => 'We believe every homeowner deserves a contractor who shows up, communicates clearly, and delivers quality work — on time and on budget.',
                    'bullets'  => [
                        'Licensed & insured in the State of Connecticut',
                        'Written workmanship warranty on every project',
                        'Transparent, fixed-price proposals — no hidden fees',
                        'One dedicated project manager from start to finish',
                        'Daily communication and photo updates',
                    ],
                ],
                'right' => [
                    'form_placeholder' => true,
                    'form_fields'      => ['name', 'email', 'phone', 'address', 'project_type', 'message'],
                    'submit_label'     => 'Send Message',
                ],
            ],
        ],
        // 3. office_info_cards
        [
            'type' => 'office_info_cards',
            'data' => [
                'headline' => 'Our Offices',
                'offices'  => [
                    [
                        'name'    => 'Fairfield County Office',
                        'phone'   => '(203) 555-0100',
                        'email'   => 'fairfield@builtwellct.com',
                        'license' => 'CT HIC #0000000',
                        'address' => 'Fairfield County, CT',
                    ],
                    [
                        'name'    => 'New Haven County Office',
                        'phone'   => '(203) 555-0200',
                        'email'   => 'newhaven@builtwellct.com',
                        'license' => 'CT HIC #0000000',
                        'address' => 'Orange, CT',
                    ],
                ],
            ],
        ],
        // 4. map_embed
        [
            'type' => 'map_embed',
            'data' => [
                'headline'    => 'Our Service Area',
                'placeholder' => true,
                'description' => 'BUILTWELL serves homeowners across Fairfield and New Haven Counties in Connecticut.',
                'center'      => ['lat' => 41.18, 'lng' => -73.19],
                'zoom'        => 10,
            ],
        ],
        // 5. faq_mini
        [
            'type' => 'faq_list',
            'data' => [
                'headline' => 'Common Questions',
                'faqs'     => [
                    [
                        'question' => 'How do I get a free estimate?',
                        'answer'   => 'Fill out the form above or call either of our offices. We\'ll schedule a visit to your home, review your project goals, and provide a detailed, fixed-price proposal within a few business days.',
                    ],
                    [
                        'question' => 'How soon can my project start?',
                        'answer'   => 'Project timelines depend on scope, permits, and material lead times. Most projects begin within 2–4 weeks of signing. We\'ll provide a clear start date and projected completion date in your proposal.',
                    ],
                    [
                        'question' => 'Do you handle permits and inspections?',
                        'answer'   => 'Yes. BUILTWELL manages all permits, engineering (when required), and inspections on your behalf. We work directly with local building departments so you don\'t have to.',
                    ],
                ],
            ],
        ],
        // 6. cta_dark_band
        [
            'type' => 'cta_dark_band',
            'data' => [
                'headline'    => 'Prefer to Talk Now?',
                'subheadline' => 'Our team is available Monday through Friday, 8am to 5pm.',
                'cta'         => ['label' => 'Call Now', 'url' => 'tel:+12035550100'],
            ],
        ],
    ]
);


// ============================================================
// 4. /subcontractors
// ============================================================
seedPage(
    '/subcontractors',
    'subcontractors',
    'subcontractors',
    [
        'title'       => 'Subcontractor Partnerships | BUILTWELL Connecticut',
        'description' => 'BUILTWELL partners with licensed, experienced trades across Connecticut. Learn about our requirements, benefits, and how to apply for subcontractor work.',
    ],
    [
        // 1. subcontractor_hero
        [
            'type' => 'hero',
            'data' => [
                'headline'         => 'Work With BUILTWELL',
                'subheadline'      => 'We partner with skilled trades who value quality and accountability.',
                'background_image' => null,
                'background_video' => null,
                'overlay_opacity'  => 0.45,
            ],
        ],
        // 2. requirements_section
        [
            'type' => 'requirements_section',
            'data' => [
                'headline'    => 'What We Require',
                'description' => 'BUILTWELL holds every trade partner to the same standard we hold ourselves. If you meet these requirements, we want to hear from you.',
                'requirements' => [
                    ['title' => 'Licensed & Insured',           'description' => 'Must carry valid Connecticut trade licenses and general liability insurance with a minimum $1M policy.'],
                    ['title' => '5+ Years Experience',           'description' => 'Minimum five years of verifiable experience in your trade, with residential remodeling project history.'],
                    ['title' => 'Clean Job Sites',               'description' => 'Daily cleanup is non-negotiable. Our clients expect tidy, organized work areas throughout the project.'],
                    ['title' => 'Professional Communication',    'description' => 'Responsive to calls and emails, clear on scheduling, and respectful in all client interactions.'],
                    ['title' => 'CT Code Knowledge',             'description' => 'Thorough understanding of Connecticut building codes, inspection requirements, and permit processes.'],
                ],
            ],
        ],
        // 3. benefits_grid
        [
            'type' => 'benefits_grid',
            'data' => [
                'headline' => 'Why Trade Partners Choose BUILTWELL',
                'benefits' => [
                    ['title' => 'Consistent Work',                'description' => 'Steady pipeline of residential remodeling projects across Fairfield and New Haven Counties year-round.'],
                    ['title' => 'Clear Scopes of Work',           'description' => 'Every project comes with detailed scopes, material specs, and expectations documented before you start.'],
                    ['title' => 'On-Time Payments',               'description' => 'We pay on schedule, every time. Net-30 terms with no games, no delays, and no excuses.'],
                    ['title' => 'Professional Project Management', 'description' => 'Dedicated project managers coordinate scheduling, materials, and client communication so you can focus on your trade.'],
                    ['title' => 'Long-Term Partnership',          'description' => 'We build relationships, not transactions. Our best trade partners have worked with us for years and are treated as part of the team.'],
                ],
            ],
        ],
        // 4. application_form_section
        [
            'type' => 'application_form_section',
            'data' => [
                'headline'    => 'Apply to Work With Us',
                'description' => 'Fill out the form below and our operations team will review your application within 5 business days.',
                'form_fields' => [
                    ['name' => 'company_name',    'label' => 'Company Name',          'type' => 'text',     'required' => true],
                    ['name' => 'contact_name',    'label' => 'Contact Name',          'type' => 'text',     'required' => true],
                    ['name' => 'phone',           'label' => 'Phone Number',          'type' => 'tel',      'required' => true],
                    ['name' => 'email',           'label' => 'Email Address',         'type' => 'email',    'required' => true],
                    ['name' => 'trade_type',      'label' => 'Trade Type',            'type' => 'select',   'required' => true, 'options' => ['Electrical', 'Plumbing', 'HVAC', 'Carpentry', 'Tile & Flooring', 'Painting', 'Roofing', 'Drywall', 'Masonry', 'Other']],
                    ['name' => 'license_number',  'label' => 'CT License #',          'type' => 'text',     'required' => true],
                    ['name' => 'years_in_business', 'label' => 'Years in Business',   'type' => 'number',   'required' => true],
                    ['name' => 'coi_upload',      'label' => 'Upload Certificate of Insurance', 'type' => 'file', 'required' => false],
                ],
                'submit_label' => 'Submit Application',
                'form_placeholder' => true,
            ],
        ],
        // 5. cta_light_section
        [
            'type' => 'cta_light_section',
            'data' => [
                'headline'    => 'Build With a Team That Builds It Right',
                'subheadline' => 'BUILTWELL is growing and we\'re looking for trade partners who share our commitment to quality, communication, and professionalism.',
                'cta'         => ['label' => 'Call Our Office', 'url' => 'tel:+12035550100'],
            ],
        ],
    ]
);


// ============================================================
// FINAL SUMMARY
// ============================================================
echo "\n========== SUMMARY ==========\n";

foreach (['/projects', '/testimonials', '/contact', '/subcontractors'] as $path) {
    $page = Page::where('full_path', $path)->first();
    if (!$page) {
        echo "{$path} — NOT FOUND (error)\n";
        continue;
    }
    $secs = Section::where('page_id', $page->id)->orderBy('sort_order')->get();
    echo "{$path} (ID:{$page->id}) — Status: {$page->status} — Sections: {$secs->count()}\n";
    echo "  Types: " . $secs->pluck('type')->implode(' → ') . "\n";
}

echo "\nDone! All 4 pages built with unique layouts.\n";
