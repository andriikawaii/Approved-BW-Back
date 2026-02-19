<?php

use App\Models\Page;

// Show current state
echo "=== BEFORE ===\n";
$paths = ['/projects', '/testimonials', '/contact', '/subcontractors', '/portfolio', '/reviews'];
$pages = Page::whereIn('full_path', $paths)->get();
foreach ($pages as $p) {
    echo "ID:{$p->id} | {$p->full_path} | {$p->template_key} | {$p->status}\n";
}

// Rename /projects -> /portfolio
$projects = Page::where('full_path', '/projects')->first();
if ($projects) {
    // Make sure /portfolio doesn't already exist as a different page
    $dup = Page::where('full_path', '/portfolio')->where('id', '!=', $projects->id)->first();
    if ($dup) {
        echo "ERROR: /portfolio already exists as ID:{$dup->id} — aborting\n";
    } else {
        $projects->update(['full_path' => '/portfolio']);
        echo "Renamed /projects -> /portfolio (ID:{$projects->id})\n";
    }
} else {
    // Maybe already renamed?
    $existing = Page::where('full_path', '/portfolio')->first();
    if ($existing) {
        echo "/portfolio already exists (ID:{$existing->id}) — no action needed\n";
    } else {
        echo "WARNING: Neither /projects nor /portfolio found\n";
    }
}

// Rename /testimonials -> /reviews
$testimonials = Page::where('full_path', '/testimonials')->first();
if ($testimonials) {
    $dup = Page::where('full_path', '/reviews')->where('id', '!=', $testimonials->id)->first();
    if ($dup) {
        echo "ERROR: /reviews already exists as ID:{$dup->id} — aborting\n";
    } else {
        $testimonials->update(['full_path' => '/reviews']);
        echo "Renamed /testimonials -> /reviews (ID:{$testimonials->id})\n";
    }
} else {
    $existing = Page::where('full_path', '/reviews')->first();
    if ($existing) {
        echo "/reviews already exists (ID:{$existing->id}) — no action needed\n";
    } else {
        echo "WARNING: Neither /testimonials nor /reviews found\n";
    }
}

// Ensure /contact has no trailing slash
$contact = Page::where('full_path', 'LIKE', '/contact%')->whereNotNull('id')->get();
foreach ($contact as $c) {
    if ($c->full_path !== '/contact') {
        echo "Fixing contact slug: '{$c->full_path}' -> '/contact' (ID:{$c->id})\n";
        $c->update(['full_path' => '/contact']);
    } else {
        echo "/contact is correct (ID:{$c->id})\n";
    }
}

// Ensure /subcontractors has no trailing slash
$subs = Page::where('full_path', 'LIKE', '/subcontractors%')->whereNotNull('id')->get();
foreach ($subs as $s) {
    if ($s->full_path !== '/subcontractors') {
        echo "Fixing subcontractors slug: '{$s->full_path}' -> '/subcontractors' (ID:{$s->id})\n";
        $s->update(['full_path' => '/subcontractors']);
    } else {
        echo "/subcontractors is correct (ID:{$s->id})\n";
    }
}

// Final verification
echo "\n=== AFTER ===\n";
$final = Page::whereIn('full_path', ['/portfolio', '/reviews', '/contact', '/subcontractors'])->get();
foreach ($final as $p) {
    $sectionCount = $p->sections()->count();
    echo "ID:{$p->id} | {$p->full_path} | {$p->template_key} | {$p->status} | sections: {$sectionCount}\n";
}

// Check for duplicates
echo "\n=== DUPLICATE CHECK ===\n";
$dupCheck = ['/portfolio', '/reviews', '/contact', '/subcontractors'];
foreach ($dupCheck as $path) {
    $count = Page::where('full_path', $path)->count();
    echo "{$path}: {$count} page(s)" . ($count > 1 ? " *** DUPLICATE ***" : " ✓") . "\n";
}

echo "\nDone.\n";
