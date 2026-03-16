<?php

use App\Http\Controllers\Api\PageController as ApiPageController;
use App\Models\Page;
use App\Models\Section;
use App\Models\Service;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

$servicePayload = [
    'name' => 'Insurance Restoration',
    'slug' => 'insurance-restoration',
    'short_description' => 'Licensed Connecticut contractor for fire, water, and storm damage reconstruction.',
    'description' => 'BuiltWell handles full insurance restoration and reconstruction work across Fairfield and New Haven Counties.',
    'meta_title' => 'Insurance Restoration Services in Connecticut | BuiltWell CT',
    'meta_description' => 'Licensed CT contractor specializing in home reconstruction after fire, water, and storm damage.',
    'is_active' => true,
    'is_primary' => false,
    'sort_order' => 200,
];

$pagePayload = [
    'full_path' => '/insurance-restoration',
    'template_key' => 'service_global',
    'status' => 'published',
    'published_at' => now(),
    'seo_title' => 'Insurance Restoration Services in Connecticut | BuiltWell CT',
    'seo_description' => 'Licensed CT contractor specializing in home reconstruction after fire, water, and storm damage. We rebuild kitchens, bathrooms, flooring, drywall, and full interiors once cleanup is complete. Fairfield: (203) 919-9616 | New Haven: (203) 466-9148',
    'canonical_url' => null,
    'schema_overrides' => [
        'description' => 'Licensed Connecticut contractor specializing in home reconstruction after fire, water, and storm damage. We rebuild homes to pre-loss condition or better once cleanup is complete. Serving Fairfield and New Haven Counties.',
        'areaServed' => [
            ['@type' => 'AdministrativeArea', 'name' => 'Fairfield County, Connecticut'],
            ['@type' => 'AdministrativeArea', 'name' => 'New Haven County, Connecticut'],
        ],
        'serviceType' => 'Insurance Restoration',
    ],
];

