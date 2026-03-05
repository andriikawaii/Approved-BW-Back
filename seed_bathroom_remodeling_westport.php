<?php

use App\Models\Page;
use App\Models\Section;
use Illuminate\Support\Facades\Cache;

$page = Page::where('full_path', '/case-studies/bathroom-remodeling-westport')->first();

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
This project was a full gut renovation of a master bathroom in a 1960s Westport colonial, replacing original fixtures and tile with a walk-in shower, double vanity with quartz top, and updated porcelain tile, completed in 4 weeks.

| Detail           | Information                                              |
|------------------|----------------------------------------------------------|
| Client           | Jennifer M., Westport, CT                                |
| Location         | Westport, CT (Fairfield County)                          |
| Project Type     | Full bathroom remodel (gut renovation)                   |
| Scope            | Master bathroom: shower, vanity, tile, fixtures, lighting |
| Services         | Bathroom remodeling                                      |
| Timeline         | 4 weeks                                                  |
| Permits          | Building permit, Westport Building Department            |
| County           | Fairfield County                                         |

Served by our Fairfield County Service Area Team.
TEXT;

$challengeContent = <<<'TEXT'
Jennifer's master bathroom was original to the house. Harvest gold fixtures, 4-inch ceramic tile on the walls, and a tub-shower combo that hadn't been used in years because the tub was worn and the layout felt too small to work in. The bathroom was functional in the most minimal sense, but it had not been updated since the home was built.

The 1960s-era waterproofing was the more serious issue. Original construction from that period used materials and techniques that don't hold up by today's standards. Moisture had been working against that bathroom for decades. Any proper renovation had to address the waterproofing completely, not just cover over it.

Jennifer wanted a walk-in shower with a modern tile profile, a double vanity, and a bathroom that felt like it belonged in the rest of her home. The goal was clear. The path to get there required a full gut to the studs and a rebuild from the inside out.
TEXT;

$approachContent = <<<'TEXT'
We pulled the permit through the Westport Building Department before demolition began. The full gut exposed the framing and allowed us to inspect the subfloor condition and confirm the scope of the waterproofing work needed.

The rebuild sequence:

- Cement board substrate on all shower walls and the floor
- RedGard membrane waterproofing layer over cement board
- Large-format tile in the walk-in shower, set to pattern
- Frameless glass panel enclosure for the shower
- Rainfall showerhead and hand shower on separate valves
- New double vanity with quartz top and undermount sinks
- Porcelain tile floor throughout
- New recessed lighting, vanity mirror, exhaust fan with humidity sensor
- All fixtures and trim installed and inspected

Because Jennifer was living in the home throughout the project, we sequenced the work carefully. The main-floor powder room was available as a backup and she never went without a functioning bathroom. Four weeks from demolition to completion.
TEXT;

$resultsContent = <<<'TEXT'
The master bathroom is now a finished, properly waterproofed space with materials that will hold up for decades. The walk-in shower has large-format tile, a frameless glass panel, and a rainfall showerhead. The double vanity provides the counter space and storage the original layout never had. The floor tile is clean and consistent. The exhaust fan runs automatically.

All inspections passed. The permit closed on schedule.

Jennifer noted that the biggest surprise was how predictable the process felt once it was underway. She knew what was happening each day and when decisions were needed. There were no gaps in communication.
TEXT;

$beforeAfterContent = <<<'TEXT'
Images are listed in display order for the project gallery.
TEXT;

$relatedProjectsContent = <<<'TEXT'
Two other projects from our Fairfield County work:

- Basement Finishing in Darien, CT: 850 sq ft, ledge rock conditions, egress window, 6-week timeline. See the Darien basement project.
- Kitchen Remodeling in New Canaan, CT: custom inset cabinets, quartz countertops, LVP flooring, 10-week timeline. See the New Canaan kitchen project.

View all case studies.
TEXT;

Section::create([
    'page_id' => $page->id,
    'type' => 'case_study_header',
    'sort_order' => $sort++,
    'is_active' => true,
    'data' => [
        'title' => 'Bathroom Remodeling in Westport, CT: Full Gut Renovation, 4 Weeks',
        'subtitle' => null,
        'cover_image' => 'westport-bathroom-frameless-glass-shower.webp',
        'cover_alt' => 'Completed walk-in shower with frameless glass panel — Westport CT bathroom remodel by BuiltWell CT',
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
            ['label' => 'Client', 'value' => 'Jennifer M., Westport, CT'],
            ['label' => 'Location', 'value' => 'Westport, CT (Fairfield County)'],
            ['label' => 'Project Type', 'value' => 'Full bathroom remodel (gut renovation)'],
            ['label' => 'Scope', 'value' => 'Master bathroom: shower, vanity, tile, fixtures, lighting'],
            ['label' => 'Services', 'value' => 'Bathroom remodeling'],
            ['label' => 'Timeline', 'value' => '4 weeks'],
            ['label' => 'Permits', 'value' => 'Building permit, Westport Building Department'],
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
                'image' => 'westport-bathroom-before-harvest-gold.webp',
                'alt' => 'Original Westport CT master bathroom with harvest gold fixtures and 1960s tile before remodel',
                'caption' => null,
            ],
            [
                'image' => 'westport-bathroom-demo-studs-cement-board.webp',
                'alt' => 'Westport CT bathroom after full gut demolition — studs and cement board substrate visible',
                'caption' => null,
            ],
            [
                'image' => 'westport-bathroom-walk-in-shower-tile.webp',
                'alt' => 'Walk-in shower large-format tile installation in progress — Westport CT bathroom remodel by BuiltWell',
                'caption' => null,
            ],
            [
                'image' => 'westport-bathroom-double-vanity-quartz.webp',
                'alt' => 'New double vanity with quartz countertop installed in Westport CT bathroom remodel',
                'caption' => null,
            ],
            [
                'image' => 'westport-bathroom-frameless-glass-shower.webp',
                'alt' => 'Completed walk-in shower with frameless glass panel — Westport CT bathroom remodel by BuiltWell CT',
                'caption' => null,
            ],
            [
                'image' => 'westport-bathroom-before-after-split.webp',
                'alt' => 'Before and after full bathroom view — Westport CT master bathroom remodel by BuiltWell CT',
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
                'name' => 'Jennifer M.',
                'location' => 'Westport',
                'quote' => "\"Four weeks of construction, and now I have the bathroom I've been dreaming about.\" - Jennifer M., Westport",
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
        'title' => 'Thinking About a Bathroom Remodel?',
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
    'seo_title' => 'Bathroom Remodeling in Westport, CT | BuiltWell Case Study',
    'seo_description' => 'Full bathroom remodel in Westport, CT, new tile, walk-in shower, vanity, fixtures. Completed in 4 weeks. See the full project by BuiltWell.',
]);
echo "Updated SEO metadata rows: {$updatedSeo}\n";

Cache::flush();
echo "Cache flushed.\n";
echo "Done! {$sort} sections created.\n";
