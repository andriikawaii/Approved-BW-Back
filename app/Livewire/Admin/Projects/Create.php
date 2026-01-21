<?php

namespace App\Livewire\Admin\Projects;

use Livewire\Component;
use App\Models\Project;
use Illuminate\Support\Str;

class Create extends Component
{
    public string $title = '';
    public string $slug = '';
    public string $location = '';
    public string $status = 'draft';
    public string $excerpt = '';

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => 'required|unique:projects,slug',
            'status' => 'required|in:draft,published',
            'excerpt' => 'nullable|string',
            'location' => 'nullable|string|max:255',
        ];
    }

    public function updatedTitle()
    {
        $this->slug = Str::slug($this->title);
    }

    public function save()
    {
        $this->validate();

        Project::create([
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'location' => $this->location,
            'status' => $this->status,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('admin.projects.index');
    }

    public function render()
    {
        return view('livewire.admin.projects.create')
            ->layout('components.layouts.app');
    }
}
