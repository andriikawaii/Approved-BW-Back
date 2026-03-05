<?php

use App\Models\Page;
use App\Models\Section;
use Illuminate\Support\Facades\Cache;

$page = Page::where('full_path', '/case-studies/kitchen-remodeling-milford')->first();

if (!$page) {
    echo "Page not found!\n";
    return;
}

echo "Found page ID: {$page->id}\n";
echo "Template key: {$page->template_key}\n";

$deleted = Section::where('page_id', $page->id)->delete();
echo "Deleted {$deleted} existing sections.\n";

$sort = 0;

$projectOverviewContent = <<<'TEXT'
This project was a complete kitchen gut and rebuild combined with LVP flooring installation throughout the entire main level of an inland Milford home. The work covered new cabinets, countertops, lighting, backsplash, appliances, and continuous flooring through the kitchen, dining room, living room, and entry. All of it was finished in six weeks.

| Detail | Info |
|---|---|
| Location | Milford, CT (New Haven County) |
| Project Type | Full kitchen remodel and main-level flooring |
| Services | Kitchen remodeling, LVP flooring installation |
| Timeline | 6 weeks |
| Client | Ivana Petrovic, Milford |

Served from our Orange, CT office.
TEXT;

$challengeContent = <<<'TEXT'
The kitchen was original to the home: 1990s laminate cabinets, tile countertops, worn vinyl flooring. The layout still worked. There was nothing structurally wrong with it. But more than two decades of use had left the finishes looking exactly as dated as they were, and the kitchen felt cut off from the rest of the main level rather than connected to it.

Ivana also wanted consistent flooring throughout the first floor. The existing mix of vinyl in the kitchen and carpet elsewhere created visual interruptions that made the space feel smaller and more segmented. Running one continuous floor surface through the kitchen, dining room, living room, and entry was a practical way to address both problems at once.

The decision to combine the kitchen remodel and flooring into a single project was the right call. Flooring goes in after cabinetry is set and before appliances are placed, which means doing them as part of one coordinated project avoids scheduling gaps, protects new floors during the heaviest part of construction, and keeps the project moving on a single timeline.
TEXT;

$approachContent = <<<'TEXT'
We brought material samples to the home so selections could happen in the actual space rather than under showroom lighting. Ivana made all her choices in one session: semi-custom cabinet style and finish, quartz countertop slab, backsplash tile, LVP flooring color and profile, sink, faucet, and lighting fixtures. No separate trips to multiple suppliers, no back-and-forth over weeks.

The kitchen scope included new semi-custom cabinets, quartz countertops with an undermount sink, a tile backsplash, recessed lighting with pendant fixtures over the island, and upgraded appliances. The existing layout stayed in place, which kept the project on a clean timeline.

LVP flooring was installed in a single continuous run from the kitchen entry through the dining room and living room. Eliminating transitions between rooms was the point: one floor, one visual plane, no seams to trip over or collect dirt.

Throughout the six weeks, Ivana received daily updates on where the project stood. Crews arrived at the time we said they would and cleaned up at the end of every day. She never came home to tools left on the counters or debris accumulating in the rooms not yet under construction.
TEXT;

$resultsContent = <<<'TEXT'
Six weeks, on schedule. The kitchen now reads as part of the main level rather than a separate space. The quartz countertops, undermount sink, and backsplash tile hold up daily and clean easily. The recessed and pendant lighting changed how the room feels at different times of day in a way that the original fixtures couldn't.

The LVP flooring running through all four spaces (kitchen, dining room, living room, and entry) accomplished exactly what Ivana wanted. The first floor reads as one connected space. The floor holds up to foot traffic and is straightforward to maintain.

What Ivana noted at the end of the project was the consistency: crews showed up when scheduled, cleaned up each evening, and kept her informed throughout. A six-week project in an occupied home is a long time to be working around someone's daily life. Getting it right on the communication and job-site management side matters as much as getting the work itself right.

For a similar project involving water damage restoration across multiple trades in Hamden, see our whole-home restoration case study at /case-studies/whole-home-restoration-hamden/.
TEXT;

$beforeAfterContent = <<<'TEXT'
[IMAGE BLOCK — 4 to 6 images]
TEXT;

$relatedProjectsContent = <<<'TEXT'
- Whole-Home Restoration in Hamden: see /case-studies/whole-home-restoration-hamden/
- Kitchen Remodeling in New Canaan: see /case-studies/kitchen-remodeling-new-canaan/

Browse all case studies at /case-studies/.
TEXT;

Section::create([
    'page_id' => $page->id,
    'type' => 'case_study_header',
    'sort_order' => $sort++,
    'is_active' => true,
    'data' => [
        'title' => 'Full Kitchen Remodel and LVP Flooring in Milford, CT',
        'subtitle' => null,
        'cover_image' => 'milford-kitchen-completed-quartz-counters-cabinets.webp',
        'cover_alt' => 'Completed kitchen remodel with quartz countertops and new cabinets in Milford CT by BuiltWell',
    ],
]);
echo "Created: case_study_header\n";

