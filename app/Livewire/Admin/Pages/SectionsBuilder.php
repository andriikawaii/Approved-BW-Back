<?php

namespace App\Livewire\Admin\Pages;

use App\Models\MediaAsset;
use App\Models\Page;
use App\Models\Section;
use App\Support\Sections\SectionRegistry;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use App\Http\Controllers\Api\PageController as ApiPageController;

class SectionsBuilder extends Component
{
    public Page $page;

    public array $sections = [];
    public string $newSectionType = '';

    protected $listeners = [
        'media-selected' => 'setMedia',
    ];

    public function mount(Page $page): void
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

    protected function allowedTypes(): array
    {
        return config("page-template-sections.{$this->page->template_key}.allowed", []);
    }

    protected function requiredTypes(): array
    {
        return config("page-template-sections.{$this->page->template_key}.required", []);
    }

    protected function isRequiredSection(string $type): bool
    {
        return in_array($type, $this->requiredTypes(), true);
    }

    protected function ensureAllowed(string $type): void
    {
        if (!in_array($type, $this->allowedTypes(), true)) {
            throw ValidationException::withMessages([
                'sections' => "Section type '{$type}' is not allowed for template '{$this->page->template_key}'.",
            ]);
        }

        if (!SectionRegistry::exists($type)) {
            throw ValidationException::withMessages([
                'sections' => "Section type '{$type}' is not registered.",
            ]);
        }
    }

    protected function normalizeData(string $type, array $data): array
    {
        return array_replace_recursive(
            SectionRegistry::defaultsFor($type),
            $data
        );
    }

    public function addSection(): void
    {
        $this->validate([
            'newSectionType' => 'required|string',
        ]);

        $type = $this->newSectionType;
        $this->ensureAllowed($type);

        $this->sections[] = [
            'id'        => null,
            'type'      => $type,
            'data'      => SectionRegistry::defaultsFor($type),
            'is_active' => true,
        ];

        $this->newSectionType = '';
    }

    public function duplicateSection(int $index): void
    {
        if (!isset($this->sections[$index])) return;

        $copy = $this->sections[$index];
        $copy['id'] = null;

        array_splice($this->sections, $index + 1, 0, [$copy]);
    }

    public function resetSection(int $index): void
    {
        if (!isset($this->sections[$index])) return;

        $type = $this->sections[$index]['type'];
        $this->sections[$index]['data'] = SectionRegistry::defaultsFor($type);
    }

    public function deleteSection(int $index): void
    {
        $type = $this->sections[$index]['type'] ?? '';

        if ($this->isRequiredSection($type)) {
            $this->addError('sections', "Section '{$type}' is required.");
            return;
        }

        unset($this->sections[$index]);
        $this->sections = array_values($this->sections);
    }

    public function moveUp(int $index): void
    {
        if ($index <= 0) return;

        [$this->sections[$index - 1], $this->sections[$index]]
            = [$this->sections[$index], $this->sections[$index - 1]];
    }

    public function moveDown(int $index): void
    {
        if ($index >= count($this->sections) - 1) return;

        [$this->sections[$index + 1], $this->sections[$index]]
            = [$this->sections[$index], $this->sections[$index + 1]];
    }

    public function toggleActive(int $index): void
    {
        if (!isset($this->sections[$index])) return;

        $this->sections[$index]['is_active'] =
            !((bool) ($this->sections[$index]['is_active'] ?? true));
    }

    // HERO
    public function addHeroImage(int $sectionIndex): void
    {
        if (!isset($this->sections[$sectionIndex])) return;

        $this->sections[$sectionIndex]['data']['images'] ??= [];
        $this->sections[$sectionIndex]['data']['images'][] = '';
    }

    public function removeHeroImage(int $sectionIndex, int $imageIndex): void
    {
        $images = $this->sections[$sectionIndex]['data']['images'] ?? [];
        if (!isset($images[$imageIndex])) return;

        unset($images[$imageIndex]);
        $this->sections[$sectionIndex]['data']['images'] = array_values($images);
    }

