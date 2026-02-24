<?php

namespace App\Livewire\Admin\Ops;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity as ActivityModel;

class Activity extends Component
{
    use WithPagination;

    public function render()
    {
        $activities = ActivityModel::with('causer', 'subject')
            ->latest()
            ->paginate(25);

        return view('livewire.admin.ops.activity', [
            'activities' => $activities,
        ])
            ->layout('components.layouts.app')
            ->title('Ops • Activity');
    }
}
