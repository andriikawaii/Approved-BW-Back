<?php

namespace App\Livewire\Admin\Services;

use Livewire\Component;
use App\Models\Service;

class Index extends Component
{
    public function render()
    {
        return view('livewire.admin.services.index', [
            'services' => Service::orderBy('name')->get(),
        ])->layout('components.layouts.app');
    }
}
