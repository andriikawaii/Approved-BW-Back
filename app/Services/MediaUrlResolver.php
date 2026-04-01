<?php

namespace App\Services;

use App\Models\MediaAsset;
use Illuminate\Support\Facades\Storage;

/**
 * Resolves raw media file paths into absolute public URLs.
 *
 * Single source of truth for all media URL generation.
 * When switching to CDN, only this class needs to change.
 */
class MediaUrlResolver
{
    /**
     * Known image/media field names in section data.
     * Any key matching these names will be resolved to a full URL.
     */
    private const MEDIA_FIELDS = [
        'background_image',
        'background_video',
        'image',
        'image_main',
        'image_secondary',
        'logo',
        'avatar',
        'before_image',
        'after_image',
        'cover_image',
        'office_image',
    ];

    /**
     * Per-request cache: file_path → alt_text from media_assets table.
     * Avoids repeated DB hits for the same image used on multiple sections.
     *
     * @var array<string, string|null>
     */
    private static array $altCache = [];

    /**
     * Convert a single file_path to an absolute public URL.
     */
    public static function url(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        // Already a full URL (e.g. external link)
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        // Many BuiltWell assets are stored directly under public/, not storage/app/public.
        $publicRelativePath = ltrim($path, '/');
        $publicAbsolutePath = public_path($publicRelativePath);

        if (file_exists($publicAbsolutePath)) {
            return rtrim(config('app.url'), '/') . '/' . str_replace('\\', '/', $publicRelativePath);
        }

        $storageUrl = Storage::disk('public')->url($path);

        // Storage::url() may return relative path — ensure absolute
        if (str_starts_with($storageUrl, '/')) {
            return rtrim(config('app.url'), '/') . $storageUrl;
        }

        return $storageUrl;
    }

    /**
     * Walk through section data and resolve all media fields to absolute URLs.
     */
    public static function resolveSection(array|object $data): array
    {
        if (is_object($data)) {
            $data = (array) $data;
        }

        return self::walk($data);
    }

    private static function walk(array $data): array
    {
        foreach ($data as $key => &$value) {
            if (is_array($value)) {
                $value = self::walk($value);
            } elseif (is_string($value) && in_array($key, self::MEDIA_FIELDS, true)) {
                $rawPath = $value;
                $value   = self::url($value);

                // If the sibling _alt field exists but is empty, fall back to
                // the alt_text stored on the media asset itself.
                $altKey = $key . '_alt';
                if (array_key_exists($altKey, $data) && empty($data[$altKey])) {
                    $data[$altKey] = self::mediaAlt($rawPath);
                }
            }
        }

        return $data;
    }

    /**
     * Look up alt_text for a file path from the media_assets table.
     * Results are cached per-request to avoid N+1 queries.
     */
    private static function mediaAlt(string $path): ?string
    {
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return null;
        }

        if (!array_key_exists($path, self::$altCache)) {
            self::$altCache[$path] = MediaAsset::where('file_path', $path)->value('alt_text');
        }

        return self::$altCache[$path] ?: null;
    }
}
