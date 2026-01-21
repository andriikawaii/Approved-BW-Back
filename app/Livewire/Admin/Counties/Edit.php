<?php

namespace App\Livewire\Admin\Counties;

use App\Models\County;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Edit extends Component
{
    public County $county;

    public string $name;
    public string $slug;
    public ?string $phone;
    public int $sort_order;
    public bool $is_active;

    public function mount(County $county): void
    {
        $this->county = $county;

        $this->fill($county->only([
            'name',
            'slug',
            'phone',
            'sort_order',
            'is_active',
        ]));
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('counties', 'slug')->ignore($this->county->id),
            ],
            'phone' => ['nullable', 'string', 'max:32'],
            'sort_order' => ['integer'],
            'is_active' => ['boolean'],
        ];
    }

    public function save()
    {
        $this->validate();

        $this->county->update([
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
        return view('livewire.admin.counties.edit');
    }
}
