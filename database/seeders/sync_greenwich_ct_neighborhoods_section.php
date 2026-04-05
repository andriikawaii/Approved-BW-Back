<?php

use App\Http\Controllers\Api\PageController as ApiPageController;
use App\Models\Page;
use App\Models\Section;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/../../vendor/autoload.php';
$app = require __DIR__ . '/../../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$pagePath = '/fairfield-county/greenwich-ct';

$sectionPayload = [
    'type' => 'accordion_list',
    'data' => [
        'eyebrow' => 'Neighborhoods',
        'title' => 'Greenwich Neighborhoods: What Matters for Your Remodeling Project',
        'highlight_text' => 'Your Remodeling Project',
        'subtitle' => "Greenwich contains eight distinct neighborhoods for remodeling purposes, including Back Country estates on four-acre lots, pre-Revolutionary colonials in Old Greenwich, and entry-level ranches in Glenville and Byram, each with different structural conditions, permitting requirements, and finish expectations.",
        'items' => [
            [
                'title' => 'Back Country',
                'content' => "Back Country sits north of the Merritt Parkway in the RA-4 zone, where minimum lot sizes run four acres. The housing stock here is the estate belt: French Provincial manors, Georgian colonials, and English manor houses built primarily in the 1920s through the 1940s for New York financiers. Primary residences here routinely run 10,000 to 25,000 square feet, and projects in Back Country often involve multiple structures simultaneously: main house, guest house, pool house, carriage house. Gated enclaves like Conyers Farm define the character of this neighborhood. Projects at this scale require careful scheduling, multiple permit applications running in parallel, and crews who can work at the finish level these homes demand. Material quality is not negotiable here; it is the starting point.",
            ],
            [
                'title' => 'Mid-Country',
                'content' => "Mid-Country occupies the band between the Merritt Parkway and the Post Road, with one- to four-acre properties and a housing stock that runs from 1950s colonials through 1980s split-levels and ranches. Neighborhoods like Khakum Wood represent the character of this zone. Since the early 2000s, Mid-Country has seen sustained gut-renovation and replacement activity as buyers absorb older properties and rebuild or fully renovate them to current expectations. The structural conditions here are more varied than in Back Country. You can encounter anything from a conventional 1960s ranch with standard platform framing to a 1930s colonial that has been partially updated three times by three different owners, each with different standards.",
            ],
            [
                'title' => 'Old Greenwich',
                'content' => "Old Greenwich is the original settlement, established in 1640, and the housing stock reflects that history: pre-Revolutionary colonials, 1920s Tudor revivals, and post-war capes clustered around Sound Beach Avenue near the shops and train station. The Indian Harbor and Field Club neighborhoods carry premiums that reflect both location and architectural quality. Renovation work in Old Greenwich frequently involves historic materials, original woodwork, and footprints that were laid out before modern concepts of kitchen or bathroom function existed. Layout changes here require structural evaluation and often HDC review for any work that touches the exterior.",
            ],
            [
                'title' => 'Cos Cob',
                'content' => "Cos Cob grew as a fishing and mill community along the Mianus River, and its housing stock is the oldest and densest in Greenwich outside of Old Greenwich proper. Late Victorian cottages, New England colonials from the 1880s through the 1920s, and post-war ranches characterize the neighborhood. The Bush-Holley House, built circa 1730 and now a National Historic Landmark, sits here and speaks to the age of the built environment. The Mianus River corridor creates elevated water table conditions throughout Cos Cob, and basement work in this neighborhood consistently requires perimeter drainage and sump systems as a baseline measure. Younger buyers who enter the market at Cos Cob's relatively lower price points have been investing heavily in renovation here, making it one of the most active sections of Greenwich for remodeling activity.",
            ],
            [
                'title' => 'Riverside',
                'content' => "Riverside is characterized by shingle-style homes and New England colonial revivals built from the 1890s through the 1930s, with post-war colonial revival construction filling in the later decades. Streets like Hendrie Avenue and Sound Beach Avenue carry price tags in the $3 million to $8 million range, which sets the baseline for what finish quality means in this neighborhood. Coastal proximity brings moisture considerations for any project that touches the lower level or exterior envelope. When we work in Riverside, the finish level and material quality need to be consistent with what these homes already represent.",
            ],
            [
                'title' => 'Glenville',
                'content' => "Glenville grew as a mill town along the Byram River and holds Greenwich's most attainable entry-level price points. The housing stock runs to ranches, bi-levels, and cape cods, with some larger colonials mixed in. Glenville has been experiencing teardown activity as buyers seek a Greenwich address at a lower acquisition cost, which means the neighborhood is in the middle of a generational shift in its building stock. For renovation projects on existing homes, the 1950s through 1970s construction here is generally straightforward from a structural standpoint, though below-grade work should still carry a ledge contingency given the geology of the area.",
            ],
            [
                'title' => 'Byram',
                'content' => "Byram occupies Greenwich's southwesternmost corner, bordering Port Chester, New York, and has the most urban character of any Greenwich neighborhood. Multi-family housing, older worker cottages, and small colonials define the stock. It is Greenwich's most affordable entry point. Renovation projects in Byram tend to be practical and value-driven, but the older housing stock (much of it from the late 19th and early 20th century) brings the same plaster walls, balloon framing, and stone foundation conditions found elsewhere in pre-war Greenwich.",
            ],
            [
                'title' => 'Belle Haven',
                'content' => "Belle Haven is a private, gated peninsula community situated between Old Greenwich and Riverside along Greenwich Harbor. The housing stock consists primarily of estate-scale residences built from the late 1800s through the early 1900s, with significant modern rebuilds on the same lots. Properties here sit on the waterfront or have direct harbor views, which means salt air exposure, elevated water tables, and coastal building code requirements factor into every project. Interior renovations in Belle Haven routinely involve premium finishes, custom millwork, and material specifications that reflect the character of the neighborhood. Exterior modifications require approval through the Belle Haven Land Trust in addition to standard Greenwich permitting. Projects here demand crews who understand both the structural realities of coastal construction and the finish-level expectations of this community.",
            ],
        ],
    ],
];

DB::transaction(function () use ($pagePath, $sectionPayload): void {
    $page = Page::query()->where('full_path', $pagePath)->firstOrFail();

    $section = Section::query()
        ->where('page_id', $page->id)
        ->where('type', 'accordion_list')
        ->first();

    if (! $section) {
        $section = new Section([
            'page_id' => $page->id,
            'type' => 'accordion_list',
        ]);
    }

    $section->fill([
        'data' => $sectionPayload['data'],
        'sort_order' => 7,
        'is_active' => true,
    ]);
    $section->save();
});

ApiPageController::forgetCacheForPath($pagePath);
Artisan::call('optimize:clear');

$page = Page::query()
    ->where('full_path', $pagePath)
    ->with(['sections' => fn ($query) => $query->orderBy('sort_order')])
    ->firstOrFail();

echo json_encode([
    'page_id' => $page->id,
    'full_path' => $page->full_path,
    'accordion_section' => $page->sections
        ->where('type', 'accordion_list')
        ->map(fn ($section) => [
            'id' => $section->id,
            'sort_order' => $section->sort_order,
            'title' => data_get($section->data, 'title'),
            'item_count' => count(data_get($section->data, 'items', [])),
        ])
        ->values()
        ->all(),
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), PHP_EOL;