    public function addHeroImageFromMedia(int $sectionIndex, int $mediaId): void
    {
        if (!isset($this->sections[$sectionIndex])) return;

        $media = MediaAsset::find($mediaId);
        if (!$media) return;

        $url = $media->url ?? ('/storage/' . ltrim($media->file_path, '/'));

        $this->sections[$sectionIndex]['data']['images'] ??= [];
        $this->sections[$sectionIndex]['data']['images'][] = $url;
    }

    // TRUST
    public function addTrustItem(int $sectionIndex): void
    {
        $this->sections[$sectionIndex]['data']['items'] ??= [];
        $this->sections[$sectionIndex]['data']['items'][] = [
            'icon'  => 'shield',
            'label' => '',
        ];
    }

    public function removeTrustItem(int $sectionIndex, int $itemIndex): void
    {
        $items = $this->sections[$sectionIndex]['data']['items'] ?? [];
        if (!isset($items[$itemIndex])) return;

        unset($items[$itemIndex]);
        $this->sections[$sectionIndex]['data']['items'] = array_values($items);
    }

    // SERVICES
    public function addServiceItem(int $sectionIndex): void
    {
        $this->sections[$sectionIndex]['data']['items'] ??= [];
        $this->sections[$sectionIndex]['data']['items'][] = [
            'title'       => '',
            'description' => '',
            'image'       => '',
        ];
    }

    public function removeServiceItem(int $sectionIndex, int $itemIndex): void
    {
        $items = $this->sections[$sectionIndex]['data']['items'] ?? [];
        if (!isset($items[$itemIndex])) return;

        unset($items[$itemIndex]);
        $this->sections[$sectionIndex]['data']['items'] = array_values($items);
    }

    public function setServiceItemImageFromMedia(int $sectionIndex, int $itemIndex, int $mediaId): void
    {
        $media = MediaAsset::find($mediaId);
        if (!$media) return;

        $url = $media->url ?? ('/storage/' . ltrim($media->file_path, '/'));
        $this->sections[$sectionIndex]['data']['items'][$itemIndex]['image'] = $url;
    }

    public function setMedia(int $index, int $mediaId): void
    {
        if (!isset($this->sections[$index])) return;
        $this->sections[$index]['data']['media_asset_id'] = $mediaId;
    }

    public function save(): void
    {
        DB::transaction(function () {
            $existing = Section::where('page_id', $this->page->id)->get()->keyBy('id');
            $keepIds = [];

            foreach ($this->sections as $order => &$payload) {
                $type = $payload['type'] ?? null;
                if (!$type) continue;

                $this->ensureAllowed($type);

                $data = $this->normalizeData($type, (array) ($payload['data'] ?? []));

                validator($data, SectionRegistry::rulesFor($type))->validate();

                $attributes = [
                    'type'       => $type,
                    'data'       => $data,
                    'sort_order' => $order,
                    'is_active'  => (bool) ($payload['is_active'] ?? true),
                ];

                if (!empty($payload['id']) && $existing->has($payload['id'])) {
                    $model = $existing[$payload['id']];
                    $model->update($attributes);
                } else {
                    $model = new Section($attributes);
                    $model->page_id = $this->page->id;
                    $model->save();
                    $payload['id'] = $model->id;
                }

                $keepIds[] = $model->id;
            }

            Section::where('page_id', $this->page->id)
                ->when($keepIds, fn ($q) => $q->whereNotIn('id', $keepIds))
                ->delete();
        });

        $this->page->refresh();

        // ✅ ISPRAVNO: invalidate API cache key
        ApiPageController::forgetCacheForPath($this->page->full_path);

        session()->flash('success', 'Sections saved successfully.');
    }

    public function render()
    {
        return view('livewire.admin.pages.sections-builder', [
            'allowedSections' => $this->allowedTypes(),
            'sectionRegistry' => config('sections'),
            'mediaAssets'     => MediaAsset::orderByDesc('created_at')
                ->get(['id', 'file_name', 'file_path']),
        ]);
    }
}
