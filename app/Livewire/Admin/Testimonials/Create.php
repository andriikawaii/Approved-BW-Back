<?php
// app/Livewire/Admin/Testimonials/Create.php

namespace App\Livewire\Admin\Testimonials;

use Livewire\Component;
use App\Models\Testimonial;

class Create extends Component
{
    public string $author_name = '';
    public ?string $author_position = null;
    public ?string $company = null;
    public string $content = '';
    public bool $is_active = true;
    public int $sort_order = 0;

    protected function rules(): array
    {
        return [
            'author_name' => 'required|string|max:255',
            'author_position' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'content' => 'required|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ];
    }

    public function save()
    {
        $this->validate();

        Testimonial::create([
            'author_name' => $this->author_name,
            'author_position' => $this->author_position,
            'company' => $this->company,
            'content' => $this->content,
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
        ]);

        return redirect()->route('admin.testimonials.index');
    }

    public function render()
    {
        return view('livewire.admin.testimonials.create')
            ->layout('components.layouts.app');
    }
}
