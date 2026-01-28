<?php

namespace App\Livewire\Admin\Pages;

use App\Http\Controllers\Api\PageController as ApiPageController;
use App\Models\MediaAsset;
use App\Models\Page;
use App\Models\Section;
use App\Support\Sections\SectionRegistry;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class SectionsBuilder extends Component
{
    public Page $page;

    /** @var array<int, array> */
    public array $sections = [];

    /** @var array<int, bool> */
    public array $collapsed = [];

    public ?string $newSectionType = null;

    // Media picker state
    public bool $showMediaModal = false;
    public ?int $mediaTargetIndex = null;
    public ?string $mediaTargetField = null;
    public ?int $mediaTargetRepeaterIdx = null;
    public ?string $mediaTargetRepeaterSubfield = null;
    public string $mediaSearch = '';

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
                'data'      => $s->data ?? [],
                'is_active' => (bool) $s->is_active,
            ])
            ->toArray();

        $this->collapsed = array_fill(0, count($this->sections), true);
    }

    /* --------------------------------------------
     | BASIC ACTIONS
     |--------------------------------------------*/
    public function addSection(): void
    {
        $this->validate([
            'newSectionType' => 'required|string',
        ]);

        if (! SectionRegistry::exists($this->newSectionType)) {
            throw ValidationException::withMessages([
                'newSectionType' => 'Unknown section type.',
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
    }

    public function deleteSection(int $index): void
    {
        unset($this->sections[$index], $this->collapsed[$index]);

        $this->sections  = array_values($this->sections);
        $this->collapsed = array_values($this->collapsed);
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
    }

    public function selectMedia(int $mediaId): void
    {
        $media = MediaAsset::find($mediaId);
        if (! $media) return;

        $path = $media->url;
        $idx = $this->mediaTargetIndex;
        $field = $this->mediaTargetField;

        if ($this->mediaTargetRepeaterIdx !== null && $this->mediaTargetRepeaterSubfield !== null) {
            $this->sections[$idx]['data'][$field][$this->mediaTargetRepeaterIdx][$this->mediaTargetRepeaterSubfield] = $path;
        } else {
            $this->sections[$idx]['data'][$field] = $path;
        }

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
        DB::transaction(function () {

            Section::where('page_id', $this->page->id)->delete();

            foreach ($this->sections as $order => $section) {

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
            $query = MediaAsset::where('mime_type', 'like', 'image/%');
            if ($this->mediaSearch) {
                $query->where(function ($q) {
                    $q->where('file_name', 'like', "%{$this->mediaSearch}%")
                      ->orWhere('alt_text', 'like', "%{$this->mediaSearch}%")
                      ->orWhere('title', 'like', "%{$this->mediaSearch}%");
                });
            }
            $mediaItems = $query->orderByDesc('created_at')->limit(60)->get();
        }

        return view('livewire.admin.pages.sections-builder', [
            'sectionRegistry' => SectionRegistry::types(),
            'mediaItems' => $mediaItems,
        ]);
    }
}
