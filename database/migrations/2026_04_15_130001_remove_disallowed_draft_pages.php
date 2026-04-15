<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const DISALLOWED_PAGE_PATHS = [
        '/subcontractors',
        '/basement-remodeling',
        '/roofing',
        '/bathroom-remodeling/new-haven-county',
        '/bathroom-remodeling/fairfield-county',
        '/kitchen-remodeling/new-haven-county',
    ];

    public function up(): void
    {
        DB::transaction(function (): void {
            DB::table('redirects')
                ->whereIn('from_path', self::DISALLOWED_PAGE_PATHS)
                ->delete();

            DB::table('pages')
                ->whereIn('full_path', self::DISALLOWED_PAGE_PATHS)
                ->delete();

            foreach (self::DISALLOWED_PAGE_PATHS as $fullPath) {
                Cache::forget('page_api:' . md5($fullPath));
            }
        });
    }

    public function down(): void
    {
        // Irreversible cleanup: deleted draft pages and redirects are intentionally not restored.
    }
};
