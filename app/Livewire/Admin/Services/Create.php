<?php

namespace App\Livewire\Admin\Services;

use Livewire\Component;
use App\Models\Service;
use Illuminate\Support\Str;

class Create extends Component
{
    public string $name = '';
    public string $slug = '';
    public string $description = '';
    public string $status = 'active';

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|unique:services,slug',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string',
        ];
    }

    public function updatedName()
    {
        $this->slug = Str::slug($this->name);
    }

    public function save()
    {
        $this->validate();

        Service::create([
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'status' => $this->status,
        ]);

        return redirect()->route('admin.services.index');
    }

    public function render()
    {
        return view('livewire.admin.services.create')
            ->layout('components.layouts.app');
    }
}
