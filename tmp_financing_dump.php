<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$page = App\Models\Page::where('full_path', '/financing')->orWhere('full_path', '/financing/')->with(['sections' => function ($q) {
    $q->orderBy('sort_order');
}])->first();

if (!$page) {
    echo "NOT_FOUND\n";
    exit(0);
}

$out = [
    'id' => $page->id,
    'full_path' => $page->full_path,
    'template_key' => $page->template_key,
    'section_count' => $page->sections->count(),
    'sections' => $page->sections->map(function ($s) {
        return [
            'sort' => $s->sort_order,
            'type' => $s->type,
            'keys' => array_keys((array) $s->data),
            'data' => $s->data,
        ];
    })->all(),
];

echo json_encode($out, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
