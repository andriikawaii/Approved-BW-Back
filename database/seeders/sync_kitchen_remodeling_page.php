<?php

use App\Http\Controllers\Api\PageController as ApiPageController;
use App\Models\Page;
use App\Models\Section;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

$pagePayload = [
    'full_path' => '/kitchen-remodeling',
    'template_key' => 'service_global',
    'status' => 'published',
    'published_at' => now(),
    'seo_title' => 'Kitchen Remodeling in Connecticut | BuiltWell CT',
    'seo_description' => 'Kitchen remodeling in Connecticut from $25K to $250K+. Custom cabinets, countertops, tile, and flooring. Licensed CT contractor. Get a free estimate.',
    'canonical_url' => null,
    'schema_overrides' => [
        'description' => 'Professional kitchen remodeling in Connecticut including cabinetry, countertops, tile, flooring, electrical, plumbing, and all permit coordination. Serving Fairfield and New Haven Counties.',
        'areaServed' => [
            ['@type' => 'AdministrativeArea', 'name' => 'Fairfield County, Connecticut'],
            ['@type' => 'AdministrativeArea', 'name' => 'New Haven County, Connecticut'],
        ],
        'serviceType' => 'Kitchen Remodeling',
    ],
];

$rich = static fn (
    string $eyebrow,
    string $title,
    string $content,
    array $overrides = []
): array => array_merge([
    'eyebrow' => $eyebrow,
    'title' => $title,
    'highlight_text' => null,
    'content' => $content,
    'image' => null,
    'image_alt' => null,
    'image_position' => 'right',
    'cta' => null,
    'align' => 'left',
    'variant' => 'default',
    'style_variant' => 'prose',
    'surface' => 'white',
    'container_width' => 'wide',
    'spacing' => 'normal',
], $overrides);

