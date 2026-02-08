<?php

namespace App\Services;

use App\Models\Page;

class PhoneResolver
{
    private const PHONES = [
        'fairfield-county' => ['label' => 'Fairfield County', 'number' => '(203) 919-9616'],
        'new-haven-county' => ['label' => 'New Haven County', 'number' => '(203) 466-9148'],
    ];

    public static function resolve(Page $page): array
    {
        if ($page->county_id) {
            $slug = $page->county?->slug;

            if ($slug && isset(self::PHONES[$slug])) {
                return [
                    'mode' => 'single',
                    'items' => [self::PHONES[$slug]],
                ];
            }
        }

        return [
            'mode' => 'both',
            'items' => array_values(self::PHONES),
        ];
    }
}
