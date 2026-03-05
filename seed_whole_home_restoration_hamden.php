<?php

use App\Models\Page;
use App\Models\Section;
use Illuminate\Support\Facades\Cache;

$page = Page::where('full_path', '/case-studies/whole-home-restoration-hamden')->first();

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
This project involved full interior restoration of a Hamden colonial following a burst pipe during a winter cold snap. We coordinated flooring, drywall repair, bathroom remodeling, and interior painting across multiple rooms. The work was completed in five weeks under a single contract.

| Detail | Info |
|---|---|
| Location | Hamden, CT (New Haven County) |
| Project Type | Whole-home water damage restoration |
| Services | Flooring, drywall repair and replacement, bathroom remodeling, interior painting |
| Timeline | 5 weeks |
| Client | The Martins, Hamden |

Served from our Orange, CT office.
TEXT;

$challengeContent = <<<'TEXT'
When the pipe burst, the damage spread fast. By the time the Martins assessed the full extent, it touched nearly every part of the main living area: the subfloor and hardwood flooring in the living and dining rooms were saturated, drywall in two rooms and the hallway had absorbed enough moisture to require replacement, the main bathroom subfloor and tile were compromised, and moisture staining had worked its way into the paint on multiple walls and ceilings.

The practical problem wasn't just the scope. It was the coordination. Water damage of this scale typically means calling a flooring company, a tile contractor, a drywall crew, and a painter separately. Each one schedules independently. Each one may not sequence their work to set up the next trade correctly. The Martins were already dealing with the disruption of a home that wasn't fully livable. Managing four or five separate contractors through that process adds real stress to an already difficult situation.

They needed one contractor who could assess the full scope, pull the required permits for New Haven County, and execute the work in the right order without them having to referee it.
TEXT;

$approachContent = <<<'TEXT'
We started with a single on-site consultation that covered every affected area. Rather than scoping trades separately, we assessed the full extent of the damage together: subfloor conditions, drywall moisture readings, bathroom tile and fixture status, and paint throughout. One proposal covered all of it.

Sequencing mattered here. Drywall had to come first so that framing could fully dry before anything went over it. Flooring followed once the subfloor was confirmed sound and level. The bathroom was addressed as a complete unit: new subfloor, tile, vanity, and fixtures. Interior painting came last, after all surfaces were prepared and stable.

We pulled all required permits through New Haven County before work started. Throughout the five weeks, the Martins received daily updates on progress. Crews arrived on time each morning and cleaned the site at the end of every day. A home undergoing this kind of work is already disruptive enough. A disorganized job site makes it worse.
TEXT;

$resultsContent = <<<'TEXT'
Five weeks from start to finish. New LVP flooring installed throughout the living and dining areas, replacing the water-damaged hardwood and subfloor. The main bathroom was fully remodeled: new subfloor, tile floor and surround, vanity, and fixtures. Drywall in both affected rooms and the hallway was repaired and refinished. Interior paint was refreshed throughout the affected areas, covering all moisture staining.

The home returned to full livable condition on the timeline we committed to at the start. For the Martins, the speed wasn't just a convenience. Their insurance claim timeline depended on it.
TEXT;

$beforeAfterContent = <<<'TEXT'
[IMAGE BLOCK — 4 to 6 images]
TEXT;

$relatedProjectsContent = <<<'TEXT'
If you're considering a bathroom remodel or flooring project, these case studies show similar work we've completed in the region:

- Kitchen Remodeling and LVP Flooring in Milford: see /case-studies/kitchen-remodeling-milford/
- Basement Finishing in Darien: see /case-studies/basement-finishing-darien/

Browse all case studies at /case-studies/.
TEXT;

Section::create([
    'page_id' => $page->id,
    'type' => 'case_study_header',
    'sort_order' => $sort++,
    'is_active' => true,
    'data' => [
        'title' => 'Whole-Home Restoration After Water Damage in Hamden, CT',
        'subtitle' => null,
        'cover_image' => 'hamden-living-area-final-result.webp',
        'cover_alt' => 'Final result living area with new LVP flooring and fresh interior paint in Hamden CT',
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
            ['label' => 'Location', 'value' => 'Hamden, CT (New Haven County)'],
            ['label' => 'Project Type', 'value' => 'Whole-home water damage restoration'],
            ['label' => 'Services', 'value' => 'Flooring, drywall repair and replacement, bathroom remodeling, interior painting'],
            ['label' => 'Timeline', 'value' => '5 weeks'],
            ['label' => 'Client', 'value' => 'The Martins, Hamden'],
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
                'image' => 'hamden-water-damaged-flooring-before.webp',
                'alt' => 'Water-damaged hardwood flooring in Hamden CT home before restoration by BuiltWell',
                'caption' => null,
            ],
            [
                'image' => 'hamden-drywall-repair-in-progress.webp',
                'alt' => 'Drywall repair in progress during whole-home restoration in Hamden CT by BuiltWell',
                'caption' => null,
            ],
            [
                'image' => 'hamden-new-lvp-flooring-living-area.webp',
                'alt' => 'New LVP flooring installed in living area of Hamden CT home by BuiltWell',
                'caption' => null,
            ],
            [
                'image' => 'hamden-bathroom-remodel-completed.webp',
                'alt' => 'Completed bathroom remodel with new tile vanity and fixtures in Hamden CT by BuiltWell',
                'caption' => null,
            ],
            [
                'image' => 'hamden-hallway-before-after-paint.webp',
                'alt' => 'Before and after hallway paint in Hamden CT whole-home restoration by BuiltWell',
                'caption' => null,
            ],
            [
                'image' => 'hamden-living-area-final-result.webp',
                'alt' => 'Final result living area with new LVP flooring and fresh interior paint in Hamden CT',
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
        'title' => 'What the Clients Said',
        'subtitle' => null,
        'layout' => 'grid',
        'items' => [
            [
                'name' => 'The Martins',
                'location' => 'Hamden',
                'quote' => '"We were devastated when we saw the damage. BuiltWell took everything off our plate." - The Martins, Hamden',
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
        'title' => 'Talk to Us About Your Project',
        'subtitle' => 'Phone: (203) 466-9148',
        'button' => [
            'label' => 'Schedule a Free Consultation',
            'url' => '/free-consultation/',
        ],
        'subtext' => 'On-site or remote (Google Meet or Zoom)',
        'variant' => 'default',
    ],
]);
echo "Created: cta_block\n";

$updatedSeo = Page::where('id', $page->id)->update([
    'seo_title' => 'Whole-Home Restoration in Hamden, CT | BuiltWell Case Study',
    'seo_description' => 'BuiltWell restored a Hamden home following water damage — flooring, drywall, bathroom, and interior painting completed in 5 weeks. See the full project.',
]);
echo "Updated SEO metadata rows: {$updatedSeo}\n";

Cache::flush();
echo "Cache flushed.\n";
echo "Done! {$sort} sections created.\n";
