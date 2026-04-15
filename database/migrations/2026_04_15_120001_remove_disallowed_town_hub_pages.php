<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const DISALLOWED_PAGE_PATHS = [
        '/fairfield-county/darien-ct',
        '/fairfield-county/new-canaan-ct',
        '/fairfield-county/stamford-ct',
        '/fairfield-county/norwalk-ct',
        '/fairfield-county/fairfield-ct',
        '/fairfield-county/ridgefield-ct',
        '/new-haven-county/hamden-ct',
        '/new-haven-county/branford-ct',
        '/new-haven-county/guilford-ct',
        '/new-haven-county/woodbridge-ct',
        '/new-haven-county/milford-ct',
    ];

    public function up(): void
    {
        DB::transaction(function (): void {
            $pagePathsById = DB::table('pages')->pluck('full_path', 'id')->all();
            $updatedPaths = [];

            $sections = DB::table('sections')
                ->whereIn('type', ['areas_served', 'town_list'])
                ->select(['id', 'page_id', 'type', 'data'])
                ->orderBy('id')
                ->get();

            foreach ($sections as $section) {
                $decoded = json_decode($section->data, true);

                if (!is_array($decoded)) {
                    continue;
                }

                $cleaned = $section->type === 'town_list'
                    ? $this->cleanTownListSection($decoded)
                    : $this->cleanAreasServedSection($decoded);

                if ($cleaned === $decoded) {
                    continue;
                }

                DB::table('sections')
                    ->where('id', $section->id)
                    ->update([
                        'data' => json_encode($cleaned, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                        'updated_at' => now(),
                    ]);

                if (isset($pagePathsById[$section->page_id])) {
                    $updatedPaths[] = $pagePathsById[$section->page_id];
                }
            }

            DB::table('pages')
                ->whereIn('full_path', self::DISALLOWED_PAGE_PATHS)
                ->delete();

            foreach (array_unique([...self::DISALLOWED_PAGE_PATHS, ...$updatedPaths]) as $fullPath) {
                Cache::forget('page_api:' . md5($fullPath));
            }
        });
    }

    public function down(): void
    {
        // Irreversible content cleanup: deleted page rows and removed links are not restored automatically.
    }

    private function cleanAreasServedSection(array $data): array
    {
        if (!isset($data['counties']) || !is_array($data['counties'])) {
            return $data;
        }

        foreach ($data['counties'] as $index => $county) {
            if (!is_array($county) || !isset($county['town_links']) || !is_array($county['town_links'])) {
                continue;
            }

            $townLinks = $county['town_links'];

            if (array_is_list($townLinks)) {
                $data['counties'][$index]['town_links'] = array_values(array_filter(
                    $townLinks,
                    fn ($link): bool => !is_array($link) || !$this->isDisallowedTownHubUrl($link['url'] ?? null),
                ));
                continue;
            }

            foreach ($townLinks as $town => $url) {
                if ($this->isDisallowedTownHubUrl($url)) {
                    unset($townLinks[$town]);
                }
            }

            $data['counties'][$index]['town_links'] = $townLinks;
        }

        return $data;
    }

    private function cleanTownListSection(array $data): array
    {
        if (!isset($data['tier1']) || !is_array($data['tier1'])) {
            return $data;
        }

        $tier1 = [];
        $tier2 = isset($data['tier2']) && is_array($data['tier2']) ? $data['tier2'] : [];

        foreach ($data['tier1'] as $entry) {
            if (!is_array($entry) || !$this->isDisallowedTownHubUrl($entry['url'] ?? null)) {
                $tier1[] = $entry;
                continue;
            }

            $label = $entry['label'] ?? null;

            if (is_string($label) && $label !== '' && !in_array($label, $tier2, true)) {
                $tier2[] = $label;
            }
        }

        $data['tier1'] = $tier1;
        $data['tier2'] = $tier2;

        return $data;
    }

    private function isDisallowedTownHubUrl(mixed $url): bool
    {
        if (!is_string($url) || trim($url) === '') {
            return false;
        }

        $normalized = '/' . ltrim(trim($url), '/');
        $normalized = $normalized === '/' ? '/' : rtrim($normalized, '/');

        return in_array($normalized, self::DISALLOWED_PAGE_PATHS, true);
    }
};
