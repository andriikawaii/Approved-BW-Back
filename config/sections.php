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
                'items.*.year' => 'nullable|string|max:10',
            ],
            'defaults' => [
                'title' => 'What Our Clients Say',
                'subtitle' => 'Real feedback from homeowners.',
                'layout' => 'carousel',
                'items' => [
                    ['name' => '', 'location' => '', 'quote' => '', 'avatar' => null, 'year' => null],
                ],
            ],
        ],

        'project_highlights' => [
            'type' => 'project_highlights',
            'label' => 'Project Highlights',
            'description' => 'Mini case studies / transformations list.',
            'schema' => [
                'title' => 'nullable|string|max:120',
                'items' => 'required|array|min:1',
                'items.*.title' => 'required|string|max:120',
                'items.*.description' => 'nullable|string|max:255',
                'items.*.image' => 'nullable|string|max:255',
                'items.*.url' => 'nullable|string|max:255',
                'items.*.tag' => 'nullable|string|max:40',
            ],
            'defaults' => [
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

    ],

];
