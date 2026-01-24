<?php
namespace App\Livewire\Admin\Sections;

use Livewire\Component;

class Index extends Component
{
    public array $types = [];

    public function mount(): void
    {
        $this->types = array_values(config('sections.types', []));
    }

    public function render()
    {
        return view('livewire.admin.sections.index')
            ->layout('components.layouts.app')
            ->title('Sections');
    }
}
