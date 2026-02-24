<?php

namespace App\Services;

use App\Models\Page;

class FooterTemplateResolver
{
    private const ORANGE_PATH = '/new-haven-county/orange-ct/';

    private const COUNTY_MAP = [
        'fairfield-county'  => 'B',
        'new-haven-county'  => 'C',
    ];

    private const OFFICE_ADDRESS = [
        'street'  => '206A Boston Post Road',
        'city'    => 'Orange',
        'state'   => 'CT',
        'zip'     => '06477',
        'country' => 'US',
    ];

    private const OFFICE_HOURS = [
        'Mon–Fri' => '8:00 AM – 5:00 PM',
        'Sat'     => '8:00 AM – 3:00 PM',
        'Sun'     => 'Closed',
    ];

    /**
     * Resolve the footer template and optional payload for a page.
     *
     * @return array{template: string, address?: array, hours?: array}
     */
    public static function resolve(Page $page): array
    {
        // D: Orange office page only
        if (self::isOrangePage($page)) {
            $phones = PhoneResolver::resolve($page);

            return [
                'template' => 'D',
                'address'  => self::OFFICE_ADDRESS,
                'hours'    => self::OFFICE_HOURS,
                'phones'   => $phones['items'],
            ];
        }

        // B or C: county-specific pages
        if ($page->county_id) {
            $slug = $page->county?->slug;

            if ($slug && isset(self::COUNTY_MAP[$slug])) {
                return ['template' => self::COUNTY_MAP[$slug]];
            }
        }

        // A: global pages (no county)
        return ['template' => 'A'];
    }

    private static function isOrangePage(Page $page): bool
    {
        $path = rtrim($page->full_path ?? '', '/') . '/';

        return $path === self::ORANGE_PATH;
    }
}
