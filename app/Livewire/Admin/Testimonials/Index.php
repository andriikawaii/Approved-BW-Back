<?php

// app/Livewire/Admin/Testimonials/Index.php

namespace App\Livewire\Admin\Testimonials;

use Livewire\Component;
use App\Models\Testimonial;

class Index extends Component
{
    public function toggleActive(int $id): void
    {
        $t = Testimonial::findOrFail($id);
        $t->update(['is_active' => ! $t->is_active]);
    }

    public function render()
    {
        return view('livewire.admin.testimonials.index', [
            'testimonials' => Testimonial::orderBy('sort_order')->get(),
        ])->layout('components.layouts.app');
    }
}
