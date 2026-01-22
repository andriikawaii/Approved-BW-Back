<?php

namespace App\Support\Paths;

class RedirectPathNormalizer
{
    public static function from(string $path): string
    {
        $path = trim($path);
        $path = '/' . ltrim($path, '/');

        return $path === '/' ? '/' : rtrim($path, '/');
    }

    public static function to(string $to): string
    {
        $to = trim($to);

        if (preg_match('#^https?://#i', $to)) {
            return rtrim($to, '/');
        }

        $to = '/' . ltrim($to, '/');

        return $to === '/' ? '/' : rtrim($to, '/');
    }
}
