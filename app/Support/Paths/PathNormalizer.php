<?php

namespace App\Support\Paths;

use Illuminate\Support\Str;

class PathNormalizer
{
    public static function normalize(string $path): string
    {
        $path = trim($path);

        if ($path === '' || $path === '/') {
            return '/';
        }

        $segments = array_filter(explode('/', trim($path, '/')));

        $segments = array_map(
            fn ($seg) => Str::slug($seg),
            $segments
        );

        return '/' . implode('/', $segments);
    }

}
