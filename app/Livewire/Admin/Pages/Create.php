<?php

namespace App\Livewire\Admin\Pages;

use App\Models\County;
use App\Models\Page;
use App\Models\Service;
use App\Models\Town;
use App\Support\Sections\SeedDefaultSections;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class Create extends Component
{
    public string $full_path = '';
    public string $template_key = '';
    public string $status = 'draft';

    // optional references (per spec)
    public ?int $service_id = null;
    public ?int $county_id = null;
    public ?int $town_id = null;

    // optional multi-select (ako koristiš checkbox listu u UI)
    public array $selectedCounties = [];

    protected function rules(): array
    {
        return [
            'full_path' => 'required|unique:pages,full_path',
            'template_key' => [
                'required',
                Rule::in((array) config('page-templates')),
            ],
            'status' => 'required|in:draft,published',

            'service_id' => ['nullable', 'integer', 'exists:services,id'],
            'county_id'  => ['nullable', 'integer', 'exists:counties,id'],
            'town_id'    => ['nullable', 'integer', 'exists:towns,id'],

            'selectedCounties' => ['array'],
            'selectedCounties.*' => ['integer', 'exists:counties,id'],
        ];
    }

    public function save()
    {
        $this->validate();

        $path = '/' . trim(Str::slug($this->full_path, '/'), '/');

        $primaryCountyId = $this->county_id;
        if (! $primaryCountyId && ! empty($this->selectedCounties)) {
            $primaryCountyId = (int) $this->selectedCounties[0];
        }

        $page = Page::create([
            'full_path'     => $path,
            'template_key'  => $this->template_key,
            'status'        => $this->status,
            'published_at'  => $this->status === 'published' ? now() : null,
            'service_id'    => $this->service_id,
            'county_id'     => $primaryCountyId ?: null,
            'town_id'       => $this->town_id,
            'created_by'    => auth()->id(),
            'updated_by'    => auth()->id(),
        ]);

        SeedDefaultSections::handle($page);

        return redirect()->route('admin.pages.index');
    }


    public function render()
    {
        return view('livewire.admin.pages.create', [
            'templates' => (array) config('page-templates'),

            // za tvoj blade (da ne puca foreach)
            'counties'  => County::orderBy('name')->get(),
            'towns'     => Town::with('county')->orderBy('name')->get(),
            'services'  => Service::orderBy('name')->get(),
        ])->layout('components.layouts.app');
    }
}
