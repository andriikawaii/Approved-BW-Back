<?php

use App\Models\Page;
use App\Models\Section;

// /portfolio (ID:35) has 0 sections — it's an empty shell. Delete it, then rename ID:13
$empty35 = Page::find(35);
if ($empty35 && $empty35->full_path === '/portfolio') {
    Section::where('page_id', 35)->delete(); // safety
    $empty35->delete();
    echo "Deleted empty /portfolio (ID:35)\n";
}

$p13 = Page::find(13);
if ($p13 && $p13->full_path === '/projects') {
    $p13->update(['full_path' => '/portfolio']);
    echo "Renamed /projects -> /portfolio (ID:13)\n";
}

// /reviews (ID:34) has 0 sections — empty shell. Delete it, then rename ID:14
$empty34 = Page::find(34);
if ($empty34 && $empty34->full_path === '/reviews') {
    Section::where('page_id', 34)->delete();
    $empty34->delete();
    echo "Deleted empty /reviews (ID:34)\n";
}

$p14 = Page::find(14);
if ($p14 && $p14->full_path === '/testimonials') {
    $p14->update(['full_path' => '/reviews']);
    echo "Renamed /testimonials -> /reviews (ID:14)\n";
}

// Final verification
echo "\n=== FINAL STATE ===\n";
$final = Page::whereIn('full_path', ['/portfolio', '/reviews', '/contact', '/subcontractors'])->get();
foreach ($final as $p) {
    $sc = $p->sections()->count();
    echo "ID:{$p->id} | {$p->full_path} | {$p->template_key} | {$p->status} | sections: {$sc}\n";
}

// Confirm no duplicates and no orphans
echo "\n=== DUPLICATE CHECK ===\n";
foreach (['/portfolio', '/reviews', '/contact', '/subcontractors'] as $path) {
    $count = Page::where('full_path', $path)->count();
    echo "{$path}: {$count} page(s)\n";
}

// Confirm old slugs are gone
echo "\n=== OLD SLUGS GONE? ===\n";
foreach (['/projects', '/testimonials'] as $old) {
    $count = Page::where('full_path', $old)->count();
    echo "{$old}: {$count} page(s)" . ($count > 0 ? " *** STILL EXISTS ***" : " ✓ gone") . "\n";
}

echo "\nDone.\n";
