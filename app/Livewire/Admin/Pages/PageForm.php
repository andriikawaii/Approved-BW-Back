<?php

namespace App\Http\Livewire\Admin\Pages;

use App\Models\Page;
use App\Models\Service;
use App\Models\County;
use App\Models\Town;
use App\Models\MediaAsset;
use Livewire\Component;
use Illuminate\Validation\Rule;

class PageForm extends Component
{
    public Page $page;

    public bool $isEdit = false;

    protected function rules(): array
    {
        return [
            'page.full_path' => [
                'required',
                'string',
                'max:255',
                Rule::unique('pages', 'full_path')->ignore($this->page->id),
            ],

            'page.template_key' => ['required', 'string', 'max:100'],

            'page.status' => ['required', Rule::in(['draft', 'published'])],

            'page.published_at' => ['nullable', 'date'],

            'page.service_id' => ['nullable', 'exists:services,id'],
            'page.county_id' => ['nullable', 'exists:counties,id'],
            'page.town_id' => ['nullable', 'exists:towns,id'],

            'page.hero_media_id' => ['nullable', 'exists:media,id'],

            'page.seo_title' => ['nullable', 'string', 'max:255'],
            'page.seo_description' => ['nullable', 'string', 'max:500'],
            'page.canonical_url' => ['nullable', 'url'],

            'page.schema_type' => ['nullable', 'string', 'max:100'],
            'page.schema_overrides' => ['nullable', 'json'],

            'page.content' => ['nullable', 'json'],
        ];
    }

    public function mount(?Page $page = null): void
    {
        if ($page && $page->exists) {
            $this->page = $page;
            $this->isEdit = true;
        } else {
            $this->page = new Page([
                'status' => 'draft',
            ]);
        }
    }

    public function save()
    {
        $this->validate();

        // Normalizacija full_path (jedno mesto istine)
        $this->page->full_path = $this->normalizePath($this->page->full_path);

        $this->page->save();

        session()->flash('success', 'Page saved successfully.');

        return redirect()->route('admin.pages.edit', $this->page);
    }

    protected function normalizePath(string $path): string
    {
        $path = trim($path);

        if ($path === '') {
            return '/';
        }

        if ($path[0] !== '/') {
            $path = '/' . $path;
        }

        return $path === '/' ? '/' : rtrim($path, '/');
    }

    public function render()
    {
        return view('livewire.admin.pages.page-form', [
            'services' => Service::orderBy('name')->get(),
            'counties' => County::orderBy('name')->get(),
            'towns' => Town::orderBy('name')->get(),
            'media' => MediaAsset::orderBy('id', 'desc')->limit(50)->get(),
        ]);
    }
}
