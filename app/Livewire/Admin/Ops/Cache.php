<?php

namespace App\Livewire\Admin\Ops;

use Livewire\Component;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache as CacheStore;

class Cache extends Component
{
    public function clearApp(): void
    {
        Artisan::call('cache:clear');
        session()->flash('success', 'Application cache cleared.');
    }

    public function clearView(): void
    {
        Artisan::call('view:clear');
        session()->flash('success', 'View cache cleared.');
    }

    public function clearConfig(): void
    {
        Artisan::call('config:clear');
        session()->flash('success', 'Config cache cleared.');
    }

    public function clearRoute(): void
    {
        Artisan::call('route:clear');
        session()->flash('success', 'Route cache cleared.');
    }

    public function clearSeoKeys(): void
    {
        CacheStore::forget('seo:sitemap.xml');
        CacheStore::forget('seo:sitemap.html');
        CacheStore::forget('seo:robots.txt');
        CacheStore::forget('seo:llms.txt');

        session()->flash('success', 'SEO cache keys cleared.');
    }

    public function render()
    {
        return view('livewire.admin.ops.cache')
            ->layout('components.layouts.app')
            ->title('Ops • Cache');
    }
}
