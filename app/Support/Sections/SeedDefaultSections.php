<?php

namespace App\Support\Sections;

use App\Models\Page;
use App\Models\Section;

class SeedDefaultSections
{
    public static function handle(Page $page): void
    {
        $config = config("page-template-sections.{$page->template_key}");

        if (! $config || empty($config['defaults'])) {
            return;
        }

        foreach ($config['defaults'] as $order => $sectionConfig) {
            $type = $sectionConfig['type'];

            Section::create([
                'page_id' => $page->id,
                'type' => $type,
                'data' => SectionRegistry::defaultsFor($type),
                'sort_order' => $order,
                'is_active' => true,
            ]);
        }
    }
}
