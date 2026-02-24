<?php

namespace App\Livewire\Admin\Projects;

use Livewire\Component;
use App\Models\Project;
use Illuminate\Support\Str;

class Create extends Component
{
    public string $title = '';
    public string $slug = '';
    public string $excerpt = '';
    public string $description = '';
    public string $client = '';
    public bool $is_published = false;
    public ?string $completed_at = null;
    public string $meta_title = '';
    public string $meta_description = '';

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:projects,slug',
            'excerpt' => 'nullable|string',
            'description' => 'nullable|string',
            'client' => 'nullable|string|max:255',
            'is_published' => 'boolean',
            'completed_at' => 'nullable|date',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
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
            'excerpt' => $this->excerpt ?: null,
            'description' => $this->description ?: null,
            'client' => $this->client ?: null,
            'is_published' => $this->is_published,
            'completed_at' => $this->completed_at ?: null,
            'meta_title' => $this->meta_title ?: null,
            'meta_description' => $this->meta_description ?: null,
        ]);

        return redirect()->route('admin.projects.index');
    }

    public function render()
    {
        return view('livewire.admin.projects.create')
            ->layout('components.layouts.app');
    }
}
