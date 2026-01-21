<?php

namespace App\Livewire\Admin\Projects;

use Livewire\Component;
use App\Models\Project;
use Illuminate\Validation\Rule;

class Edit extends Component
{
    public Project $project;

    public string $title;
    public string $slug;
    public string $location;
    public string $status;
    public string $excerpt;

    public function mount(Project $project)
    {
        $this->project = $project;

        $this->title = $project->title;
        $this->slug = $project->slug;
        $this->location = $project->location ?? '';
        $this->status = $project->status;
        $this->excerpt = $project->excerpt ?? '';
    }

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => [
                'required',
                Rule::unique('projects', 'slug')->ignore($this->project->id),
            ],
            'status' => 'required|in:draft,published',
            'excerpt' => 'nullable|string',
            'location' => 'nullable|string|max:255',
        ];
    }

    public function save()
    {
        $this->validate();

        $this->project->update([
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'location' => $this->location,
            'status' => $this->status,
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('admin.projects.index');
    }

    public function render()
    {
        return view('livewire.admin.projects.edit')
            ->layout('components.layouts.app');
    }
}
