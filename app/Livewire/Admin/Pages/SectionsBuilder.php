<?php

namespace App\Livewire\Admin\Pages;

use App\Http\Controllers\Api\PageController as ApiPageController;
use App\Models\MediaAsset;
use App\Models\Page;
use App\Models\Section;
use App\Support\Sections\SectionRegistry;
use App\Support\Sections\ContentPolicyValidator;
use App\Support\Sections\SectionValidator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class SectionsBuilder extends Component
{
    private const FEATURE_LIST_TWO_COLUMN_DEFAULT_BULLETS = [
        'Structural Integrity Guarantee',
        'Material Defect Coverage Assistance',
        'Workmanship Quality Assurance',
        'Post-Completion Support',
        'Transparent Warranty Documentation',
        'Annual Maintenance Check-ins',
        'Priority Service Scheduling',
    ];

    private const SEO_RELEVANT_TYPES = [
        'hero',
        'hero_slider',
        'rich_text',
        'faq_list',
        'service_area_links',
        'town_list',
        'services_grid',
        'local_context',
        'case_study_header',
        'case_study_body',
    ];

    public Page $page;

    /** @var array<int, array> */
    public array $sections = [];

    /** @var array<int, bool> */
    public array $collapsed = [];

    public ?string $newSectionType = null;

    // UI state
    public ?int $confirmingDelete = null;
    public bool $showAddPanel = false;
    public string $addPanelSearch = '';

    // Media picker state
    public bool $showMediaModal = false;
    public ?int $mediaTargetIndex = null;
    public ?string $mediaTargetField = null;
    public ?int $mediaTargetRepeaterIdx = null;
    public ?string $mediaTargetRepeaterSubfield = null;
    public string $mediaSearch = '';
    public array $selectedMediaIds = [];
    public bool $isMultiSelectMode = false;

    /* --------------------------------------------
     | MOUNT
     |--------------------------------------------*/
    public function mount(Page $page): void
    {
        $this->page = $page->load('sections');

        $this->sections = $this->page->sections
            ->sortBy('sort_order')
            ->values()
            ->map(fn (Section $s) => [
                'id'        => $s->id,
                'type'      => $s->type,
                'data'      => $this->normalizeSectionData($s->type, $s->data ?? []),
                'is_active' => (bool) $s->is_active,
            ])
            ->toArray();

        $this->collapsed = array_fill(0, count($this->sections), true);
    }

    /* --------------------------------------------
     | BASIC ACTIONS
     |--------------------------------------------*/
    public function addSection(?string $type = null): void
    {
        if ($type !== null) {
            $this->newSectionType = $type;
        }

        $this->validate([
            'newSectionType' => 'required|string',
        ]);

        // ✅ SECURITY: Validate section type exists in registry
        if (! SectionRegistry::exists($this->newSectionType)) {
            throw ValidationException::withMessages([
                'newSectionType' => 'Unknown section type.',
            ]);
        }

        // ✅ SECURITY: Validate section type is allowed for this template
        $allowed = config("page-template-sections.{$this->page->template_key}.allowed", []);

        if (!empty($allowed) && !in_array($this->newSectionType, $allowed, true)) {
            $templateLabel = \Illuminate\Support\Str::headline($this->page->template_key);
            $sectionLabel = SectionRegistry::labelFor($this->newSectionType);

            throw ValidationException::withMessages([
                'newSectionType' => "Section '{$sectionLabel}' is not allowed for template '{$templateLabel}'.",
            ]);
        }

        $this->sections[] = [
            'id'        => null,
            'type'      => $this->newSectionType,
            'data'      => SectionRegistry::defaultsFor($this->newSectionType),
            'is_active' => true,
        ];

        $this->collapsed[] = false;
        $this->newSectionType = null;
        $this->showAddPanel = false;
    }

    public function confirmDelete(int $index): void
    {
        $this->confirmingDelete = $index;
    }

    public function cancelDelete(): void
    {
        $this->confirmingDelete = null;
    }

    public function deleteSection(int $index): void
    {
        unset($this->sections[$index], $this->collapsed[$index]);

        $this->sections  = array_values($this->sections);
        $this->collapsed = array_values($this->collapsed);
        $this->confirmingDelete = null;
    }

    public function duplicateSection(int $index): void
    {
        $copy = $this->sections[$index];
        $copy['id'] = null;

        array_splice($this->sections, $index + 1, 0, [$copy]);
        array_splice($this->collapsed, $index + 1, 0, [false]);
    }

    public function toggleActive(int $index): void
    {
        $this->sections[$index]['is_active'] =
            ! ($this->sections[$index]['is_active'] ?? true);
    }

    public function toggleCollapse(int $index): void
    {
        $this->collapsed[$index] = ! ($this->collapsed[$index] ?? true);
    }

    public function reorderSections(array $orderedIndexes): void
    {
        $total = count($this->sections);

        if ($total <= 1 || count($orderedIndexes) !== $total) {
            return;
        }

        $orderedIndexes = array_map('intval', $orderedIndexes);
        $sorted = $orderedIndexes;
        sort($sorted);

        if ($sorted !== range(0, $total - 1)) {
            return;
        }

        $nextSections = [];
        $nextCollapsed = [];

        foreach ($orderedIndexes as $index) {
            $nextSections[] = $this->sections[$index];
            $nextCollapsed[] = $this->collapsed[$index] ?? true;
        }

        $this->sections = $nextSections;
        $this->collapsed = $nextCollapsed;
    }

    /* --------------------------------------------
     | ORDERING (↑ ↓)
     |--------------------------------------------*/
    public function moveUp(int $index): void
    {
        if ($index === 0) return;

        [$this->sections[$index - 1], $this->sections[$index]] =
            [$this->sections[$index], $this->sections[$index - 1]];

        [$this->collapsed[$index - 1], $this->collapsed[$index]] =
            [$this->collapsed[$index], $this->collapsed[$index - 1]];
    }

    public function moveDown(int $index): void
    {
        if ($index >= count($this->sections) - 1) return;

        [$this->sections[$index + 1], $this->sections[$index]] =
            [$this->sections[$index], $this->sections[$index + 1]];

        [$this->collapsed[$index + 1], $this->collapsed[$index]] =
            [$this->collapsed[$index], $this->collapsed[$index + 1]];
    }

    /* --------------------------------------------
     | MEDIA PICKER
     |--------------------------------------------*/
    public function openMediaPicker(int $index, string $field, ?int $repeaterIdx = null, ?string $subfield = null): void
    {
        $this->mediaTargetIndex = $index;
        $this->mediaTargetField = $field;
        $this->mediaTargetRepeaterIdx = $repeaterIdx;
        $this->mediaTargetRepeaterSubfield = $subfield;
        $this->mediaSearch = '';
        $this->selectedMediaIds = [];

        // Multi-select mode: when opening for a repeater field without a specific item
        // (used for bulk-adding multiple items like hero slider slides)
        $this->isMultiSelectMode = ($repeaterIdx === null && $subfield === null) &&
                                   isset($this->sections[$index]) &&
                                   isset($this->sections[$index]['data'][$field]) &&
                                   is_array($this->sections[$index]['data'][$field]) &&
                                   is_array($this->sections[$index]['data'][$field][0] ?? null) &&
                                   isset($this->sections[$index]['data'][$field][0]['image']);

        $this->showMediaModal = true;
    }

    public function closeMediaPicker(): void
    {
        $this->showMediaModal = false;
        $this->mediaTargetIndex = null;
        $this->mediaTargetField = null;
        $this->mediaTargetRepeaterIdx = null;
        $this->mediaTargetRepeaterSubfield = null;
        $this->mediaSearch = '';
        $this->selectedMediaIds = [];
        $this->isMultiSelectMode = false;
    }

    public function selectMedia(int $mediaId): void
    {
        if ($this->isMultiSelectMode) {
            // Toggle selection in multi-select mode
            if (in_array($mediaId, $this->selectedMediaIds, true)) {
                $this->selectedMediaIds = array_values(array_diff($this->selectedMediaIds, [$mediaId]));
            } else {
                $this->selectedMediaIds[] = $mediaId;
            }
        } else {
            // Single selection mode
            $media = MediaAsset::find($mediaId);
            if (! $media) return;

            $path = $media->url;
            $idx = $this->mediaTargetIndex;
            $field = $this->mediaTargetField;

            if ($idx === null || $field === null) return;

            // Clone-and-reassign so Livewire's dirty-tracking catches the deep mutation
            $sections = $this->sections;

            if ($this->mediaTargetRepeaterIdx !== null && $this->mediaTargetRepeaterSubfield !== null) {
                $sections[$idx]['data'][$field][$this->mediaTargetRepeaterIdx][$this->mediaTargetRepeaterSubfield] = $path;
            } else {
                $sections[$idx]['data'][$field] = $path;
            }

            $this->sections = $sections;
            $this->closeMediaPicker();
        }
    }

    public function applyMultipleMedia(): void
    {
        if (empty($this->selectedMediaIds) || !$this->isMultiSelectMode) return;

        $idx = $this->mediaTargetIndex;
        $field = $this->mediaTargetField;

        if ($idx === null || $field === null) return;

        $sections = $this->sections;
        $items = $sections[$idx]['data'][$field] ?? [];
        if (!is_array($items)) $items = [];

        // Get template from first existing item
        $template = $items[0] ?? [];

        // Add new items for each selected media
        foreach ($this->selectedMediaIds as $mediaId) {
            $media = MediaAsset::find($mediaId);
            if (!$media) continue;

            // Create new item based on template
            if (is_array($template)) {
                $newItem = array_map(fn() => null, $template);
                $newItem['image'] = $media->url;
            } else {
                $newItem = $media->url;
            }

            $items[] = $newItem;
        }

        $sections[$idx]['data'][$field] = array_values($items);
        $this->sections = $sections;

        $this->closeMediaPicker();
    }

    public function setMedia(int $index, int $mediaId): void
    {
        $this->sections[$index]['data']['media_asset_id'] = $mediaId;
    }

    /* --------------------------------------------
     | REPEATER
     |--------------------------------------------*/
    public function addRepeaterItem(int $index, string $field): void
    {
        $defaults = SectionRegistry::defaultsFor($this->sections[$index]['type']);

        $existing = $this->sections[$index]['data'][$field] ?? [];
        if (! is_array($existing)) $existing = [];

        // Determine template from defaults or existing items
        $template = null;
        if (! empty($defaults[$field]) && is_array($defaults[$field])) {
            $first = reset($defaults[$field]);
            if (is_array($first)) {
                $template = array_map(fn ($v) => is_array($v) ? array_map(fn () => null, $v) : null, $first);
            }
        }

        // Fallback: use first existing item as template
        if ($template === null && ! empty($existing)) {
            $first = reset($existing);
            if (is_array($first)) {
                $template = array_map(fn ($v) => is_array($v) ? array_map(fn () => null, $v) : null, $first);
            }
        }

        $existing[] = $template ?? '';
        $this->sections[$index]['data'][$field] = array_values($existing);
    }

    public function removeRepeaterItem(int $index, string $field, int $itemIndex): void
    {
        $items = $this->sections[$index]['data'][$field] ?? [];
        if (! is_array($items)) return;

        unset($items[$itemIndex]);
        $this->sections[$index]['data'][$field] = array_values($items);
    }

    /* --------------------------------------------
     | SAVE
     |--------------------------------------------*/
    public function save(): void
    {
        // ✅ SECURITY: Validate required sections exist and are active
        $this->sections = array_map(function (array $section): array {
            $section['data'] = $this->normalizeSectionData(
                (string) ($section['type'] ?? ''),
                $section['data'] ?? []
            );

            return $section;
        }, $this->sections);

        $required = config("page-template-sections.{$this->page->template_key}.required", []);

        if (!empty($required)) {
            $activeSectionTypes = array_map(
                fn($section) => ($section['is_active'] ?? true) ? $section['type'] : null,
                $this->sections
            );
            $activeSectionTypes = array_filter($activeSectionTypes);

            foreach ($required as $requiredType) {
                if (!in_array($requiredType, $activeSectionTypes, true)) {
                    $label = SectionRegistry::labelFor($requiredType);
                    session()->flash('error', "Required section '{$label}' is missing or inactive.");
                    return;
                }
            }
        }

        // ✅ LOCKED COPY: Validate CTA text per BuiltWell spec v3.3
        foreach ($this->sections as $index => $section) {
            if (!($section['is_active'] ?? true)) {
                continue; // Skip inactive sections
            }

            $errors = SectionValidator::validate($section['type'], $section['data'] ?? []);

            if (!empty($errors)) {
                $sectionLabel = SectionRegistry::labelFor($section['type']);
                $errorMessage = sprintf(
                    'Section "%s" (#%d) has invalid CTA text: %s',
                    $sectionLabel,
                    $index + 1,
                    implode(' ', $errors)
                );

                session()->flash('error', $errorMessage);
                return;
            }
        }

        // ✅ LOCKED: Content policy validation (owner name, forbidden terms, link direction)
        $policyErrors = ContentPolicyValidator::validate($this->page, $this->sections);

        if (!empty($policyErrors)) {
            session()->flash('error', implode(' | ', $policyErrors));
            return;
        }

        DB::transaction(function () {
            Section::where('page_id', $this->page->id)->delete();

            foreach ($this->sections as $order => $section) {
                // ✅ SECURITY: Skip sections with unknown types (fallback)
                if (!SectionRegistry::exists($section['type'])) {
                    \Log::warning("Unknown section type '{$section['type']}' for page {$this->page->id}, skipping.", [
                        'page_id' => $this->page->id,
                        'page_path' => $this->page->full_path,
                        'section_type' => $section['type'],
                    ]);
                    continue;
                }

                Section::create([
                    'page_id'    => $this->page->id,
                    'type'       => $section['type'],
                    'data'       => $section['data'] ?? [],
                    'is_active'  => (bool) ($section['is_active'] ?? true),
                    'sort_order' => $order,
                ]);
            }
        });

        ApiPageController::forgetCacheForPath($this->page->full_path);

        session()->flash('success', 'Sections saved successfully.');
    }

    public function render()
    {
        $mediaItems = collect();
        if ($this->showMediaModal) {
            $isVideoField = str_contains($this->mediaTargetField ?? '', 'video');
            $query = MediaAsset::where('mime_type', 'like', $isVideoField ? 'video/%' : 'image/%');
            if ($this->mediaSearch) {
                $query->where(function ($q) {
                    $q->where('file_name', 'like', "%{$this->mediaSearch}%")
                      ->orWhere('alt_text', 'like', "%{$this->mediaSearch}%")
                      ->orWhere('title', 'like', "%{$this->mediaSearch}%");
                });
            }
            $mediaItems = $query->orderByDesc('created_at')->limit(60)->get();
        }

        // Build grouped section catalog for the add-section panel
        $allTypes = SectionRegistry::types();
        $allowed = config("page-template-sections.{$this->page->template_key}.allowed", []);

        $categoryMap = [
            'Hero'          => ['hero', 'hero_slider'],
            'Content'       => ['rich_text', 'consultation_cards_split', 'local_context'],
            'Social Proof'  => ['trust_bar', 'stats_counter', 'testimonials', 'project_highlights', 'logo_strip', 'before_after'],
            'Services'      => ['services_grid', 'service_includes', 'pricing_table', 'timeline_block', 'process_steps', 'service_area_links'],
            'CTA & Forms'   => ['cta_block', 'lead_form'],
            'Media'         => ['image_gallery', 'map_embed'],
            'Location'      => ['areas_we_serve_cards', 'town_list'],
            'Case Studies'  => ['case_study_header', 'case_study_meta', 'case_study_body', 'case_study_gallery'],
            'Other'         => ['faq_list'],
        ];

        $groupedSections = [];
        foreach ($categoryMap as $category => $types) {
            $items = [];
            foreach ($types as $type) {
                if (! isset($allTypes[$type])) continue;
                $meta = $allTypes[$type];
                $isAllowed = empty($allowed) || in_array($type, $allowed, true);
                $items[] = [
                    'type'        => $type,
                    'label'       => $meta['label'],
                    'description' => $meta['description'] ?? '',
                    'allowed'     => $isAllowed,
                ];
            }
            if (! empty($items)) {
                $groupedSections[$category] = $items;
            }
        }

        return view('livewire.admin.pages.sections-builder', [
            'sectionRegistry' => $allTypes,
            'groupedSections' => $groupedSections,
            'allowedTypes'    => $allowed,
            'requiredTypes'   => config("page-template-sections.{$this->page->template_key}.required", []),
            'seoRelevantTypes' => self::SEO_RELEVANT_TYPES,
            'mediaItems'      => $mediaItems,
        ]);
    }

    private function normalizeSectionData(string $type, mixed $data): array
    {
        if (! is_array($data)) {
            $data = [];
        }

        if ($type === 'before_after_grid') {
            $data = $this->normalizeBeforeAfterGridData($data);
        }

        if ($type === 'project_highlights') {
            $data = $this->normalizeProjectHighlightsData($data);
        }

        if ($type === 'feature_list_two_column') {
            $data = $this->ensureFeatureListTwoColumnBullets($data);
        }

        return $data;
    }

    private function ensureFeatureListTwoColumnBullets(array $data): array
    {
        $bullets = $data['right_bullets'] ?? [];

        if (! is_array($bullets)) {
            $bullets = [];
        }

        $bullets = array_values(array_filter(array_map(
            static fn ($item) => is_string($item) ? trim($item) : '',
            $bullets
        )));

        foreach (self::FEATURE_LIST_TWO_COLUMN_DEFAULT_BULLETS as $defaultBullet) {
            if (count($bullets) >= count(self::FEATURE_LIST_TWO_COLUMN_DEFAULT_BULLETS)) {
                break;
            }

            if (! in_array($defaultBullet, $bullets, true)) {
                $bullets[] = $defaultBullet;
            }
        }

        $data['right_bullets'] = $bullets;

        return $data;
    }

    private function normalizeBeforeAfterGridData(array $data): array
    {
        $projects = $data['projects'] ?? [];

        if (! is_array($projects)) {
            $projects = [];
        }

        $data['projects'] = array_map(static function ($project): array {
            if (! is_array($project)) {
                $project = [];
            }

            if (! array_key_exists('image', $project) && array_key_exists('before_image', $project)) {
                $project['image'] = $project['before_image'];
            }

            if (! array_key_exists('image_alt', $project) && array_key_exists('before_image_alt', $project)) {
                $project['image_alt'] = $project['before_image_alt'];
            }

            unset(
                $project['before_image'],
                $project['before_image_alt'],
                $project['after_image'],
                $project['after_image_alt']
            );

            return $project;
        }, $projects);

        return $data;
    }

    private function normalizeProjectHighlightsData(array $data): array
    {
        $items = $data['items'] ?? [];

        if (! is_array($items)) {
            $items = [];
        }

        $data['items'] = array_map(static function ($item): array {
            if (! is_array($item)) {
                $item = [];
            }

            if (! array_key_exists('url', $item) && array_key_exists('link', $item)) {
                $item['url'] = $item['link'];
            }

            if (! array_key_exists('link', $item) && array_key_exists('url', $item)) {
                $item['link'] = $item['url'];
            }

            if (! array_key_exists('image', $item)) {
                $item['image'] = null;
            }

            if (! array_key_exists('image_alt', $item)) {
                $item['image_alt'] = null;
            }

            return $item;
        }, $items);

        return $data;
    }
}
