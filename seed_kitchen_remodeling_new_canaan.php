<?php

use App\Models\Page;
use App\Models\Section;
use Illuminate\Support\Facades\Cache;

$page = Page::where('full_path', '/case-studies/kitchen-remodeling-new-canaan')->first();

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
This project replaced a 1980s laminate kitchen with custom inset cabinets, quartz countertops, subway tile backsplash, and LVP flooring continuous with the adjacent living areas, completed across a 10-week timeline that included an 8-week custom cabinet lead time.

| Detail           | Information                                                     |
|------------------|-----------------------------------------------------------------|
| Client           | The Chens, New Canaan, CT                                       |
| Location         | New Canaan, CT (Fairfield County)                               |
| Project Type     | Full kitchen remodel with flooring installation                 |
| Scope            | Kitchen gut renovation: cabinets, counters, backsplash, flooring |
| Services         | Kitchen remodeling, flooring installation                       |
| Timeline         | 10 weeks total (8-week cabinet lead time, 2 weeks active build) |
| Permits          | Building and electrical permits, New Canaan Building Department  |
| County           | Fairfield County                                                |

Served by our Fairfield County Service Area Team.
TEXT;

$challengeContent = <<<'TEXT'
The Chens had lived with their 1980s kitchen for several years since buying the home. The cabinets were laminate construction with worn edges. The countertops were laminate over plywood. The layout functioned, but the kitchen felt disconnected from the open living area visible through the doorway, and the finishes didn't match the quality of the rest of the house.

They knew what they wanted: custom inset cabinets with a painted finish and a clean, well-considered design that would hold up in a New Canaan colonial. Inset doors are a more demanding cabinet type to build and install than overlay doors, and they require a manufacturer with the precision to deliver consistent tolerances. The 8-week lead time that comes with custom work was a factor the Chens needed to plan around, not a reason to settle for something off the shelf.

New Canaan's permitting process is among the more thorough in Fairfield County. We factored that into the schedule from the start so the permit was in hand before the cabinet order was placed.
TEXT;

$approachContent = <<<'TEXT'
The cabinet order went in during week one. While the cabinets were being built, the work that didn't depend on their arrival moved forward.

Weeks 1 through 8:

- Building and electrical permits pulled from the New Canaan Building Department
- Existing kitchen flooring demolished
- LVP flooring installed in adjacent rooms, running continuously through to the kitchen footprint to ensure a consistent floor level at cabinet installation
- Electrical rough-in for under-cabinet lighting circuits
- New dedicated circuits for appliances laid in advance
- Appliance delivery coordination confirmed for week 9

Week 9 and 10 (active kitchen build):

- Custom inset cabinet boxes and doors installed
- Quartz countertop template taken on day one of cabinet install, fabricated and returned within days
- Subway tile backsplash set and grouted
- Undermount sink, faucet, and garbage disposal installed
- Under-cabinet lighting connected and trimmed
- Appliances set and connected
- Final inspection, permit close-out

The Chens were kept current throughout the cabinet lead time so they always knew where things stood. When the cabinets arrived, the space was ready for them.
TEXT;

$resultsContent = <<<'TEXT'
A fully remodeled kitchen with custom inset cabinets in a painted finish, quartz countertops, subway tile backsplash, undermount sink, under-cabinet lighting, and LVP flooring running continuously from the kitchen into the adjacent living areas. The kitchen now connects visually and materially to the rest of the home in a way the original layout never achieved.

All permits closed. All inspections passed. The 10-week total timeline was communicated from the beginning, and the project finished within it.

The Chens noted that once they understood the cabinet lead time upfront, the process unfolded exactly as described. No surprises. No gaps in communication. Work moved when it was supposed to move.
TEXT;

$beforeAfterContent = <<<'TEXT'
Images are listed in display order for the project gallery.
TEXT;

$relatedProjectsContent = <<<'TEXT'
Two other recent projects from Fairfield County:

- Bathroom Remodeling in Westport, CT: full gut renovation, walk-in shower, double vanity, 4-week timeline. See the Westport bathroom project.
- Basement Finishing in Darien, CT: 850 sq ft, egress window, LVP flooring, 6-week timeline. See the Darien basement project.

View all case studies.
TEXT;

Section::create([
    'page_id' => $page->id,
    'type' => 'case_study_header',
    'sort_order' => $sort++,
    'is_active' => true,
    'data' => [
        'title' => 'Kitchen Remodeling in New Canaan, CT: Custom Cabinets, 10-Week Timeline',
        'subtitle' => null,
        'cover_image' => 'new-canaan-kitchen-completed-cabinets-counters.webp',
        'cover_alt' => 'Completed kitchen with custom inset cabinets and quartz countertops — New Canaan CT remodel by BuiltWell',
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
            ['label' => 'Client', 'value' => 'The Chens, New Canaan, CT'],
            ['label' => 'Location', 'value' => 'New Canaan, CT (Fairfield County)'],
            ['label' => 'Project Type', 'value' => 'Full kitchen remodel with flooring installation'],
            ['label' => 'Scope', 'value' => 'Kitchen gut renovation: cabinets, counters, backsplash, flooring'],
            ['label' => 'Services', 'value' => 'Kitchen remodeling, flooring installation'],
            ['label' => 'Timeline', 'value' => '10 weeks total (8-week cabinet lead time, 2 weeks active build)'],
            ['label' => 'Permits', 'value' => 'Building and electrical permits, New Canaan Building Department'],
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
                'image' => 'new-canaan-kitchen-before-laminate-cabinets.webp',
                'alt' => 'Original 1980s New Canaan CT kitchen with laminate cabinets and countertops before remodel',
                'caption' => null,
            ],
            [
                'image' => 'new-canaan-kitchen-cabinet-installation.webp',
                'alt' => 'Custom inset cabinet boxes being installed in New Canaan CT kitchen remodel by BuiltWell',
                'caption' => null,
            ],
            [
                'image' => 'new-canaan-kitchen-quartz-countertop.webp',
                'alt' => 'Quartz countertop installation in progress in New Canaan CT kitchen remodel by BuiltWell CT',
                'caption' => null,
            ],
            [
                'image' => 'new-canaan-kitchen-completed-cabinets-counters.webp',
                'alt' => 'Completed kitchen with custom inset cabinets and quartz countertops — New Canaan CT remodel by BuiltWell',
                'caption' => null,
            ],
            [
                'image' => 'new-canaan-kitchen-lvp-flooring-continuous.webp',
                'alt' => 'LVP flooring running continuously from kitchen into living area — New Canaan CT kitchen project by BuiltWell',
                'caption' => null,
            ],
            [
                'image' => 'new-canaan-kitchen-before-after-split.webp',
                'alt' => 'Before and after view from dining area — New Canaan CT kitchen remodel by BuiltWell CT',
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
                'name' => 'The Chens',
                'location' => 'New Canaan',
                'quote' => '"BuiltWell made it straightforward. Now we can\'t imagine how we lived before." - The Chens, New Canaan',
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
        'title' => 'Planning a Kitchen Remodel in Fairfield County?',
        'subtitle' => 'Phone: (203) 919-9616',
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
    'seo_title' => 'Kitchen Remodeling in New Canaan, CT | BuiltWell Case Study',
    'seo_description' => 'Full kitchen remodel and new flooring in New Canaan, CT. Custom cabinets, quartz counters, 10-week timeline. See the full project by BuiltWell.',
]);
echo "Updated SEO metadata rows: {$updatedSeo}\n";

Cache::flush();
echo "Cache flushed.\n";
echo "Done! {$sort} sections created.\n";
