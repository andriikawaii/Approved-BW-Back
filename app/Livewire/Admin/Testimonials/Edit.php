<?php
namespace App\Livewire\Admin\Testimonials;

use Livewire\Component;
use App\Models\Testimonial;

class Edit extends Component
{
    public Testimonial $testimonial;

    public string $author_name;
    public ?string $author_position;
    public ?string $company;
    public string $content;
    public bool $is_active;
    public int $sort_order;

    public function mount(Testimonial $testimonial): void
    {
        $this->testimonial = $testimonial;

        $this->author_name = $testimonial->author_name;
        $this->author_position = $testimonial->author_position;
        $this->company = $testimonial->company;
        $this->content = $testimonial->content;
        $this->is_active = (bool) $testimonial->is_active;
        $this->sort_order = $testimonial->sort_order;
    }

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

        $this->testimonial->update([
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
        return view('livewire.admin.testimonials.edit')
            ->layout('components.layouts.app');
    }
}
