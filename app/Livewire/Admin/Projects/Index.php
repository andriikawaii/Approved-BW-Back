<?php

namespace App\Livewire\Admin\Projects;

use Livewire\Component;
use App\Models\Project;

class Index extends Component
{
    public function render()
    {
        return view('livewire.admin.projects.index', [
            'projects' => Project::orderByDesc('updated_at')->get(),
        ])->layout('components.layouts.app');
    }
}
