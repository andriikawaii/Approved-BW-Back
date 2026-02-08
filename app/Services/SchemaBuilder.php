<?php

namespace App\Services;

use App\Models\Page;

class SchemaBuilder
{
    private const SITE_URL = 'https://www.builtwellct.com';
    private const BUSINESS_NAME = 'BuiltWell';
    private const HIC_LICENSE = 'CT HIC #0668405';
    private const LOGO_URL = 'https://www.builtwellct.com/images/builtwell-logo.png';
    private const ORANGE_PATH = '/new-haven-county/orange-ct/';

    private const OFFICE_ADDRESS = [
        '@type'           => 'PostalAddress',
        'streetAddress'   => '477 S Orange Center Rd',
        'addressLocality' => 'Orange',
        'addressRegion'   => 'CT',
        'postalCode'      => '06477',
        'addressCountry'  => 'US',
    ];

    private const OPENING_HOURS = [
        'Mo-Fr 08:00-18:00',
        'Sa 09:00-14:00',
    ];

    private const GEO = [
        '@type'     => 'GeoCoordinates',
        'latitude'  => 41.2782,
        'longitude' => -73.0256,
    ];

    private const AREA_SERVED = [
        ['@type' => 'State', 'name' => 'Connecticut'],
    ];

    private const PHONES = [
        'fairfield-county' => '+12039199616',
        'new-haven-county' => '+12034669148',
    ];

    /**
     * Build the complete schema array for a page.
     * Returns an array of JSON-LD objects (multiple schemas per page are valid).
     * BreadcrumbList is always included.
     *
     * @return array<int, array>
     */
    public static function build(Page $page): array
    {
        $schemas = [];

        // Primary schema based on page type
        $primary = self::buildPrimary($page);
        if ($primary) {
            $schemas[] = $primary;
        }

        // BreadcrumbList on ALL pages (already built by BreadcrumbBuilder)
        $schemas[] = BreadcrumbBuilder::build($page)['schema'];

        return $schemas;
    }

    private static function buildPrimary(Page $page): ?array
    {
        $template = $page->template_key;
        $path = rtrim($page->full_path ?? '', '/') . '/';

        // Orange office → HomeAndConstructionBusiness
        if ($path === self::ORANGE_PATH) {
            return self::buildHomeAndConstructionBusiness($page);
        }

        // FAQ page
        if ($template === 'faq') {
            return self::buildFaqPage($page);
        }

        // Service×Town pages → Service schema
        if ($template === 'service_town') {
            return self::buildService($page);
        }

        // Service×County pages → Service schema
        if ($template === 'service_county') {
            return self::buildService($page);
        }

        // Service global → Service schema
        if ($template === 'service_global') {
            return self::buildService($page);
        }

        return null;
    }

    /**
     * HomeAndConstructionBusiness — Orange office page ONLY.
     */
    private static function buildHomeAndConstructionBusiness(Page $page): array
    {
        $base = [
            '@context'    => 'https://schema.org',
            '@type'       => 'HomeAndConstructionBusiness',
            '@id'         => self::SITE_URL . self::ORANGE_PATH . '#business',
            'name'        => self::BUSINESS_NAME,
            'url'         => self::SITE_URL,
            'logo'        => self::LOGO_URL,
            'image'       => self::LOGO_URL,
            'description' => $page->seo_description ?: 'Licensed CT home remodeling contractor serving Fairfield and New Haven counties.',
            'telephone'   => self::PHONES['new-haven-county'],
            'address'     => self::OFFICE_ADDRESS,
            'geo'         => self::GEO,
            'openingHoursSpecification' => self::buildOpeningHoursSpec(),
            'areaServed'  => self::AREA_SERVED,
            'priceRange'  => '$$$$',
            'hasCredential' => [
                '@type'          => 'EducationalOccupationalCredential',
                'credentialCategory' => 'Home Improvement Contractor License',
                'recognizedBy'  => [
                    '@type' => 'GovernmentOrganization',
                    'name'  => 'State of Connecticut',
                ],
                'name' => self::HIC_LICENSE,
            ],
            'sameAs' => [],
        ];

        return self::applyOverrides($base, $page);
    }

