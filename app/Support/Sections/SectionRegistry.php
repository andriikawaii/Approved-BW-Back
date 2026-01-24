<?php

namespace App\Support\Sections;

use Illuminate\Support\Arr;

class SectionRegistry
{
    /**
     * Returns the full registry map: [type => config]
     */
    public static function types(): array
    {
        return config('sections.types', []);
    }

    /**
     * Returns single type config.
     */
    public static function get(string $type): array
    {
        return config("sections.types.$type", []);
    }

    public static function exists(string $type): bool
    {
        return config()->has("sections.types.$type");
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

    public static function descriptionFor(string $type): string
    {
        return (string) Arr::get(self::get($type), 'description', '');
    }
}
