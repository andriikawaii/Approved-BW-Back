<?php

return [
    'storage' => [
        'disk' => 'local',
    ],

    'features' => [
        'lazy_loading' => true,
    ],

    'inject_assets' => true,

    'navigate' => [
        'enabled' => true,
        'show_progress_bar' => true,
    ],

    'temporary_file_upload' => [
        'disk' => 'local',
        'directory' => 'livewire-tmp',
        'rules' => 'required|file|max:102400', // 100MB
        'stream' => [
            'disk' => 'local',
            'directory' => 'livewire-tmp',
        ],
    ],

    'auto_inject_morph_assets' => true,

    'javascript_path' => '/livewire/livewire.js',

    'asset_url' => null,

    'app_url' => null,

    'asset_tag' => 'livewire:scripts',

    'layout' => 'components.layouts.app',

    'middleware' => [
        'web',
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\VerifyCsrfToken::class,
        \Illuminate\Session\Middleware\AuthenticateSession::class,
    ],
];
