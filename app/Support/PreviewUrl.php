<?php

namespace App\Support;

use App\Models\Page;
use Illuminate\Support\Facades\URL;

class PreviewUrl
{
    public static function forPage(Page $page): string
    {
        $signedApiUrl = URL::signedRoute('api.pages.preview', [
            'path' => ltrim($page->full_path, '/'),
        ]);

        return config('app.frontend_url')
            . '/preview'
            . '?api=' . urlencode($signedApiUrl);
    }
}
