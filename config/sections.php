<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Sections Registry (Single Source of Truth)
    |--------------------------------------------------------------------------
    |
    | - All sections supported by frontend must be defined here.
    | - Admin page builder can only create sections from this list.
    | - Each section has:
    |   - type, label, description
    |   - schema (Laravel validation rules)
    |   - defaults (initial data)
    |
    */

    'types' => [

        // ---------------------------------------------------------------------
        // CORE
        // ---------------------------------------------------------------------

        'hero' => [
            'type' => 'hero',
            'label' => 'Hero',
            'description' => 'Hero section with headline, subheadline, background image (or video) and CTAs.',
            'schema' => [
                'eyebrow' => 'nullable|string|max:60',
                'headline' => 'required|string|max:140',
                'subheadline' => 'nullable|string|max:255',

                'background_image' => 'nullable|string|max:255',
                'background_video' => 'nullable|string|max:255',
                'overlay' => 'nullable|array',
                'overlay.opacity' => 'nullable|numeric|min:0|max:1',

                'cta_primary' => 'nullable|array',
                'cta_primary.label' => 'nullable|string|max:50',
                'cta_primary.url' => 'nullable|string|max:255',

                'cta_secondary' => 'nullable|array',
                'cta_secondary.label' => 'nullable|string|max:50',
                'cta_secondary.url' => 'nullable|string|max:255',

                'badges' => 'nullable|array',
                'badges.*.label' => 'required_with:badges|string|max:60',
                'badges.*.value' => 'nullable|string|max:60',
            ],
            'defaults' => [
                'eyebrow' => null,
                'headline' => '',
                'subheadline' => null,
                'background_image' => null,
                'background_video' => null,
                'overlay' => ['opacity' => 0.45],
                'cta_primary' => [
                    'label' => 'Schedule a Free Consultation',
                    'url' => '/free-consultation/',
                ],
                'cta_secondary' => [
                    'label' => 'Call Now',
                    'url' => 'tel:',
                ],
                'badges' => [],
            ],
        ],

        'hero_slider' => [
            'type' => 'hero_slider',
            'label' => 'Hero Slider',
            'description' => 'Hero with image slider + headline and CTAs.',
            'schema' => [
                'headline' => 'required|string|max:140',
                'subheadline' => 'nullable|string|max:255',

                'slides' => 'required|array|min:1',
                'slides.*.image' => 'required|string|max:255',
                'slides.*.alt' => 'nullable|string|max:125',
                'slides.*.caption' => 'nullable|string|max:120',

                'cta_primary' => 'nullable|array',
                'cta_primary.label' => 'nullable|string|max:50',
                'cta_primary.url' => 'nullable|string|max:255',

                'cta_secondary' => 'nullable|array',
                'cta_secondary.label' => 'nullable|string|max:50',
                'cta_secondary.url' => 'nullable|string|max:255',

                'badges' => 'nullable|array',
                'badges.*.label' => 'required_with:badges|string|max:60',
                'badges.*.value' => 'nullable|string|max:60',
            ],
            'defaults' => [
                'headline' => '',
                'subheadline' => null,
                'slides' => [
                    ['image' => '', 'alt' => '', 'caption' => null],
                ],
                'cta_primary' => [
                    'label' => 'Schedule a Free Consultation',
                    'url' => '/free-consultation/',
                ],
                'cta_secondary' => [
                    'label' => 'Call Now',
                    'url' => 'tel:',
                ],
                'badges' => [],
            ],
        ],

        'rich_text' => [
            'type' => 'rich_text',
            'label' => 'Rich Text',
            'description' => 'Flexible text block: eyebrow, title, body, optional image and CTA button.',
            'schema' => [
                'eyebrow' => 'nullable|string|max:60',
                'title' => 'nullable|string|max:160',
                'content' => 'required|string',

                'image' => 'nullable|string|max:255',
                'image_alt' => 'nullable|string|max:125',
                'image_position' => 'nullable|in:left,right,top,bottom',

                'cta' => 'nullable|array',
                'cta.label' => 'nullable|string|max:50',
                'cta.url' => 'nullable|string|max:255',

                'align' => 'nullable|in:left,center,right',
                'variant' => 'nullable|in:default,light,dark',
            ],
            'defaults' => [
                'eyebrow' => null,
                'title' => null,
                'content' => '',
                'image' => null,
                'image_alt' => null,
                'image_position' => 'right',
                'cta' => ['label' => '', 'url' => ''],
                'align' => 'left',
                'variant' => 'default',
            ],
        ],

        'cta_block' => [
            'type' => 'cta_block',
            'label' => 'CTA Block',
            'description' => 'Primary CTA section with heading, supporting text and button.',
            'schema' => [
                'eyebrow' => 'nullable|string|max:60',
                'title' => 'required|string|max:140',
                'subtitle' => 'nullable|string|max:255',

                'button' => 'required|array',
                'button.label' => 'required|string|max:50',
                'button.url' => 'required|string|max:255',

                'subtext' => 'nullable|string|max:80', // LOCKED copy is enforced elsewhere if needed
                'variant' => 'nullable|in:default,light,dark',
            ],
            'defaults' => [
                'eyebrow' => null,
                'title' => 'Ready to Start Your Project?',
                'subtitle' => null,
                'button' => [
                    'label' => 'Schedule a Free Consultation',
                    'url' => '/free-consultation/',
                ],
                'subtext' => 'On-site or remote (Google Meet or Zoom)',
                'variant' => 'default',
            ],
        ],

        'faq_list' => [
            'type' => 'faq_list',
            'label' => 'FAQ List',
            'description' => 'Accordion FAQ list (schema is page-level; only /faq/ has FAQPage schema).',
            'schema' => [
                'title' => 'nullable|string|max:120',
                'subtitle' => 'nullable|string|max:255',

                'items' => 'required|array|min:1',
                'items.*.question' => 'required|string|max:255',
                'items.*.answer' => 'required|string',
            ],
            'defaults' => [
                'title' => 'Frequently Asked Questions',
                'subtitle' => null,
                'items' => [
                    ['question' => '', 'answer' => ''],
                ],
            ],
        ],

        'image_gallery' => [
            'type' => 'image_gallery',
            'label' => 'Image Gallery',
            'description' => 'Grid or carousel gallery.',
            'schema' => [
                'title' => 'nullable|string|max:120',
                'subtitle' => 'nullable|string|max:255',

                'layout' => 'nullable|in:grid,carousel,masonry',
                'items' => 'required|array|min:1',
                'items.*.image' => 'required|string|max:255',
                'items.*.alt' => 'nullable|string|max:125',
                'items.*.caption' => 'nullable|string|max:120',
            ],
            'defaults' => [
                'title' => null,
                'subtitle' => null,
                'layout' => 'grid',
                'items' => [
                    ['image' => '', 'alt' => '', 'caption' => null],
                ],
            ],
        ],

        // ---------------------------------------------------------------------
        // TRUST / E-E-A-T
        // ---------------------------------------------------------------------

        'trust_bar' => [
            'type' => 'trust_bar',
            'label' => 'Trust Bar',
            'description' => 'Inline proof points (license, rating, years, service area).',
            'schema' => [
                'items' => 'required|array|min:1|max:6',
                'items.*.icon' => 'nullable|string|max:60',
                'items.*.label' => 'required|string|max:60',
                'items.*.value' => 'nullable|string|max:60',
            ],
            'defaults' => [
                'items' => [
                    ['icon' => 'shield', 'label' => 'CT HIC License', 'value' => '#0668405'],
                    ['icon' => 'star', 'label' => '5-Star Rated Contractor', 'value' => null],
                    ['icon' => 'clock', 'label' => '15+ Years Experience', 'value' => null],
                    ['icon' => 'map', 'label' => 'Fairfield & New Haven Counties', 'value' => null],
                ],
            ],
        ],

        'stats_counter' => [
            'type' => 'stats_counter',
            'label' => 'Stats Counter',
            'description' => 'Key numbers / metrics displayed as large counters (e.g. 24+ Years, 100%).',
            'schema' => [
                'title' => 'nullable|string|max:120',
                'subtitle' => 'nullable|string|max:255',
                'variant' => 'nullable|in:default,light,dark',

                'items' => 'required|array|min:1|max:6',
                'items.*.value' => 'required|string|max:20',
                'items.*.label' => 'required|string|max:60',
                'items.*.icon' => 'nullable|string|max:60',
            ],
            'defaults' => [
                'title' => null,
                'subtitle' => null,
                'variant' => 'default',
                'items' => [
                    ['value' => '24+', 'label' => 'Years of Experience', 'icon' => 'clock'],
                    ['value' => '19+', 'label' => 'Industry Awards', 'icon' => 'trophy'],
                    ['value' => '100%', 'label' => 'Client Satisfaction', 'icon' => 'star'],
                    ['value' => '500+', 'label' => 'Projects Completed', 'icon' => 'check'],
                ],
            ],
        ],

        'testimonials' => [
            'type' => 'testimonials',
            'label' => 'Testimonials',
            'description' => 'Testimonials grid or carousel.',
            'schema' => [
                'title' => 'nullable|string|max:120',
                'subtitle' => 'nullable|string|max:255',
                'layout' => 'nullable|in:grid,carousel',

                'items' => 'required|array|min:1',
                'items.*.name' => 'required|string|max:100',
                'items.*.location' => 'nullable|string|max:100',
                'items.*.quote' => 'required|string|max:500',
                'items.*.avatar' => 'nullable|string|max:255',
                'items.*.rating' => 'nullable|integer|min:1|max:5',
                'items.*.year' => 'nullable|string|max:10',
            ],
            'defaults' => [
                'title' => 'What Our Clients Say',
                'subtitle' => 'Real feedback from homeowners.',
                'layout' => 'carousel',
                'items' => [
                    ['name' => '', 'location' => '', 'quote' => '', 'avatar' => null, 'rating' => 5, 'year' => null],
                ],
            ],
        ],

        'project_highlights' => [
            'type' => 'project_highlights',
            'label' => 'Project Highlights',
            'description' => 'Mini case studies / transformations list.',
            'schema' => [
                'eyebrow' => 'nullable|string|max:60',
                'title' => 'nullable|string|max:120',
                'items' => 'required|array|min:1',
                'items.*.title' => 'required|string|max:120',
                'items.*.description' => 'nullable|string|max:255',
                'items.*.image' => 'nullable|string|max:255',
                'items.*.url' => 'nullable|string|max:255',
                'items.*.tag' => 'nullable|string|max:40',
            ],
            'defaults' => [
                'eyebrow' => 'Recent Projects',
                'title' => 'Real Transformations',
                'items' => [
                    ['title' => '', 'description' => null, 'image' => null, 'url' => null, 'tag' => null],
                ],
            ],
        ],

        'logo_strip' => [
            'type' => 'logo_strip',
            'label' => 'Logo Strip',
            'description' => 'Brand logos strip (e.g., countertop partners).',
            'schema' => [
                'title' => 'nullable|string|max:120',
                'subtitle' => 'nullable|string|max:255',
                'items' => 'required|array|min:1',
                'items.*.name' => 'required|string|max:60',
                'items.*.logo' => 'required|string|max:255',
                'items.*.url' => 'nullable|string|max:255',
            ],
            'defaults' => [
                'title' => 'Premium Materials We Work With',
                'subtitle' => null,
                'items' => [
                    ['name' => 'Cambria', 'logo' => '', 'url' => null],
                ],
            ],
        ],

        'rich_text_image' => [
            'type' => 'rich_text_image',
            'label' => 'Rich Text + Image',
            'description' => 'Text block with side image and optional pull-quote.',
            'schema' => [
                'eyebrow' => 'nullable|string|max:60',
                'title' => 'nullable|string|max:160',
                'content' => 'required|string',
                'image' => 'nullable|string|max:255',
                'image_alt' => 'nullable|string|max:125',
                'image_position' => 'nullable|in:left,right',
                'quote_text' => 'nullable|string|max:500',
            ],
            'defaults' => [
                'eyebrow' => null,
                'title' => null,
                'content' => '',
                'image' => null,
                'image_alt' => null,
                'image_position' => 'right',
                'quote_text' => null,
            ],
        ],

        'feature_list_two_column' => [
            'type' => 'feature_list_two_column',
            'label' => 'Feature List (Two Column)',
            'description' => 'Two-column feature layout: left features with descriptions, right bullet list.',
            'schema' => [
                'eyebrow' => 'nullable|string|max:60',
                'title' => 'nullable|string|max:160',
                'left_paragraph' => 'nullable|string|max:500',

                'left_features' => 'required|array|min:1',
                'left_features.*.title' => 'required|string|max:80',
                'left_features.*.description' => 'required|string|max:400',

                'right_title' => 'nullable|string|max:120',
                'right_bullets' => 'nullable|array',
                'right_bullets.*' => 'required_with:right_bullets|string|max:120',

                'closing_quote' => 'nullable|string|max:500',
            ],
            'defaults' => [
                'eyebrow' => 'Peace of Mind Guarantee',
                'title' => 'Our Solid Warranty Promise',
                'left_paragraph' => "We don't just build it right; we stand behind it. Every project comes with our comprehensive workmanship guarantee, ensuring your investment is protected long after we leave the job site.",
                'left_features' => [
                    [
                        'title' => 'Superior Craftsmanship',
                        'description' => 'Built to code and beyond, using only premium materials.',
                    ],
                    [
                        'title' => 'Long-Term Protection',
                        'description' => 'Issues related to our work? We fix them. No questions asked.',
                    ],
                ],
                'right_title' => "What's Included",
                'right_bullets' => [
                    'Structural Integrity Guarantee',
                    'Material Defect Coverage Assistance',
                    'Workmanship Quality Assurance',
                    'Post-Completion Support',
                    'Transparent Warranty Documentation',
                    'Annual Maintenance Check-ins',
                    'Priority Service Scheduling',
                ],
                'closing_quote' => "We build it like it's our own home. That's the BuiltWell standard.",
            ],
        ],

        'areas_served' => [
            'type' => 'areas_served',
            'label' => 'Areas Served',
            'description' => 'Counties with expandable town lists.',
            'schema' => [
                'title' => 'nullable|string|max:120',
                'subtitle' => 'nullable|string|max:255',

                'counties' => 'required|array|min:1',
                'counties.*.name' => 'required|string|max:80',
                'counties.*.towns' => 'required|array|min:1',
                'counties.*.towns.*' => 'required|string|max:60',
            ],
            'defaults' => [
                'title' => 'Areas We Serve',
                'subtitle' => null,
                'counties' => [
                    ['name' => 'Fairfield County', 'towns' => ['Greenwich', 'Stamford', 'Norwalk']],
                    ['name' => 'New Haven County', 'towns' => ['New Haven', 'Orange', 'Milford']],
                ],
            ],
        ],

        // ---------------------------------------------------------------------
        // SERVICE-SPECIFIC
        // ---------------------------------------------------------------------

        'service_includes' => [
            'type' => 'service_includes',
            'label' => 'What’s Included',
            'description' => 'Checklist bullets for what is included in the service.',
            'schema' => [
                'title' => 'nullable|string|max:120',
                'items' => 'required|array|min:1',
                'items.*' => 'required|string|max:120',
            ],
            'defaults' => [
                'title' => "What's Included",
                'items' => [
                    'Layout planning and space flow',
                    'Cabinetry and material selection',
                    'Lighting and electrical coordination',
                    'Structural changes when required',
                ],
            ],
        ],

        'pricing_table' => [
            'type' => 'pricing_table',
            'label' => 'Pricing Table',
            'description' => 'Service pricing table (tiers / ranges).',
            'schema' => [
                'title' => 'nullable|string|max:120',
                'subtitle' => 'nullable|string|max:255',

                'columns' => 'nullable|array|min:2|max:5',
                'columns.*' => 'required_with:columns|string|max:40',

                'rows' => 'required|array|min:1',
                'rows.*.label' => 'required|string|max:80',
                'rows.*.price' => 'required|string|max:50',
                'rows.*.notes' => 'nullable|string|max:255',
            ],
            'defaults' => [
                'title' => 'Cost in Connecticut',
                'subtitle' => null,
                'columns' => ['Level', 'Price Range', "What's Included"],
                'rows' => [
                    ['label' => 'Mid-Range', 'price' => '$50,000 – $90,000', 'notes' => 'New cabinets, counters, flooring, lighting'],
                ],
            ],
        ],

        'timeline_block' => [
            'type' => 'timeline_block',
            'label' => 'Timeline / Lead Times',
            'description' => 'Typical timeline section (weeks/days) + notes.',
            'schema' => [
                'title' => 'nullable|string|max:120',
                'timeline' => 'required|string|max:60',
                'notes' => 'nullable|string|max:255',
            ],
            'defaults' => [
                'title' => 'Timeline in Connecticut',
                'timeline' => '6–12 weeks',
                'notes' => 'Custom cabinets can add 8–12 weeks lead time.',
            ],
        ],

        'process_steps' => [
            'type' => 'process_steps',
            'label' => '5-Step Process',
            'description' => 'Exactly 5 steps (Consultation → Planning → Selections → Build → Walkthrough).',
            'schema' => [
                'title' => 'nullable|string|max:120',
                'subtitle' => 'nullable|string|max:255',
                'steps' => 'required|array|size:5',
                'steps.*.title' => 'required|string|max:50',
                'steps.*.short' => 'required|string|max:120',
                'steps.*.description' => 'required|string|max:255',
            ],
            'defaults' => [
                'title' => 'From First Call to Finished Project',
                'subtitle' => null,
                'steps' => [
                    ['title' => 'Consultation', 'short' => 'We visit your home or meet via Zoom', 'description' => 'We discuss goals, assess the space, and answer questions.'],
                    ['title' => 'Planning', 'short' => 'Detailed scope, timeline, and proposal', 'description' => 'You receive a clear proposal with what’s included and how long it takes.'],
                    ['title' => 'Selections', 'short' => 'Materials and finish selections', 'description' => 'We guide you through cabinets, counters, tile, flooring, and options.'],
                    ['title' => 'Build', 'short' => 'Construction with daily communication', 'description' => 'We show up when we say we will, keep the site clean, and update you daily.'],
                    ['title' => 'Walkthrough', 'short' => 'Final review before we call it done', 'description' => 'We walk through everything together and address anything that needs attention.'],
                ],
            ],
        ],

        'service_area_links' => [
            'type' => 'service_area_links',
            'label' => 'Service Area Links',
            'description' => 'Links to county hubs / towns (respects linking rules).',
            'schema' => [
                'title' => 'nullable|string|max:120',
                'subtitle' => 'nullable|string|max:255',

                'groups' => 'required|array|min:1',
                'groups.*.title' => 'required|string|max:80',
                'groups.*.links' => 'required|array|min:1',
                'groups.*.links.*.label' => 'required|string|max:80',
                'groups.*.links.*.url' => 'required|string|max:255',
            ],
            'defaults' => [
                'title' => 'Serving Fairfield & New Haven County',
                'subtitle' => null,
                'groups' => [
                    [
                        'title' => 'Fairfield County',
                        'links' => [
                            ['label' => 'Fairfield County', 'url' => '/fairfield-county/'],
                        ],
                    ],
                    [
                        'title' => 'New Haven County',
                        'links' => [
                            ['label' => 'New Haven County', 'url' => '/new-haven-county/'],
                            ['label' => 'Visit our Orange office', 'url' => '/new-haven-county/orange-ct/'],
                        ],
                    ],
                ],
            ],
        ],

        'local_context' => [
            'type' => 'local_context',
            'label' => 'Local Context',
            'description' => 'Town/county specific context block (2-3 sentences).',
            'schema' => [
                'title' => 'nullable|string|max:120',
                'content' => 'required|string|max:600',
            ],
            'defaults' => [
                'title' => 'Local Context',
                'content' => '',
            ],
        ],

        'before_after' => [
            'type' => 'before_after',
            'label' => 'Before / After',
            'description' => 'Before/After transformation blocks with images and copy.',
            'schema' => [
                'title' => 'nullable|string|max:120',
                'subtitle' => 'nullable|string|max:255',

                'items' => 'required|array|min:1',
                'items.*.title' => 'nullable|string|max:120',
                'items.*.before_image' => 'required|string|max:255',
                'items.*.after_image' => 'required|string|max:255',
                'items.*.before_text' => 'nullable|string|max:400',
                'items.*.after_text' => 'nullable|string|max:400',

                'items.*.quote' => 'nullable|array',
                'items.*.quote.text' => 'nullable|string|max:500',
                'items.*.quote.author' => 'nullable|string|max:120',
                'items.*.quote.location' => 'nullable|string|max:120',
            ],
            'defaults' => [
                'title' => 'Real Transformations',
                'subtitle' => null,
                'items' => [
                    [
                        'title' => null,
                        'before_image' => '',
                        'after_image' => '',
                        'before_text' => null,
                        'after_text' => null,
                        'quote' => [
                            'text' => null,
                            'author' => null,
                            'location' => null,
                        ],
                    ],
                ],
            ],
        ],

        // ---------------------------------------------------------------------
        // HUBS / LISTS
        // ---------------------------------------------------------------------

        'services_grid' => [
            'type' => 'services_grid',
            'label' => 'Services Grid',
            'description' => 'Card grid for services (image, title, summary, link).',
            'schema' => [
                'title' => 'nullable|string|max:120',
                'subtitle' => 'nullable|string|max:255',

                'items' => 'required|array|min:1',
                'items.*.title' => 'required|string|max:80',
                'items.*.summary' => 'nullable|string|max:160',
                'items.*.image' => 'nullable|string|max:255',
                'items.*.url' => 'required|string|max:255',
                'items.*.cta_label' => 'nullable|string|max:40',
            ],
            'defaults' => [
                'title' => 'Our Services',
                'subtitle' => null,
                'items' => [
                    [
                        'title' => 'Kitchen Remodeling',
                        'summary' => null,
                        'image' => null,
                        'url' => '/kitchen-remodeling/',
                        'cta_label' => 'Book Consultation',
                    ],
                ],
            ],
        ],

        'areas_we_serve_cards' => [
            'type' => 'areas_we_serve_cards',
            'label' => 'Areas We Serve Cards',
            'description' => 'Two big cards (Fairfield County / New Haven County).',
            'schema' => [
                'title' => 'nullable|string|max:120',
                'subtitle' => 'nullable|string|max:255',
                'items' => 'required|array|min:2|max:2',
                'items.*.title' => 'required|string|max:60',
                'items.*.image' => 'nullable|string|max:255',
                'items.*.url' => 'required|string|max:255',
            ],
            'defaults' => [
                'title' => 'Areas We Serve',
                'subtitle' => 'Proudly serving homeowners throughout Fairfield County and New Haven County, Connecticut.',
                'items' => [
                    ['title' => 'Fairfield County', 'image' => null, 'url' => '/fairfield-county/'],
                    ['title' => 'New Haven County', 'image' => null, 'url' => '/new-haven-county/'],
                ],
            ],
        ],

        'town_list' => [
            'type' => 'town_list',
            'label' => 'Town List',
            'description' => 'Town list grouped by county (Tier 1 links + Tier 2 text-only).',
            'schema' => [
                'county' => 'required|in:fairfield,new_haven',
                'title' => 'nullable|string|max:120',

                'tier1' => 'required|array|min:1',
                'tier1.*.label' => 'required|string|max:60',
                'tier1.*.url' => 'required|string|max:255',

                'tier2' => 'nullable|array',
                'tier2.*' => 'required_with:tier2|string|max:60',
            ],
            'defaults' => [
                'county' => 'fairfield',
                'title' => null,
                'tier1' => [
                    ['label' => 'Greenwich', 'url' => '/fairfield-county/greenwich-ct/'],
                ],
                'tier2' => [],
            ],
        ],

        'map_embed' => [
            'type' => 'map_embed',
            'label' => 'Map Embed',
            'description' => 'Map embed (use ONLY on Orange office page per spec).',
            'schema' => [
                'title' => 'nullable|string|max:120',
                'embed_url' => 'required|string|max:500',
                'height' => 'nullable|integer|min:200|max:900',
            ],
            'defaults' => [
                'title' => 'Map',
                'embed_url' => '',
                'height' => 420,
            ],
        ],

        // ---------------------------------------------------------------------
        // CASE STUDIES
        // ---------------------------------------------------------------------

        'case_study_header' => [
            'type' => 'case_study_header',
            'label' => 'Case Study Header',
            'description' => 'Case study hero header with title, intro, cover image.',
            'schema' => [
                'title' => 'required|string|max:160',
                'subtitle' => 'nullable|string|max:255',
                'cover_image' => 'nullable|string|max:255',
                'cover_alt' => 'nullable|string|max:125',
            ],
            'defaults' => [
                'title' => '',
                'subtitle' => null,
                'cover_image' => null,
                'cover_alt' => null,
            ],
        ],

        'case_study_meta' => [
            'type' => 'case_study_meta',
            'label' => 'Case Study Meta',
            'description' => 'Structured fields: location, services, timeline, square footage, etc.',
            'schema' => [
                'items' => 'required|array|min:1',
                'items.*.label' => 'required|string|max:40',
                'items.*.value' => 'required|string|max:120',
            ],
            'defaults' => [
                'items' => [
                    ['label' => 'Location', 'value' => ''],
                    ['label' => 'Timeline', 'value' => ''],
                ],
            ],
        ],

        'case_study_body' => [
            'type' => 'case_study_body',
            'label' => 'Case Study Body',
            'description' => 'Main narrative blocks: challenge, approach, results.',
            'schema' => [
                'blocks' => 'required|array|min:1',
                'blocks.*.heading' => 'required|string|max:80',
                'blocks.*.content' => 'required|string',
            ],
            'defaults' => [
                'blocks' => [
                    ['heading' => 'The Challenge', 'content' => ''],
                    ['heading' => 'Our Approach', 'content' => ''],
                    ['heading' => 'The Results', 'content' => ''],
                ],
            ],
        ],

        'case_study_gallery' => [
            'type' => 'case_study_gallery',
            'label' => 'Case Study Gallery',
            'description' => 'Before/after or detail gallery for case study.',
            'schema' => [
                'items' => 'required|array|min:1',
                'items.*.image' => 'required|string|max:255',
                'items.*.alt' => 'nullable|string|max:125',
                'items.*.caption' => 'nullable|string|max:120',
            ],
            'defaults' => [
                'items' => [
                    ['image' => '', 'alt' => '', 'caption' => null],
                ],
            ],
        ],

        // ---------------------------------------------------------------------
        // FREE CONSULTATION (FORM LAYOUT SECTION)
        // ---------------------------------------------------------------------

        'lead_form' => [
            'type' => 'lead_form',
            'label' => 'Lead Form',
            'description' => 'Free consultation form section (layout + steps + fields).',
            'schema' => [
                'title' => 'nullable|string|max:120',
                'subtitle' => 'nullable|string|max:255',

                'steps' => 'nullable|array|min:1|max:5',
                'steps.*.number' => 'required_with:steps|integer|min:1|max:5',
                'steps.*.text' => 'required_with:steps|string|max:80',

                'fields' => 'required|array|min:1',
                'fields.*.name' => 'required|string|max:40',
                'fields.*.label' => 'required|string|max:60',
                'fields.*.type' => 'required|in:text,email,tel,select,textarea,file',
                'fields.*.required' => 'boolean',
                'fields.*.options' => 'nullable|array',
                'fields.*.options.*.label' => 'required_with:fields.*.options|string|max:60',
                'fields.*.options.*.value' => 'required_with:fields.*.options|string|max:60',

                'submit_label' => 'required|string|max:50',
                'consent_text' => 'nullable|string|max:180',
            ],
            'defaults' => [
                'title' => 'Schedule Your Free Consultation',
                'subtitle' => "Select a time that works for you. We'll confirm shortly.",
                'steps' => [
                    ['number' => 1, 'text' => 'Tell us about your project'],
                    ['number' => 2, 'text' => 'Schedule a site visit'],
                    ['number' => 3, 'text' => 'Get your detailed proposal'],
                ],
                'fields' => [
                    ['name' => 'full_name', 'label' => 'Full Name', 'type' => 'text', 'required' => true],
                    ['name' => 'phone', 'label' => 'Phone Number', 'type' => 'tel', 'required' => true],
                    ['name' => 'contact_preference', 'label' => 'Contact Preference', 'type' => 'select', 'required' => false, 'options' => [
                        ['label' => 'Call', 'value' => 'call'],
                        ['label' => 'Text', 'value' => 'text'],
                        ['label' => 'Email', 'value' => 'email'],
                    ]],
                    ['name' => 'email', 'label' => 'Email Address', 'type' => 'email', 'required' => true],
                    ['name' => 'project_type', 'label' => 'Project Type', 'type' => 'select', 'required' => true, 'options' => [
                        ['label' => 'Kitchen Remodeling', 'value' => 'kitchen'],
                        ['label' => 'Bathroom Remodeling', 'value' => 'bathroom'],
                        ['label' => 'Basement Finishing', 'value' => 'basement'],
                        ['label' => 'Flooring', 'value' => 'flooring'],
                        ['label' => 'Other', 'value' => 'other'],
                    ]],
                    ['name' => 'zip', 'label' => 'Zip Code', 'type' => 'text', 'required' => true],
                    ['name' => 'street_address', 'label' => 'Street Address (Optional)', 'type' => 'text', 'required' => false],
                    ['name' => 'photos', 'label' => 'Upload Project Photos (Optional)', 'type' => 'file', 'required' => false],
                    ['name' => 'details', 'label' => 'Tell us a bit about your project (optional)...', 'type' => 'textarea', 'required' => false],
                ],
                'submit_label' => 'Book Free Consultation',
                'consent_text' => 'By submitting, you agree to receive calls or texts from BUILTWELL.',
            ],
        ],

        // ---------------------------------------------------------------------
        // ABOUT / GENERAL PURPOSE
        // ---------------------------------------------------------------------

        'page_hero' => [
            'type' => 'page_hero',
            'label' => 'Page Hero',
            'description' => 'Interior page hero with eyebrow, title, subtitle and background image.',
            'schema' => [
                'eyebrow' => 'nullable|string|max:60',
                'title' => 'required|string|max:140',
                'subtitle' => 'nullable|string|max:400',
                'background_image' => 'nullable|string|max:255',
                'overlay_opacity' => 'nullable|numeric|min:0|max:1',
                'alignment' => 'nullable|in:left,center',
            ],
            'defaults' => [
                'eyebrow' => null,
                'title' => '',
                'subtitle' => null,
                'background_image' => null,
                'overlay_opacity' => 0.45,
                'alignment' => 'left',
            ],
        ],

        'image_text_split' => [
            'type' => 'image_text_split',
            'label' => 'Image + Text Split',
            'description' => 'Side-by-side layout with image and rich text content, optional quote.',
            'schema' => [
                'eyebrow' => 'nullable|string|max:60',
                'title' => 'nullable|string|max:160',
                'content' => 'required|string',
                'image' => 'nullable|string|max:255',
                'image_alt' => 'nullable|string|max:125',
                'image_position' => 'nullable|in:left,right',
                'quote' => 'nullable|string|max:500',
            ],
            'defaults' => [
                'eyebrow' => null,
                'title' => null,
                'content' => '',
                'image' => null,
                'image_alt' => null,
                'image_position' => 'left',
                'quote' => null,
            ],
        ],

        'icon_cards' => [
            'type' => 'icon_cards',
            'label' => 'Icon Cards',
            'description' => 'Grid of cards with icon, title, and description.',
            'schema' => [
                'title' => 'nullable|string|max:120',
                'subtitle' => 'nullable|string|max:255',
                'items' => 'required|array|min:1|max:6',
                'items.*.icon' => 'nullable|string|max:60',
                'items.*.title' => 'required|string|max:80',
                'items.*.description' => 'required|string|max:400',
            ],
            'defaults' => [
                'title' => null,
                'subtitle' => null,
                'items' => [
                    ['icon' => '', 'title' => '', 'description' => ''],
                ],
            ],
        ],

        'team_members' => [
            'type' => 'team_members',
            'label' => 'Team Members',
            'description' => 'Team/leadership profile cards with photo and bio.',
            'schema' => [
                'title' => 'nullable|string|max:120',
                'subtitle' => 'nullable|string|max:255',
                'members' => 'required|array|min:1',
                'members.*.name' => 'required|string|max:100',
                'members.*.position' => 'nullable|string|max:100',
                'members.*.image' => 'nullable|string|max:255',
                'members.*.bio' => 'nullable|string',
            ],
            'defaults' => [
                'title' => 'Our Leadership',
                'subtitle' => null,
                'members' => [
                    ['name' => '', 'position' => '', 'image' => null, 'bio' => ''],
                ],
            ],
        ],

        'two_column_text' => [
            'type' => 'two_column_text',
            'label' => 'Two Column Text',
            'description' => 'Side-by-side text columns with a shared heading.',
            'schema' => [
                'title' => 'nullable|string|max:120',
                'left_content' => 'required|string',
                'right_content' => 'required|string',
            ],
            'defaults' => [
                'title' => null,
                'left_content' => '',
                'right_content' => '',
            ],
        ],

        'service_areas' => [
            'type' => 'service_areas',
            'label' => 'Service Areas (About)',
            'description' => 'County cards with images, city lists, and optional map embed.',
            'schema' => [
                'title' => 'nullable|string|max:120',
                'subtitle' => 'nullable|string|max:255',
                'counties' => 'required|array|min:1',
                'counties.*.county_name' => 'required|string|max:80',
                'counties.*.image' => 'nullable|string|max:255',
                'counties.*.cities' => 'required|array|min:1',
                'counties.*.cities.*' => 'required|string|max:60',
                'show_map' => 'nullable|boolean',
                'map_embed' => 'nullable|string|max:500',
            ],
            'defaults' => [
                'title' => 'Areas We Serve',
                'subtitle' => null,
                'counties' => [
                    ['county_name' => 'Fairfield County', 'image' => null, 'cities' => ['Greenwich']],
                ],
                'show_map' => false,
                'map_embed' => null,
            ],
        ],

        'office_info' => [
            'type' => 'office_info',
            'label' => 'Office Info',
            'description' => 'Office location card with address, phone, email, and directions link.',
            'schema' => [
                'title' => 'nullable|string|max:120',
                'address' => 'required|string|max:255',
                'phone' => 'nullable|string|max:32',
                'email' => 'nullable|string|max:120',
                'office_image' => 'nullable|string|max:255',
                'directions_url' => 'nullable|string|max:500',
            ],
            'defaults' => [
                'title' => 'Visit Our Office',
                'address' => '',
                'phone' => null,
                'email' => null,
                'office_image' => null,
                'directions_url' => null,
            ],
        ],

        'feature_grid' => [
            'type' => 'feature_grid',
            'label' => 'Feature Grid',
            'description' => 'Grid of feature/guarantee cards with icon, title, and description.',
            'schema' => [
                'title' => 'nullable|string|max:120',
                'subtitle' => 'nullable|string|max:255',
                'items' => 'required|array|min:1|max:8',
                'items.*.icon' => 'nullable|string|max:60',
                'items.*.title' => 'required|string|max:80',
                'items.*.description' => 'required|string|max:400',
            ],
            'defaults' => [
                'title' => null,
                'subtitle' => null,
                'items' => [
                    ['icon' => '', 'title' => '', 'description' => ''],
                ],
            ],
        ],

        'dark_text_section' => [
            'type' => 'dark_text_section',
            'label' => 'Dark Text Section',
            'description' => 'Full-width dark background section with title and rich text content.',
            'schema' => [
                'title' => 'nullable|string|max:120',
                'content' => 'required|string',
            ],
            'defaults' => [
                'title' => null,
                'content' => '',
            ],
        ],

        'cta_split_form' => [
            'type' => 'cta_split_form',
            'label' => 'CTA Split Form',
            'description' => 'CTA section with steps list alongside a consultation form.',
            'schema' => [
                'title' => 'nullable|string|max:120',
                'subtitle' => 'nullable|string|max:255',
                'steps' => 'nullable|array|min:1|max:5',
                'steps.*.number' => 'required_with:steps|integer|min:1|max:5',
                'steps.*.text' => 'required_with:steps|string|max:80',
                'form_id' => 'nullable|string|max:60',
                'background_image' => 'nullable|string|max:255',
            ],
            'defaults' => [
                'title' => 'Ready to Start Your Project?',
                'subtitle' => null,
                'steps' => [
                    ['number' => 1, 'text' => 'Tell us about your project'],
                    ['number' => 2, 'text' => 'Schedule a site visit'],
                    ['number' => 3, 'text' => 'Get your detailed proposal'],
                ],
                'form_id' => null,
                'background_image' => null,
            ],
        ],

        'service_hero' => [
            'type' => 'service_hero',
            'label' => 'Service Hero',
            'description' => 'Service page hero with title, subtitle, background image and CTAs.',
            'schema' => [
                'title' => 'required|string|max:140',
                'subtitle' => 'nullable|string|max:400',
                'background_image' => 'nullable|string|max:255',
                'primary_cta' => 'nullable|array',
                'primary_cta.label' => 'nullable|string|max:50',
                'primary_cta.url' => 'nullable|string|max:255',
                'secondary_cta' => 'nullable|array',
                'secondary_cta.label' => 'nullable|string|max:50',
                'secondary_cta.url' => 'nullable|string|max:255',
                'overlay_opacity' => 'nullable|numeric|min:0|max:1',
            ],
            'defaults' => [
                'title' => '',
                'subtitle' => null,
                'background_image' => null,
                'primary_cta' => ['label' => 'Schedule a Free Consultation', 'url' => '/free-consultation/'],
                'secondary_cta' => ['label' => 'Call Now', 'url' => 'tel:'],
                'overlay_opacity' => 0.45,
            ],
        ],

        'service_intro_split' => [
            'type' => 'service_intro_split',
            'label' => 'Service Intro Split',
            'description' => 'Service overview with text, image(s), and bullet points.',
            'schema' => [
                'title' => 'nullable|string|max:160',
                'content' => 'required|string',
                'image_main' => 'nullable|string|max:255',
                'image_secondary' => 'nullable|string|max:255',
                'bullet_points' => 'nullable|array',
                'bullet_points.*.text' => 'required_with:bullet_points|string|max:120',
            ],
            'defaults' => [
                'title' => null,
                'content' => '',
                'image_main' => null,
                'image_secondary' => null,
                'bullet_points' => [],
            ],
        ],

        'service_process' => [
            'type' => 'service_process',
            'label' => 'Service Process',
            'description' => 'Horizontal step cards showing the service process.',
            'schema' => [
                'title' => 'nullable|string|max:120',
                'steps' => 'required|array|min:1|max:7',
                'steps.*.step_number' => 'required|integer|min:1',
                'steps.*.title' => 'required|string|max:50',
                'steps.*.description' => 'required|string|max:255',
            ],
            'defaults' => [
                'title' => null,
                'steps' => [
                    ['step_number' => 1, 'title' => 'Consultation', 'description' => ''],
                    ['step_number' => 2, 'title' => 'Planning', 'description' => ''],
                    ['step_number' => 3, 'title' => 'Selections', 'description' => ''],
                    ['step_number' => 4, 'title' => 'Build', 'description' => ''],
                    ['step_number' => 5, 'title' => 'Walkthrough', 'description' => ''],
                ],
            ],
        ],

        'full_width_text_dark' => [
            'type' => 'full_width_text_dark',
            'label' => 'Full Width Text (Dark)',
            'description' => 'Dark background band with title, subtitle, and alignment.',
            'schema' => [
                'title' => 'required|string|max:140',
                'subtitle' => 'nullable|string|max:400',
                'alignment' => 'nullable|in:left,center,right',
            ],
            'defaults' => [
                'title' => '',
                'subtitle' => null,
                'alignment' => 'center',
            ],
        ],

        'before_after_grid' => [
            'type' => 'before_after_grid',
            'label' => 'Before/After Grid',
            'description' => 'Project showcase with before/after images and testimonials.',
            'schema' => [
                'title' => 'nullable|string|max:120',
                'subtitle' => 'nullable|string|max:255',
                'projects' => 'required|array|min:1',
                'projects.*.before_image' => 'nullable|string|max:255',
                'projects.*.after_image' => 'nullable|string|max:255',
                'projects.*.location' => 'nullable|string|max:100',
                'projects.*.description' => 'nullable|string|max:255',
                'projects.*.testimonial_quote' => 'nullable|string|max:500',
            ],
            'defaults' => [
                'title' => null,
                'subtitle' => null,
                'projects' => [
                    ['before_image' => null, 'after_image' => null, 'location' => '', 'description' => '', 'testimonial_quote' => null],
                ],
            ],
        ],

        'testimonial_slider_small' => [
            'type' => 'testimonial_slider_small',
            'label' => 'Testimonial Slider (Small)',
            'description' => 'Compact testimonial carousel strip.',
            'schema' => [
                'title' => 'nullable|string|max:120',
                'testimonials' => 'required|array|min:1',
                'testimonials.*.name' => 'required|string|max:100',
                'testimonials.*.city' => 'nullable|string|max:100',
                'testimonials.*.rating' => 'nullable|integer|min:1|max:5',
                'testimonials.*.text' => 'required|string|max:500',
            ],
            'defaults' => [
                'title' => null,
                'testimonials' => [
                    ['name' => '', 'city' => '', 'rating' => 5, 'text' => ''],
                ],
            ],
        ],

        'service_area_text' => [
            'type' => 'service_area_text',
            'label' => 'Service Area Text',
            'description' => 'Text strip listing service areas by county.',
            'schema' => [
                'title' => 'nullable|string|max:120',
                'content' => 'nullable|string|max:600',
                'counties' => 'nullable|array',
                'counties.*' => 'required_with:counties|string|max:80',
            ],
            'defaults' => [
                'title' => null,
                'content' => null,
                'counties' => [],
            ],
        ],

        'faq_accordion' => [
            'type' => 'faq_accordion',
            'label' => 'FAQ Accordion',
            'description' => 'Collapsible FAQ section for service pages.',
            'schema' => [
                'title' => 'nullable|string|max:120',
                'items' => 'required|array|min:1',
                'items.*.question' => 'required|string|max:255',
                'items.*.answer' => 'required|string',
            ],
            'defaults' => [
                'title' => 'Frequently Asked Questions',
                'items' => [
                    ['question' => '', 'answer' => ''],
                ],
            ],
        ],

        // ---------------------------------------------------------------------
        // SERVICE LOCATION (TOWN-LEVEL) SECTIONS
        // ---------------------------------------------------------------------

        'hero_service_location' => [
            'type' => 'hero_service_location',
            'label' => 'Service Location Hero',
            'description' => 'Hero section for town-level service pages with background image and CTAs.',
            'schema' => [
                'headline' => 'required|string|max:140',
                'subheadline' => 'nullable|string|max:400',
                'background_image' => 'nullable|string|max:255',
                'primary_cta' => 'nullable|array',
                'primary_cta.label' => 'nullable|string|max:50',
                'primary_cta.url' => 'nullable|string|max:255',
                'secondary_cta' => 'nullable|array',
                'secondary_cta.label' => 'nullable|string|max:50',
                'secondary_cta.url' => 'nullable|string|max:255',
            ],
            'defaults' => [
                'headline' => '',
                'subheadline' => null,
                'background_image' => null,
                'primary_cta' => ['label' => 'Schedule a Free Consultation', 'url' => '/free-consultation/'],
                'secondary_cta' => ['label' => 'Call Now', 'url' => 'tel:'],
            ],
        ],

        'service_two_column' => [
            'type' => 'service_two_column',
            'label' => 'Service Two Column',
            'description' => 'Service overview with headline, description, bullet points, and image.',
            'schema' => [
                'section_label' => 'nullable|string|max:60',
                'headline' => 'required|string|max:160',
                'description' => 'required|string',
                'bullet_points' => 'nullable|array',
                'bullet_points.*' => 'required_with:bullet_points|string|max:120',
                'image' => 'nullable|string|max:255',
                'image_alt' => 'nullable|string|max:125',
            ],
            'defaults' => [
                'section_label' => null,
                'headline' => '',
                'description' => '',
                'bullet_points' => [],
                'image' => null,
                'image_alt' => null,
            ],
        ],

        'before_after_showcase' => [
            'type' => 'before_after_showcase',
            'label' => 'Before/After Showcase',
            'description' => 'Single before/after comparison with description.',
            'schema' => [
                'section_label' => 'nullable|string|max:60',
                'headline' => 'nullable|string|max:160',
                'description' => 'nullable|string|max:500',
                'before_image' => 'nullable|string|max:255',
                'after_image' => 'nullable|string|max:255',
            ],
            'defaults' => [
                'section_label' => null,
                'headline' => null,
                'description' => null,
                'before_image' => null,
                'after_image' => null,
            ],
        ],

        'service_area_highlight' => [
            'type' => 'service_area_highlight',
            'label' => 'Service Area Highlight',
            'description' => 'Short service area callout for town-level pages.',
            'schema' => [
                'headline' => 'required|string|max:120',
                'description' => 'nullable|string|max:400',
            ],
            'defaults' => [
                'headline' => '',
                'description' => null,
            ],
        ],

        'consultation_cta_split' => [
            'type' => 'consultation_cta_split',
            'label' => 'Consultation CTA Split',
            'description' => 'Split layout with CTA steps on left and consultation form on right.',
            'schema' => [
                'headline' => 'required|string|max:140',
                'steps' => 'nullable|array|min:1|max:5',
                'steps.*.number' => 'required_with:steps|integer|min:1|max:5',
                'steps.*.text' => 'required_with:steps|string|max:80',
                'fields' => 'required|array|min:1',
                'fields.*.name' => 'required|string|max:40',
                'fields.*.label' => 'required|string|max:60',
                'fields.*.type' => 'required|in:text,email,tel,select,textarea',
                'fields.*.required' => 'boolean',
                'fields.*.options' => 'nullable|array',
                'fields.*.options.*.label' => 'required_with:fields.*.options|string|max:60',
                'fields.*.options.*.value' => 'required_with:fields.*.options|string|max:60',
                'submit_label' => 'required|string|max:50',
            ],
            'defaults' => [
                'headline' => 'Stop Dreaming, Start Building.',
                'steps' => [
                    ['number' => 1, 'text' => 'Tell us about your project'],
                    ['number' => 2, 'text' => 'Schedule a visit'],
                    ['number' => 3, 'text' => 'Get your proposal'],
                ],
                'fields' => [
                    ['name' => 'full_name', 'label' => 'Full Name', 'type' => 'text', 'required' => true],
                    ['name' => 'phone', 'label' => 'Phone', 'type' => 'tel', 'required' => true],
                    ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'required' => true],
                ],
                'submit_label' => 'Book Free Consultation',
            ],
        ],

    ],

];
