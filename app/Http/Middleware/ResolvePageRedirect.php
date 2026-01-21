<?php

namespace App\Http\Middleware;

use App\Models\Redirect;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolvePageRedirect
{
    public function handle(Request $request, Closure $next): Response
    {
        // parametar iz rute: /api/pages/{path}
        $path = (string) $request->route('path', '');

        $from = $this->normalizePath($path);

        // "/" obično ne redirectuješ iz API-a
        if ($from === '/') {
            return $next($request);
        }

        $redirect = Redirect::query()
            ->select(['id', 'to_path', 'status_code', 'is_active'])
            ->where('from_path', $from)
            ->where('is_active', true)
            ->first();

        if (!$redirect) {
            return $next($request);
        }

        $to = $this->normalizePath((string) $redirect->to_path);

        // loop protection
        if ($to === $from) {
            return $next($request);
        }

        // hits (opciono)
        $redirect->increment('hits');

        // Redirect na API rutu koja vraća JSON (frontend fetch prati redirect)
        return redirect()->route(
            'api.pages.show',
            ['path' => ltrim($to, '/')],
            (int) $redirect->status_code
        );
    }

    private function normalizePath(string $path): string
    {
        $path = trim($path);

        // pazi: ako je prazan path, tretiraj kao "/"
        if ($path === '') {
            return '/';
        }

        $path = '/' . ltrim($path, '/');

        // "/" ostaje "/", ostalo skini trailing slash
        return $path === '/' ? '/' : rtrim($path, '/');
    }
}
