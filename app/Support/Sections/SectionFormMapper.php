<?php

namespace App\Support\Sections;

class SectionFormMapper
{
    public static function fieldType(string $rule): string
    {
        if (str_contains($rule, 'boolean')) return 'toggle';
        if (str_contains($rule, 'numeric')) return 'number';
        if (str_contains($rule, 'integer')) return 'number';
        if (str_contains($rule, 'email')) return 'email';
        if (str_contains($rule, 'in:')) return 'select';
        if (str_contains($rule, 'array')) return 'repeater';
        if (str_contains($rule, 'string')) return 'text';

        return 'text';
    }

    public static function isRepeater(string $key): bool
    {
        return str_contains($key, '.*.');
    }
}
