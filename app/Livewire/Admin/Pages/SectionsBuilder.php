<?php

namespace App\Livewire\Admin\Pages;

use App\Models\MediaAsset;
use Livewire\Component;
use App\Models\Page;
use App\Models\Section;
use App\Support\Sections\SectionRegistry;
use Illuminate\Support\Facades\DB;

class SectionsBuilder extends Component
{
    public Page $page;

    public array $sections = [];
    public string $newSectionType = '';
    public ?int $openSection = null;

    /**
     * Event listeners (za Media Picker)
     */
    protected $listeners = [
        'media-selected' => 'setMedia',
    ];

    public function mount(Page $page)
    {
        $this->page = $page->load('sections');

        $this->sections = $this->page->sections
            ->sortBy('sort_order')
            ->values()
            ->map(fn (Section $section) => [
                'id'        => $section->id,
                'type'      => $section->type,
                'data'      => $section->data ?? [],
                'is_active' => (bool) $section->is_active,
            ])
            ->toArray();
    }
    public function toggleSection(int $index): void
    {
        $this->openSection = $this->openSection === $index ? null : $index;
    }
    /* -----------------
     | Helpers
     |-----------------*/

    protected function isRequiredSection(string $type): bool
    {
        $required = config(
            "page-template-sections.{$this->page->template_key}.required",
            []
        );

        return in_array($type, $required, true);
    }

    /* -----------------
     | Actions
     |-----------------*/

    public function addSection(): void
    {
        $this->validate([
            'newSectionType' => 'required|string',
        ]);

        $allowed = config(
            "page-template-sections.{$this->page->template_key}.allowed",
            []
        );

        if (! in_array($this->newSectionType, $allowed, true)) {
            $this->addError('newSectionType', 'Section not allowed for this template.');
            return;
        }

        $this->sections[] = [
            'id'        => null,
            'type'      => $this->newSectionType,
            'data'      => SectionRegistry::defaultsFor($this->newSectionType),
            'is_active' => true,
        ];

        $this->newSectionType = '';
    }

    public function moveUp(int $index): void
    {
        if ($index === 0) return;

        [$this->sections[$index - 1], $this->sections[$index]]
            = [$this->sections[$index], $this->sections[$index - 1]];
    }

    public function moveDown(int $index): void
    {
        if ($index >= count($this->sections) - 1) return;

        [$this->sections[$index + 1], $this->sections[$index]]
            = [$this->sections[$index], $this->sections[$index + 1]];
    }

    public function deleteSection(int $index): void
    {
        $type = $this->sections[$index]['type'];

        if ($this->isRequiredSection($type)) {
            $this->addError('sections', "Section '{$type}' is required.");
            return;
        }

        unset($this->sections[$index]);
        $this->sections = array_values($this->sections);
    }

    /**
     * Prima event iz Media Picker-a
     */
    public function setMedia(int $index, int $mediaId): void
    {
        if (! isset($this->sections[$index])) {
            return;
        }

        $this->sections[$index]['data']['media_asset_id'] = $mediaId;
    }

    public function save(): void
    {
        DB::transaction(function () {

            Section::where('page_id', $this->page->id)->delete();

            foreach ($this->sections as $order => $section) {

                validator(
                    $section['data'],
                    SectionRegistry::rulesFor($section['type'])
                )->validate();

                Section::create([
                    'page_id'    => $this->page->id,
                    'type'       => $section['type'],
                    'data'       => $section['data'],
                    'sort_order' => $order,
                    'is_active'  => $section['is_active'],
                ]);
            }
        });

        session()->flash('success', 'Sections saved successfully.');
    }

    public function render()
    {
        return view('livewire.admin.pages.sections-builder', [
            'allowedSections' => config(
                "page-template-sections.{$this->page->template_key}.allowed",
                []
            ),
            'sectionRegistry' => config('sections'),
            'mediaAssets' => MediaAsset::orderByDesc('created_at')
                ->get(['id', 'file_name', 'file_path']),
        ]);
    }
}
