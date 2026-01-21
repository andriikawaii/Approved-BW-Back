<?php

return [

    'home' => [
        'allowed' => ['hero_slider', 'trust_bar', 'services_section', 'rich_text'],
        'required' => ['hero_slider'],
        'min' => 1,
        'max' => 20,
        'defaults' => [
            ['type' => 'hero_slider'],
            ['type' => 'trust_bar'],
            ['type' => 'services_section'],
        ],
    ],

    'service_global' => [
        'allowed' => ['hero_slider', 'trust_bar', 'services_section', 'rich_text'],
        'required' => ['hero_slider'],
        'defaults' => [
            ['type' => 'hero_slider'],
            ['type' => 'trust_bar'],
            ['type' => 'services_section'],
            ['type' => 'rich_text'],
        ],
    ],

    'about' => [
        'allowed' => ['hero_slider', 'rich_text', 'trust_bar'],
        'required' => ['hero_slider'],
        'defaults' => [
            ['type' => 'hero_slider'],
            ['type' => 'rich_text'],
        ],
    ],

];