$sections = [
    [
        'type' => 'service_hero',
        'data' => [
            'title' => 'Kitchen Remodeling in Connecticut',
            'subtitle' => 'Kitchen remodeling in Connecticut costs $25,000 to $250,000+, with most projects completed in 6 to 12 weeks. Custom cabinets, countertops, tile, plumbing, and electrical, all handled in-house by our licensed crews.',
            'background_image' => '/images/headers/kitchen-remodeling-header.jpg',
            'primary_cta' => ['label' => 'Get Your Free Estimate', 'url' => '#contact'],
            'secondary_cta' => null,
            'overlay_opacity' => 0.5,
        ],
    ],
    [
        'type' => 'trust_bar',
        'data' => [
            'variant' => 'hero_stats',
            'items' => [
                ['icon' => 'clock', 'label' => 'Years of Experience', 'value' => '15+'],
                ['icon' => 'check', 'label' => 'Completed Projects', 'value' => '100+'],
                ['icon' => 'star', 'label' => 'Google Rating', 'value' => '4.9', 'url' => 'https://www.google.com/maps/search/?api=1&query=BuiltWell+CT,+206A+Boston+Post+Road,+Orange,+CT+06477'],
                ['icon' => 'shield', 'label' => 'Fully Bonded & Insured', 'value' => null],
            ],
        ],
    ],
    [
        'type' => 'rich_text',
        'data' => $rich(
            "Connecticut's Kitchen Remodeling Contractor",
            'Full-Service Kitchen Remodeling Across Connecticut',
            implode("\n\n", [
                "BuiltWell CT handles kitchen remodeling across Fairfield and New Haven Counties - from straightforward cabinet and countertop replacements to full gut renovations that reconfigure layouts, move plumbing, and upgrade electrical. Every project is managed by our in-house team, not subcontracted out. We hold a Connecticut Home Improvement Contractor license (#0668405) and carry full liability and workers' compensation insurance.",
                "Our work spans the full range of Connecticut homes - colonial and cape cod construction in Fairfield County, coastal and older-stock homes throughout New Haven County. We understand the permit requirements that vary by municipality, the structural conditions common to homes built before 1980, and the material choices that hold up to Connecticut's climate over time.",
            ]),
            ['highlight_text' => 'Across Connecticut']
        ),
    ],
    [
        'type' => 'service_intro_split',
        'data' => [
            'title' => 'What Is Included in a Kitchen Remodel',
            'content' => implode("\n\n", [
                "A full kitchen remodel covers every trade from demolition through final paint: cabinetry, countertops, tile, flooring, electrical, plumbing, appliance installation, and all required permits and inspections.",
                "We start with demolition and removal of existing cabinets, countertops, and flooring. Before any new material goes in, we assess what's behind the walls. Connecticut homes built before the mid-1990s regularly turn up undersized electrical panels, deteriorated plumbing, water damage inside wall cavities, or structural framing that needs attention before the renovation can proceed properly.",
                "New cabinetry installation includes soft-close hardware and adjustable shelving as standard. Countertop fabrication and installation, backsplash tile, and underlayment preparation are all part of the project. Electrical scope covers new circuits for refrigerators, ranges, dishwashers, and microwaves, plus under-cabinet lighting and updated outlet and switch locations.",
                "Plumbing includes sink and dishwasher connections, garbage disposal installation, and any relocated water lines. We coordinate appliance delivery and installation so you're not managing that separately.",
                "Interior painting, drywall patching, and daily cleanup are included throughout. We install dust barriers to protect the rest of your home during construction. The project closes with a final walkthrough where we go through every detail together.",
            ]),
            'image_main' => '/services/kitchen-remodeling-ct.jpg',
            'image_secondary' => '/portfolio/builtwell-contractor-client-consultation-ct.jpeg',
            'bullet_points' => [
                ['text' => 'Cabinetry'],
                ['text' => 'Countertops & Tile'],
                ['text' => 'Electrical'],
                ['text' => 'Plumbing'],
                ['text' => 'Appliances'],
                ['text' => 'Flooring'],
                ['text' => 'Demolition & Prep'],
                ['text' => 'Painting & Finish'],
            ],
        ],
    ],
    [
        'type' => 'before_after_grid',
        'data' => [
            'title' => 'Recent Kitchen Remodeling Projects',
            'subtitle' => "The projects below give you a sense of what we've completed in Connecticut recently.",
            'projects' => [
                [
                    'before_image' => '/images/before-after/kitchen-before-after-1.jpg',
                    'after_image' => '/images/before-after/kitchen-before-after-1.png',
                    'location' => 'Kitchen Remodeling in New Canaan',
                    'description' => 'A New Canaan family wanted to open up a kitchen closed off by a load-bearing wall. We engineered a beam solution, installed white shaker cabinets with a quartz island, and extended hardwood flooring into the new open layout.',
                    'testimonial_quote' => '"BuiltWell made it straightforward. Now we can\'t imagine how we lived before." - New Canaan Homeowner',
                ],
                [
                    'before_image' => '/images/before-after/kitchen-before-after-2.jpg',
                    'after_image' => '/images/before-after/kitchen-before-after-2.png',
                    'location' => 'Kitchen Remodeling in Milford',
                    'description' => 'A Milford homeowner needed to replace dated 1990s oak cabinets and laminate countertops. We removed the peninsula, installed soft-close cabinetry with quartz countertops and subway tile, and extended LVP flooring into the dining room.',
                    'testimonial_quote' => '"They showed up when they said they would, cleaned up every day, and the kitchen turned out better than I imagined." - Milford Homeowner',
                ],
                [
                    'before_image' => '/images/before-after/kitchen-before-after-3.jpg',
                    'after_image' => '/images/before-after/kitchen-before-after-3.png',
                    'location' => 'Kitchen Remodeling in Westport',
                    'description' => 'A Westport couple wanted a brighter, more functional kitchen. We gutted the space, reconfigured the layout to add a center island, installed custom cabinetry with quartz countertops, and added recessed lighting throughout.',
                    'testimonial_quote' => '"We cook together every night now. The island changed everything about how we use the kitchen." - Westport Homeowner',
                ],
            ],
        ],
    ],
    [
        'type' => 'pricing_table',
        'data' => [
            'title' => 'Kitchen Remodeling Cost in Connecticut',
            'subtitle' => 'Kitchen remodeling in Connecticut ranges from $25,000 for a basic cabinet and countertop update to $250,000 or more for a fully custom renovation with structural changes and imported materials.',
            'columns' => ['Scope', 'Cost Range', "What's Typically Included"],
            'rows' => [
                ['label' => 'Basic', 'price' => '$25,000-$50,000', 'notes' => 'Cabinet refacing or stock cabinets, new countertops, updated appliances, paint'],
                ['label' => 'Mid-Range', 'price' => '$50,000-$90,000', 'notes' => 'New cabinets, stone countertops, new flooring, appliances, updated lighting'],
                ['label' => 'High-End', 'price' => '$90,000-$150,000', 'notes' => 'Custom or semi-custom cabinets, layout changes, premium appliances, full finishes'],
                ['label' => 'Full Custom', 'price' => '$150,000-$250,000+', 'notes' => 'Fully custom cabinetry, structural changes, imported materials, high-end fixtures'],
            ],
        ],
    ],
    [
        'type' => 'cta_block',
        'data' => [
            'eyebrow' => null,
            'title' => 'Ready to Begin Your Kitchen Remodel?',
            'subtitle' => 'Great kitchen remodeling starts with the right team.',
            'button' => ['label' => 'Get Your Free Estimate', 'url' => '#contact'],
            'subtext' => 'On-site or remote via Google Meet. No charge, no obligation.',
            'variant' => 'dark',
        ],
    ],
    [
        'type' => 'rich_text',
        'data' => $rich(
            'Local Knowledge',
            'Why Connecticut Kitchen Remodeling Requires Local Expertise',
            implode("\n\n", [
                'Connecticut homes present construction conditions that contractors without local experience regularly miss.',
                'Kitchens built before 2000 frequently have electrical panels that cannot support modern appliance loads. A kitchen with a new refrigerator, induction range, dishwasher, microwave, and under-cabinet lighting may draw more power than the original panel was designed to handle. We assess panel capacity early and address it in the scope if needed. Homes built in the 1960s through 1980s may have asbestos in floor tiles or lead paint on existing cabinets. Both require proper abatement by certified professionals before renovation work begins. We identify these conditions during the assessment and handle abatement coordination as part of the project.',
                'Coastal kitchens in towns like Westport, Milford, Branford, Guilford, and Madison deal with elevated humidity and salt air. Material selection matters in these environments. Solid wood cabinet doors, certain countertop sealants, and some flooring products perform differently within a mile of Long Island Sound than they do in an inland location. We account for this when making recommendations.',
                "Older homes in Greenwich, Guilford, and New Haven may have balloon framing, stone foundations, or plaster walls. When a kitchen layout change involves removing or modifying a wall in one of these homes, the structural approach differs from a standard platform-framed house. We've worked in all of these conditions and know what to expect.",
                'Permitting varies significantly by municipality. Some towns require separate electrical, plumbing, and building permits, each with their own inspections. Others consolidate under a single application. Towns like Greenwich, New Canaan, Ridgefield, and Guilford have historic district requirements that add a design review step for certain projects. We handle all permit applications and inspection coordination regardless of which town your home is in. Connecticut homeowners may also qualify for Eversource rebates on ENERGY STAR appliances and lighting upgrades installed during a kitchen remodel - visit energizect.com for current programs.',
            ]),
            ['highlight_text' => 'Local Expertise']
        ),
    ],
    [
        'type' => 'process_steps',
        'data' => [
            'title' => 'Our Kitchen Remodeling Process',
            'subtitle' => 'Every kitchen remodel follows the same five-step process. This structure keeps projects on schedule, on budget, and clearly communicated throughout.',
            'steps' => [
                ['title' => 'Consultation', 'short' => 'On-site or remote walkthrough', 'description' => 'We visit your home or connect via Google Meet or Zoom to discuss your goals, assess the space, and answer your questions. No charge. No obligation. We look at the layout, note the existing electrical and plumbing conditions, and get a clear picture of what you want to accomplish.'],
                ['title' => 'Planning', 'short' => 'Clear scope and line items', 'description' => "You receive a clear written proposal covering exactly what's included, how long it will take, and what it costs. Line items are specific. We break out cabinetry, countertops, tile, electrical, plumbing, flooring, and permits separately so you understand exactly where the budget is going."],
                ['title' => 'Selections', 'short' => 'Materials and lead times', 'description' => 'We guide you through material choices with options at different price points. We communicate lead times clearly so selections are made on schedule before construction begins. If a material has a long lead time, we flag that immediately.'],
                ['title' => 'Build', 'short' => 'Daily updates and clean jobsite', 'description' => 'Construction begins on the agreed schedule. You receive daily updates on progress, a clean job site at the end of every workday, and crews who arrive when scheduled. If something unexpected comes up behind a wall, we contact you that day and present your options.'],
                ['title' => 'Walkthrough', 'short' => 'Final review before closeout', 'description' => 'We walk through the finished project together. We check every cabinet, every drawer, every tile, every light fixture. If anything needs attention, we address it before calling the project complete. Your written acceptance at the final walkthrough is the last step.'],
            ],
        ],
    ],
    [
        'type' => 'feature_grid',
        'data' => [
            'title' => 'Project Timeline',
            'subtitle' => 'Most kitchen remodels take ten to twenty-two weeks from signed proposal to final walkthrough.',
            'items' => [
                ['icon' => 'file-text', 'title' => 'Planning & Design', 'description' => '1-2 Weeks. Consultation, measurements, material selections, and detailed proposal.'],
                ['icon' => 'clock', 'title' => 'Cabinet Lead Time', 'description' => '4-12 Weeks. Custom orders require the longest lead time. Semi-custom options arrive in four to six weeks.'],
                ['icon' => 'hammer', 'title' => 'Construction', 'description' => '6-12 Weeks. Demo, rough-ins, cabinetry, countertops, tile, flooring, and fixtures.'],
                ['icon' => 'shield-check', 'title' => 'Final Touches', 'description' => '1 Week. Hardware, final connections, punch list, and walkthrough.'],
            ],
        ],
    ],
    [
        'type' => 'areas_served',
        'data' => [
            'eyebrow' => 'Where We Work',
            'title' => 'Kitchen Remodeling Across Two Counties',
            'highlight_text' => 'Two Counties',
            'subtitle' => 'We provide kitchen remodeling throughout Fairfield and New Haven Counties, with dedicated teams serving both regions.',
            'note_html' => 'Not sure if we cover your area? <a href="/contact/">Contact our Connecticut remodeling team</a> and we&#39;ll let you know.',
            'counties' => [
                [
                    'name' => 'Fairfield County',
                    'image' => '/images/areas/fairfield-county.jpg',
                    'url' => '/fairfield-county/',
                    'phone' => '(203) 919-9616',
                    'description' => 'Kitchen remodeling across Fairfield County, from custom chef kitchens in Greenwich and Westport to practical layout upgrades in Norwalk and Stamford. We handle every phase of the build in house.',
                    'towns' => ['Greenwich', 'Stamford', 'Norwalk', 'Westport', 'Darien', 'New Canaan', 'Fairfield', 'Ridgefield', 'Trumbull'],
                    'extra_towns' => ['Bethel', 'Bridgeport', 'Brookfield', 'Danbury', 'Easton', 'Monroe', 'New Fairfield', 'Newtown', 'Redding', 'Shelton', 'Sherman', 'Stratford', 'Weston', 'Wilton'],
                    'town_links' => ['Greenwich' => '/fairfield-county/greenwich-ct/', 'Westport' => '/fairfield-county/westport-ct/', 'Trumbull' => '/fairfield-county/'],
                    'cta_label' => 'Learn more about Fairfield County',
                ],
                [
                    'name' => 'New Haven County',
                    'image' => '/images/areas/new-haven-county.jpg',
                    'url' => '/new-haven-county/',
                    'phone' => '(203) 466-9148',
                    'description' => 'Kitchen remodeling across New Haven County, from our Orange, CT office. We modernize galley kitchens, open up floor plans, and deliver full gut renovations tailored to the local housing stock.',
                    'towns' => ['Orange', 'New Haven', 'Hamden', 'Branford', 'Guilford', 'Madison', 'Woodbridge', 'Milford', 'Cheshire'],
                    'extra_towns' => ['Ansonia', 'Beacon Falls', 'Bethany', 'Derby', 'East Haven', 'Meriden', 'Middlebury', 'Naugatuck', 'North Branford', 'North Haven', 'Oxford', 'Prospect', 'Seymour', 'Southbury', 'Wallingford', 'Waterbury', 'West Haven', 'Wolcott'],
                    'town_links' => ['Orange' => '/new-haven-county/orange-ct/', 'New Haven' => '/new-haven-county/new-haven-ct/', 'Madison' => '/new-haven-county/madison-ct/'],
                    'cta_label' => 'Learn more about New Haven County',
                ],
            ],
        ],
    ],
    [
        'type' => 'faq_list',
        'data' => [
            'title' => 'Kitchen Remodeling Questions',
            'items' => [
                ['question' => 'Do I need a permit for a kitchen remodel in Connecticut?', 'answer' => "Yes. Most kitchen remodeling projects in Connecticut require permits, particularly when the work involves electrical, plumbing, or structural modifications. Even cosmetic renovations that include new electrical outlets, relocated fixtures, or new plumbing connections will typically require a permit in most Connecticut towns. Some towns require separate permits for electrical, plumbing, and building work, each with their own inspections. We handle all permit applications and coordinate every required inspection as part of the project; you don't need to manage that process yourself. If your town has a historic district and your property falls within it, we'll identify that during the planning phase and account for any additional review requirements."],
                ['question' => 'How long does a kitchen remodel take in CT, including cabinet lead times?', 'answer' => "Active construction on a Connecticut kitchen remodel takes six to twelve weeks depending on the scope of work. However, cabinet lead time needs to be added to that figure. Custom cabinets ordered in Connecticut typically require eight to twelve weeks to be manufactured and delivered before construction can begin. Semi-custom cabinets, which offer many of the same configuration options at a lower cost, reduce that window to four to six weeks. Stock cabinets are available much faster and can work well for basic to mid-range renovations. During the planning phase, we finalize your cabinet selections and order immediately so the lead time runs concurrently with other pre-construction work. Your written proposal includes a complete project schedule that accounts for all lead times."],
                ['question' => 'Can I do the design consultation remotely before the on-site visit?', 'answer' => "Yes. We offer initial consultations via Google Meet or Zoom. You can share photos and measurements of your current kitchen, walk us through what you're hoping to accomplish, and get preliminary cost guidance, all before scheduling an in-person visit. Many homeowners find this useful for narrowing down scope and confirming budget ranges before committing to an on-site assessment. The remote consultation is free and carries no obligation. If you decide to move forward, we schedule the in-person visit to take detailed measurements and assess conditions that a remote conversation can't fully capture."],
                ['question' => 'Can I stay in my home during a kitchen remodel?', 'answer' => "Yes, most homeowners remain in their homes throughout the remodeling process. We install dust barriers to contain debris and protect the rest of your home, and we clean up at the end of every workday. You will need a plan for temporary cooking and food preparation during the construction period; most homeowners use a microwave, a hot plate, or order out, and we can give you a realistic sense of how long you'll be without a functioning kitchen based on your specific project scope. Access to a second bathroom for hand washing is helpful but not required. We'll walk through what to expect from daily life during the project before construction begins so there are no surprises."],
                ['question' => 'What does a full kitchen remodel include?', 'answer' => 'A full-scope kitchen remodel with us includes demolition and removal of all existing cabinetry, countertops, and flooring; structural assessment and any required framing or structural modifications; new cabinetry installation with hardware; countertop fabrication and installation; backsplash tile work; new flooring with proper underlayment; all electrical work including new circuits and lighting; all plumbing including sink, dishwasher, and disposal connections; appliance coordination and installation; interior painting within the kitchen space; all drywall patching and finish work; permit applications and inspection coordination; daily cleanup throughout the project; and a final walkthrough. Abatement of hazardous materials, if asbestos or lead is discovered during demolition, is handled as part of the project with proper certification and documentation.'],
                ['question' => 'How do I choose between custom, semi-custom, and stock cabinets?', 'answer' => "Stock cabinets come in fixed sizes and finishes and are the most affordable option, typically available within one to two weeks. They work well for standard kitchen layouts where the existing dimensions align with available stock sizes. Semi-custom cabinets offer the same build quality as custom with more size, finish, and hardware options, and typically arrive in four to six weeks. Custom cabinets are built to your exact specifications - any size, any finish, any interior configuration - and require eight to twelve weeks for manufacturing. During our planning phase, we'll walk you through all three options with pricing so you can make an informed decision based on your budget, timeline, and design goals."],
                ['question' => 'Do you offer financing for kitchen remodeling projects?', 'answer' => 'Yes. We offer flexible financing through GreenSky, which allows you to get approved in about 60 seconds and start your project right away. Financing options include low monthly payments and promotional periods depending on the plan you choose. We can walk you through the options during your consultation so you have a clear picture of both the project cost and the monthly payment before you commit to anything.'],
                ['question' => 'What happens if you find unexpected issues behind the walls during demolition?', 'answer' => "It happens regularly in Connecticut homes, especially those built before 2000. Common findings include outdated electrical wiring, deteriorated plumbing, water damage, asbestos floor tiles, or lead paint. When we discover something unexpected, we contact you that same day, explain what we found, present your options with clear costs, and wait for your approval before proceeding. We don't make decisions about your home without your input. Any additional work is documented in a change order with a specific cost and timeline impact so there are no surprises on the final invoice."],
            ],
        ],
    ],
    [
        'type' => 'trust_bar',
        'data' => [
            'variant' => 'service_trust_strip',
            'items' => [
                ['icon' => 'star', 'label' => 'Google Rating', 'value' => '4.9', 'url' => 'https://www.google.com/search?q=builtwell+ct+reviews'],
                ['icon' => 'check', 'label' => 'Houzz', 'value' => 'Trusted on Houzz', 'url' => 'https://www.houzz.com/professionals/general-contractors/builtwell-ct'],
                ['icon' => 'calendar', 'label' => 'CT HIC License', 'value' => '#0668405', 'url' => 'https://www.elicense.ct.gov/Lookup/LicenseLookup.aspx'],
                ['icon' => 'check', 'label' => 'Angi', 'value' => 'Verified on Angi', 'url' => 'https://www.angi.com/companylist/us/ct/orange/builtwell-ct-reviews-'],
            ],
        ],
    ],
    [
        'type' => 'logo_strip',
        'data' => [
            'title' => 'Materials We Stand Behind',
            'subtitle' => 'We work exclusively with proven brands trusted by contractors and homeowners across Connecticut.',
            'items' => [
                ['name' => 'KraftMaid', 'logo' => '/images/brands/kraftmaid.svg', 'url' => 'https://www.kraftmaid.com'],
                ['name' => 'Kohler', 'logo' => '/images/brands/kohler.svg', 'url' => 'https://www.kohler.com'],
                ['name' => 'Cambria', 'logo' => '/images/brands/cambria.svg', 'url' => 'https://www.cambriausa.com'],
                ['name' => 'Moen', 'logo' => '/images/brands/moen.svg', 'url' => 'https://www.moen.com'],
                ['name' => 'Delta', 'logo' => '/images/brands/delta.svg', 'url' => 'https://www.deltafaucet.com'],
                ['name' => 'Wellborn', 'logo' => '/images/brands/wellborn.svg', 'url' => 'https://www.wellborn.com'],
                ['name' => 'Wolf Cabinets', 'logo' => '/images/brands/wolf-cabinets.svg', 'url' => 'https://www.wolfcabinets.com'],
                ['name' => 'Fabuwood', 'logo' => '/images/brands/fabuwood.svg', 'url' => 'https://www.fabuwood.com'],
                ['name' => 'Silestone', 'logo' => '/images/brands/silestone.svg', 'url' => 'https://www.cosentino.com/silestone/'],
                ['name' => 'Caesarstone', 'logo' => '/images/brands/caesarstone.svg', 'url' => 'https://www.caesarstone.com'],
            ],
        ],
    ],
    [
        'type' => 'lead_form',
        'data' => [
            'eyebrow' => 'GET IN TOUCH',
            'title' => 'Ready to Start Your Kitchen Remodeling Project?',
            'title_highlight' => 'Remodeling Project',
            'subtitle' => 'Tell us about your project. We respond within one business day. No obligation.',
            'images' => [
                ['image' => '/team/builtwell-owner-handshake-client-ct-02.jpg', 'alt' => 'BuiltWell CT owner meeting with a Connecticut homeowner for a remodeling consultation.'],
                ['image' => '/portfolio/builtwell-job-site-aerial-ct.jpg', 'alt' => 'BuiltWell CT owner meeting homeowner for a free consultation.'],
            ],
            'fields' => [
                ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'required' => true, 'placeholder' => 'Your full name'],
                ['name' => 'phone', 'label' => 'Phone', 'type' => 'tel', 'required' => true, 'placeholder' => '(203) 000-0000'],
                ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'required' => true, 'placeholder' => 'you@email.com'],
                ['name' => 'zip', 'label' => 'Zip Code', 'type' => 'text', 'required' => true, 'placeholder' => '06477'],
                ['name' => 'services', 'label' => 'Services Needed', 'type' => 'checkbox_group', 'required' => true, 'options' => [['label' => 'Kitchen Remodeling', 'value' => 'Kitchen Remodeling'], ['label' => 'Bathroom Remodeling', 'value' => 'Bathroom Remodeling'], ['label' => 'Basement Finishing', 'value' => 'Basement Finishing'], ['label' => 'Flooring Installation', 'value' => 'Flooring'], ['label' => 'Home Additions', 'value' => 'Home Addition'], ['label' => 'Interior Painting', 'value' => 'Interior Painting'], ['label' => 'Interior Carpentry', 'value' => 'Interior Carpentry'], ['label' => 'Attic Conversions', 'value' => 'Attic Conversion'], ['label' => 'Decks & Porches', 'value' => 'Decks & Porches'], ['label' => 'Design & Planning', 'value' => 'Design & Planning'], ['label' => 'Comfort & Accessibility', 'value' => 'Comfort & Accessibility'], ['label' => 'Other', 'value' => 'Other']]],
                ['name' => 'best_time', 'label' => 'Best Time to Contact', 'type' => 'select', 'required' => true, 'options' => [['label' => 'Morning (8am - 12pm)', 'value' => 'Morning (8am - 12pm)'], ['label' => 'Afternoon (12pm - 4pm)', 'value' => 'Afternoon (12pm - 4pm)'], ['label' => 'Evening (4pm - 6pm)', 'value' => 'Evening (4pm - 6pm)'], ['label' => 'Anytime', 'value' => 'Anytime']]],
                ['name' => 'contact_method', 'label' => 'Preferred Contact Method', 'type' => 'radio_group', 'required' => true, 'options' => [['label' => 'Call', 'value' => 'call'], ['label' => 'Text', 'value' => 'text'], ['label' => 'Email', 'value' => 'email']]],
                ['name' => 'message', 'label' => 'Tell Us About Your Project', 'type' => 'textarea', 'required' => false, 'placeholder' => 'Describe your project, timeline, budget range, or any questions...'],
                ['name' => 'files', 'label' => 'Upload Photos', 'type' => 'file', 'required' => false, 'help_text' => 'JPEG, PNG, or HEIC. Multiple files allowed.'],
            ],
            'submit_label' => 'Get Your Free Estimate',
            'consent_text' => 'We respond within 24 hours. No spam, no obligation.',
        ],
    ],
    [
        'type' => 'rich_text',
        'data' => $rich(
            '',
            'Flexible Financing Available',
            'Get approved in about 60 seconds and start your project today.',
            ['image' => '/images/brands/greensky.svg', 'image_alt' => 'GreenSky financing logo.', 'image_position' => 'left', 'cta' => ['label' => 'Check Financing Options', 'url' => 'https://www.greensky.com'], 'style_variant' => 'financing_strip', 'spacing' => 'compact']
        ),
    ],
    [
        'type' => 'project_highlights',
        'data' => [
            'eyebrow' => 'Related Services',
            'title' => 'You May Also Need',
            'items' => [
                ['title' => 'Bathroom Remodeling', 'description' => 'Complete bathroom renovations including tile, vanities, showers, tubs, and plumbing upgrades throughout Connecticut.', 'image' => '/services/bathroom-remodeling-ct.jpg', 'url' => '/bathroom-remodeling/', 'tag' => null],
                ['title' => 'Flooring', 'description' => 'Hardwood, luxury vinyl plank, tile, and engineered wood flooring installation with expert subfloor preparation.', 'image' => '/services/flooring-installation-ct.jpg', 'url' => '/flooring/', 'tag' => null],
                ['title' => 'Interior Painting', 'description' => 'Professional interior painting with proper prep, premium paints, and clean lines throughout your Connecticut home.', 'image' => '/services/interior-painting-ct.jpg', 'url' => '/interior-painting/', 'tag' => null],
            ],
        ],
    ],
];

DB::transaction(function () use ($pagePayload, $sections): void {
    $page = Page::query()->firstOrNew(['full_path' => $pagePayload['full_path']]);
    $page->fill($pagePayload);
    $page->save();

    $page->sections()->delete();

    foreach ($sections as $index => $sectionPayload) {
        Section::query()->create([
            'page_id' => $page->id,
            'type' => $sectionPayload['type'],
            'data' => $sectionPayload['data'],
            'sort_order' => $index + 1,
            'is_active' => true,
        ]);
    }
});

ApiPageController::forgetCacheForPath('/kitchen-remodeling');
Artisan::call('optimize:clear');

$page = Page::query()
    ->where('full_path', '/kitchen-remodeling')
    ->with(['sections' => fn ($query) => $query->orderBy('sort_order')])
    ->firstOrFail();

echo json_encode([
    'page_id' => $page->id,
    'full_path' => $page->full_path,
    'template_key' => $page->template_key,
    'section_types' => $page->sections->pluck('type')->all(),
    'section_count' => $page->sections->count(),
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
