<?php

namespace App\Http\Controllers\Admin\Pages;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class PagePreviewController extends Controller
{
    public function __invoke(Request $request, Page $page)
    {
        // Signed URL ka API preview endpointu (bez cache, može i draft)
        $signedApiUrl = URL::temporarySignedRoute(
            'api.pages.preview',
            now()->addMinutes(10),
            [
                'path' => ltrim($page->full_path, '/'),
            ]
        );

        // Ako imaš frontend app (Next/React) preview stranicu:
        // npr FRONTEND_URL=http://localhost:3000
        $frontend = rtrim(config('app.frontend_url', 'http://localhost:3000'), '/');

        // Primer: frontend route /preview prima api_url i povuče DTO
        $url = $frontend . '/preview?api_url=' . urlencode($signedApiUrl);

        return redirect()->away($url);

        // Alternativa: ako hoćeš blade preview sa iframe-om (vidi ispod)
        // return view('admin.pages.preview', compact('page', 'signedApiUrl', 'frontend'));
    }
}
