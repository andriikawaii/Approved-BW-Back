<?php

return [

    'home' => [
        'allowed' => ['hero', 'rich_text', 'cta'],
        'required' => ['hero'],
        'min' => 1,
        'max' => 10,
        'defaults' => [
            ['type' => 'hero'],
            ['type' => 'rich_text'],
        ],
    ],

    'service_global' => [
        'allowed' => ['hero', 'rich_text', 'cta'],
        'required' => ['hero'],
        'defaults' => [
            ['type' => 'hero'],
            ['type' => 'rich_text'],
        ],
    ],

    'about' => [
        'allowed' => ['hero', 'rich_text'],
        'required' => ['hero'],
        'defaults' => [
            ['type' => 'hero'],
            ['type' => 'rich_text'],
        ],
    ],

];
