<?php

require __DIR__ . '/vendor/autoload.php';

use App\Models\Page;
use App\Models\Section;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;

$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$page = Page::query()
    ->whereIn('full_path', ['/bathroom-remodeling', '/bathroom-remodeling/'])
    ->with(['sections' => fn ($query) => $query->orderBy('sort_order')])
    ->first();

if (!$page) {
    fwrite(STDERR, "Page not found: /bathroom-remodeling\n");
    exit(1);
}

$takeUnused = function ($sections, &$usedIds, string $type): ?Section {
    foreach ($sections as $section) {
        if ($section->type === $type && !in_array($section->id, $usedIds, true)) {
            $usedIds[] = $section->id;
            return $section;
        }
    }
    return null;
};

DB::transaction(function () use ($page, $takeUnused): void {
    $sections = $page->sections()->orderBy('sort_order')->get();
    $usedIds = [];

    $hero = $takeUnused($sections, $usedIds, 'service_hero');
    $heroTrust = $takeUnused($sections, $usedIds, 'trust_bar');
    $overview = $takeUnused($sections, $usedIds, 'rich_text');
    $scope = $takeUnused($sections, $usedIds, 'service_intro_split');
    $caseStudies = $takeUnused($sections, $usedIds, 'before_after_grid');
    $pricing = $takeUnused($sections, $usedIds, 'pricing_table');
    $midCta = $takeUnused($sections, $usedIds, 'cta_block');
    $localExpertise = $takeUnused($sections, $usedIds, 'rich_text');
    $process = $takeUnused($sections, $usedIds, 'process_steps');
    $timeline = $takeUnused($sections, $usedIds, 'feature_grid');
    $areas = $takeUnused($sections, $usedIds, 'areas_served');
    $faq = $takeUnused($sections, $usedIds, 'faq_list');
    $trustStrip = $takeUnused($sections, $usedIds, 'trust_bar');
    $brands = $takeUnused($sections, $usedIds, 'logo_strip');
    $lead = $takeUnused($sections, $usedIds, 'lead_form');
    $financing = $takeUnused($sections, $usedIds, 'rich_text');
    $related = $takeUnused($sections, $usedIds, 'project_highlights');

    if (!$midCta) {
        $midCta = new Section([
            'page_id' => $page->id,
            'type' => 'cta_block',
            'is_active' => true,
            'data' => [],
        ]);
        $midCta->save();
        $usedIds[] = $midCta->id;
    }

    $ordered = [
        $hero,
        $heroTrust,
        $overview,
        $scope,
        $caseStudies,
        $pricing,
        $midCta,
        $localExpertise,
        $process,
        $timeline,
        $areas,
        $faq,
        $trustStrip,
        $brands,
        $lead,
        $financing,
        $related,
    ];

    foreach ($ordered as $sortOrder => $section) {
        if (!$section) {
            continue;
        }
        $section->sort_order = $sortOrder;
        $section->is_active = true;
        $section->save();
    }

    foreach ($sections as $section) {
        if (!in_array($section->id, $usedIds, true)) {
            $section->delete();
        }
    }

    if ($hero) {
        $data = $hero->data ?? [];
        $data['title'] = 'Bathroom Remodeling in Connecticut';
        $data['subtitle'] = 'Bathroom remodeling in Connecticut ranges from $15,000 to $120,000+, typically completed in 4 to 8 weeks. Walk-in showers, custom tile, vanities, and fixtures, fully managed from demolition to final walkthrough.';
        $data['background_image'] = '/images/headers/bathroom-remodeling-header.jpg';
        $data['primary_cta'] = ['label' => 'Get Your Free Estimate', 'url' => '#contact'];
        $hero->data = $data;
        $hero->save();
    }

    if ($heroTrust) {
        $heroTrust->data = [
            'variant' => 'hero_stats',
            'items' => [
                ['icon' => 'clock', 'label' => 'Years of Experience', 'value' => '15+', 'url' => null],
                ['icon' => 'check', 'label' => 'Completed Projects', 'value' => '100+', 'url' => null],
                ['icon' => 'star', 'label' => 'Google Rating', 'value' => '4.9', 'url' => 'https://www.google.com/maps/search/?api=1&query=BuiltWell+CT,+206A+Boston+Post+Road,+Orange,+CT+06477'],
                ['icon' => 'shield', 'label' => 'Fully Bonded & Insured', 'value' => null, 'url' => null],
            ],
        ];
        $heroTrust->save();
    }

    if ($scope) {
        $data = $scope->data ?? [];
        $data['title'] = 'What Is Included in a Bathroom Remodel';
        $data['highlight_text'] = 'Bathroom Remodel';
        $data['image_main'] = '/services/bathroom-remodeling-luxury-master-ct-01.jpeg';
        $data['image_secondary'] = '/services/bathroom-remodeling-master-suite-ct-02.jpeg';
        $data['content'] = "A complete bathroom remodel covers demolition, waterproofing, tile, plumbing fixtures, vanity, electrical, ventilation, and all required permits, managed as a single coordinated project from start to final walkthrough.\n\nWe begin with full demolition of existing tile, fixtures, vanity, and flooring. Before any new materials go in, we inspect the subfloor, wall framing, and plumbing lines. Connecticut homes built before 1990 frequently turn up galvanized drain lines that have corroded, inadequate subfloor support for heavy tile, or plumbing venting that does not meet current code.\n\nWaterproofing is installed behind all shower and tub tile using membrane systems. We use cement board in wet areas as a standard practice. New work includes tile installation on floors and walls, vanity and countertop installation, all plumbing fixture connections, electrical updates for lighting and outlets, and exhaust fan installation or replacement.\n\nEvery project includes permit coordination, daily cleanup, dust barriers to protect adjacent rooms, and a final walkthrough where we review every detail together.";
        $data['bullet_points'] = [
            ['text' => 'Demolition', 'description' => 'Full removal of existing tile, fixtures, vanity, and flooring. Subfloor and framing inspection before any new work begins.'],
            ['text' => 'Waterproofing', 'description' => 'Membrane systems behind all shower and tub tile. Cement board in wet areas as standard practice.'],
            ['text' => 'Tile Work', 'description' => 'Floor and wall tile installation with proper substrate preparation and grout finishing.'],
            ['text' => 'Plumbing', 'description' => 'All fixture connections, drain lines, supply lines, and shower valve installation. Relocated lines as needed.'],
            ['text' => 'Electrical', 'description' => 'Updated lighting, GFCI outlets, exhaust fan installation, and new circuits as required by code.'],
            ['text' => 'Vanity & Countertop', 'description' => 'Vanity installation with countertop, faucet, and mirror. Stock, semi-custom, or custom options available.'],
            ['text' => 'Ventilation', 'description' => 'Properly sized exhaust fan ducted to exterior. We correct improperly vented fans found during demolition.'],
            ['text' => 'Permits & Finish', 'description' => 'All permit applications, inspection coordination, interior painting, drywall patching, and final walkthrough.'],
        ];
        $scope->data = $data;
        $scope->save();
    }

    if ($caseStudies) {
        $data = $caseStudies->data ?? [];
        $data['title'] = 'Recent Bathroom Remodeling Projects';
        $data['title_highlight'] = 'Remodeling Projects';
        $data['subtitle'] = "The projects below give you a sense of what we've completed in Connecticut recently.";
        $data['projects'] = [
            [
                'location' => 'Primary Bathroom in Westport',
                'description' => 'Full gut renovation with walk-in shower, frameless glass, floor-to-ceiling tile, and 48-inch vanity with stone countertop. Completed in four weeks.',
                'before_image' => '/images/before-after/bathroom-renovation-1.jpg',
                'after_image' => '/images/before-after/bathroom-renovation-1.jpg',
                'testimonial_quote' => 'Four weeks of construction, and now I have the bathroom I have been wanting for years. - Westport Homeowner',
            ],
            [
                'location' => 'Hall Bathroom in Hamden',
                'description' => 'Tub-to-shower conversion with custom tile, new vanity, GFCI outlets, and properly ducted exhaust fan. Subfloor repairs addressed during demo.',
                'before_image' => '/images/before-after/bathroom-renovation-2.jpg',
                'after_image' => '/images/before-after/bathroom-renovation-2.jpg',
                'testimonial_quote' => 'They found issues behind the walls and fixed them properly before finishing. That is what a professional contractor does. - Hamden Homeowner',
            ],
            [
                'location' => 'Guest Bathroom in Fairfield',
                'description' => 'Complete refresh with new tile floors, updated vanity, modern lighting, and fresh paint. Clean, straightforward three-week project.',
                'before_image' => '/images/before-after/bathroom-renovation-3.jpg',
                'after_image' => '/images/before-after/bathroom-renovation-3.jpg',
                'testimonial_quote' => 'Clean work site every day, finished ahead of schedule. Exactly what we were looking for. - Fairfield Homeowner',
            ],
        ];
        $caseStudies->data = $data;
        $caseStudies->save();
    }

    if ($pricing) {
        $data = $pricing->data ?? [];
        $data['title'] = 'Bathroom Remodeling Cost in Connecticut';
        $data['title_highlight'] = 'Connecticut';
        $data['subtitle'] = "Bathroom remodeling in Connecticut costs \$15,000 to \$120,000 or more depending on the bathroom's size, the quality of fixtures and tile selected, and whether the project involves any layout changes or room expansion.";
        $data['columns'] = ['Scope', 'Cost Range', "What's Typically Included"];
        $data['rows'] = [
            ['label' => 'Basic', 'price' => '$15,000 – $25,000', 'notes' => 'New fixtures, vanity, flooring, tile, paint'],
            ['label' => 'Mid-Range', 'price' => '$25,000 – $55,000', 'notes' => 'Full gut renovation, new tile, shower or tub, vanity, lighting'],
            ['label' => 'High-End', 'price' => '$55,000 – $80,000', 'notes' => 'Custom tile, walk-in shower, premium fixtures, heated floors'],
            ['label' => 'Expansion / Layout Change', 'price' => '$80,000 – $120,000+', 'notes' => 'Layout changes, bathroom addition, high-end finishes throughout'],
        ];
        $pricing->data = $data;
        $pricing->save();
    }

    if ($midCta) {
        $midCta->data = [
            'title' => 'Ready to Begin Your Bathroom Remodel?',
            'title_highlight' => 'Bathroom Remodel',
            'subtitle' => 'Great bathroom remodeling starts with the right team.',
            'button' => ['label' => 'Get Your Free Estimate', 'url' => '#contact'],
            'subtext' => 'On-site or remote via Google Meet. No charge, no obligation.',
        ];
        $midCta->save();
    }

    if ($process) {
        $data = $process->data ?? [];
        $data['eyebrow'] = 'Our Process';
        $data['title'] = 'Our Bathroom Remodeling Process';
        $data['title_highlight'] = 'Remodeling Process';
        $data['subtitle'] = 'Every bathroom remodel follows the same five-step process. This consistency is how we keep projects on schedule, communicate clearly throughout, and deliver finished results that hold up.';
        $data['steps'] = [
            ['title' => 'Consultation', 'description' => 'We visit your home or connect via Google Meet or Zoom to discuss your goals, assess the space, and answer your questions. No charge. No obligation.'],
            ['title' => 'Planning', 'description' => "You receive a clear written proposal covering exactly what's included, how long it will take, and what it costs. Line items are specific and broken out separately. No surprises mid-project."],
            ['title' => 'Selections', 'description' => 'We guide you through material choices including tile, vanity, countertop, fixtures, shower enclosure, and lighting, with options at different price points and clear lead time communication.'],
            ['title' => 'Build', 'description' => 'Construction begins on the agreed schedule. You receive daily updates on progress, a clean job site at the end of every workday, and crews who arrive when scheduled.'],
            ['title' => 'Walkthrough', 'description' => 'We walk through the finished project together. We check every grout line, every fixture connection, every light, every drawer. Your written acceptance at the final walkthrough is the last step.'],
            ['title' => 'Get Started', 'description' => '', 'is_cta' => true, 'cta_url' => '#contact', 'cta_icon' => 'phone'],
        ];
        $process->data = $data;
        $process->save();
    }

    if ($timeline) {
        $data = $timeline->data ?? [];
        $data['title'] = 'Project Timeline';
        $data['title_highlight'] = 'Timeline';
        $data['subtitle'] = 'Most full bathroom remodels complete in five to twelve weeks from signed proposal to final walkthrough.';
        $data['items'] = [
            ['title' => 'Phase 1: Planning & Design', 'description' => '1–2 Weeks. Consultation, measurements, material selections, and detailed proposal.'],
            ['title' => 'Phase 2: Material Lead Time', 'description' => '2–4 Weeks. Specialty tile, custom vanities, and premium fixtures ordered and delivered.'],
            ['title' => 'Phase 3: Construction', 'description' => '3–6 Weeks. Demo, plumbing, electrical, waterproofing, tile, vanity, and fixtures.'],
            ['title' => 'Phase 4: Final Touches', 'description' => '3–5 Days. Grouting, hardware, final connections, and walkthrough.'],
        ];
        $timeline->data = $data;
        $timeline->save();
    }

    if ($areas) {
        $data = $areas->data ?? [];
        $data['eyebrow'] = 'Where We Work';
        $data['title'] = 'Bathroom Remodeling Across Two Counties';
        $data['highlight_text'] = 'Two Counties';
        $data['subtitle'] = 'We provide bathroom remodeling throughout Fairfield and New Haven Counties, with dedicated teams serving both regions.';
        $data['note_html'] = 'Not sure if we cover your area? <a href="/contact/">Contact our Connecticut remodeling team</a> and we\'ll let you know.';
        if (isset($data['counties'][0])) {
            $data['counties'][0]['initial_visible_towns'] = 9;
            $data['counties'][0]['towns'] = ['Greenwich', 'Stamford', 'Norwalk', 'Westport', 'Darien', 'New Canaan', 'Fairfield', 'Ridgefield', 'Trumbull'];
            $data['counties'][0]['extra_towns'] = ['Bethel', 'Bridgeport', 'Brookfield', 'Danbury', 'Easton', 'Monroe', 'New Fairfield', 'Newtown', 'Redding', 'Shelton', 'Sherman', 'Stratford', 'Weston', 'Wilton'];
            $data['counties'][0]['town_links']['Trumbull'] = '/fairfield-county/';
            $data['counties'][0]['cta_label'] = 'Learn more about Fairfield County';
            $data['counties'][0]['image'] = '/images/areas/fairfield-county.jpg';
        }
        if (isset($data['counties'][1])) {
            $data['counties'][1]['initial_visible_towns'] = 9;
            $data['counties'][1]['towns'] = ['Orange', 'New Haven', 'Hamden', 'Branford', 'Guilford', 'Madison', 'Woodbridge', 'Milford', 'Cheshire'];
            $data['counties'][1]['extra_towns'] = ['Ansonia', 'Beacon Falls', 'Bethany', 'Derby', 'East Haven', 'Meriden', 'Middlebury', 'Naugatuck', 'North Branford', 'North Haven', 'Oxford', 'Prospect', 'Seymour', 'Southbury', 'Wallingford', 'Waterbury', 'West Haven', 'Wolcott'];
            $data['counties'][1]['cta_label'] = 'Learn more about New Haven County';
            $data['counties'][1]['image'] = '/images/areas/new-haven-county.jpg';
        }
        $areas->data = $data;
        $areas->save();
    }

    if ($faq) {
        $data = $faq->data ?? [];
        $data['title'] = 'Bathroom Remodeling Questions';
        $faq->data = $data;
        $faq->save();
    }

    if ($trustStrip) {
        $trustStrip->data = [
            'variant' => 'service_trust_strip',
            'items' => [
                ['icon' => 'star', 'label' => 'Google Rating', 'value' => '4.9', 'url' => 'https://www.google.com/search?q=builtwell+ct+reviews'],
                ['icon' => 'check', 'label' => 'Trusted on Houzz', 'value' => '', 'url' => 'https://www.houzz.com/professionals/general-contractors/builtwell-ct'],
                ['icon' => 'calendar', 'label' => 'CT HIC License', 'value' => '#0668405', 'url' => 'https://www.elicense.ct.gov/Lookup/LicenseLookup.aspx'],
                ['icon' => 'check', 'label' => 'Verified on Angi', 'value' => '', 'url' => 'https://www.angi.com/companylist/us/ct/orange/builtwell-ct-reviews-'],
            ],
        ];
        $trustStrip->save();
    }

    if ($brands) {
        $data = $brands->data ?? [];
        $data['title'] = 'Materials We Stand Behind';
        $data['subtitle'] = 'We work exclusively with proven brands trusted by contractors and homeowners across Connecticut.';
        $brands->data = $data;
        $brands->save();
    }

    if ($lead) {
        $data = $lead->data ?? [];
        $data['eyebrow'] = 'GET IN TOUCH';
        $data['title'] = 'Ready to Start Your Bathroom Remodeling Project?';
        $data['title_highlight'] = 'Remodeling Project';
        $data['subtitle'] = 'Tell us about your project. We respond within one business day. No obligation.';
        $data['images'] = [
            ['image' => '/team/builtwell-owner-handshake-client-ct-02.jpg', 'alt' => 'BuiltWell CT owner meeting with a Connecticut homeowner for a remodeling consultation'],
            ['image' => '/portfolio/builtwell-job-site-aerial-ct.jpg', 'alt' => 'BuiltWell CT owner meeting homeowner for a free consultation'],
        ];
        $data['submit_label'] = 'Get Your Free Estimate';
        $data['consent_text'] = 'We respond within 24 hours. No spam, no obligation.';
        $lead->data = $data;
        $lead->save();
    }

    if ($financing) {
        $data = $financing->data ?? [];
        $data['title'] = 'Flexible Financing Available';
        $data['content'] = 'Get approved in about 60 seconds and start your project today.';
        $data['style_variant'] = 'financing_strip';
        $data['cta'] = ['label' => 'Check Financing Options', 'url' => 'https://www.greensky.com'];
        $financing->data = $data;
        $financing->save();
    }

    if ($related) {
        $data = $related->data ?? [];
        $data['eyebrow'] = 'Related Services';
        $data['title'] = 'You May Also Need';
        $data['items'] = [
            [
                'title' => 'Kitchen Remodeling',
                'description' => 'Complete kitchen renovations including cabinetry, countertops, islands, appliances, tile, lighting, and plumbing across Fairfield and New Haven Counties.',
                'image' => '/services/kitchen-remodeling-ct.jpg',
                'url' => '/kitchen-remodeling/',
            ],
            [
                'title' => 'Flooring',
                'description' => 'Hardwood, luxury vinyl plank, tile, and engineered wood flooring installation with expert subfloor preparation.',
                'image' => '/services/flooring-installation-ct.jpg',
                'url' => '/flooring/',
            ],
            [
                'title' => 'Comfort & Accessibility',
                'description' => 'Aging-in-place renovations including curbless showers, grab bars, wider doorways, and barrier-free design for safe, comfortable living.',
                'image' => '/services/comfort-accessibility-ct.jpg',
                'url' => '/comfort-accessibility-remodeling/',
            ],
        ];
        $related->data = $data;
        $related->save();
    }
});

echo "Updated bathroom remodeling page content and order.\n";