    /**
     * FAQPage — /faq/ ONLY.
     */
    private static function buildFaqPage(Page $page): array
    {
        $faqItems = self::extractFaqItems($page);

        $base = [
            '@context'   => 'https://schema.org',
            '@type'      => 'FAQPage',
            'name'       => $page->seo_title ?: 'Frequently Asked Questions',
            'url'        => self::SITE_URL . $page->full_path,
            'mainEntity' => $faqItems,
        ];

        return self::applyOverrides($base, $page);
    }

    /**
     * Service schema — service_global, service_county, service_town.
     */
    private static function buildService(Page $page): array
    {
        $service = $page->service;
        $county  = $page->county;
        $town    = $page->town;

        $serviceName = $service?->name ?? 'Home Remodeling';

        // Build location-aware name
        if ($town) {
            $name = $serviceName . ' in ' . $town->name;
        } elseif ($county) {
            $name = $serviceName . ' in ' . $county->name;
        } else {
            $name = $serviceName . ' in Connecticut';
        }

        $base = [
            '@context'    => 'https://schema.org',
            '@type'       => 'Service',
            'name'        => $name,
            'url'         => self::SITE_URL . $page->full_path,
            'description' => $page->seo_description ?: null,
            'provider'    => [
                '@type'         => 'HomeAndConstructionBusiness',
                '@id'           => self::SITE_URL . self::ORANGE_PATH . '#business',
                'name'          => self::BUSINESS_NAME,
                'url'           => self::SITE_URL,
                'telephone'     => self::resolvePhone($page),
                'hasCredential' => [
                    '@type'              => 'EducationalOccupationalCredential',
                    'credentialCategory' => 'Home Improvement Contractor License',
                    'name'               => self::HIC_LICENSE,
                ],
            ],
            'areaServed' => self::buildAreaServed($page),
            'serviceType' => $serviceName,
        ];

        return self::applyOverrides($base, $page);
    }

    /**
     * Extract FAQ Q&A pairs from the page's faq_list sections.
     */
    private static function extractFaqItems(Page $page): array
    {
        if (!$page->relationLoaded('sections')) {
            return [];
        }

        $items = [];

        foreach ($page->sections as $section) {
            if ($section->type !== 'faq_list' || !$section->is_active) {
                continue;
            }

            $data = $section->data ?? [];

            foreach ($data['items'] ?? [] as $faq) {
                $question = $faq['question'] ?? null;
                $answer   = $faq['answer'] ?? null;

                if ($question && $answer) {
                    $items[] = [
                        '@type' => 'Question',
                        'name'  => $question,
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text'  => strip_tags($answer),
                        ],
                    ];
                }
            }
        }

        return $items;
    }

    private static function resolvePhone(Page $page): string
    {
        $slug = $page->county?->slug;

        return self::PHONES[$slug] ?? self::PHONES['fairfield-county'];
    }

    private static function buildAreaServed(Page $page): array
    {
        $town   = $page->town;
        $county = $page->county;

        if ($town && $county) {
            return [
                [
                    '@type'              => 'City',
                    'name'               => $town->name,
                    'containedInPlace'   => [
                        '@type' => 'AdministrativeArea',
                        'name'  => $county->name . ', CT',
                    ],
                ],
            ];
        }

        if ($county) {
            return [
                [
                    '@type' => 'AdministrativeArea',
                    'name'  => $county->name . ', CT',
                ],
            ];
        }

        return self::AREA_SERVED;
    }

    private static function buildOpeningHoursSpec(): array
    {
        return [
            [
                '@type'     => 'OpeningHoursSpecification',
                'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
                'opens'     => '08:00',
                'closes'    => '18:00',
            ],
            [
                '@type'     => 'OpeningHoursSpecification',
                'dayOfWeek' => ['Saturday'],
                'opens'     => '09:00',
                'closes'    => '14:00',
            ],
        ];
    }

    /**
     * Merge schema_overrides from the page (admin edits) on top of the generated base.
     */
    private static function applyOverrides(array $base, Page $page): array
    {
        $overrides = is_array($page->schema_overrides) ? $page->schema_overrides : [];

        if (empty($overrides)) {
            return $base;
        }

        return array_replace_recursive($base, $overrides);
    }
}
