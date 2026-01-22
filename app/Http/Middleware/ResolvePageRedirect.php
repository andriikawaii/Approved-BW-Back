<?php

namespace App\Http\Middleware;

use App\Models\Redirect;
use App\Support\Paths\RedirectPathNormalizer;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolvePageRedirect
{
    public function handle(Request $request, Closure $next): Response
    {
        // API route: /api/pages/{path}
        $path = (string) $request->route('path', '');

        $from = RedirectPathNormalizer::from($path);

        // "/" nikad ne redirectuj iz CMS API-ja
        if ($from === '/') {
            return $next($request);
        }

        $redirect = Redirect::query()
            ->where('from_path', $from)
            ->where('is_active', true)
            ->first(['id', 'to_path', 'status_code']);

        if (!$redirect) {
            return $next($request);
        }

        $to = $redirect->to_path;

        // 🔒 loop protection
        if ($to === $from) {
            return $next($request);
        }

        // 🔢 hits
        $redirect->increment('hits');

        // 🌍 full URL redirect
        if (preg_match('#^https?://#i', $to)) {
            return redirect()->away($to, (int) $redirect->status_code);
        }

        // 🧠 internal path redirect (API-friendly)
        $normalizedTo = RedirectPathNormalizer::from($to);

        return redirect(
            '/api/pages/' . ltrim($normalizedTo, '/'),
            (int) $redirect->status_code
        );
    }
}
