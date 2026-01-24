<?php

namespace App\Livewire\Admin\Seo;

use Livewire\Component;
use Illuminate\Support\Facades\Cache;

class Robots extends Component
{
    public function clear(): void
    {
        Cache::forget('seo:robots.txt');
        session()->flash('success', 'robots.txt cache cleared.');
    }

    public function render()
    {
        return view('livewire.admin.seo.robots')
            ->layout('components.layouts.app')
            ->title('SEO • robots.txt');
    }
}
