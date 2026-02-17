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
            'rich_text_image',
            'services_grid',
            'stats_counter',
            'feature_list_two_column',
            'process_steps',
            'testimonials',
            'project_highlights',
            'before_after',
            'faq_list',
            'areas_served',
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
            ['type' => 'rich_text'],
            ['type' => 'services_grid'],
            ['type' => 'stats_counter'],
            ['type' => 'rich_text_image'],
            ['type' => 'feature_list_two_column'],
            ['type' => 'faq_list'],
            ['type' => 'areas_served'],
            ['type' => 'process_steps'],
            ['type' => 'testimonials'],
            ['type' => 'project_highlights'],
            ['type' => 'lead_form'],
        ],
    ],

    // ========================================================================
    // SERVICE PAGES (Global)
    // ========================================================================
    'service_global' => [
        'allowed' => [
            'service_hero',
            'service_intro_split',
            'service_process',
            'full_width_text_dark',
            'before_after_grid',
            'testimonial_slider_small',
            'logo_strip',
            'service_area_text',
            'faq_accordion',
            'cta_split_form',
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
        'required' => ['service_hero'],
        'min' => 3,
        'max' => 25,
        'defaults' => [
            ['type' => 'service_hero'],
            ['type' => 'service_intro_split'],
            ['type' => 'service_process'],
            ['type' => 'full_width_text_dark'],
            ['type' => 'before_after_grid'],
            ['type' => 'testimonial_slider_small'],
            ['type' => 'logo_strip'],
            ['type' => 'service_area_text'],
            ['type' => 'faq_accordion'],
            ['type' => 'cta_split_form'],
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
            'hero_service_location',
            'trust_bar',
            'rich_text',
            'local_context',
            'service_two_column',
            'service_includes',
            'service_process',
            'process_steps',
            'before_after',
            'before_after_showcase',
            'testimonials',
            'pricing_table',
            'timeline_block',
            'service_area_highlight',
            'consultation_cta_split',
            'cta_block',
            'cta_split_form',
            'faq_list',
        ],
        'required' => [],
        'min' => 2,
        'max' => 15,
        'defaults' => [
            ['type' => 'hero_service_location'],
            ['type' => 'trust_bar'],
            ['type' => 'service_two_column'],
            ['type' => 'process_steps'],
            ['type' => 'consultation_cta_split'],
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
            'page_hero',
            'image_text_split',
            'icon_cards',
            'team_members',
            'two_column_text',
            'service_areas',
            'office_info',
            'feature_grid',
            'dark_text_section',
            'cta_split_form',
            'rich_text',
            'trust_bar',
            'testimonials',
            'cta_block',
        ],
        'required' => ['page_hero'],
        'min' => 2,
        'max' => 15,
        'defaults' => [
            ['type' => 'page_hero'],
            ['type' => 'image_text_split'],
            ['type' => 'icon_cards'],
            ['type' => 'team_members'],
            ['type' => 'two_column_text'],
            ['type' => 'service_areas'],
            ['type' => 'office_info'],
            ['type' => 'feature_grid'],
            ['type' => 'dark_text_section'],
            ['type' => 'cta_split_form'],
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
