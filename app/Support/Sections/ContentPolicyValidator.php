<?php

namespace App\Support\Sections;

use App\Models\Page;

/**
 * BuiltWell v3.3 Content Policy Validator
 *
 * Enforces LOCKED content rules at save-time:
 * 1. Owner name restricted to /about/ only
 * 2. Forbidden terminology blocked globally
 * 3. Hub-and-Spoke link direction enforced
 */
class ContentPolicyValidator
{
    /**
     * Forbidden terms — blocked on ALL pages, no exceptions.
     */
    private const FORBIDDEN_TERMS = [
        'Stamford Service Area Team',
    ];

    /**
     * Link direction rules: source template → allowed link path patterns.
     * A link from `service_global` to a service×town path is BLOCKED.
     */
    private const BLOCKED_LINK_DIRECTIONS = [
        // Global Service → Service×Town is FORBIDDEN (prevents doorway clustering)
        'service_global' => [
            // Matches: /county-slug/service-slug/town-slug/
            '#^/[a-z0-9-]+-county/[a-z0-9-]+/[a-z0-9-]+-ct/?$#',
        ],
    ];

    /**
     * Validate all section data for a page against content policy rules.
     *
     * @param Page $page The page being saved
     * @param array $sections Array of section arrays [{type, data, is_active}, ...]
     * @return array Error messages (empty if valid)
     */
    public static function validate(Page $page, array $sections): array
    {
        $errors = [];
        $pagePath = $page->full_path ?? '';
        $template = $page->template_key ?? '';

        foreach ($sections as $index => $section) {
            if (!($section['is_active'] ?? true)) {
                continue;
            }

            $data = $section['data'] ?? [];
            $sectionNum = $index + 1;
            $textContent = self::extractText($data);

            // 1. Owner name check
            $ownerErrors = self::validateOwnerName($pagePath, $textContent, $sectionNum);
            array_push($errors, ...$ownerErrors);

            // 2. Forbidden terms check
            $termErrors = self::validateForbiddenTerms($textContent, $sectionNum);
            array_push($errors, ...$termErrors);

            // 3. Link direction check
            $linkErrors = self::validateLinkDirections($template, $data, $sectionNum);
            array_push($errors, ...$linkErrors);
        }

        return $errors;
    }

    /**
     * Owner name must appear ONLY on /about/.
     */
    private static function validateOwnerName(string $pagePath, string $text, int $sectionNum): array
    {
        $ownerName = config('builtwell.owner_name');

        if (!$ownerName || trim($ownerName) === '') {
            return [];
        }

        $isAboutPage = rtrim($pagePath, '/') === '/about';

        if ($isAboutPage) {
            return []; // Owner name is allowed on /about/
        }

        if (stripos($text, $ownerName) !== false) {
            return [
                "Section #{$sectionNum}: Owner name is only allowed on the /about/ page. Please remove it from this content.",
            ];
        }

        return [];
    }

    /**
     * Forbidden terminology blocked globally.
     */
    private static function validateForbiddenTerms(string $text, int $sectionNum): array
    {
        $errors = [];

        foreach (self::FORBIDDEN_TERMS as $term) {
            if (stripos($text, $term) !== false) {
                $errors[] = "Section #{$sectionNum}: The phrase \"{$term}\" is not allowed. Use the correct county team name instead (e.g. \"Fairfield County Service Area Team\").";
            }
        }

        return $errors;
    }

    /**
     * Validate internal link directions per Hub-and-Spoke model.
     *
     * BLOCKED: service_global → service×town paths
     * (e.g. /kitchen-remodeling/ must NOT link to /fairfield-county/kitchen-remodeling/darien-ct/)
     */
    private static function validateLinkDirections(string $template, array $data, int $sectionNum): array
    {
        $blockedPatterns = self::BLOCKED_LINK_DIRECTIONS[$template] ?? [];

        if (empty($blockedPatterns)) {
            return [];
        }

        $links = self::extractLinks($data);
        $errors = [];

        foreach ($links as $link) {
            foreach ($blockedPatterns as $pattern) {
                if (preg_match($pattern, $link)) {
                    $errors[] = "Section #{$sectionNum}: Link \"{$link}\" violates Hub-and-Spoke rules. Global service pages must not link down to Service×Town pages (causes doorway clustering).";
                }
            }
        }

        return $errors;
    }

    /**
     * Recursively extract all string values from section data into one text blob.
     */
    private static function extractText(array $data): string
    {
        $parts = [];

        array_walk_recursive($data, function ($value) use (&$parts) {
            if (is_string($value)) {
                $parts[] = $value;
            }
        });

        return implode(' ', $parts);
    }

    /**
     * Recursively extract all URL/link values from section data.
     * Looks for keys named 'url', 'href', or 'link'.
     */
    private static function extractLinks(array $data, string $parentKey = ''): array
    {
        $links = [];

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                array_push($links, ...self::extractLinks($value, (string) $key));
            } elseif (is_string($value) && in_array($key, ['url', 'href', 'link'], true)) {
                $cleaned = trim($value);
                // Only validate internal links (starting with /)
                if ($cleaned !== '' && str_starts_with($cleaned, '/')) {
                    $links[] = $cleaned;
                }
            }
        }

        return $links;
    }
}
