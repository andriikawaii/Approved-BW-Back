<?php

namespace App\Livewire\Admin\Seo;

use Livewire\Component;
use Illuminate\Support\Facades\Cache;

class Sitemap extends Component
{
    public function clear(): void
    {
        Cache::forget('seo:sitemap.xml');
        session()->flash('success', 'Sitemap cache cleared.');
    }

    public function render()
    {
        return view('livewire.admin.seo.sitemap')
            ->layout('components.layouts.app')
            ->title('SEO • Sitemap');
    }
}
