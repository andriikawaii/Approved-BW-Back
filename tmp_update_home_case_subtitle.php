<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$section = App\Models\Section::query()
    ->where('page_id', 2)
    ->where('type', 'project_highlights')
    ->firstOrFail();

$data = (array) ($section->data ?? []);
$data['subtitle'] = "Here's a look at three projects we've completed recently. You can read the full write-ups in our case studies.";
$section->data = $data;
$section->save();

echo json_encode([
    'section_id' => $section->id,
    'subtitle' => $section->data['subtitle'] ?? null,
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . PHP_EOL;
