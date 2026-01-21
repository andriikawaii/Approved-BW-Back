<?php

namespace App\Livewire\Admin\Towns;

use App\Models\Town;
use App\Models\County;
use Livewire\Component;

class Index extends Component
{
    public ?int $countyId = null;

    public function toggleActive(Town $town): void
    {
        $town->update([
            'is_active' => ! $town->is_active,
        ]);
    }

    public function render()
    {
        return view('livewire.admin.towns.index', [
            'counties' => County::orderBy('name')->get(),
            'towns' => Town::with('county')
                ->when($this->countyId, fn ($q) => $q->where('county_id', $this->countyId))
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(),
        ]);
    }
}
