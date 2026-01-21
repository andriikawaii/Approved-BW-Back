<?php

return [

    'hero' => [
        'label' => 'Hero',
        'schema' => [
            'headline' => 'required|string|max:120',
            'subheadline' => 'nullable|string|max:255',
            'media_asset_id' => 'integer|nullable|exists:media,id',
            'cta_label' => 'nullable|string|max:50',
            'cta_url' => 'nullable|string|max:255',
        ],
        'defaults' => [
            'headline' => '',
            'subheadline' => null,
            'media_asset_id' => null,
            'cta_label' => null,
            'cta_url' => null,
        ],
    ],

    'rich_text' => [
        'label' => 'Rich Text',
        'schema' => [
            'content' => 'required|string',
        ],
        'defaults' => [
            'content' => '',
        ],
    ],

    'cta' => [
        'label' => 'Call To Action',
        'schema' => [
            'title' => 'required|string|max:120',
            'button_label' => 'required|string|max:50',
            'button_url' => 'required|string|max:255',
        ],
        'defaults' => [
            'title' => '',
            'button_label' => '',
            'button_url' => '',
        ],
    ],

];
