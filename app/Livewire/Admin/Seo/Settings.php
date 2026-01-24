<?php

namespace App\Livewire\Admin\Seo;

use Livewire\Component;

class Settings extends Component
{
    public function render()
    {
        return view('livewire.admin.seo.settings')
            ->layout('components.layouts.app')
            ->title('SEO • Settings');
    }
}
