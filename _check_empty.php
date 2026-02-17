<?php
$pages = \App\Models\Page::where('status', 'published')
    ->whereNotIn('template_key', ['service_town', 'service_county'])
    ->orderBy('full_path')
    ->get(['id','full_path','template_key','status','content']);

echo "=== PUBLISHED PAGES WITH 0 SECTIONS (excluding service_town/service_county) ===\n\n";
$empty = 0;
$hasContent = 0;
foreach ($pages as $p) {
    $s = is_array($p->content) ? count($p->content) : 0;
    $tag = $s === 0 ? '*** EMPTY ***' : "OK ({$s} sections)";
    echo "ID:{$p->id} | {$p->full_path} | {$p->template_key} | {$tag}\n";
    if ($s === 0) $empty++;
    else $hasContent++;
}
echo "\nEmpty: {$empty} | Has content: {$hasContent}\n";

echo "\n=== SERVICE_TOWN / SERVICE_COUNTY pages (will NOT touch) ===\n";
$protected = \App\Models\Page::whereIn('template_key', ['service_town', 'service_county'])
    ->orderBy('full_path')
    ->get(['id','full_path','template_key','content']);
foreach ($protected as $p) {
    $s = is_array($p->content) ? count($p->content) : 0;
    echo "ID:{$p->id} | {$p->full_path} | {$p->template_key} | sections:{$s}\n";
}
