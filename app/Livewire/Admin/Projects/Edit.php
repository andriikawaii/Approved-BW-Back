<?php

namespace App\Livewire\Admin\Projects;

use Livewire\Component;
use App\Models\Project;
use Illuminate\Validation\Rule;

class Edit extends Component
{
    public Project $project;

    public string $title = '';
    public string $slug = '';
    public string $excerpt = '';
    public string $description = '';
    public string $client = '';
    public bool $is_published = false;
    public ?string $completed_at = null;
    public string $meta_title = '';
    public string $meta_description = '';

    public function mount(Project $project)
    {
        $this->project = $project;

        $this->title = $project->title ?? '';
        $this->slug = $project->slug ?? '';
        $this->excerpt = $project->excerpt ?? '';
        $this->description = $project->description ?? '';
        $this->client = $project->client ?? '';
        $this->is_published = (bool) $project->is_published;
        $this->completed_at = $project->completed_at?->format('Y-m-d');
        $this->meta_title = $project->meta_title ?? '';
        $this->meta_description = $project->meta_description ?? '';
    }

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('projects', 'slug')->ignore($this->project->id),
            ],
            'excerpt' => 'nullable|string',
            'description' => 'nullable|string',
            'client' => 'nullable|string|max:255',
            'is_published' => 'boolean',
            'completed_at' => 'nullable|date',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ];
    }

    public function save()
    {
        $this->validate();

        $this->project->update([
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt ?: null,
            'description' => $this->description ?: null,
            'client' => $this->client ?: null,
            'is_published' => $this->is_published,
            'completed_at' => $this->completed_at ?: null,
            'meta_title' => $this->meta_title ?: null,
            'meta_description' => $this->meta_description ?: null,
        ]);

        session()->flash('success', 'Project updated.');

        return redirect()->route('admin.projects.index');
    }

    public function render()
    {
        return view('livewire.admin.projects.edit')
            ->layout('components.layouts.app');
    }
}
