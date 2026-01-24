<?php

namespace App\Livewire\Admin\Seo;

use Livewire\Component;
use Illuminate\Support\Facades\Cache;

class HtmlSitemap extends Component
{
    public function clear(): void
    {
        Cache::forget('seo:sitemap.html');
        session()->flash('success', 'HTML sitemap cache cleared.');
    }

    public function render()
    {
        return view('livewire.admin.seo.html-sitemap')
            ->layout('components.layouts.app')
            ->title('SEO • HTML Sitemap');
    }
}
