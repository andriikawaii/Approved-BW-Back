<?php

use App\Models\Page;
use App\Models\Section;
use Illuminate\Support\Facades\Cache;

$page = Page::where('full_path', '/case-studies/basement-finishing-darien')->first();

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
This project converted an 850 sq ft unfinished basement in a Darien colonial into finished living space, including a dedicated home office and an open gym area, completed in 6 weeks with all permits pulled and closed.

| Detail           | Information                                      |
|------------------|--------------------------------------------------|
| Client           | Steve R., Darien, CT                             |
| Location         | Darien, CT (Fairfield County)                    |
| Project Type     | Basement finishing                               |
| Scope            | 850 sq ft: home office, gym area, utility room   |
| Services         | Basement finishing, LVP flooring, egress window  |
| Timeline         | 6 weeks                                          |
| Permits          | Building, electrical, egress window, all closed  |
| County           | Fairfield County                                 |

Served by our Fairfield County Service Area Team.
TEXT;

$challengeContent = <<<'TEXT'
Steve's basement had been unfinished for the 12 years he'd lived in the house. The space held storage but nothing more. Darien's ledge rock geology was working against him: the slab had been poured around a rock formation, leaving one section of the floor uneven by several inches. That condition is not unusual in this part of Fairfield County, but it requires a contractor who has seen it before and knows how to address it properly.

Beyond the floor, a proper egress window was required by the Darien Building Department before any portion of the space could be classified as habitable. Without it, no permit, no certificate of occupancy, and no finished basement.

Steve had the space and he had the plan. What he didn't have was clarity on how to get started. He wasn't sure whether the ledge condition was a dealbreaker, how the permitting worked, or what the right sequence of steps looked like. He reached out to us to find out.
TEXT;

$approachContent = <<<'TEXT'
We assessed the basement in a single visit. The ledge condition was identified, measured, and accounted for in the proposal; it wasn't going to disappear, but it wasn't going to stop the project either. Our solution combined a perimeter drainage channel with a self-leveling compound application to create a flat, consistent floor surface across the full footprint.

Before framing started, we pulled all required permits from the Darien Building Department separately: building permit, electrical permit, and egress window permit. We coordinated the egress window cut and well installation as the first major site activity so that the inspection could be completed early and framing could proceed without interruption.

The work sequence from there:

- Interior drainage channel installation along the ledge section
- Spray foam insulation on all rim joists
- Framing for home office, open gym area, and enclosed utility room
- Electrical rough-in, recessed lighting layout, dedicated circuits
- Drywall, tape, finish
- LVP flooring throughout the finished areas
- Built-in shelving in the home office
- Final inspections and permit close-out

Every trade was coordinated in-house. Steve received daily updates throughout. When something needed a decision, he heard from us the same day.
TEXT;

$resultsContent = <<<'TEXT'
850 sq ft of finished living space, completed in 6 weeks. The home office has built-in shelving and dedicated lighting. The gym area has LVP flooring throughout and open space for equipment. The utility room is properly framed, enclosed, and separated from the finished areas.

All permits were closed. All inspections passed. The floor is flat.

Steve now has a finished basement that functions the way he planned 12 years ago. The ledge condition that might have stopped another contractor from starting became a solved problem in week one.
TEXT;

$beforeAfterContent = <<<'TEXT'
Images are listed in display order for the project gallery.
TEXT;

$relatedProjectsContent = <<<'TEXT'
We've completed similar projects across Fairfield County. Two recent examples:

- Bathroom Remodeling in Westport, CT: full gut renovation, walk-in shower, 4-week timeline. See the Westport bathroom project.
- Kitchen Remodeling in New Canaan, CT: custom inset cabinets, quartz countertops, LVP flooring, 10-week timeline. See the New Canaan kitchen project.

View all case studies.
TEXT;

Section::create([
    'page_id' => $page->id,
    'type' => 'case_study_header',
    'sort_order' => $sort++,
    'is_active' => true,
    'data' => [
        'title' => 'Basement Finishing in Darien, CT: 850 Sq Ft, 6 Weeks, Full Permits',
        'subtitle' => null,
        'cover_image' => 'darien-basement-home-office-completed.webp',
        'cover_alt' => 'Completed basement home office with built-in shelving in Darien CT — finished by BuiltWell CT',
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
            ['label' => 'Client', 'value' => 'Steve R., Darien, CT'],
            ['label' => 'Location', 'value' => 'Darien, CT (Fairfield County)'],
            ['label' => 'Project Type', 'value' => 'Basement finishing'],
            ['label' => 'Scope', 'value' => '850 sq ft: home office, gym area, utility room'],
            ['label' => 'Services', 'value' => 'Basement finishing, LVP flooring, egress window'],
            ['label' => 'Timeline', 'value' => '6 weeks'],
            ['label' => 'Permits', 'value' => 'Building, electrical, egress window, all closed'],
            ['label' => 'County', 'value' => 'Fairfield County'],
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
                'image' => 'darien-basement-before-ledge-rock.webp',
                'alt' => 'Unfinished Darien basement with visible ledge rock formation before finishing project began',
                'caption' => null,
            ],
            [
                'image' => 'darien-basement-egress-window-installed.webp',
                'alt' => 'Egress window installed with drainage well in Darien CT basement finishing project by BuiltWell',
                'caption' => null,
            ],
            [
                'image' => 'darien-basement-framing-in-progress.webp',
                'alt' => 'Basement framing in progress during finishing project at Darien CT colonial home',
                'caption' => null,
            ],
            [
                'image' => 'darien-basement-lvp-flooring-installation.webp',
                'alt' => 'LVP flooring installation in progress in Darien CT finished basement by BuiltWell',
                'caption' => null,
            ],
            [
                'image' => 'darien-basement-home-office-completed.webp',
                'alt' => 'Completed basement home office with built-in shelving in Darien CT — finished by BuiltWell CT',
                'caption' => null,
            ],
            [
                'image' => 'darien-basement-before-after-split.webp',
                'alt' => 'Before and after view from stairs — Darien CT basement finishing project by BuiltWell CT',
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
                'name' => 'Steve R.',
                'location' => 'Darien',
                'quote' => '"We should have done this ten years ago." - Steve R., Darien',
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
        'title' => 'Ready to Finish Your Basement?',
        'subtitle' => 'Phone: (203) 919-9616',
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
    'seo_title' => 'Basement Finishing in Darien, CT | BuiltWell Case Study',
    'seo_description' => '850 sq ft finished basement in Darien, CT — egress window, LVP flooring, full permits. Completed in 6 weeks. See the full project by BuiltWell.',
]);
echo "Updated SEO metadata rows: {$updatedSeo}\n";

Cache::flush();
echo "Cache flushed.\n";
echo "Done! {$sort} sections created.\n";
