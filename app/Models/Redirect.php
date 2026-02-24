<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Support\Paths\RedirectPathNormalizer;

class Redirect extends Model
{
    public const MAX_REDIRECT_DEPTH = 10;

    protected $fillable = [
        'from_path',
        'to_path',
        'type',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public static function wouldCreateLoop(string $fromPath, string $toPath, ?int $ignoreId = null): bool
    {
        $normalizedFrom = RedirectPathNormalizer::from($fromPath);
        $normalizedTo = RedirectPathNormalizer::to($toPath);

        if ($normalizedFrom === $normalizedTo) {
            return true;
        }

        if (preg_match('#^https?://#i', $normalizedTo)) {
            return false;
        }

        $reverseExists = self::query()
            ->where('from_path', $normalizedTo)
            ->where('to_path', $normalizedFrom)
            ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
            ->exists();

        if ($reverseExists) {
            return true;
        }

        $visited = [
            $normalizedFrom => true,
        ];

        $current = RedirectPathNormalizer::from($normalizedTo);

        for ($depth = 0; $depth < self::MAX_REDIRECT_DEPTH; $depth++) {
            if (isset($visited[$current])) {
                return true;
            }

            $visited[$current] = true;

            $redirect = self::query()
                ->where('from_path', $current)
                ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
                ->first(['to_path']);

            if (!$redirect) {
                return false;
            }

            if (preg_match('#^https?://#i', $redirect->to_path)) {
                return false;
            }

            $current = RedirectPathNormalizer::from($redirect->to_path);
        }

        return true;
    }
}
