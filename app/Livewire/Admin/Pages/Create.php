<?php

namespace App\Livewire\Admin\Pages;

use App\Models\County;
use App\Models\Page;
use App\Models\Service;
use App\Models\Town;
use App\Support\Paths\PathNormalizer;
use App\Support\Sections\SeedDefaultSections;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Create extends Component
{
    public string $full_path = '';
    public string $template_key = '';
    public string $status = 'draft';

    public ?int $service_id = null;
    public ?int $county_id = null;
    public ?int $town_id = null;

    // checkbox counties (UI)
    public array $selectedCounties = [];

    protected function rules(): array
    {
        return [
            'full_path' => ['required', 'string', 'max:255'],
            'template_key' => [
                'required',
                Rule::in((array) config('page-templates')),
            ],
            'status' => ['required', 'in:draft,published'],

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

        // ✅ JEDINO MESTO ZA NORMALIZACIJU
        $path = PathNormalizer::normalize($this->full_path);

        // unikatan path check (posle normalizacije!)
        if (Page::where('full_path', $path)->exists()) {
            $this->addError('full_path', 'This path is already taken.');
            return;
        }

        // primarni county (ako koristiš checkbox UI)
        $primaryCountyId = $this->county_id;
        if (!$primaryCountyId && !empty($this->selectedCounties)) {
            $primaryCountyId = (int) $this->selectedCounties[0];
        }

        $page = Page::create([
            'full_path'    => $path,
            'template_key' => $this->template_key,
            'status'       => $this->status,
            'published_at' => $this->status === 'published' ? now() : null,

            'service_id' => $this->service_id,
            'county_id'  => $primaryCountyId ?: null,
            'town_id'    => $this->town_id,

            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        // default sekcije po template-u
        SeedDefaultSections::handle($page);

        return redirect()->route('admin.pages.index');
    }

    public function render()
    {
        return view('livewire.admin.pages.create', [
            'templates' => (array) config('page-templates'),
            'counties'  => County::orderBy('name')->get(),
            'towns'     => Town::with('county')->orderBy('name')->get(),
            'services'  => Service::orderBy('name')->get(),
        ])->layout('components.layouts.app');
    }
}
