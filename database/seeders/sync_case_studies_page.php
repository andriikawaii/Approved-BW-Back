<?php

use App\Http\Controllers\Api\PageController as ApiPageController;
use App\Models\Page;
use App\Models\Section;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

$pagePayload = [
    'full_path' => '/case-studies',
    'template_key' => 'generic',
    'status' => 'published',
    'published_at' => now(),
    'seo_title' => 'Case Studies | BuiltWell CT',
    'seo_description' => 'Real remodeling projects across Connecticut. Before and after photos, timelines, and client testimonials. Fairfield: (203) 919-9616 | New Haven: (203) 466-9148',
    'canonical_url' => null,
];

$sections = [
    [
        'type' => 'hero',
        'data' => [
            'headline' => 'Our Case Studies',
            'subheadline' => 'Real Connecticut remodeling projects with before-and-after photos, timelines, and honest feedback from homeowners we have worked with.',
            'background_image' => '/images/headers/kitchen-remodeling-header.jpg',
            'overlay' => ['opacity' => 0.52],
            'cta_primary' => ['label' => 'Free Estimate', 'url' => '#contact'],
            'cta_secondary' => ['label' => 'Call Now', 'url' => 'tel:2034669148'],
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
        'type' => 'rich_text',
        'data' => [
            'eyebrow' => 'Real Projects, Real Results',
            'title' => 'Every Project Tells a Story',
            'highlight_text' => 'Story',
            'content' => 'These are real Connecticut homes, real challenges, and real results. Each case study documents the scope of work, timeline, and the homeowner\'s experience from start to finish.',
            'align' => 'center',
            'variant' => 'default',
            'style_variant' => 'default',
            'surface' => 'white',
            'container_width' => 'wide',
            'spacing' => 'normal',
        ],
    ],
    [
        'type' => 'rich_text',
        'data' => [
            'title' => 'Whole-Home Restoration — Hamden',
            'content' => implode("\n", [
                'Timeline: 5 Weeks',
                'Badge: Post-Disaster Restoration',
                'Summary: Post-disaster restoration including flooring, painting, bathroom rebuild, and drywall repair throughout the entire home.',
                'Quote: We were devastated when we saw the damage. BuiltWell took everything off our plate.',
                'Attribution: The Martins, Hamden',
                'Link: #contact',
            ]),
            'align' => 'left',
            'variant' => 'default',
            'style_variant' => 'default',
            'surface' => 'default',
            'container_width' => 'default',
            'spacing' => 'normal',
        ],
    ],
    [
        'type' => 'rich_text',
        'data' => [
            'title' => 'Basement Finishing — Darien',
            'content' => implode("\n", [
                'Timeline: 6 Weeks',
                'Badge: 850 Sq Ft',
                'Summary: Full basement finishing with rec room, dedicated home office, and workout area — 850 square feet of new living space.',
                'Quote: We should have done this ten years ago.',
                'Attribution: Steve R., Darien',
                'Link: #contact',
            ]),
            'align' => 'left',
            'variant' => 'default',
            'style_variant' => 'default',
            'surface' => 'default',
            'container_width' => 'default',
            'spacing' => 'normal',
        ],
    ],
    [
        'type' => 'rich_text',
        'data' => [
            'title' => 'Bathroom Remodeling — Westport',
            'content' => implode("\n", [
                'Timeline: 4 Weeks',
                'Badge: Master Bath',
                'Summary: Complete master bathroom renovation with walk-in shower, double vanity, and heated floors.',
                'Quote: Four weeks of construction, and now I have the bathroom I\'ve been dreaming about.',
                'Attribution: Jennifer M., Westport',
                'Link: #contact',
            ]),
            'align' => 'left',
            'variant' => 'default',
            'style_variant' => 'default',
            'surface' => 'default',
            'container_width' => 'default',
            'spacing' => 'normal',
        ],
    ],
    [
        'type' => 'rich_text',
        'data' => [
            'title' => 'Kitchen Remodeling — New Canaan',
            'content' => implode("\n", [
                'Timeline: 10 Weeks',
                'Badge: Open Concept',
                'Summary: Open-concept kitchen renovation with custom cabinetry, quartz countertops, and a complete layout reconfiguration.',
                'Quote: BuiltWell made it straightforward. Now we can\'t imagine how we lived before.',
                'Attribution: The Chens, New Canaan',
                'Link: #contact',
            ]),
            'align' => 'left',
            'variant' => 'default',
            'style_variant' => 'default',
            'surface' => 'default',
            'container_width' => 'default',
            'spacing' => 'normal',
        ],
    ],
    [
        'type' => 'rich_text',
        'data' => [
            'title' => 'Kitchen + Flooring — Milford',
            'content' => implode("\n", [
                'Timeline: 6 Weeks',
                'Badge: Kitchen + LVP Flooring',
                'Summary: Full kitchen remodel plus luxury vinyl plank flooring installed through the dining and living areas.',
                'Quote: BuiltWell made it easy. They showed up when they said they would, cleaned up every day.',
                'Attribution: Ivana P., Milford',
                'Link: #contact',
            ]),
            'align' => 'left',
            'variant' => 'default',
            'style_variant' => 'default',
            'surface' => 'default',
            'container_width' => 'default',
            'spacing' => 'normal',
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
            'title' => 'Ready to Start Your Remodeling Project?',
            'title_highlight' => 'Remodeling Project',
            'subtitle' => 'Tell us about your project. We respond within one business day. No obligation.',
            'images' => [
                ['image' => '/portfolio/builtwell-team-client-arrival-ct.jpeg', 'alt' => 'BuiltWell CT remodeling team arriving for a free consultation'],
                ['image' => '/portfolio/builtwell-contractor-sign-consultation-ct-01.jpg', 'alt' => 'BuiltWell CT owner meeting with a homeowner'],
            ],
            'fields' => [
                ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'required' => true, 'placeholder' => 'Your full name'],
                ['name' => 'phone', 'label' => 'Phone', 'type' => 'tel', 'required' => true, 'placeholder' => '(203) 000-0000'],
                ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'required' => true, 'placeholder' => 'you@email.com'],
                ['name' => 'zip', 'label' => 'Zip Code', 'type' => 'text', 'required' => true, 'placeholder' => '06477'],
                [
                    'name' => 'services_needed',
                    'label' => 'Services Needed',
                    'type' => 'checkbox_group',
                    'required' => true,
                    'options' => [
                        ['label' => 'Kitchen Remodeling', 'value' => 'Kitchen Remodeling'],
                        ['label' => 'Bathroom Remodeling', 'value' => 'Bathroom Remodeling'],
                        ['label' => 'Basement Finishing', 'value' => 'Basement Finishing'],
                        ['label' => 'Flooring Installation', 'value' => 'Flooring Installation'],
                        ['label' => 'Home Additions', 'value' => 'Home Additions'],
                        ['label' => 'Interior Painting', 'value' => 'Interior Painting'],
                        ['label' => 'Interior Carpentry', 'value' => 'Interior Carpentry'],
                        ['label' => 'Attic Conversions', 'value' => 'Attic Conversions'],
                        ['label' => 'Decks & Porches', 'value' => 'Decks & Porches'],
                        ['label' => 'Design & Planning', 'value' => 'Design & Planning'],
                        ['label' => 'Comfort & Accessibility', 'value' => 'Comfort & Accessibility'],
                        ['label' => 'Other', 'value' => 'Other'],
                    ],
                ],
                [
                    'name' => 'best_time',
                    'label' => 'Best Time to Contact',
                    'type' => 'select',
                    'required' => true,
                    'options' => [
                        ['label' => 'Morning (8am - 12pm)', 'value' => 'Morning (8am - 12pm)'],
                        ['label' => 'Afternoon (12pm - 4pm)', 'value' => 'Afternoon (12pm - 4pm)'],
                        ['label' => 'Evening (4pm - 6pm)', 'value' => 'Evening (4pm - 6pm)'],
                        ['label' => 'Anytime', 'value' => 'Anytime'],
                    ],
                ],
                [
                    'name' => 'contact_method',
                    'label' => 'Preferred Contact Method',
                    'type' => 'radio_group',
                    'required' => true,
                    'options' => [
                        ['label' => 'Call', 'value' => 'call'],
                        ['label' => 'Text', 'value' => 'text'],
                        ['label' => 'Email', 'value' => 'email'],
                    ],
                ],
                [
                    'name' => 'message',
                    'label' => 'Tell Us About Your Project',
                    'type' => 'textarea',
                    'required' => false,
                    'placeholder' => 'Describe your project, timeline, budget range, or any questions...',
                ],
                ['name' => 'files', 'label' => 'Upload Photos', 'type' => 'file', 'required' => false],
            ],
            'submit_label' => 'Send Request',
            'consent_text' => 'We respond within 24 hours. No spam, no obligation.',
        ],
    ],
    [
        'type' => 'rich_text',
        'data' => [
            'title' => 'Flexible Financing Available',
            'content' => 'Get approved in about 60 seconds and start your project today.',
            'align' => 'left',
            'variant' => 'default',
            'style_variant' => 'financing_strip',
            'surface' => 'white',
            'container_width' => 'wide',
            'spacing' => 'compact',
            'cta' => ['label' => 'Check Financing Options', 'url' => 'https://www.greensky.com'],
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

ApiPageController::forgetCacheForPath('/case-studies');
Artisan::call('optimize:clear');

$page = Page::query()
    ->where('full_path', '/case-studies')
    ->with(['sections' => fn ($query) => $query->orderBy('sort_order')])
    ->firstOrFail();

echo json_encode([
    'page_id' => $page->id,
    'full_path' => $page->full_path,
    'template_key' => $page->template_key,
    'section_types' => $page->sections->pluck('type')->all(),
    'section_count' => $page->sections->count(),
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
