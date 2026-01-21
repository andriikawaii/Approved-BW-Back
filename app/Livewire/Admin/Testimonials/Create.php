<?php

namespace App\Livewire\Admin\Testimonials;

use Livewire\Component;
use App\Models\Testimonial;

class Create extends Component
{
    public string $author = '';
    public string $content = '';
    public int $rating = 5;
    public string $status = 'draft';

    protected function rules(): array
    {
        return [
            'author' => 'required|string|max:255',
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'status' => 'required|in:draft,published',
        ];
    }

    public function save()
    {
        $this->validate();

        Testimonial::create([
            'author' => $this->author,
            'content' => $this->content,
            'rating' => $this->rating,
            'status' => $this->status,
        ]);

        return redirect()->route('admin.testimonials.index');
    }

    public function render()
    {
        return view('livewire.admin.testimonials.create')
            ->layout('components.layouts.app');
    }
}
