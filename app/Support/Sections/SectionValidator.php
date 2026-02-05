<?php

namespace App\Support\Sections;

/**
 * Section Data Validator
 *
 * Enforces BuiltWell v3.3 LOCKED rules:
 * - CTA button text must be one of approved labels
 * - CTA subtext must match exact copy
 *
 * Usage:
 *   $errors = SectionValidator::validate($section['type'], $section['data']);
 *   if (!empty($errors)) { ... }
 */
class SectionValidator
{
    /**
     * Allowed CTA button labels (LOCKED per spec v3.3)
     */
    const ALLOWED_CTA_LABELS = [
        'Schedule a Free Consultation',
        'Get a Free Estimate',
    ];

    /**
     * Required CTA subtext (LOCKED per spec v3.3)
     */
    const LOCKED_CTA_SUBTEXT = 'On-site or remote (Google Meet or Zoom)';

    /**
     * Section types that have CTA fields requiring validation
     */
    const SECTIONS_WITH_CTA = [
        'hero',
        'hero_slider',
        'cta_block',
    ];

    /**
     * Validate section data against BuiltWell LOCKED rules
     *
     * @param string $type Section type
     * @param array $data Section data
     * @return array Array of error messages (empty if valid)
     */
    public static function validate(string $type, array $data): array
    {
        $errors = [];

        // Only validate sections that have CTA fields
        if (!in_array($type, self::SECTIONS_WITH_CTA, true)) {
            return $errors;
        }

        // Validate CTA primary label
        if (isset($data['cta_primary']['label'])) {
            $label = trim($data['cta_primary']['label']);

            if ($label !== '' && !in_array($label, self::ALLOWED_CTA_LABELS, true)) {
                $errors[] = sprintf(
                    'CTA button text must be one of: "%s" or "%s". Found: "%s"',
                    self::ALLOWED_CTA_LABELS[0],
                    self::ALLOWED_CTA_LABELS[1],
                    $label
                );
            }
        }

        // Validate CTA subtext (only for cta_block)
        if ($type === 'cta_block' && isset($data['subtext'])) {
            $subtext = trim($data['subtext']);

            if ($subtext !== '' && $subtext !== self::LOCKED_CTA_SUBTEXT) {
                $errors[] = sprintf(
                    'CTA subtext must be exactly: "%s". Found: "%s"',
                    self::LOCKED_CTA_SUBTEXT,
                    $subtext
                );
            }
        }

        // Validate button field in cta_block
        if ($type === 'cta_block' && isset($data['button']['label'])) {
            $label = trim($data['button']['label']);

            if ($label !== '' && !in_array($label, self::ALLOWED_CTA_LABELS, true)) {
                $errors[] = sprintf(
                    'CTA button text must be one of: "%s" or "%s". Found: "%s"',
                    self::ALLOWED_CTA_LABELS[0],
                    self::ALLOWED_CTA_LABELS[1],
                    $label
                );
            }
        }

        return $errors;
    }

    /**
     * Get human-readable allowed CTA labels
     *
     * @return string Formatted list of allowed labels
     */
    public static function getAllowedCtaLabelsFormatted(): string
    {
        return '"' . implode('" or "', self::ALLOWED_CTA_LABELS) . '"';
    }

    /**
     * Get locked CTA subtext
     *
     * @return string The required subtext
     */
    public static function getLockedCtaSubtext(): string
    {
        return self::LOCKED_CTA_SUBTEXT;
    }
}
