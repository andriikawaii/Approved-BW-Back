<?php

use App\Http\Controllers\Api\PageController as ApiPageController;
use App\Models\Page;
use App\Models\Section;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

$pagePayload = [
    'full_path' => '/kitchen-remodeling/stamford-ct',
    'template_key' => 'service_town',
    'status' => 'published',
    'published_at' => now(),
    'seo_title' => 'Kitchen Remodeling in Stamford, CT | BuiltWell CT',
    'seo_description' => 'Kitchen remodeling in Stamford, CT, from $30,000 to $150,000+. Licensed Connecticut kitchen remodeling contractor serving Stamford and Fairfield County. Call (203) 919-9616.',
    'canonical_url' => null,
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
        'type' => 'hero_service_location',
        'data' => [
            'headline' => 'Kitchen Remodeling in Stamford, CT',
            'subheadline' => 'Licensed Connecticut kitchen remodeling contractor serving Stamford and Fairfield County - full-service renovations from $30,000 to $150,000+.',
            'background_image' => '/images/headers/kitchen-remodeling-header.jpg',
            'primary_cta' => ['label' => 'Free Estimate', 'url' => '#contact'],
            'secondary_cta' => ['label' => 'Call Now', 'url' => 'tel:2039199616'],
        ],
    ],
    [
        'type' => 'trust_bar',
        'data' => [
            'variant' => 'hero_stats',
            'items' => [
                ['icon' => 'clock', 'label' => 'Years of Experience', 'value' => '15+'],
                ['icon' => 'check', 'label' => 'Completed Projects', 'value' => '100+'],
                ['icon' => 'star', 'label' => 'Google Rating', 'value' => '4.9', 'url' => 'https://www.google.com/search?q=builtwell+ct+reviews'],
                ['icon' => 'shield', 'label' => 'Fully Bonded & Insured', 'value' => null],
            ],
        ],
    ],
    [
        'type' => 'rich_text',
        'data' => $rich(
            "Stamford's Kitchen Remodeling Contractor",
            'Full-Service Kitchen Remodeling in Stamford, CT',
            implode("\n\n", [
                "Kitchen remodeling in Stamford typically ranges from \$30,000 for a basic cabinet and countertop update to \$150,000 or more for a full custom renovation with structural changes. Most projects take six to twelve weeks of active construction, plus cabinet lead time. We hold CT HIC License #0668405 and serve Stamford through our Fairfield County Service Area Team.",
                "Stamford is the most urban and architecturally diverse market in Fairfield County. You'll find high-rise condos in the downtown core, pre-war colonials and Victorians in Shippan Point and Harbor Point, 1950s-to-1980s single-family homes in Springdale and Newfield, and larger wooded properties along the Long Ridge Road corridor in North Stamford.",
                "Stamford homeowners have heard contractor promises before. We differentiate on follow-through: clear proposals, daily updates during construction, and a project that finishes on the schedule we quoted.",
            ]),
            ['highlight_text' => 'Stamford, CT', 'align' => 'center']
        ),
    ],
    [
        'type' => 'service_intro_split',
        'data' => [
            'title' => 'What Is Included in a Kitchen Remodel in Stamford',
            'content' => implode("\n\n", [
                "A full kitchen remodel in Stamford covers every trade from demolition through final paint: cabinetry, countertops, tile, flooring, electrical, plumbing, appliance installation, and all required Stamford Building Department permits and inspections.",
                "We start with demolition and removal of existing cabinets, countertops, and flooring. Before any new material goes in, we assess what's behind the walls. Stamford homes - especially pre-war stock in Shippan Point and older single-family homes in Springdale - regularly turn up undersized electrical panels, deteriorated plumbing, water damage inside wall cavities, or structural framing that needs attention before the renovation can proceed properly.",
                "For condo kitchens in downtown Stamford, we coordinate with building management on delivery logistics, elevator reservations, construction hours, and any HOA approval requirements before work begins. This is standard for us and built into the project schedule.",
                "Cabinetry installation includes soft-close hardware and adjustable shelving as standard. Countertop fabrication and installation, backsplash tile, and underlayment preparation are all part of the project. Electrical scope covers new circuits for appliances, under-cabinet lighting, and updated outlet and switch locations.",
                "Plumbing includes sink and dishwasher connections, garbage disposal installation, and any relocated water lines. We coordinate appliance delivery and installation so you're not managing that separately.",
                "Interior painting, drywall patching, and daily cleanup are included throughout. We install dust barriers to protect the rest of your home during construction. The project closes with a final walkthrough where we go through every detail together.",
            ]),
            'image_main' => '/services/kitchen-remodeling-ct.jpg',
            'image_secondary' => '/services/kitchen-remodeling-luxury-consultation-ct-01.jpeg',
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
        'type' => 'rich_text',
        'data' => $rich(
            'Condo Expertise',
            'What Makes Kitchen Remodeling in a Stamford Condo Different',
            implode("\n\n", [
                "Stamford has one of the densest concentrations of condos and high-rise residential buildings in Connecticut. Remodeling a kitchen in a condo is fundamentally different from remodeling in a single-family home. Contractors without condo experience regularly underestimate the logistics, restrictions, and coordination involved.",
                "### Building Management Approval\nMost Stamford condo buildings require board or management approval before any renovation work begins. This typically involves submitting detailed plans, proof of insurance, and a construction schedule. Some buildings in Harbor Point and downtown Stamford require a refundable deposit to cover potential common area damage. We handle the entire approval package and coordinate directly with your building management.",
                "### Elevator and Delivery Restrictions\nHigh-rise buildings restrict freight elevator access to specific time windows, often requiring reservations days in advance. Material deliveries, debris removal, and crew access all depend on elevator scheduling. We plan every delivery and haul around your building's specific rules so the project does not stall waiting for elevator time.",
                "### Noise and Work Hour Restrictions\nStamford condo associations typically enforce strict work hours, often limited to weekdays between 9 AM and 5 PM, with no work on weekends or holidays. Some buildings prohibit loud demolition work during certain hours. We build these constraints into the project schedule from day one so there are no violations or fines.",
                "### Shared Plumbing and Electrical Risers\nIn condo buildings, plumbing stacks and electrical risers are shared between units. Moving a kitchen sink or adding circuits may require coordination with neighboring units and building engineering. Water shutoffs affect other residents. We identify shared system impacts during planning and coordinate with the building to minimize disruption to other owners.",
                "### Colonial and Single-Family Homes\nStamford's residential neighborhoods - Springdale, Newfield, Shippan Point, and North Stamford - are predominantly single-family colonials and capes built between 1940 and 1980. These homes present their own challenges: outdated 100-amp electrical panels, galvanized plumbing, and original kitchen layouts designed for a different era. We modernize these kitchens with updated systems while respecting the character of the home.",
            ]),
            ['highlight_text' => 'Stamford Condo']
        ),
    ],
    [
        'type' => 'rich_text',
        'data' => $rich(
            'Permits & Approvals',
            'Stamford Building Department and Permits',
            implode("\n\n", [
                "The Stamford Building Department is located at 888 Washington Boulevard. Stamford uses an online permit application system for residential renovation projects. Typical review times run 2 to 4 weeks depending on project scope and current backlog.",
                "Kitchen remodeling projects that involve electrical, plumbing, or structural modifications require separate trade permits, each with its own inspections. In condo buildings, the permit application may also require documentation of HOA approval. We handle all permit applications and schedule every required inspection as part of the project.",
                "For properties in Stamford's historic overlay zones - particularly in the Shippan Point area and parts of downtown - exterior modifications may require additional review. Interior kitchen renovations typically do not trigger historic review, but if your project involves window replacement or changes visible from the street, we identify that during planning and build the review timeline into the schedule.",
            ]),
            ['highlight_text' => 'and Permits', 'surface' => 'light']
        ),
    ],
    [
        'type' => 'before_after',
        'data' => [
            'title' => 'Recent Kitchen Remodeling Projects',
            'subtitle' => "The projects below give you a sense of what we've completed in Connecticut recently.",
            'items' => [
                [
                    'title' => 'Kitchen Remodeling in New Canaan',
                    'before_image' => '/images/before-after/kitchen-before-after-1.jpg',
                    'after_image' => '/images/before-after/kitchen-before-after-1.png',
                    'before_text' => 'A New Canaan family wanted to open up a kitchen closed off by a load-bearing wall. We engineered a beam solution, installed white shaker cabinets with a quartz island, and extended hardwood flooring into the new open layout.',
                    'after_text' => null,
                    'quote' => ['text' => "BuiltWell made it straightforward. Now we can't imagine how we lived before.", 'author' => 'The Chens', 'location' => 'New Canaan'],
                ],
                [
                    'title' => 'Kitchen Remodeling in Milford',
                    'before_image' => '/images/before-after/kitchen-before-after-2.jpg',
                    'after_image' => '/images/before-after/kitchen-before-after-2.png',
                    'before_text' => 'A Milford homeowner needed to replace dated 1990s oak cabinets and laminate countertops. We removed the peninsula, installed soft-close cabinetry with quartz countertops and subway tile, and extended LVP flooring into the dining room.',
                    'after_text' => null,
                    'quote' => ['text' => 'They showed up when they said they would, cleaned up every day, and the kitchen turned out better than I imagined.', 'author' => 'Ivana P.', 'location' => 'Milford'],
                ],
                [
                    'title' => 'Kitchen Remodeling in Westport',
                    'before_image' => '/images/before-after/kitchen-before-after-3.jpg',
                    'after_image' => '/images/before-after/kitchen-before-after-3.png',
                    'before_text' => 'A Westport couple wanted a brighter, more functional kitchen. We gutted the space, reconfigured the layout to add a center island, installed custom cabinetry with quartz countertops, and added recessed lighting throughout.',
                    'after_text' => null,
                    'quote' => ['text' => 'We cook together every night now. The island changed everything about how we use the kitchen.', 'author' => 'The Martins', 'location' => 'Westport'],
                ],
            ],
        ],
    ],
    [
        'type' => 'pricing_table',
        'data' => [
            'title' => 'Kitchen Remodeling Cost in Stamford, CT',
            'subtitle' => 'Kitchen remodeling in Stamford ranges from $30,000 for a basic cabinet and countertop refresh to $150,000 or more for a full custom renovation with structural changes.',
            'columns' => ['Scope', 'Cost Range', "What's Typically Included"],
            'rows' => [
                ['label' => 'Basic Refresh', 'price' => '$30,000-$60,000', 'notes' => 'Cabinet refacing or stock cabinets, new countertops, updated appliances, paint'],
                ['label' => 'Mid-Range', 'price' => '$60,000-$100,000', 'notes' => 'New cabinets, stone countertops, new flooring, appliances, updated lighting'],
                ['label' => 'High-End', 'price' => '$100,000-$150,000+', 'notes' => 'Custom or semi-custom cabinets, layout changes, premium appliances, full finishes'],
            ],
        ],
    ],
    [
        'type' => 'rich_text',
        'data' => $rich(
            'Local Knowledge',
            'Why Stamford Kitchen Remodeling Requires Local Expertise',
            implode("\n\n", [
                'Stamford homes present construction conditions that contractors without local experience regularly miss.',
                'Stamford kitchens built before 2000 frequently have electrical panels that cannot support modern appliance loads. A kitchen with a new refrigerator, induction range, dishwasher, microwave, and under-cabinet lighting may draw more power than the original panel was designed to handle. We assess panel capacity early and address it in the scope if needed. Homes in Springdale and Newfield built in the 1950s through 1980s may have asbestos in floor tiles or lead paint on existing cabinets. Both require proper abatement by certified professionals before renovation work begins.',
                'Condo kitchens in downtown Stamford and Harbor Point present their own logistics. Building management rules govern delivery hours, elevator access, noise restrictions, and contractor insurance requirements. We handle this coordination as a standard part of every Stamford condo project and factor it into the project schedule from day one.',
                'Pre-war colonials and Victorians in Shippan Point and the South End may have balloon framing, plaster walls, or outdated plumbing that requires a different structural approach than newer construction. When a kitchen layout change involves removing or modifying a wall in one of these homes, we know what to expect before the wall comes down.',
                'Stamford Building Department requires separate electrical, plumbing, and building permits, each with their own inspections. We handle all permit applications and coordinate every required inspection as part of the project. If your property falls within a historic or overlay district, we identify that during the planning phase and account for any additional review requirements.',
            ]),
            ['highlight_text' => 'Local Expertise']
        ),
    ],
    [
        'type' => 'process_steps',
        'data' => [
            'title' => 'Our Kitchen Remodeling Process',
            'subtitle' => 'Every Stamford kitchen remodel follows the same five-step process. This structure keeps projects on schedule, on budget, and clearly communicated throughout.',
            'steps' => [
                ['title' => 'Consultation', 'short' => 'Visit your Stamford home or connect by video', 'description' => 'We visit your Stamford home or connect via Google Meet or Zoom to discuss your goals, assess the space, and answer your questions. For condos, we also review HOA rules and building access logistics. For older homes, we note the existing electrical and plumbing conditions upfront.'],
                ['title' => 'Planning', 'short' => 'Clear written proposal and line items', 'description' => "You receive a clear written proposal covering exactly what's included, how long it will take, and what it costs. We break out cabinetry, countertops, tile, electrical, plumbing, flooring, and Stamford Building Department permits separately so you understand exactly where the budget is going."],
                ['title' => 'Selections', 'short' => 'Materials and lead times locked in early', 'description' => 'We guide you through material choices with options at different price points. We communicate lead times clearly so selections are made on schedule before construction begins. If a material has a long lead time, we flag that immediately.'],
                ['title' => 'Build', 'short' => 'Daily updates and clean jobsite', 'description' => 'Construction begins on the agreed schedule. You receive daily updates on progress, a clean job site at the end of every workday, and crews who arrive when scheduled. In Stamford condos, we coordinate deliveries and noise-sensitive work around building rules. If something unexpected comes up behind a wall in an older home, we contact you that day and present your options.'],
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
            'counties' => [
                [
                    'name' => 'Fairfield County',
                    'image' => '/images/areas/fairfield-county.jpg',
                    'url' => '/fairfield-county/',
                    'phone' => '(203) 919-9616',
                    'description' => 'Kitchen remodeling across Fairfield County, from custom chef kitchens in Greenwich and Westport to practical layout upgrades in Norwalk and Stamford. We handle every phase of the build in house.',
                    'towns' => ['Greenwich', 'Stamford', 'Norwalk', 'Westport', 'Darien', 'New Canaan', 'Fairfield', 'Ridgefield'],
                    'extra_towns' => ['Bethel', 'Bridgeport', 'Brookfield', 'Danbury', 'Easton', 'Monroe', 'New Fairfield', 'Newtown', 'Redding', 'Shelton', 'Sherman', 'Stratford', 'Trumbull', 'Weston', 'Wilton'],
                    'town_links' => ['Greenwich' => '/fairfield-county/greenwich-ct/', 'Stamford' => '/fairfield-county/stamford-ct/', 'Norwalk' => '/fairfield-county/norwalk-ct/', 'Westport' => '/fairfield-county/westport-ct/', 'Darien' => '/fairfield-county/darien-ct/', 'New Canaan' => '/fairfield-county/new-canaan-ct/', 'Fairfield' => '/fairfield-county/fairfield-ct/', 'Ridgefield' => '/fairfield-county/ridgefield-ct/'],
                    'cta_label' => 'Learn more about Fairfield County',
                ],
                [
                    'name' => 'New Haven County',
                    'image' => '/images/areas/new-haven-county.jpg',
                    'url' => '/new-haven-county/',
                    'phone' => '(203) 466-9148',
                    'description' => 'Kitchen remodeling across New Haven County, from our Orange, CT office. We modernize galley kitchens, open up floor plans, and deliver full gut renovations tailored to the local housing stock.',
                    'towns' => ['Orange', 'New Haven', 'Hamden', 'Branford', 'Guilford', 'Madison', 'Woodbridge', 'Milford'],
                    'extra_towns' => ['Ansonia', 'Beacon Falls', 'Bethany', 'Cheshire', 'Derby', 'East Haven', 'Meriden', 'Middlebury', 'Naugatuck', 'North Branford', 'North Haven', 'Oxford', 'Prospect', 'Seymour', 'Southbury', 'Wallingford', 'Waterbury', 'West Haven', 'Wolcott'],
                    'town_links' => ['Orange' => '/new-haven-county/orange-ct/', 'New Haven' => '/new-haven-county/new-haven-ct/', 'Hamden' => '/new-haven-county/hamden-ct/', 'Branford' => '/new-haven-county/branford-ct/', 'Guilford' => '/new-haven-county/guilford-ct/', 'Madison' => '/new-haven-county/madison-ct/', 'Woodbridge' => '/new-haven-county/woodbridge-ct/', 'Milford' => '/new-haven-county/milford-ct/'],
                    'cta_label' => 'Learn more about New Haven County',
                ],
            ],
        ],
    ],
    [
        'type' => 'faq_list',
        'data' => [
            'title' => 'Kitchen Remodeling in Stamford Questions',
            'items' => [
                ['question' => 'Do I need a permit for kitchen remodeling in Stamford?', 'answer' => 'Yes. Most kitchen remodeling projects in Stamford require permits from the Stamford Building Department, particularly when the work involves electrical, plumbing, or structural modifications. Stamford requires separate electrical, plumbing, and building permits, each with their own inspections. We handle all permit applications and coordinate every required inspection as part of the project; you do not need to manage that process yourself. For condo kitchens, we also work with your building management to secure any required HOA approvals before construction begins.'],
                ['question' => 'How much does kitchen remodeling cost in Stamford?', 'answer' => 'Kitchen remodeling in Stamford typically ranges from $30,000 for a basic cabinet and countertop refresh to $150,000 or more for a full custom renovation with structural changes. A mid-range project with new cabinets, stone countertops, new flooring, and updated lighting generally falls between $60,000 and $100,000. Condo projects in downtown Stamford may include additional costs for building coordination, elevator reservations, and after-hours delivery requirements. Your written proposal includes a detailed cost breakdown by trade so you know exactly where the budget is going.'],
                ['question' => 'How long does a kitchen remodel take in Stamford?', 'answer' => 'Active construction on a Stamford kitchen remodel takes six to twelve weeks depending on the scope of work. Cabinet lead time needs to be added to that figure: custom cabinets require eight to twelve weeks, semi-custom four to six weeks, and stock cabinets are available within one to two weeks. For condo projects, building access rules and delivery scheduling can add coordination time to the overall timeline. Your written proposal includes a complete project schedule that accounts for all lead times and logistics.'],
                ['question' => 'Do you remodel condo kitchens in Stamford?', 'answer' => 'Yes. We regularly remodel kitchens in Stamford condos and high-rises, including buildings in the downtown core and Harbor Point. We coordinate with building management on delivery logistics, elevator reservations, construction hours, noise restrictions, and any HOA approval requirements. We carry the insurance coverage that most Stamford buildings require from contractors and provide certificates of insurance directly to your management company. Condo kitchen logistics are built into our standard project planning for Stamford.'],
                ['question' => 'What is included in a kitchen remodel in Stamford?', 'answer' => 'A full-scope kitchen remodel in Stamford includes demolition and removal of all existing cabinetry, countertops, and flooring; structural assessment and any required framing or structural modifications; new cabinetry installation with hardware; countertop fabrication and installation; backsplash tile work; new flooring with proper underlayment; all electrical work including new circuits and lighting; all plumbing including sink, dishwasher, and disposal connections; appliance coordination and installation; interior painting within the kitchen space; all drywall patching and finish work; Stamford Building Department permit applications and inspection coordination; daily cleanup throughout the project; and a final walkthrough.'],
            ],
        ],
    ],
    [
        'type' => 'trust_bar',
        'data' => [
            'variant' => 'service_trust_strip',
            'items' => [
                ['icon' => 'star', 'label' => 'Google Rating', 'value' => '4.9', 'url' => 'https://www.google.com/search?q=builtwell+ct+reviews'],
                ['icon' => 'shield', 'label' => 'BBB', 'value' => 'A+ Accredited', 'url' => 'https://www.bbb.org/search?find_country=USA&find_text=builtwell+ct&find_loc=Orange%2C+CT'],
                ['icon' => 'check', 'label' => 'Houzz', 'value' => 'Trusted on Houzz', 'url' => 'https://www.houzz.com/professionals/general-contractors/builtwell-ct'],
                ['icon' => 'calendar', 'label' => 'CT HIC License', 'value' => '#0668405', 'url' => 'https://www.elicense.ct.gov/Lookup/LicenseLookup.aspx'],
                ['icon' => 'check', 'label' => 'Angi & Thumbtack', 'value' => 'Verified', 'url' => 'https://www.angi.com/companylist/us/ct/orange/builtwell-ct-reviews-'],
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
                ['name' => 'Wolf Cabinets', 'logo' => '/images/brands/wolf-cabinets.svg', 'url' => 'https://www.wolfhomeproducts.com/cabinets'],
                ['name' => 'Fabuwood', 'logo' => '/images/brands/fabuwood.svg', 'url' => 'https://www.fabuwood.com'],
                ['name' => 'Silestone', 'logo' => '/images/brands/silestone.svg', 'url' => 'https://www.cosentino.com/silestone'],
                ['name' => 'Caesarstone', 'logo' => '/images/brands/caesarstone.svg', 'url' => 'https://www.caesarstoneus.com'],
            ],
        ],
    ],
    [
        'type' => 'lead_form',
        'data' => [
            'eyebrow' => 'GET IN TOUCH',
            'title' => 'Ready to Start Your Stamford Kitchen Remodeling Project',
            'title_highlight' => 'Remodeling Project',
            'subtitle' => 'Tell us about your Stamford project. We respond within one business day. No obligation.',
            'images' => [
                ['image' => '/hero/builtwell-team-van-consultation-hero-ct.jpg', 'alt' => 'BuiltWell CT remodeling team arriving at a Connecticut home for a free consultation.'],
                ['image' => '/team/builtwell-owner-handshake-client-ct-02.jpg', 'alt' => 'BuiltWell CT owner meeting with a Connecticut homeowner for a remodeling consultation.'],
            ],
            'fields' => [
                ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'required' => true, 'placeholder' => 'Your full name'],
                ['name' => 'phone', 'label' => 'Phone', 'type' => 'tel', 'required' => true, 'placeholder' => '(203) 000-0000'],
                ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'required' => true, 'placeholder' => 'you@email.com'],
                ['name' => 'zip', 'label' => 'Zip Code', 'type' => 'text', 'required' => true, 'placeholder' => '06477'],
                ['name' => 'services', 'label' => 'Services Needed', 'type' => 'checkbox_group', 'required' => true, 'options' => [['label' => 'Kitchen Remodeling', 'value' => 'Kitchen Remodeling'], ['label' => 'Bathroom Remodeling', 'value' => 'Bathroom Remodeling'], ['label' => 'Basement Finishing', 'value' => 'Basement Finishing'], ['label' => 'Flooring Installation', 'value' => 'Flooring Installation'], ['label' => 'Home Additions', 'value' => 'Home Additions'], ['label' => 'Interior Painting', 'value' => 'Interior Painting'], ['label' => 'Interior Carpentry', 'value' => 'Interior Carpentry'], ['label' => 'Attic Conversions', 'value' => 'Attic Conversions'], ['label' => 'Decks & Porches', 'value' => 'Decks & Porches'], ['label' => 'Design & Planning', 'value' => 'Design & Planning'], ['label' => 'Comfort & Accessibility', 'value' => 'Comfort & Accessibility'], ['label' => 'Other', 'value' => 'Other']]],
                ['name' => 'best_time', 'label' => 'Best Time to Contact', 'type' => 'select', 'required' => true, 'options' => [['label' => 'Morning (8am - 12pm)', 'value' => 'Morning (8am - 12pm)'], ['label' => 'Afternoon (12pm - 4pm)', 'value' => 'Afternoon (12pm - 4pm)'], ['label' => 'Evening (4pm - 6pm)', 'value' => 'Evening (4pm - 6pm)'], ['label' => 'Anytime', 'value' => 'Anytime']]],
                ['name' => 'contact_method', 'label' => 'Preferred Contact Method', 'type' => 'radio_group', 'required' => true, 'options' => [['label' => 'Call', 'value' => 'call'], ['label' => 'Text', 'value' => 'text'], ['label' => 'Email', 'value' => 'email']]],
                ['name' => 'message', 'label' => 'Tell Us About Your Project', 'type' => 'textarea', 'required' => false, 'placeholder' => 'Describe your project, timeline, budget range, or any questions...'],
                ['name' => 'files', 'label' => 'Upload Photos', 'type' => 'file', 'required' => false, 'help_text' => 'JPEG, PNG, or HEIC. Multiple files allowed.'],
            ],
            'submit_label' => 'Send Request',
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

ApiPageController::forgetCacheForPath('/kitchen-remodeling/stamford-ct');
Artisan::call('optimize:clear');

$page = Page::query()
    ->where('full_path', '/kitchen-remodeling/stamford-ct')
    ->with(['sections' => fn ($query) => $query->orderBy('sort_order')])
    ->firstOrFail();

echo json_encode([
    'page_id' => $page->id,
    'full_path' => $page->full_path,
    'template_key' => $page->template_key,
    'section_types' => $page->sections->pluck('type')->all(),
    'section_count' => $page->sections->count(),
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
