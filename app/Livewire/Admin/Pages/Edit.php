<?php

namespace App\Livewire\Admin\Pages;

use Livewire\Component;
use App\Models\Page;
use App\Models\County;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class Edit extends Component
{
    public Page $page;

    public string $full_path;
    public string $status;
    public ?int $county_id = null;

    public function mount(Page $page)
    {
        $this->page = $page->load('sections');
        $this->full_path = ltrim($page->full_path, '/');
        $this->status = $page->status;
        $this->county_id = $page->county_id;
    }

    protected function rules(): array
    {
        return [
            'full_path' => [
                'required',
                Rule::unique('pages', 'full_path')->ignore($this->page->id),
            ],
            'status' => 'required|in:draft,published',
            'county_id' => 'nullable|exists:counties,id',
        ];
    }

    public function save()
    {
        $this->validate();

        $path = '/' . trim(Str::slug($this->full_path, '/'), '/');

        $this->page->update([
            'full_path'    => $path,
            'status'       => $this->status,
            'county_id'    => $this->county_id,
            'published_at' => $this->status === 'published'
                ? ($this->page->published_at ?? now())
                : null,
            'updated_by'   => auth()->id(),
        ]);

        return redirect()->route('admin.pages.index');
    }

    public function render()
    {
        return view('livewire.admin.pages.edit', [
            'counties' => County::orderBy('name')->get(),
        ])->layout('components.layouts.app');
    }
}
