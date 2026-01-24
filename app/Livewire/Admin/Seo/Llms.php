<?php

namespace App\Livewire\Admin\Seo;

use Livewire\Component;
use Illuminate\Support\Facades\Cache;

class Llms extends Component
{
    public function clear(): void
    {
        Cache::forget('seo:llms.txt');
        session()->flash('success', 'llms.txt cache cleared.');
    }

    public function render()
    {
        return view('livewire.admin.seo.llms')
            ->layout('components.layouts.app')
            ->title('SEO • llms.txt');
    }
}
