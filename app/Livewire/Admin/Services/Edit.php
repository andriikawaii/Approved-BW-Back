<?php

namespace App\Livewire\Admin\Services;

use Livewire\Component;
use App\Models\Service;
use Illuminate\Validation\Rule;

class Edit extends Component
{
    public Service $service;

    public string $name;
    public string $slug;
    public string $description;
    public string $status;

    public function mount(Service $service)
    {
        $this->service = $service;

        $this->name = $service->name;
        $this->slug = $service->slug;
        $this->description = $service->description ?? '';
        $this->status = $service->status;
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => [
                'required',
                Rule::unique('services', 'slug')->ignore($this->service->id),
            ],
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string',
        ];
    }

    public function save()
    {
        $this->validate();

        $this->service->update([
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'status' => $this->status,
        ]);

        return redirect()->route('admin.services.index');
    }

    public function render()
    {
        return view('livewire.admin.services.edit')
            ->layout('components.layouts.app');
    }
}
