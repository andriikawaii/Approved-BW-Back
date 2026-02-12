<?php

namespace App\Livewire\Admin\Pages;

use App\Http\Controllers\Api\PageController as ApiPageController;
use App\Models\County;
use App\Models\Page;
use App\Models\Service;
use App\Models\Town;
use App\Support\Paths\PathNormalizer;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Edit extends Component
{
    public Page $page;

    public string $full_path;
    public string $status;

    public string $tab = 'builder';

    public ?int $service_id = null;
    public ?int $county_id = null;
    public ?int $town_id = null;

    public function mount(Page $page): void
    {
        $this->page = $page->load('sections');

        // u inputu bez /
        $this->full_path = ltrim($page->full_path, '/');
        $this->status = $page->status;

        $this->service_id = $page->service_id;
        $this->county_id  = $page->county_id;
        $this->town_id    = $page->town_id;
    }

    protected function rules(): array
    {
        return [
            'full_path' => ['required', 'string', 'max:255'],
            'status'    => ['required', 'in:draft,published'],

            'service_id' => ['nullable', 'exists:services,id'],
            'county_id'  => ['nullable', 'exists:counties,id'],
            'town_id'    => ['nullable', 'exists:towns,id'],
        ];
    }

    protected function ensureUniquePath(string $path): void
    {
        $exists = Page::query()
            ->where('full_path', $path)
            ->whereKeyNot($this->page->id)
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'full_path' => 'This path is already taken.',
            ]);
        }
    }

    public function save()
    {
        $this->validate();

        $oldPath = $this->page->full_path;
        $newPath = PathNormalizer::normalize($this->full_path);

        $this->ensureUniquePath($newPath);

        $this->page->update([
            'full_path'    => $newPath,
            'status'       => $this->status,
            'service_id'   => $this->service_id,
            'county_id'    => $this->county_id,
            'town_id'      => $this->town_id,
            'published_at' => $this->status === 'published'
                ? ($this->page->published_at ?? now())
                : null,
            'updated_by'   => auth()->id(),
        ]);

        // cache invalidate (old + new)
        ApiPageController::forgetCacheForPath($oldPath);
        ApiPageController::forgetCacheForPath($newPath);

        return redirect()->route('admin.pages.index');
    }

    public function render()
    {
        return view('livewire.admin.pages.edit', [
            'counties' => County::orderBy('name')->get(),
            'services' => Service::orderBy('name')->get(),
            'towns'    => Town::orderBy('name')->get(),
        ])->layout('components.layouts.app');
    }
}
