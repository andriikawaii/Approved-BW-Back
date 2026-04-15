<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const ORANGE_PATH = '/new-haven-county/orange-ct';

    public function up(): void
    {
        DB::table('pages')
            ->where('full_path', self::ORANGE_PATH)
            ->update([
                'template_key' => 'service_town',
                'schema_type' => 'Service',
                'updated_at' => now(),
            ]);

        Cache::forget('page_api:' . md5(self::ORANGE_PATH));
        Cache::forget('seo:sitemap.xml');
    }

    public function down(): void
    {
        DB::table('pages')
            ->where('full_path', self::ORANGE_PATH)
            ->update([
                'template_key' => 'office',
                'schema_type' => 'HomeAndConstructionBusiness',
                'updated_at' => now(),
            ]);

        Cache::forget('page_api:' . md5(self::ORANGE_PATH));
        Cache::forget('seo:sitemap.xml');
    }
};
