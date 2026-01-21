<?php

namespace App\Livewire\Admin\Testimonials;

use Livewire\Component;
use App\Models\Testimonial;

class Index extends Component
{
    public function render()
    {
        return view('livewire.admin.testimonials.index', [
            'testimonials' => Testimonial::latest()->get(),
        ])->layout('components.layouts.app');
    }
}
