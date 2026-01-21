<?php

namespace App\Livewire\Admin\Pages;

use Livewire\Component;
use App\Models\Page;
use App\Models\County;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Api\PageController as ApiPageController;

class Edit extends Component
{
    public Page $page;

    public string $full_path;
    public string $status;
    public ?int $county_id = null;

    public function mount(Page $page): void
    {
        $this->page = $page->load('sections');

        // u formi drži bez leading slash
        $this->full_path = ltrim($page->full_path, '/');
        $this->status = $page->status;
        $this->county_id = $page->county_id;
    }

    protected function rules(): array
    {
        return [
            'full_path' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:draft,published'],
            'county_id' => ['nullable', 'exists:counties,id'],
        ];
    }

    /**
     * "a/b c/d" -> "/a/b-c/d"
     * "/" or "" -> "/"
     */
    protected function normalizeFullPath(string $input): string
    {
        $input = trim($input);

        if ($input === '' || $input === '/') {
            return '/';
        }

        $parts = array_values(array_filter(explode('/', trim($input, '/'))));

        $slugs = array_map(function ($part) {
            // slug per segment da ne ubije "/" strukturu
            return Str::slug($part);
        }, $parts);

        $joined = implode('/', array_filter($slugs));

        return $joined === '' ? '/' : '/' . $joined;
    }

    protected function ensureUniqueFullPath(string $path): void
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
        $newPath = $this->normalizeFullPath($this->full_path);

        $this->ensureUniqueFullPath($newPath);

        $this->page->update([
            'full_path'    => $newPath,
            'status'       => $this->status,
            'county_id'    => $this->county_id,
            'published_at' => $this->status === 'published'
                ? ($this->page->published_at ?? now())
                : null,
            'updated_by'   => auth()->id(),
        ]);

        ApiPageController::forgetCacheForPath($oldPath);
        ApiPageController::forgetCacheForPath($newPath);

        return redirect()->route('admin.pages.index');
    }


    public function render()
    {
        return view('livewire.admin.pages.edit', [
            'counties' => County::orderBy('name')->get(),
        ])->layout('components.layouts.app');
    }
}
