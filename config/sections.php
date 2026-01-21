<?php

return [

    /*
    |--------------------------------------------------------------------------
    | hero_slider
    |--------------------------------------------------------------------------
    | Frontend očekuje:
    | {
    |   headline: string,
    |   subheadline: string|null,
    |   cta_primary: { label, url },
    |   cta_secondary: { label, url } | null,
    |   images: string[]
    | }
    */
    'hero_slider' => [
        'label' => 'Hero Slider',
        'schema' => [
            'headline' => 'required|string|max:140',
            'subheadline' => 'nullable|string|max:255',

            'cta_primary' => 'required|array',
            'cta_primary.label' => 'required|string|max:60',
            'cta_primary.url' => 'required|string|max:255',

            'cta_secondary' => 'nullable|array',
            'cta_secondary.label' => 'nullable|string|max:60',
            'cta_secondary.url' => 'nullable|string|max:255',

            'images' => 'required|array|min:1',
            'images.*' => 'required|string|max:255',
        ],
        'defaults' => [
            'headline' => '',
            'subheadline' => null,
            'cta_primary' => [
                'label' => '',
                'url' => '',
            ],
            'cta_secondary' => [
                'label' => '',
                'url' => '',
            ],
            'images' => [],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | trust_bar
    |--------------------------------------------------------------------------
    | Frontend očekuje:
    | {
    |   items: [{ icon: string, label: string }]
    | }
    */
    'trust_bar' => [
        'label' => 'Trust Bar',
        'schema' => [
            'items' => 'required|array|min:1|max:6',
            'items.*.icon' => 'required|string|max:40',
            'items.*.label' => 'required|string|max:140',
        ],
        'defaults' => [
            'items' => [
                ['icon' => 'shield', 'label' => ''],
                ['icon' => 'star', 'label' => ''],
                ['icon' => 'clock', 'label' => ''],
                ['icon' => 'map-pin', 'label' => ''],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | services_section
    |--------------------------------------------------------------------------
    | Frontend očekuje:
    | {
    |   title: string,
    |   subtitle: string|null,
    |   cta: { label, url }|null,
    |   items: [{ title, description, image }]
    | }
    */
    'services_section' => [
        'label' => 'Services Section',
        'schema' => [
            'title' => 'required|string|max:120',
            'subtitle' => 'nullable|string|max:255',

            'cta' => 'nullable|array',
            'cta.label' => 'nullable|string|max:60',
            'cta.url' => 'nullable|string|max:255',

            'items' => 'required|array|min:1',
            'items.*.title' => 'required|string|max:120',
            'items.*.description' => 'required|string|max:500',
            'items.*.image' => 'required|string|max:255', // final path/url, npr /images/services/kitchen.jpg
        ],
        'defaults' => [
            'title' => '',
            'subtitle' => null,
            'cta' => [
                'label' => '',
                'url' => '',
            ],
            'items' => [],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | rich_text
    |--------------------------------------------------------------------------
    | Minimalno (kao kod tebe): plain text content string.
    */
    'rich_text' => [
        'label' => 'Rich Text',
        'schema' => [
            'title' => 'nullable|string|max:140',
            'content' => 'required|string',
        ],
        'defaults' => [
            'title' => null,
            'content' => '',
        ],
    ],

];
