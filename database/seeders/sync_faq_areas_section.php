<?php

use App\Http\Controllers\Api\PageController as ApiPageController;
use App\Models\Page;
use App\Models\Section;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Sync FAQ Page — Areas Served Section
|--------------------------------------------------------------------------
| Updates the areas_served section on the FAQ page to match the
| HomeownerHub page's Where We Work section exactly.
|
| Run: php artisan tinker database/seeders/sync_faq_areas_section.php
*/

$fullPath = '/faq';

$areasData = [
    'eyebrow' => 'Where We Work',
    'title' => 'Home Remodeling Across Two Counties',
    'highlight_text' => 'Two Counties',
    'subtitle' => 'We provide home remodeling throughout Fairfield and New Haven Counties, with dedicated teams serving both regions.',
    'counties' => [
        [
            'name' => 'Fairfield County',
            'phone' => '(203) 919-9616',
            'description' => 'Serving all of Fairfield County with dedicated local crews. From Greenwich estates to Ridgefield colonials, we know the housing stock and building departments across the county.',
            'image' => '/images/areas/fairfield-county.jpg',
            'image_alt' => 'Fairfield County, Connecticut — home remodeling service area for BuiltWell CT',
            'url' => '/fairfield-county/',
            'cta_label' => 'Learn more about Fairfield County',
            'towns' => ['Greenwich', 'Stamford', 'Norwalk', 'Westport', 'Darien', 'New Canaan', 'Fairfield', 'Ridgefield'],
            'town_links' => [
                ['name' => 'Greenwich', 'url' => '/fairfield-county/greenwich-ct/'],
                ['name' => 'Westport', 'url' => '/fairfield-county/westport-ct/'],
            ],
            'extra_towns' => ['Trumbull', 'Bethel', 'Bridgeport', 'Brookfield', 'Danbury', 'Easton', 'Monroe', 'New Fairfield', 'Newtown', 'Redding', 'Shelton', 'Sherman', 'Stratford', 'Weston', 'Wilton'],
        ],
        [
            'name' => 'New Haven County',
            'phone' => '(203) 466-9148',
            'description' => 'Served from our Orange, CT office. We cover every town in New Haven County from coastal Branford and Madison to inland Woodbridge and Cheshire — delivering expert remodeling across the region.',
            'image' => '/images/areas/new-haven-county.jpg',
            'image_alt' => 'New Haven County, Connecticut — home remodeling service area for BuiltWell CT',
            'url' => '/new-haven-county/',
            'cta_label' => 'Learn more about New Haven County',
            'towns' => ['Orange', 'New Haven', 'Hamden', 'Branford', 'Guilford', 'Madison', 'Woodbridge', 'Milford'],
            'town_links' => [
                ['name' => 'Orange', 'url' => '/new-haven-county/orange-ct/'],
                ['name' => 'New Haven', 'url' => '/new-haven-county/new-haven-ct/'],
                ['name' => 'Madison', 'url' => '/new-haven-county/madison-ct/'],
            ],
            'extra_towns' => ['Cheshire', 'Ansonia', 'Beacon Falls', 'Bethany', 'Derby', 'East Haven', 'Meriden', 'Middlebury', 'Naugatuck', 'North Branford', 'North Haven', 'Oxford', 'Prospect', 'Seymour', 'Southbury', 'Wallingford', 'Waterbury', 'West Haven', 'Wolcott'],
        ],
    ],
    'note' => 'Not sure if we cover your area? Contact our Connecticut remodeling team and we will let you know.',
    'note_link_text' => 'Contact our Connecticut remodeling team',
    'note_link_url' => '/contact/',
];

DB::transaction(function () use ($fullPath, $areasData): void {
    $page = Page::query()->where('full_path', $fullPath)->firstOrFail();

    // Remove any existing areas_served section(s)
    $page->sections()->where('type', 'areas_served')->delete();

    // Determine sort_order — place it after the last faq_list section
    $lastFaqSection = $page->sections()
        ->where('type', 'faq_list')
        ->orderByDesc('sort_order')
        ->first();

    $sortOrder = $lastFaqSection
        ? $lastFaqSection->sort_order + 1
        : $page->sections()->max('sort_order') + 1;

    // Bump sort_order for sections that come after
    $page->sections()
        ->where('sort_order', '>=', $sortOrder)
        ->increment('sort_order');

    Section::query()->create([
        'page_id' => $page->id,
        'type' => 'areas_served',
        'data' => $areasData,
        'sort_order' => $sortOrder,
        'is_active' => true,
    ]);
});

ApiPageController::forgetCacheForPath($fullPath);
Artisan::call('optimize:clear');

$page = Page::query()
    ->where('full_path', $fullPath)
    ->with(['sections' => fn ($query) => $query->orderBy('sort_order')])
    ->firstOrFail();

echo json_encode([
    'page_id' => $page->id,
    'full_path' => $page->full_path,
    'template_key' => $page->template_key,
    'section_types' => $page->sections->pluck('type')->all(),
    'section_count' => $page->sections->count(),
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