$sections = [
    [
        'type' => 'service_hero',
        'data' => [
            'title' => 'Insurance Reconstruction Services',
            'subtitle' => 'Licensed Connecticut contractor specializing in rebuilding homes after fire, water, and storm damage. We handle the full reconstruction, from structural framing to finish carpentry, once the cleanup is complete.',
            'background_image' => '/portfolio/builtwell-team-client-arrival-ct.jpeg',
            'primary_cta' => ['label' => 'Schedule Now', 'url' => '#contact'],
            'secondary_cta' => ['label' => 'Call BuiltWell', 'url' => 'tel:2039199616'],
            'overlay_opacity' => 0.52,
        ],
    ],
    [
        'type' => 'trust_bar',
        'data' => [
            'variant' => 'insurance_primary',
            'items' => [
                ['icon' => 'clock', 'label' => 'Years of Experience', 'value' => '15+'],
                ['icon' => 'check', 'label' => 'Completed Projects', 'value' => '100+'],
                ['icon' => 'star', 'label' => 'Google Rating', 'value' => '4.9'],
                ['icon' => 'shield-check', 'label' => 'Fully Bonded & Insured', 'value' => null],
            ],
        ],
    ],
    [
        'type' => 'rich_text',
        'data' => [
            'eyebrow' => 'What We Do',
            'title' => 'Rebuilding Homes After Damage',
            'highlight_text' => 'Damage',
            'content' => implode("\n\n", [
                'When your home suffers fire, water, or storm damage, the cleanup is only the first step. Once the affected areas have been dried, cleared, and stabilized, the real work begins: rebuilding everything that was lost. That is where BuiltWell CT comes in.',
                'We handle the complete reconstruction phase. Structural framing, drywall, flooring, cabinetry, plumbing, electrical, painting, trim, and every finish detail needed to return your home to pre-loss condition or better. We work directly with your insurance carrier throughout the process.',
                'Whether your kitchen needs to be rebuilt from the studs, your basement flooring and drywall need full replacement, or an entire floor of your home requires reconstruction, we have the capability and experience to deliver.',
                'Our team has completed full gut rebuilds after catastrophic fire damage, extensive water intrusion, and severe storm events. We understand what it takes to bring a home back from nothing - structural framing, insulation, MEP rough-ins, drywall, flooring, cabinetry, tile, trim, paint, and every final detail.',
                'We also handle the documentation that insurance carriers require at every stage - photo evidence, line-item estimates, change order approvals, and completion reports. You focus on your family. We handle the rebuild and the paperwork.',
            ]),
            'align' => 'left',
            'variant' => 'default',
            'style_variant' => 'default',
            'surface' => 'white',
            'container_width' => 'wide',
            'spacing' => 'normal',
        ],
    ],
    [
        'type' => 'image_gallery',
        'data' => [
            'title' => 'Insurance restoration gallery',
            'subtitle' => null,
            'layout' => 'grid',
            'items' => [
                [
                    'image' => '/portfolio/builtwell-contractor-handshake-arrival-ct-optimized.jpg',
                    'alt' => 'BuiltWell CT contractor meeting homeowner for insurance reconstruction assessment in Connecticut',
                    'caption' => null,
                ],
                [
                    'image' => '/portfolio/builtwell-team-completed-interior-ct.png',
                    'alt' => 'Completed interior reconstruction by BuiltWell CT after insurance claim in Connecticut',
                    'caption' => null,
                ],
            ],
        ],
    ],
    [
        'type' => 'rich_text',
        'data' => [
            'eyebrow' => 'Know Your Rights',
            'title' => 'You Choose Your Contractor',
            'highlight_text' => 'Contractor',
            'content' => implode("\n\n", [
                'Your insurance company may suggest a preferred vendor. You are not obligated to use them. Under Connecticut law, you have the right to hire the contractor of your choice for any insurance claim reconstruction work.',
                "Preferred vendor programs often prioritize the carrier's cost control over your best interest. The contractor works for the insurance company, not for you. When you choose your own contractor, you get someone who advocates for the full scope of work your home actually needs.",
                "We work for you, not your insurance company. We document every condition, write detailed Xactimate estimates that carriers accept, and fight for the complete scope your home requires. If the adjuster's estimate misses something, we submit supplements with full documentation until the claim reflects the actual work.",
            ]),
            'align' => 'left',
            'variant' => 'default',
            'style_variant' => 'default',
            'surface' => 'default',
            'container_width' => 'wide',
            'spacing' => 'normal',
        ],
    ],
    [
        'type' => 'service_includes',
        'data' => [
            'title' => 'What This Means for You',
            'items' => [
                'We advocate for the full scope of work, not the cheapest option',
                'We write Xactimate line-item estimates that carriers accept',
                'We submit supplements when the initial scope misses items',
                'We handle all documentation, photos, and adjuster communication',
                'Your home gets rebuilt to pre-loss condition or better, not to a budget',
            ],
        ],
    ],
    [
        'type' => 'feature_grid',
        'data' => [
            'title' => 'What We Rebuild',
            'subtitle' => null,
            'items' => [
                ['icon' => 'hammer', 'title' => 'Drywall and Framing', 'description' => 'Complete structural framing, drywall installation, taping, and finishing. We rebuild walls, ceilings, and soffits to match or exceed the original construction.'],
                ['icon' => 'house', 'title' => 'Kitchen Reconstruction', 'description' => 'Full kitchen rebuilds including cabinetry, countertops, plumbing, electrical, flooring, backsplash, and all finish work. Restored to pre-loss condition or better.'],
                ['icon' => 'droplets', 'title' => 'Bathroom Reconstruction', 'description' => 'Complete bathroom rebuilds including tile, fixtures, plumbing, waterproofing, vanities, and finish carpentry. Every detail returned to full function.'],
                ['icon' => 'grid', 'title' => 'Flooring Replacement', 'description' => 'Hardwood, tile, LVP, and carpet installation throughout damaged areas. We match existing flooring or install new materials per the approved scope of work.'],
                ['icon' => 'square-stack', 'title' => 'Interior Carpentry and Trim', 'description' => 'Crown molding, baseboards, door casings, built ins, and custom millwork. We replicate or upgrade all interior woodwork damaged during the event.'],
                ['icon' => 'shield', 'title' => 'Full Interior Reconstruction', 'description' => 'When the damage requires starting from the studs up. Complete interior rebuild including all trades, all systems, and all finish work throughout the home.'],
            ],
        ],
    ],
    [
        'type' => 'feature_grid',
        'data' => [
            'title' => 'Why Homeowners Choose BuiltWell',
            'subtitle' => null,
            'items' => [
                ['icon' => 'shield', 'title' => 'Nearly 15 Years in Reconstruction', 'description' => 'Rebuilt homes from the studs up after every type of residential disaster throughout Connecticut.'],
                ['icon' => 'file-text', 'title' => 'Insurance Grade Quality', 'description' => 'We meet the demanding documentation, quality, and timeline standards that insurance carriers require on every project.'],
                ['icon' => 'message-circle', 'title' => 'Direct Carrier Communication', 'description' => 'We handle all adjuster communication and documentation so you do not have to manage the process yourself.'],
                ['icon' => 'house', 'title' => 'Full Scope Capability', 'description' => 'Every phase of the rebuild handled in house. One team, one standard, from structural framing to custom finish carpentry.'],
                ['icon' => 'shield-check', 'title' => 'CT Licensed and Insured', 'description' => 'CT HIC License #0668405. Fully insured for residential reconstruction work throughout Fairfield and New Haven Counties.'],
            ],
        ],
    ],
    [
        'type' => 'logo_strip',
        'data' => [
            'title' => 'Insurance Carriers We Work With',
            'subtitle' => 'We understand the documentation requirements, the approval process, and the quality standards that carriers demand. Our experience with these carriers means a smoother, faster reconstruction process for you.',
            'items' => [
                ['name' => 'State Farm', 'logo' => '/logos/builtwell-logo-text-only.png', 'url' => 'https://www.statefarm.com'],
                ['name' => 'Liberty Mutual', 'logo' => '/logos/builtwell-logo-text-only.png', 'url' => 'https://www.libertymutual.com'],
                ['name' => 'Chubb', 'logo' => '/logos/builtwell-logo-text-only.png', 'url' => 'https://www.chubb.com'],
                ['name' => 'PURE', 'logo' => '/logos/builtwell-logo-text-only.png', 'url' => 'https://www.pureinsurance.com'],
                ['name' => 'Allstate', 'logo' => '/logos/builtwell-logo-text-only.png', 'url' => 'https://www.allstate.com'],
                ['name' => 'Travelers', 'logo' => '/logos/builtwell-logo-text-only.png', 'url' => 'https://www.travelers.com'],
                ['name' => 'Hartford', 'logo' => '/logos/builtwell-logo-text-only.png', 'url' => 'https://www.thehartford.com'],
                ['name' => 'Nationwide', 'logo' => '/logos/builtwell-logo-text-only.png', 'url' => 'https://www.nationwide.com'],
                ['name' => 'USAA', 'logo' => '/logos/builtwell-logo-text-only.png', 'url' => 'https://www.usaa.com'],
                ['name' => 'Erie', 'logo' => '/logos/builtwell-logo-text-only.png', 'url' => 'https://www.erieinsurance.com'],
            ],
        ],
    ],
    [
        'type' => 'rich_text',
        'data' => [
            'eyebrow' => 'The Advantage',
            'title' => 'A General Contractor Built for Insurance Work',
            'highlight_text' => 'Insurance Work',
            'content' => implode("\n\n", [
                'BuiltWell is a fully licensed general contractor with deep insurance restoration experience. That combination means you get quality construction and claims expertise under one roof.',
                'Most contractors can build. Few understand the insurance process. BuiltWell does both - so your rebuild is built right and fully covered by your claim.',
            ]),
            'align' => 'center',
            'variant' => 'default',
            'style_variant' => 'default',
            'surface' => 'light',
            'container_width' => 'wide',
            'spacing' => 'normal',
        ],
    ],
    [
        'type' => 'service_includes',
        'data' => [
            'title' => 'Licensed General Contractor',
            'items' => [
                'Full-scope construction from framing to finish carpentry',
                'CT HIC licensed (#0668405), bonded, and fully insured',
                'In-house crews for structural, mechanical, and finish work',
                'Kitchens, bathrooms, additions, and whole-home remodels',
                'Nearly 15 years of residential construction experience',
            ],
        ],
    ],
    [
        'type' => 'service_includes',
        'data' => [
            'title' => 'Insurance Claims Expertise',
            'items' => [
                'Writes Xactimate line-item estimates that carriers require',
                'Understands depreciation, supplements, and policy language',
                'Documents every condition for carrier approval',
                'Coordinates directly with adjusters on scope and payment',
                'Submits supplements when hidden damage is discovered',
            ],
        ],
    ],
    [
        'type' => 'service_process',
        'data' => [
            'title' => 'Our Reconstruction Process',
            'steps' => [
                ['step_number' => 1, 'title' => 'Emergency Assessment', 'description' => 'We inspect the damage, document conditions, and provide a detailed scope of work for your insurance carrier.'],
                ['step_number' => 2, 'title' => 'Insurance Coordination', 'description' => 'We work directly with your adjuster, provide all required documentation, and ensure nothing is missed in the claim.'],
                ['step_number' => 3, 'title' => 'Scope and Planning', 'description' => 'Once cleanup is complete, we develop a detailed reconstruction plan, finalize materials, and coordinate the build schedule with your carrier.'],
                ['step_number' => 4, 'title' => 'Complete Reconstruction', 'description' => 'From framing and drywall to cabinetry, flooring, and paint, we rebuild every damaged area to pre-loss condition or better.'],
            ],
        ],
    ],
    [
        'type' => 'faq_list',
        'data' => [
            'title' => 'Insurance Reconstruction FAQ',
            'subtitle' => null,
            'items' => [
                ['question' => "Do I have to use my insurance company's preferred contractor?", 'answer' => "No. Under Connecticut law, you have the right to choose any licensed contractor for your insurance claim reconstruction. Your carrier may recommend a preferred vendor, but you are not required to use them. Preferred vendor programs often prioritize the carrier's cost control over your interests. When you choose your own contractor, you get someone who works for you and advocates for the full scope of work your home needs."],
                ['question' => 'Should I call my insurance company or a contractor first?', 'answer' => 'Call us first. Having a reconstruction specialist assess the damage before you file your claim ensures that the scope of work is documented correctly from the start. We photograph every condition, write a detailed scope, and help you file the claim with complete information. This prevents situations where the initial claim underestimates the actual damage and you have to fight for supplements later.'],
                ['question' => "What if I disagree with my insurance company's estimate?", 'answer' => 'This happens frequently. Insurance estimates often miss hidden damage, underestimate material costs, or exclude necessary scope items. We write detailed Xactimate line-item estimates with full documentation and submit supplements to your carrier for every item that was missed. Our experience with major carriers means we know what documentation adjusters need to approve additional scope. You do not have to accept an estimate that does not cover the actual work required.'],
                ['question' => 'Can I upgrade materials during reconstruction? Who pays the difference?', 'answer' => 'Yes. Insurance covers like-kind-and-quality replacement, meaning materials equivalent to what was damaged. If you want to upgrade beyond that level, the insurance payment covers the like-kind cost and you pay the difference. We clearly separate what the carrier covers from any upgrades you choose so there are no surprises. Many homeowners use reconstruction as an opportunity to improve finishes while insurance covers the base rebuild.'],
                ['question' => 'What happens if hidden damage is found during the work?', 'answer' => 'Hidden damage behind walls, under floors, or in structural members is common in fire and water damage reconstruction. When we find it, we document the condition immediately with photos and a written scope, contact you the same day, and submit a supplement to your insurance carrier. The carrier reviews the documentation and approves additional coverage for the newly discovered damage. This is a standard part of the claims process and the reason detailed documentation matters at every stage.'],
                ['question' => 'How long does the insurance reconstruction process take?', 'answer' => 'Timeline depends on the scope of damage. A single-room reconstruction may take 4 to 8 weeks of active construction. A full-floor or whole-home rebuild can take 12 to 16 weeks or more. Add 2 to 4 weeks for insurance approval and material lead times before construction begins. We provide a detailed written schedule with every project so you know exactly when each phase starts and finishes. We do not give vague timelines.'],
            ],
        ],
    ],
    [
        'type' => 'cta_block',
        'data' => [
            'eyebrow' => 'Get Started',
            'title' => 'Need Your Home Rebuilt?',
            'subtitle' => 'Call us directly or schedule a free assessment. We will review the damage scope, coordinate with your insurance carrier, and begin the reconstruction process.',
            'button' => ['label' => 'Schedule Now', 'url' => '#contact'],
            'subtext' => 'Free assessment available in both counties',
            'variant' => 'dark',
        ],
    ],
    [
        'type' => 'areas_served',
        'data' => [
            'eyebrow' => 'Where We Work',
            'title' => 'Insurance Reconstruction Across Two Counties',
            'highlight_text' => 'Two Counties',
            'subtitle' => 'We provide insurance reconstruction throughout Fairfield and New Haven Counties, with dedicated teams serving both regions.',
            'counties' => [
                [
                    'name' => 'Fairfield County',
                    'image' => '/images/areas/fairfield-county.jpg',
                    'url' => '/fairfield-county/',
                    'phone' => '(203) 919-9616',
                    'description' => 'Insurance reconstruction across Fairfield County. We rebuild homes after fire, water, and storm damage in every town in the county with direct carrier coordination included.',
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
                    'description' => 'Insurance reconstruction across New Haven County from our Orange, CT office. Full home reconstruction after damage with direct insurance carrier communication throughout.',
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
            'variant' => 'insurance_secondary',
            'items' => [
                ['icon' => 'star', 'label' => 'Google Rating', 'value' => '4.9', 'url' => 'https://www.google.com/search?q=builtwell+ct+reviews'],
                ['icon' => 'shield-check', 'label' => 'BBB A+ Accredited', 'value' => null, 'url' => 'https://www.bbb.org/search?find_country=USA&find_text=builtwell+ct&find_loc=Orange%2C+CT'],
                ['icon' => 'check', 'label' => 'Trusted on Houzz', 'value' => null, 'url' => 'https://www.houzz.com/professionals/general-contractors/builtwell-ct'],
                ['icon' => 'calendar', 'label' => 'CT HIC License #0668405', 'value' => null, 'url' => 'https://www.elicense.ct.gov/Lookup/LicenseLookup.aspx'],
                ['icon' => 'check', 'label' => 'Verified on Angi & Thumbtack', 'value' => null, 'url' => 'https://www.angi.com/companylist/us/ct/orange/builtwell-ct-reviews-'],
            ],
        ],
    ],
    [
        'type' => 'lead_form',
        'data' => [
            'eyebrow' => 'Get In Touch',
            'title' => 'Ready to Discuss Your Reconstruction?',
            'title_highlight' => 'Reconstruction',
            'subtitle' => "Fill out the form and we'll get back to you within one business day with next steps. No obligation, no pressure.",
            'images' => [
                ['image' => '/portfolio/builtwell-contractor-handshake-arrival-ct-optimized.jpg', 'alt' => 'BuiltWell CT contractor meeting homeowner for insurance reconstruction assessment'],
                ['image' => '/images/headers/kitchen-remodeling-header.jpg', 'alt' => 'Beautiful kitchen remodel completed by BuiltWell CT'],
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
                        ['label' => 'Insurance Reconstruction', 'value' => 'Insurance Reconstruction'],
                        ['label' => 'Kitchen Remodeling', 'value' => 'Kitchen Remodeling'],
                        ['label' => 'Bathroom Remodeling', 'value' => 'Bathroom Remodeling'],
                        ['label' => 'Basement Finishing', 'value' => 'Basement Finishing'],
                        ['label' => 'Flooring Installation', 'value' => 'Flooring Installation'],
                        ['label' => 'Interior Carpentry', 'value' => 'Interior Carpentry'],
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
                    'placeholder' => 'Describe the damage, your insurance carrier, and any details about the reconstruction needed...',
                ],
                ['name' => 'photos', 'label' => 'Upload Photos', 'type' => 'file', 'required' => false],
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
            'style_variant' => 'financing_strip',
            'surface' => 'white',
            'container_width' => 'wide',
            'cta' => ['label' => 'Check Financing Options', 'url' => 'https://www.greensky.com'],
        ],
    ],
];

DB::transaction(function () use ($servicePayload, $pagePayload, $sections): void {
    $service = Service::query()->firstOrNew(['slug' => $servicePayload['slug']]);
    $service->fill($servicePayload);
    $service->save();

    $page = Page::query()->firstOrNew(['full_path' => $pagePayload['full_path']]);
    $page->fill(array_merge($pagePayload, ['service_id' => $service->id]));
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

ApiPageController::forgetCacheForPath('/insurance-restoration');
Artisan::call('optimize:clear');

$page = Page::query()
    ->where('full_path', '/insurance-restoration')
    ->with(['service', 'sections' => fn ($query) => $query->orderBy('sort_order')])
    ->firstOrFail();

echo json_encode([
    'page_id' => $page->id,
    'full_path' => $page->full_path,
    'template_key' => $page->template_key,
    'service' => $page->service?->name,
    'section_types' => $page->sections->pluck('type')->all(),
    'section_count' => $page->sections->count(),
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
