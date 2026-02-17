<?php
$pages = \App\Models\Page::orderBy('full_path')->get(['id','full_path','template_key','status','content']);
echo "Total pages: " . count($pages) . "\n\n";
foreach ($pages as $p) {
    $s = is_array($p->content) ? count($p->content) : 0;
    echo "ID:{$p->id} | {$p->full_path} | {$p->template_key} | {$p->status} | sections:{$s}\n";
}
