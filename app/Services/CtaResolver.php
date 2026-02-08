<?php

namespace App\Services;

use App\Models\Page;

class CtaResolver
{
    private const LOCKED_SUBTEXT = 'On-site or remote (Google Meet or Zoom)';

    private const CTA_FIELDS = [
        'hero'        => ['cta_primary', 'cta_secondary'],
        'hero_slider' => ['cta_primary', 'cta_secondary'],
        'cta_block'   => ['button'],
    ];

    /**
     * Process all section data for a page, injecting correct phone tel: links
     * and enforcing locked CTA copy.
     */
    public static function resolve(Page $page, array $sectionData, string $sectionType): array
    {
        $fields = self::CTA_FIELDS[$sectionType] ?? [];

        if (empty($fields)) {
            return $sectionData;
        }

        $telLink = self::buildTelLink($page);

        foreach ($fields as $field) {
            if (!isset($sectionData[$field]) || !is_array($sectionData[$field])) {
                continue;
            }

            $url = $sectionData[$field]['url'] ?? '';

            // Inject phone into any tel: URL (empty tel:, wrong tel:, or bare tel:)
            if ($url === 'tel:' || str_starts_with($url, 'tel:')) {
                $sectionData[$field]['url'] = $telLink;
            }
        }

        // Enforce locked subtext on cta_block
        if ($sectionType === 'cta_block') {
            $sectionData['subtext'] = self::LOCKED_SUBTEXT;
        }

        return $sectionData;
    }

    /**
     * Build a tel:+1XXXXXXXXXX link from the page's resolved phone.
     */
    private static function buildTelLink(Page $page): string
    {
        $phones = PhoneResolver::resolve($page);
        $number = $phones['items'][0]['number'];

        // Strip formatting: "(203) 919-9616" → "2039199616"
        $digits = preg_replace('/\D/', '', $number);

        return 'tel:+1' . $digits;
    }
}
