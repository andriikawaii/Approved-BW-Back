<?php

use App\Http\Controllers\Api\PageController as ApiPageController;
use App\Models\Page;
use App\Models\Section;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

$pagePayload = [
    'full_path' => '/services',
    'template_key' => 'services_overview',
    'status' => 'published',
    'published_at' => now(),
    'seo_title' => 'Home Remodeling Services in CT | BuiltWell CT',
    'seo_description' => 'BuiltWell CT offers 11 home remodeling services in Connecticut — kitchens, bathrooms, basements, additions, and more. Get a free consultation today.',
    'canonical_url' => 'https://buildwellct.com/services/',
];

$sections = [
    [
        'type' => 'hero',
        'data' => [
            'headline' => 'Home Remodeling Services in Connecticut',
            'highlight_text' => 'Services in Connecticut',
            'subheadline' => 'Full-service home remodeling for homeowners throughout Fairfield County and New Haven County. Kitchens, bathrooms, basements, additions, flooring, and more.',
            'background_image' => '/hero/builtwell-job-site-aerial-hero-ct.jpg',
            'badges' => [],
        ],
    ],
    [
        'type' => 'trust_bar',
        'data' => [
            'variant' => 'stats',
            'items' => [
                ['icon' => 'clock', 'label' => 'Years of Experience', 'value' => '15+'],
                ['icon' => 'check', 'label' => 'Completed Projects', 'value' => '100+'],
                ['icon' => 'star', 'label' => 'Google Rating', 'value' => '4.9'],
                ['icon' => 'shield', 'label' => 'Fully Bonded & Insured', 'value' => null],
            ],
        ],
    ],
    [
        'type' => 'services_grid',
        'data' => [
            'eyebrow' => 'What We Do',
            'title' => 'Our Services',
            'highlight_text' => 'Services',
            'subtitle' => 'Full-scope remodeling and improvement services — available as standalone projects or as part of a larger scope.',
            'items' => [
                [
                    'title' => 'Kitchen Remodeling',
                    'summary' => 'Cabinet layout, countertop fabrication, tile work, lighting, plumbing, electrical, and appliance coordination. Full kitchen remodels from demolition to final walkthrough.',
                    'image' => '/services/kitchen-remodeling-ct.jpg',
                    'url' => '/kitchen-remodeling/',
                    'price' => '$25K–$150K+',
                    'timeline' => '6–12 weeks',
                    'cta_label' => 'Get Started',
                ],
                [
                    'title' => 'Bathroom Remodeling',
                    'summary' => 'Tile, waterproofing, fixtures, vanities, shower systems, lighting, and ventilation — carefully sequenced for tight spaces.',
                    'image' => '/services/bathroom-remodeling-ct.jpg',
                    'url' => '/bathroom-remodeling/',
                    'price' => '$15K–$120K+',
                    'timeline' => '3–6 weeks',
                    'cta_label' => 'Get Started',
                ],
                [
                    'title' => 'Basement Finishing',
                    'summary' => 'Framing, insulation, drywall, flooring, lighting, electrical, egress windows, and full bathroom builds when needed.',
                    'image' => '/services/basement-finishing-ct.jpg',
                    'url' => '/basement-finishing/',
                    'price' => '$25K–$100K+',
                    'timeline' => '4–8 weeks',
                    'cta_label' => 'Get Started',
                ],
                [
                    'title' => 'Flooring',
                    'summary' => 'Hardwood, engineered hardwood, luxury vinyl plank, tile, and carpet installation throughout Fairfield and New Haven Counties.',
                    'image' => '/services/flooring-installation-ct.jpg',
                    'url' => '/flooring/',
                    'price' => '$4–$25/sq ft',
                    'timeline' => '2–5 days',
                    'cta_label' => 'Get Started',
                ],
                [
                    'title' => 'Home Additions',
                    'summary' => 'Single-story and second-story additions, sunrooms, garage conversions, and bump-outs with full structural and finish work.',
                    'image' => '/services/home-additions-ct.jpg',
                    'url' => '/home-additions/',
                    'price' => '$150–$400/sq ft',
                    'timeline' => '8–16 weeks',
                    'cta_label' => 'Get Started',
                ],
                [
                    'title' => 'Interior Painting',
                    'summary' => 'Walls, ceilings, trim, doors, and built-ins. Professional-grade paints with proper surface preparation on every project.',
                    'image' => '/services/interior-painting-ct.jpg',
                    'url' => '/interior-painting/',
                    'price' => '$3–$6/sq ft',
                    'timeline' => '2–5 days',
                    'cta_label' => 'Get Started',
                ],
                [
                    'title' => 'Interior Carpentry',
                    'summary' => 'Custom millwork, built-in cabinetry, wainscoting, crown molding, coffered ceilings, closet systems, and finish trim.',
                    'image' => '/services/interior-carpentry-ct.jpg',
                    'url' => '/interior-carpentry/',
                    'price' => '$75–$150/hr',
                    'timeline' => 'Varies',
                    'cta_label' => 'Get Started',
                ],
                [
                    'title' => 'Attic Conversions',
                    'summary' => 'Converting unfinished attics into bedrooms, offices, or playrooms — structural assessment through final finish.',
                    'image' => '/services/attic-conversions-ct.jpg',
                    'url' => '/attic-conversions/',
                    'price' => '$50K–$150K',
                    'timeline' => '6–12 weeks',
                    'cta_label' => 'Get Started',
                ],
                [
                    'title' => 'Decks & Porches',
                    'summary' => 'Pressure-treated lumber, composite, and hardwood. Covered porches, screened-in structures, pergolas, and multi-level decks.',
                    'image' => '/services/decks-porches-ct.jpg',
                    'url' => '/decks-porches/',
                    'price' => '$15K–$75K',
                    'timeline' => '2–4 weeks',
                    'cta_label' => 'Get Started',
                ],
                [
                    'title' => 'Design & Planning',
                    'summary' => 'Space planning, material selection, finish coordination, and project documentation before construction begins.',
                    'image' => '/services/design-planning-ct.jpg',
                    'url' => '/remodeling-design-planning/',
                    'price' => '$2.5K–$15K',
                    'timeline' => '2–6 weeks',
                    'cta_label' => 'Get Started',
                ],
                [
                    'title' => 'Comfort & Accessibility',
                    'summary' => 'Grab bars, roll-in showers, wider doorways, ramp installation, and first-floor adaptations for all ages and abilities.',
                    'image' => '/services/comfort-accessibility-ct.jpg',
                    'url' => '/comfort-accessibility-remodeling/',
                    'price' => '$5K–$50K',
                    'timeline' => '1–4 weeks',
                    'cta_label' => 'Get Started',
                ],
                [
                    'title' => 'Insurance Reconstruction',
                    'summary' => 'Rebuilding homes after fire, water, and storm damage. We handle the full reconstruction once cleanup is complete, working directly with your carrier.',
                    'image' => '/portfolio/builtwell-contractor-handshake-arrival-ct-optimized.jpg',
                    'url' => '/insurance-restoration/',
                    'price' => 'Varies',
                    'timeline' => '4–16 weeks',
                    'cta_label' => 'Get Started',
                ],
            ],
        ],
    ],
    [
        'type' => 'process_steps',
        'data' => [
            'eyebrow' => 'Our Process',
            'title' => 'Our Remodeling Process',
            'highlight_text' => 'Process',
            'subtitle' => 'We follow the same five-step process on every project. It keeps jobs on schedule and keeps you informed at every stage.',
            'steps' => [
                [
                    'title' => 'Consultation',
                    'description' => 'We visit your home or connect via Google Meet at no charge and with no obligation, so we can understand the scope before we put anything in writing.',
                ],
                [
                    'title' => 'Planning',
                    'description' => 'You receive a written proposal with a clear breakdown of exactly what\'s included, the full project timeline, and the cost. No vague ranges, no surprises later.',
                ],
                [
                    'title' => 'Selections',
                    'description' => 'We guide you through every material choice and communicate lead times upfront, so nothing delays construction once the schedule is set.',
                ],
                [
                    'title' => 'Build',
                    'description' => 'Construction begins on the agreed start date. Crews show up on time, daily updates are sent, and the job site is cleaned every evening.',
                ],
                [
                    'title' => 'Walkthrough',
                    'description' => 'When the work is finished, we walk through the project together. Nothing is signed off until you\'re satisfied with every detail.',
                ],
            ],
        ],
    ],
    [
        'type' => 'logo_strip',
        'data' => [
            'eyebrow' => 'Trusted Brands',
            'title' => 'Materials We Stand Behind',
            'highlight_text' => 'Stand Behind',
            'subtitle' => 'We work exclusively with proven brands trusted by contractors and homeowners across Connecticut.',
            'items' => [
                ['name' => 'KraftMaid', 'logo' => null, 'url' => 'https://www.kraftmaid.com'],
                ['name' => 'Kohler', 'logo' => 'https://www.us.kohler.com/webassets/img/content/header-footer/kohler-logo-2021.svg', 'url' => 'https://www.kohler.com'],
                ['name' => 'Cambria', 'logo' => null, 'url' => 'https://www.cambriausa.com'],
                ['name' => 'Moen', 'logo' => null, 'url' => 'https://www.moen.com'],
                ['name' => 'Caesarstone', 'logo' => null, 'url' => 'https://www.caesarstone.com'],
                ['name' => 'Delta', 'logo' => null, 'url' => 'https://www.deltafaucet.com'],
                ['name' => 'Daltile', 'logo' => null, 'url' => 'https://www.daltile.com'],
                ['name' => 'American Standard', 'logo' => null, 'url' => 'https://www.americanstandard-us.com'],
                ['name' => 'Wellborn', 'logo' => null, 'url' => 'https://www.wellborn.com'],
                ['name' => 'Grohe', 'logo' => null, 'url' => 'https://www.grohe.com/en_us/'],
                ['name' => 'MSI Surfaces', 'logo' => null, 'url' => 'https://www.msisurfaces.com'],
                ['name' => 'TOTO', 'logo' => null, 'url' => 'https://www.totousa.com'],
                ['name' => 'Schluter', 'logo' => null, 'url' => 'https://www.schluter.com/schluter-us/en_US/'],
                ['name' => 'Silestone', 'logo' => null, 'url' => 'https://www.cosentino.com/silestone/'],
                ['name' => 'Wolf Cabinets', 'logo' => null, 'url' => 'https://www.wolfcabinets.com'],
                ['name' => 'Fabuwood', 'logo' => null, 'url' => 'https://www.fabuwood.com'],
            ],
        ],
    ],
    [
        'type' => 'areas_served',
        'data' => [
            'eyebrow' => 'Where We Work',
            'title' => 'Where We Work',
            'highlight_text' => 'Work',
            'subtitle' => null,
            'counties' => [
                [
                    'name' => 'Fairfield County',
                    'image' => '/images/areas/fairfield-county.jpg',
                    'url' => '/fairfield-county/',
                    'phone' => '(203) 919-9616',
                    'description' => 'Full service home remodeling across Fairfield County. Kitchens, bathrooms, basements, additions, and more. Dedicated local crews who know the housing stock and building departments across the county.',
                    'towns' => ['Greenwich', 'Stamford', 'Norwalk', 'Westport', 'Darien', 'New Canaan', 'Fairfield', 'Ridgefield'],
                    'extra_towns' => ['Bethel', 'Bridgeport', 'Brookfield', 'Danbury', 'Easton', 'Monroe', 'New Fairfield', 'Newtown', 'Redding', 'Shelton', 'Sherman', 'Stratford', 'Trumbull', 'Weston', 'Wilton'],
                    'town_links' => [
                        'Greenwich' => '/fairfield-county/greenwich-ct/',
                        'Stamford' => '/fairfield-county/stamford-ct/',
                        'Norwalk' => '/fairfield-county/norwalk-ct/',
                        'Westport' => '/fairfield-county/westport-ct/',
                        'Darien' => '/fairfield-county/darien-ct/',
                        'New Canaan' => '/fairfield-county/new-canaan-ct/',
                        'Fairfield' => '/fairfield-county/fairfield-ct/',
                        'Ridgefield' => '/fairfield-county/ridgefield-ct/',
                    ],
                    'cta_label' => 'Learn more about Fairfield County',
                ],
                [
                    'name' => 'New Haven County',
                    'image' => '/images/areas/new-haven-county.jpg',
                    'url' => '/new-haven-county/',
                    'phone' => '(203) 466-9148',
                    'description' => 'Full service home remodeling across New Haven County, from our Orange, CT office. Kitchens, bathrooms, basements, additions, and more. Local crews covering every town from the coast to inland.',
                    'towns' => ['Orange', 'New Haven', 'Hamden', 'Branford', 'Guilford', 'Madison', 'Woodbridge', 'Milford'],
                    'extra_towns' => ['Ansonia', 'Beacon Falls', 'Bethany', 'Cheshire', 'Derby', 'East Haven', 'Meriden', 'Middlebury', 'Naugatuck', 'North Branford', 'North Haven', 'Oxford', 'Prospect', 'Seymour', 'Southbury', 'Wallingford', 'Waterbury', 'West Haven', 'Wolcott'],
                    'town_links' => [
                        'Orange' => '/new-haven-county/orange-ct/',
                        'New Haven' => '/new-haven-county/new-haven-ct/',
                        'Hamden' => '/new-haven-county/hamden-ct/',
                        'Branford' => '/new-haven-county/branford-ct/',
                        'Guilford' => '/new-haven-county/guilford-ct/',
                        'Madison' => '/new-haven-county/madison-ct/',
                        'Woodbridge' => '/new-haven-county/woodbridge-ct/',
                        'Milford' => '/new-haven-county/milford-ct/',
                    ],
                    'cta_label' => 'Learn more about New Haven County',
                ],
            ],
        ],
    ],
    [
        'type' => 'trust_bar',
        'data' => [
            'variant' => 'link_strip',
            'items' => [
                ['icon' => 'star', 'label' => 'Google Rating 4.9', 'value' => null, 'url' => 'https://www.google.com/search?q=builtwell+ct+reviews'],
                ['icon' => 'shield', 'label' => 'BBB A+ Accredited', 'value' => null, 'url' => 'https://www.bbb.org/search?find_country=USA&find_text=builtwell+ct&find_loc=Orange%2C+CT'],
                ['icon' => 'check', 'label' => 'Trusted on Houzz', 'value' => null, 'url' => 'https://www.houzz.com/professionals/general-contractors/builtwell-ct'],
                ['icon' => 'calendar', 'label' => 'CT HIC License #0668405', 'value' => null, 'url' => 'https://www.elicense.ct.gov/Lookup/LicenseLookup.aspx'],
                ['icon' => 'check', 'label' => 'Verified on Angi & Thumbtack', 'value' => null, 'url' => 'https://www.angi.com/companylist/us/ct/orange/builtwell-ct-reviews-'],
            ],
        ],
    ],
    [
        'type' => 'lead_form',
        'data' => [
            'eyebrow' => 'GET IN TOUCH',
            'title' => 'Tell Us About Your Project',
            'title_highlight' => 'Project',
            'subtitle' => 'Fill out the form and we\'ll get back to you within one business day with next steps. No obligation, no pressure.',
            'images' => [
                ['image' => '/team/builtwell-owner-handshake-client-ct-02.jpg', 'alt' => 'BuiltWell CT owner meeting with a Connecticut homeowner for a remodeling consultation'],
                ['image' => '/portfolio/builtwell-job-site-aerial-ct.jpg', 'alt' => 'BuiltWell CT aerial view of an active remodeling job site in Connecticut'],
            ],
            'fields' => [
                ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'required' => true, 'placeholder' => 'Your full name'],
                ['name' => 'phone', 'label' => 'Phone', 'type' => 'tel', 'required' => true, 'placeholder' => '(203) 000-0000'],
                ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'required' => true, 'placeholder' => 'you@email.com'],
                ['name' => 'zip', 'label' => 'Zip Code', 'type' => 'text', 'required' => true, 'placeholder' => '06477'],
                ['name' => 'services', 'label' => 'Services Needed', 'type' => 'checkbox_group', 'required' => true, 'options' => [
                    ['label' => 'Kitchen Remodeling', 'value' => 'Kitchen Remodeling'],
                    ['label' => 'Bathroom Remodeling', 'value' => 'Bathroom Remodeling'],
                    ['label' => 'Basement Finishing', 'value' => 'Basement Finishing'],
                    ['label' => 'Flooring', 'value' => 'Flooring'],
                    ['label' => 'Home Additions', 'value' => 'Home Addition'],
                    ['label' => 'Interior Painting', 'value' => 'Interior Painting'],
                    ['label' => 'Interior Carpentry', 'value' => 'Interior Carpentry'],
                    ['label' => 'Attic Conversions', 'value' => 'Attic Conversion'],
                    ['label' => 'Decks & Porches', 'value' => 'Decks & Porches'],
                    ['label' => 'Design & Planning', 'value' => 'Design & Planning'],
                    ['label' => 'Comfort & Accessibility', 'value' => 'Comfort & Accessibility'],
                    ['label' => 'Other', 'value' => 'Other'],
                ]],
                ['name' => 'best_time', 'label' => 'Best Time to Contact', 'type' => 'select', 'required' => true, 'options' => [
                    ['label' => 'Morning (8am - 12pm)', 'value' => 'Morning (8am - 12pm)'],
                    ['label' => 'Afternoon (12pm - 4pm)', 'value' => 'Afternoon (12pm - 4pm)'],
                    ['label' => 'Evening (4pm - 6pm)', 'value' => 'Evening (4pm - 6pm)'],
                    ['label' => 'Anytime', 'value' => 'Anytime'],
                ]],
                ['name' => 'contact_method', 'label' => 'Preferred Contact Method', 'type' => 'radio_group', 'required' => true, 'options' => [
                    ['label' => 'Call', 'value' => 'call'],
                    ['label' => 'Text', 'value' => 'text'],
                    ['label' => 'Email', 'value' => 'email'],
                ]],
                ['name' => 'message', 'label' => 'Tell Us About Your Project', 'type' => 'textarea', 'required' => false, 'placeholder' => 'Describe your project, any specific needs, or questions you have...'],
            ],
            'submit_label' => 'Get Your Free Estimate',
            'consent_text' => 'We respond within 24 hours. No spam, no obligation.',
        ],
    ],
    [
        'type' => 'rich_text',
        'data' => [
            'title' => 'Flexible Financing Available',
            'content' => 'Get approved in about 60 seconds and start your project today.',
            'cta' => ['label' => 'Check Financing Options', 'url' => 'https://www.greensky.com'],
            'style_variant' => 'financing_strip',
            'image' => '/images/brands/greensky.svg',
            'image_alt' => 'GreenSky financing logo.',
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

ApiPageController::forgetCacheForPath('/services');
Artisan::call('optimize:clear');

$page = Page::query()
    ->where('full_path', '/services')
    ->with(['sections' => fn ($query) => $query->orderBy('sort_order')])
    ->firstOrFail();

echo json_encode([
    'page_id' => $page->id,
    'full_path' => $page->full_path,
    'template_key' => $page->template_key,
    'section_types' => $page->sections->pluck('type')->all(),
    'section_count' => $page->sections->count(),
], JSON_PRETTY_PRINT);
