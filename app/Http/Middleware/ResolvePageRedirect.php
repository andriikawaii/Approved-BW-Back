<?php

namespace App\Http\Middleware;

use App\Models\Redirect;
use App\Support\Paths\RedirectPathNormalizer;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class ResolvePageRedirect
{
    private const MAX_REDIRECT_DEPTH = 10;

    public function handle(Request $request, Closure $next): Response
    {
        if (!Schema::hasTable('redirects')) {
            return $next($request);
        }

        // API route: /api/pages/{path}
        $path = (string) $request->route('path', '');

        $from = RedirectPathNormalizer::from($path);

        // "/" nikad ne redirectuj iz CMS API-ja
        if ($from === '/') {
            return $next($request);
        }

        [$redirect, $normalizedTo] = $this->resolveRedirectChain($from);

        if (!$redirect) {
            return $next($request);
        }

        // 🔢 hits
        try {
            $redirect->increment('hits');
        } catch (\Throwable) {
            // Column may not exist yet
        }

        // 🌍 full URL redirect
        if (preg_match('#^https?://#i', $redirect->to_path)) {
            return redirect()->away($redirect->to_path, (int) ($redirect->type ?? 301));
        }

        // Internal path: return JSON with redirect target so frontend can handle it
        $statusCode = (int) ($redirect->type ?? 301);
        $targetPath = '/' . ltrim($normalizedTo, '/');

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'redirect' => true,
                'status'   => $statusCode,
                'location' => $targetPath,
            ], $statusCode, ['Location' => $targetPath]);
        }

        // Non-API request: redirect to the frontend path directly
        $frontendUrl = rtrim(config('app.frontend_url', config('app.url')), '/');

        return redirect()->away(
            $frontendUrl . $targetPath,
            $statusCode,
        );
    }

    private function resolveRedirectChain(string $from): array
    {
        $visited = [$from => true];
        $current = $from;
        $firstRedirect = null;
        $firstNormalizedTo = null;

        for ($depth = 0; $depth < self::MAX_REDIRECT_DEPTH; $depth++) {
            $redirect = Redirect::query()
                ->where('from_path', $current)
                ->where('is_active', true)
                ->first(['id', 'to_path', 'type']);

            if (!$redirect) {
                break;
            }

            if (!$firstRedirect) {
                $firstRedirect = $redirect;
                if (!preg_match('#^https?://#i', $redirect->to_path)) {
                    $firstNormalizedTo = RedirectPathNormalizer::from($redirect->to_path);
                }
            }

            if (preg_match('#^https?://#i', $redirect->to_path)) {
                break;
            }

            $normalizedTo = RedirectPathNormalizer::from($redirect->to_path);

            if (isset($visited[$normalizedTo])) {
                return [null, null];
            }

            $visited[$normalizedTo] = true;
            $current = $normalizedTo;
        }

        if (!$firstRedirect) {
            return [null, null];
        }

        if (count($visited) > self::MAX_REDIRECT_DEPTH) {
            return [null, null];
        }

        return [$firstRedirect, $firstNormalizedTo];
    }
}
