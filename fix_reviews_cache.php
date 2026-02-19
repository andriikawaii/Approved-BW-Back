<?php

use App\Models\Page;
use App\Models\Section;
use App\Http\Controllers\Api\PageController;
use Illuminate\Support\Facades\Cache;

// 1. Cleanup orphan sections for deleted page 34
$orphans = Section::where('page_id', 34)->count();
echo "Orphan sections for page_id=34: {$orphans}\n";
Section::where('page_id', 34)->delete();
echo "Deleted orphan sections.\n";

// 2. Verify page 14 is /reviews with sections
$page = Page::where('full_path', '/reviews')->first();
if ($page) {
    $count = $page->sections()->count();
    echo "Page ID:{$page->id} | {$page->full_path} | {$page->template_key} | sections: {$count}\n";
} else {
    echo "ERROR: No page with full_path=/reviews found!\n";
}

// 3. Clear all page caches
Cache::flush();
echo "Cache flushed.\n";

// 4. Also explicitly clear the /reviews cache key
ApiPageController::forgetCacheForPath('/reviews');
echo "Explicitly cleared /reviews cache.\n";

echo "\nDone. Try /api/pages/reviews/ again.\n";
