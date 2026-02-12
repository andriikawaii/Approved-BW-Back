<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Page Template → Allowed Sections Registry
    |--------------------------------------------------------------------------
    |
    | Defines which section types are allowed/required for each page template.
    |
    | - allowed: Array of section types that CAN be added to this template
    | - required: Array of section types that MUST exist (and be active) to save
    | - min/max: Min and max number of sections allowed
    | - defaults: Sections automatically created when page is initialized
    |
    */

    // ========================================================================
    // HOME PAGE
    // ========================================================================
    'home' => [
        'allowed' => [
            'hero_slider',
            'trust_bar',
            'rich_text',
            'services_grid',
            'stats_counter',
            'process_steps',
            'testimonials',
            'project_highlights',
            'before_after',
            'faq_list',
            'areas_we_serve_cards',
            'town_list',
            'image_gallery',
            'cta_block',
            'lead_form',
        ],
        'required' => ['hero_slider'],
        'min' => 1,
        'max' => 20,
        'defaults' => [
            ['type' => 'hero_slider'],
            ['type' => 'trust_bar'],
            ['type' => 'services_grid'],
            ['type' => 'areas_we_serve_cards'],
        ],
    ],

    // ========================================================================
    // SERVICE PAGES (Global)
    // ========================================================================
    'service_global' => [
        'allowed' => [
            'hero_slider',
            'trust_bar',
            'rich_text',
            'service_includes',
            'pricing_table',
            'timeline_block',
            'process_steps',
            'before_after',
            'testimonials',
            'faq_list',
            'service_area_links',
            'cta_block',
            'project_highlights',
            'logo_strip',
            'image_gallery',
        ],
        'required' => ['hero_slider', 'process_steps'],
        'min' => 3,
        'max' => 25,
        'defaults' => [
            ['type' => 'hero_slider'],
            ['type' => 'trust_bar'],
            ['type' => 'service_includes'],
            ['type' => 'process_steps'],
            ['type' => 'pricing_table'],
            ['type' => 'cta_block'],
        ],
    ],

    // ========================================================================
    // SERVICE PAGES (County-Specific)
    // ========================================================================
    'service_county' => [
        'allowed' => [
            'hero',
            'trust_bar',
            'rich_text',
            'local_context',
            'service_includes',
            'pricing_table',
            'timeline_block',
            'testimonials',
            'town_list',
            'cta_block',
            'before_after',
            'faq_list',
        ],
        'required' => ['hero', 'town_list'],
        'min' => 2,
        'max' => 15,
        'defaults' => [
            ['type' => 'hero'],
            ['type' => 'trust_bar'],
            ['type' => 'local_context'],
            ['type' => 'town_list'],
            ['type' => 'cta_block'],
        ],
    ],

    // ========================================================================
    // SERVICE PAGES (Town-Specific)
    // ========================================================================
    'service_town' => [
        'allowed' => [
            'hero',
            'trust_bar',
            'rich_text',
            'local_context',
            'service_includes',
            'before_after',
            'testimonials',
            'pricing_table',
            'timeline_block',
            'cta_block',
            'faq_list',
        ],
        'required' => ['hero'],
        'min' => 2,
        'max' => 12,
        'defaults' => [
            ['type' => 'hero'],
            ['type' => 'trust_bar'],
            ['type' => 'local_context'],
            ['type' => 'service_includes'],
            ['type' => 'cta_block'],
        ],
    ],

    // ========================================================================
    // COUNTY HUB PAGES
    // ========================================================================
    'county_hub' => [
        'allowed' => [
            'hero',
            'rich_text',
            'trust_bar',
            'services_grid',
            'town_list',
            'testimonials',
            'cta_block',
            'local_context',
            'before_after',
        ],
        'required' => ['hero', 'town_list'],
        'min' => 2,
        'max' => 15,
        'defaults' => [
            ['type' => 'hero'],
            ['type' => 'trust_bar'],
            ['type' => 'local_context'],
            ['type' => 'services_grid'],
            ['type' => 'town_list'],
            ['type' => 'cta_block'],
        ],
    ],

    // ========================================================================
    // OFFICE PAGE (Orange, CT)
    // ========================================================================
    'office' => [
        'allowed' => [
            'hero',
            'rich_text',
            'map_embed',
            'trust_bar',
            'testimonials',
            'cta_block',
            'image_gallery',
        ],
        'required' => ['hero', 'map_embed'],
        'min' => 2,
        'max' => 8,
        'defaults' => [
            ['type' => 'hero'],
            ['type' => 'rich_text'],
            ['type' => 'map_embed'],
            ['type' => 'trust_bar'],
            ['type' => 'cta_block'],
        ],
    ],

    // ========================================================================
    // ABOUT PAGE
    // ========================================================================
    'about' => [
        'allowed' => [
            'hero_slider',
            'rich_text',
            'trust_bar',
            'testimonials',
            'cta_block',
            'before_after',
            'logo_strip',
        ],
        'required' => ['hero_slider'],
        'min' => 2,
        'max' => 12,
        'defaults' => [
            ['type' => 'hero_slider'],
            ['type' => 'rich_text'],
            ['type' => 'trust_bar'],
            ['type' => 'cta_block'],
        ],
    ],

    // ========================================================================
    // FAQ PAGE
    // ========================================================================
    'faq' => [
        'allowed' => [
            'hero',
            'faq_list',
            'cta_block',
            'rich_text',
            'trust_bar',
        ],
        'required' => ['hero', 'faq_list'],
        'min' => 2,
        'max' => 5,
        'defaults' => [
            ['type' => 'hero'],
            ['type' => 'faq_list'],
            ['type' => 'cta_block'],
        ],
    ],

    // ========================================================================
    // CASE STUDY / PROJECT PAGES
    // ========================================================================
    'case_study' => [
        'allowed' => [
            'case_study_header',
            'case_study_meta',
            'case_study_body',
            'case_study_gallery',
            'before_after',
            'testimonials',
            'cta_block',
            'rich_text',
        ],
        'required' => ['case_study_header', 'case_study_body'],
        'min' => 2,
        'max' => 10,
        'defaults' => [
            ['type' => 'case_study_header'],
            ['type' => 'case_study_meta'],
            ['type' => 'case_study_body'],
            ['type' => 'case_study_gallery'],
            ['type' => 'cta_block'],
        ],
    ],

    // ========================================================================
    // FREE CONSULTATION FORM PAGE
    // ========================================================================
    'consultation' => [
        'allowed' => [
            'hero',
            'lead_form',
            'trust_bar',
            'process_steps',
            'faq_list',
            'testimonials',
            'cta_block',
            'rich_text',
        ],
        'required' => ['hero', 'lead_form'],
        'min' => 2,
        'max' => 8,
        'defaults' => [
            ['type' => 'hero'],
            ['type' => 'lead_form'],
            ['type' => 'process_steps'],
            ['type' => 'faq_list'],
        ],
    ],

];
