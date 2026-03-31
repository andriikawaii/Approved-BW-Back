<?php

namespace App\Livewire\Admin\Pages;

use App\Models\Page;
use Livewire\Component;
use App\Http\Controllers\Api\PageController as ApiPageController;

class PageSeoEditor extends Component
{
    public Page $page;

    public string $seo_title = '';
    public string $seo_description = '';
    public ?string $canonical_url = null;
    public ?string $og_image_alt = null;
    public ?string $robots = null;

    public function mount(Page $page): void
    {
        $this->page = $page;

        $this->seo_title = $page->seo_title ?? '';
        $this->seo_description = $page->seo_description ?? '';
        $this->canonical_url = $page->canonical_url;
        $this->og_image_alt = $page->og_image_alt;
        $this->robots = $page->robots;
    }

    protected function rules(): array
    {
        return [
            'seo_title' => $this->page->status === 'published'
                ? 'required|string|max:60'
                : 'nullable|string|max:60',
            'seo_description' => $this->page->status === 'published'
                ? 'required|string|max:160'
                : 'nullable|string|max:160',
            'canonical_url' => 'nullable|string|max:255',
            'og_image_alt' => 'nullable|string|max:125',
            'robots' => 'nullable|string|max:64',
        ];
    }

    public function save(): void
    {
        $this->validate();

        $this->page->update([
            'seo_title' => $this->seo_title ?: null,
            'seo_description' => $this->seo_description ?: null,
            'canonical_url' => $this->canonical_url ?: null,
            'og_image_alt' => $this->og_image_alt ?: null,
            'robots' => $this->robots ?: null,
        ]);

        // ✅ invalidiraj cache
        ApiPageController::forgetCacheForPath($this->page->full_path);

        session()->flash('success', 'SEO settings saved.');
    }

    public function render()
    {
        return view('livewire.admin.pages.page-seo-editor');
    }
}
