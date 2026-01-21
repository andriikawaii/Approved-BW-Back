<?php

namespace App\Livewire\Admin\Counties;

use App\Models\County;
use Livewire\Component;

class Index extends Component
{
    public function toggleActive(County $county): void
    {
        $county->update([
            'is_active' => ! $county->is_active,
        ]);
    }

    public function render()
    {
        return view('livewire.admin.counties.index', [
            'counties' => County::orderBy('sort_order')
                ->orderBy('name')
                ->get(),
        ]);
    }
}
