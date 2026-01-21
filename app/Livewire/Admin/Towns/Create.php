<?php

namespace App\Livewire\Admin\Towns;

use App\Models\Town;
use App\Models\County;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Create extends Component
{
    public int $county_id;
    public string $name = '';
    public string $slug = '';
    public int $tier = 2;
    public bool $has_hub_page = false;
    public bool $is_active = true;
    public int $sort_order = 0;

    protected function rules(): array
    {
        return [
            'county_id' => ['required', 'exists:counties,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('towns', 'slug')->where('county_id', $this->county_id),
            ],
            'tier' => ['required', 'in:1,2'],
            'has_hub_page' => ['boolean'],
            'is_active' => ['boolean'],
            'sort_order' => ['integer'],
        ];
    }

    public function updatedName(): void
    {
        if ($this->slug === '') {
            $this->slug = Str::slug($this->name);
        }
    }

    public function updatedTier(): void
    {
        if ($this->tier === 2) {
            $this->has_hub_page = false;
        }
    }

    public function save()
    {
        $this->validate();

        Town::create([
            'county_id' => $this->county_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'tier' => $this->tier,
            'has_hub_page' => $this->tier === 1 && $this->has_hub_page,
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
        ]);

        return redirect()->route('admin.towns.index');
    }

    public function render()
    {
        return view('livewire.admin.towns.create', [
            'counties' => County::orderBy('name')->get(),
        ]);
    }
}
