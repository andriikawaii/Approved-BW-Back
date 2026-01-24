<?php

namespace App\Livewire\Admin\Ops;

use Livewire\Component;

class Activity extends Component
{
    public function render()
    {
        return view('livewire.admin.ops.activity')
            ->layout('components.layouts.app')
            ->title('Ops • Activity');
    }
}
