<?php

namespace App\Support\Sections;

use Illuminate\Support\Arr;

class SectionRegistry
{
    public static function all(): array
    {
        return config('sections', []);
    }

    public static function get(string $type): array
    {
        return config("sections.$type") ?? [];
    }

    public static function exists(string $type): bool
    {
        return config()->has("sections.$type");
    }

    public static function defaultsFor(string $type): array
    {
        return Arr::get(self::get($type), 'defaults', []);
    }

    public static function rulesFor(string $type): array
    {
        return Arr::get(self::get($type), 'schema', []);
    }

    public static function labelFor(string $type): string
    {
        return (string) Arr::get(self::get($type), 'label', $type);
    }
}
