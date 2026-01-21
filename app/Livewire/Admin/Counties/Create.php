<?php

namespace App\Livewire\Admin\Counties;

use App\Models\County;
use Illuminate\Support\Str;
use Livewire\Component;

class Create extends Component
{
    public string $name = '';
    public string $slug = '';
    public ?string $phone = null;
    public int $sort_order = 0;
    public bool $is_active = true;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:counties,slug'],
            'phone' => ['nullable', 'string', 'max:32'],
            'sort_order' => ['integer'],
            'is_active' => ['boolean'],
        ];
    }

    public function updatedName(): void
    {
        if ($this->slug === '') {
            $this->slug = Str::slug($this->name);
        }
    }

    public function save()
    {
        $this->validate();

        County::create([
            'name' => $this->name,
            'slug' => $this->slug,
            'phone' => $this->phone,
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
        ]);

        return redirect()->route('admin.counties.index');
    }

    public function render()
    {
        return view('livewire.admin.counties.create');
    }
}
