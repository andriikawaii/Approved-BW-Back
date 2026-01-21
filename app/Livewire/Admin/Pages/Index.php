<?php

namespace App\Livewire\Admin\Pages;

use Livewire\Component;
use App\Models\Page;

class Index extends Component
{
    public function render()
    {
        return view('livewire.admin.pages.index', [
            'pages' => Page::orderByDesc('updated_at')->get(),
        ])->layout('components.layouts.app');
    }
}
