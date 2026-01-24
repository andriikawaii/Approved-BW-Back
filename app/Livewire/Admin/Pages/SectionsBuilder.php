<?php

namespace App\Livewire\Admin\Pages;

use App\Http\Controllers\Api\PageController as ApiPageController;
use App\Models\MediaAsset;
use App\Models\Page;
use App\Models\Section;
use App\Support\Sections\SectionRegistry;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class SectionsBuilder extends Component
{
    public Page $page;

    /** @var array<int, array> */
    public array $sections = [];

    public string $newSectionType = '';

    public array $sectionRegistry = [];
    public array $sectionOptions = [];
    protected $listeners = [
        'media-selected' => 'setMedia',
    ];

    public function mount(Page $page): void
    {
        $this->page = $page->load('sections');

        // registry
        $this->sectionRegistry = SectionRegistry::types();

        // EXISTING SECTIONS FROM DB ✅
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
    }

    protected function allowedTypes(): array
    {
        return config("page-template-sections.{$this->page->template_key}.allowed", []);
    }

    protected function ensureAllowed(string $type): void
    {
        if (! in_array($type, $this->allowedTypes(), true)) {
            throw ValidationException::withMessages([
                'sections' => "Section '{$type}' not allowed for this template.",
            ]);
        }

        if (! SectionRegistry::exists($type)) {
            throw ValidationException::withMessages([
                'sections' => "Section '{$type}' not registered.",
            ]);
        }
    }

    // =====================
    // ACTIONS
    // =====================

    public function addSection(): void
    {
        $this->validate([
            'newSectionType' => 'required|string',
        ]);

        $this->ensureAllowed($this->newSectionType);

        $this->sections[] = [
            'id'        => null,
            'type'      => $this->newSectionType,
            'data'      => SectionRegistry::defaultsFor($this->newSectionType),
            'is_active' => true,
        ];

        $this->newSectionType = '';
    }

    public function duplicateSection(int $index): void
    {
        if (! isset($this->sections[$index])) return;

        $copy = $this->sections[$index];
        $copy['id'] = null;

        array_splice($this->sections, $index + 1, 0, [$copy]);
    }

    public function deleteSection(int $index): void
    {
        unset($this->sections[$index]);
        $this->sections = array_values($this->sections);
    }

    public function moveUp(int $index): void
    {
        if ($index === 0) return;

        [$this->sections[$index - 1], $this->sections[$index]] =
            [$this->sections[$index], $this->sections[$index - 1]];
    }

    public function moveDown(int $index): void
    {
        if ($index >= count($this->sections) - 1) return;

        [$this->sections[$index + 1], $this->sections[$index]] =
            [$this->sections[$index], $this->sections[$index + 1]];
    }

    public function toggleActive(int $index): void
    {
        $this->sections[$index]['is_active'] =
            ! ($this->sections[$index]['is_active'] ?? true);
    }

    public function setMedia(int $index, int $mediaId): void
    {
        $this->sections[$index]['data']['media_asset_id'] = $mediaId;
    }

    protected function groupedSections(): array
    {
        $types = SectionRegistry::types();
        $allowed = null;

        $groups = [
            'Hero / Above the fold' => ['hero', 'hero_slider'],
            'Content' => ['rich_text', 'image_gallery', 'before_after'],
            'Conversion' => ['cta_block', 'lead_form'],
            'Trust & Proof' => ['trust_bar', 'testimonials', 'logo_strip'],
            'Services' => ['services_grid', 'service_includes', 'pricing_table'],
            'Process & Timeline' => ['process_steps', 'timeline_block'],
            'Location & SEO' => ['local_context', 'service_area_links', 'town_list', 'map_embed'],
            'Case Studies' => [
                'case_study_header',
                'case_study_meta',
                'case_study_body',
                'case_study_gallery',
            ],
        ];

        $result = [];

        foreach ($groups as $label => $keys) {
            foreach ($keys as $key) {
                if (isset($types[$key]) && (is_null($allowed) || in_array($key, $allowed, true))) {
                    $result[$label][$key] = $types[$key];
                }
            }
        }

        return $result;
    }

    // =====================
    // SAVE
    // =====================

    public function save(): void
    {
        DB::transaction(function () {

            Section::where('page_id', $this->page->id)->delete();

            foreach ($this->sections as $order => $section) {

                $type = $section['type'];

                // 1. sigurnost
                if (! SectionRegistry::exists($type)) {
                    throw ValidationException::withMessages([
                        'sections' => "Unknown section type: {$type}",
                    ]);
                }

                // 2. VALIDACIJA PODATAKA
                $rules = SectionRegistry::rulesFor($type);

                validator(
                    $section['data'] ?? [],
                    $rules,
                    [],
                    collect($rules)->mapWithKeys(fn ($_, $k) => [$k => ucfirst(str_replace('_', ' ', $k))])->toArray()
                )->validate();

                // 3. UPIS
                Section::create([
                    'page_id'    => $this->page->id,
                    'type'       => $type,
                    'data'       => $section['data'],
                    'is_active'  => (bool) $section['is_active'],
                    'sort_order' => $order,
                ]);
            }
        });

        ApiPageController::forgetCacheForPath($this->page->full_path);

        session()->flash('success', 'Sections saved successfully.');
    }


    public function render()
    {
        return view('livewire.admin.pages.sections-builder', [
            'groupedSections' => $this->groupedSections(),
            'sectionRegistry' => SectionRegistry::types(),
        ]);
    }
}
