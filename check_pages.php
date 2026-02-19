<?php

$pages = App\Models\Page::whereIn('full_path', ['/kitchen-remodeling', '/bathroom-remodeling', '/basement-finishing'])
    ->get();

foreach ($pages as $p) {
    echo "ID:{$p->id} | {$p->full_path} | {$p->template_key} | {$p->status}\n";
    echo "  SEO Title: {$p->seo_title}\n";
    echo "  SEO Desc: {$p->seo_description}\n";
    $sections = $p->sections()->orderBy('sort_order')->get();
    echo "  Sections ({$sections->count()}):\n";
    foreach ($sections as $s) {
        echo "    #{$s->sort_order} {$s->type} (id:{$s->id}, active:" . ($s->is_active ? 'yes' : 'no') . ")\n";
        echo "      data: " . json_encode($s->data, JSON_UNESCAPED_SLASHES) . "\n";
    }
    echo "\n";
}