Section::create([
    'page_id' => $page->id,
    'type' => 'case_study_meta',
    'sort_order' => $sort++,
    'is_active' => true,
    'data' => [
        'items' => [
            ['label' => 'Location', 'value' => 'Milford, CT (New Haven County)'],
            ['label' => 'Project Type', 'value' => 'Full kitchen remodel and main-level flooring'],
            ['label' => 'Services', 'value' => 'Kitchen remodeling, LVP flooring installation'],
            ['label' => 'Timeline', 'value' => '6 weeks'],
            ['label' => 'Client', 'value' => 'Ivana Petrovic, Milford'],
        ],
    ],
]);
echo "Created: case_study_meta\n";

Section::create([
    'page_id' => $page->id,
    'type' => 'case_study_body',
    'sort_order' => $sort++,
    'is_active' => true,
    'data' => [
        'blocks' => [
            ['heading' => 'Project Overview', 'content' => $projectOverviewContent],
            ['heading' => 'The Challenge', 'content' => $challengeContent],
            ['heading' => 'Our Approach', 'content' => $approachContent],
            ['heading' => 'The Results', 'content' => $resultsContent],
        ],
    ],
]);
echo "Created: case_study_body\n";

Section::create([
    'page_id' => $page->id,
    'type' => 'case_study_gallery',
    'sort_order' => $sort++,
    'is_active' => true,
    'data' => [
        'items' => [
            [
                'image' => 'milford-kitchen-original-laminate-cabinets-before.webp',
                'alt' => 'Original 1990s laminate kitchen cabinets in Milford CT home before remodel by BuiltWell',
                'caption' => null,
            ],
            [
                'image' => 'milford-kitchen-cabinet-installation-in-progress.webp',
                'alt' => 'Semi-custom kitchen cabinets being installed during Milford CT remodel by BuiltWell',
                'caption' => null,
            ],
            [
                'image' => 'milford-kitchen-completed-quartz-counters-cabinets.webp',
                'alt' => 'Completed kitchen remodel with quartz countertops and new cabinets in Milford CT by BuiltWell',
                'caption' => null,
            ],
            [
                'image' => 'milford-lvp-flooring-kitchen-living-area.webp',
                'alt' => 'Continuous LVP flooring installed through kitchen and living area in Milford CT by BuiltWell',
                'caption' => null,
            ],
            [
                'image' => 'milford-kitchen-backsplash-undermount-sink-detail.webp',
                'alt' => 'Tile backsplash and undermount sink detail in remodeled Milford CT kitchen by BuiltWell',
                'caption' => null,
            ],
            [
                'image' => 'milford-kitchen-entry-before-after.webp',
                'alt' => 'Before and after kitchen entry view after full remodel in Milford CT by BuiltWell',
                'caption' => null,
            ],
        ],
    ],
]);
echo "Created: case_study_gallery\n";

Section::create([
    'page_id' => $page->id,
    'type' => 'rich_text',
    'sort_order' => $sort++,
    'is_active' => true,
    'data' => [
        'eyebrow' => null,
        'title' => 'Before and After',
        'content' => $beforeAfterContent,
        'image' => null,
        'image_alt' => null,
        'image_position' => 'right',
        'cta' => ['label' => '', 'url' => ''],
        'align' => 'left',
        'variant' => 'default',
    ],
]);
echo "Created: rich_text (Before and After)\n";

Section::create([
    'page_id' => $page->id,
    'type' => 'testimonials',
    'sort_order' => $sort++,
    'is_active' => true,
    'data' => [
        'title' => 'What the Client Said',
        'subtitle' => null,
        'layout' => 'grid',
        'items' => [
            [
                'name' => 'Ivana P.',
                'location' => 'Milford',
                'quote' => '"BuiltWell made it easy. They showed up when they said they would, cleaned up every day." - Ivana P., Milford',
                'avatar' => null,
                'rating' => 5,
                'year' => null,
            ],
        ],
    ],
]);
echo "Created: testimonials\n";

Section::create([
    'page_id' => $page->id,
    'type' => 'rich_text',
    'sort_order' => $sort++,
    'is_active' => true,
    'data' => [
        'eyebrow' => null,
        'title' => 'Related Projects',
        'content' => $relatedProjectsContent,
        'image' => null,
        'image_alt' => null,
        'image_position' => 'right',
        'cta' => ['label' => '', 'url' => ''],
        'align' => 'left',
        'variant' => 'default',
    ],
]);
echo "Created: rich_text (Related Projects)\n";

Section::create([
    'page_id' => $page->id,
    'type' => 'cta_block',
    'sort_order' => $sort++,
    'is_active' => true,
    'data' => [
        'eyebrow' => null,
        'title' => 'Ready to Start Your Kitchen Project?',
        'subtitle' => 'Phone: (203) 466-9148',
        'button' => [
            'label' => 'Get a Free Estimate',
            'url' => '/free-consultation/',
        ],
        'subtext' => 'On-site or remote (Google Meet or Zoom)',
        'variant' => 'default',
    ],
]);
echo "Created: cta_block\n";

$updatedSeo = Page::where('id', $page->id)->update([
    'seo_title' => 'Kitchen Remodeling in Milford, CT | BuiltWell Case Study',
    'seo_description' => 'Full kitchen remodel and LVP flooring installation in Milford, CT. Completed in 6 weeks. BuiltWell — clear communication, daily cleanup, no surprises.',
]);
echo "Updated SEO metadata rows: {$updatedSeo}\n";

Cache::flush();
echo "Cache flushed.\n";
echo "Done! {$sort} sections created.\n";
