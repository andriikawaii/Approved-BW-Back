<?php

namespace App\Livewire\Admin\Pages;

use App\Http\Controllers\Api\PageController as ApiPageController;
use App\Models\County;
use App\Models\Page;
use App\Models\Service;
use App\Models\Town;
use App\Support\Paths\PathNormalizer;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Edit extends Component
{
    public Page $page;

    public string $full_path;
    public string $template_key;
    public string $status;
    public ?string $published_at = null;

    public string $tab = 'builder';

    public ?int $service_id = null;
    public ?int $county_id = null;
    public ?int $town_id = null;

    public function mount(Page $page): void
    {
        $this->page = $page->load('sections');

        $this->full_path = ltrim($page->full_path, '/');
        $this->template_key = $page->template_key;
        $this->status = $page->status;
        $this->published_at = $page->published_at?->format('Y-m-d\TH:i');

        $this->service_id = $page->service_id;
        $this->county_id  = $page->county_id;
        $this->town_id    = $page->town_id;
    }

    protected function rules(): array
    {
        return [
            'full_path' => ['required', 'string', 'max:255'],
            'template_key' => [
                'required',
                Rule::in($this->availableTemplates()),
            ],
            'status' => ['required', 'in:draft,published'],
            'published_at' => ['nullable', 'date'],

            'service_id' => ['nullable', 'exists:services,id'],
            'county_id'  => ['nullable', 'exists:counties,id'],
            'town_id'    => ['nullable', 'exists:towns,id'],
        ];
    }

    protected function ensureUniquePath(string $path): void
    {
        $exists = Page::query()
            ->where('full_path', $path)
            ->whereKeyNot($this->page->id)
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'full_path' => 'This path is already taken.',
            ]);
        }
    }

    public function save()
    {
        $this->validate();

        $oldPath = $this->page->full_path;
        $newPath = PathNormalizer::normalize($this->full_path);
        $publishedAt = null;

        if ($this->status === 'published') {
            $publishedAt = $this->published_at
                ? Carbon::parse($this->published_at)
                : ($this->page->published_at ?? now());
        }

        $this->ensureUniquePath($newPath);

        $this->page->update([
            'full_path'    => $newPath,
            'template_key' => $this->template_key,
            'status'       => $this->status,
            'service_id'   => $this->service_id,
            'county_id'    => $this->county_id,
            'town_id'      => $this->town_id,
            'published_at' => $publishedAt,
            'updated_by'   => auth()->id(),
        ]);

        ApiPageController::forgetCacheForPath($oldPath);
        ApiPageController::forgetCacheForPath($newPath);

        return redirect()->route('admin.pages.index');
    }

    public function getTemplateDescriptionsProperty(): array
    {
        return [
            'home' => 'Home Page - used for your primary homepage with broad company messaging.',
            'service_global' => 'Service Global Page - used for a service overview across all locations.',
            'service_county' => 'Service County Page - used for service pages targeting a specific county.',
            'service_town' => 'Service Town Page - used for service pages inside a specific town.',
            'county_hub' => 'County Hub Page - used as a location hub linking to services and towns.',
            'office' => 'Office Page - used for a physical office location page.',
            'about' => 'About Page - used for company profile, values, and trust-building content.',
            'faq' => 'FAQ Page - used for grouped question and answer content.',
            'contact' => 'Contact Page - used for contact details and primary lead actions.',
            'case_study' => 'Case Study Page - used for project story, process, and outcomes.',
            'consultation' => 'Consultation Page - used for lead capture and appointment requests.',
        ];
    }

    public function getTemplateDescriptionProperty(): string
    {
        return $this->templateDescriptions[$this->template_key]
            ?? 'Template controls layout rules and allowed section types for this page.';
    }

    public function getUrlDepthProperty(): int
    {
        $path = PathNormalizer::normalize($this->full_path);
        $segments = array_filter(explode('/', trim($path, '/')));

        return count($segments);
    }

    public function getFooterVariantProperty(): string
    {
        if ($this->isOrangeOfficePath()) {
            return 'D';
        }

        return match ($this->resolvedCountySlug()) {
            'fairfield-county' => 'B',
            'new-haven-county' => 'C',
            default => 'A',
        };
    }

    public function getPhoneLogicProperty(): string
    {
        return match ($this->resolvedCountySlug()) {
            'fairfield-county' => 'Automatic: single Fairfield County number',
            'new-haven-county' => 'Automatic: single New Haven County number',
            default => 'Automatic: both county phone numbers',
        };
    }

    public function getPublishStatusLabelProperty(): string
    {
        return $this->status === 'published' ? 'Published' : 'Draft';
    }

    protected function availableTemplates(): array
    {
        $templateSections = array_keys((array) config('page-template-sections', []));
        $baseTemplates = (array) config('page-templates', []);
        $templates = array_values(array_unique(array_merge($templateSections, $baseTemplates)));

        sort($templates);

        return $templates;
    }

    protected function isOrangeOfficePath(): bool
    {
        $path = rtrim(PathNormalizer::normalize($this->full_path), '/') . '/';

        return $path === '/new-haven-county/orange-ct/';
    }

    protected function resolvedCountySlug(): ?string
    {
        if (!$this->county_id) {
            return null;
        }

        return County::query()->whereKey($this->county_id)->value('slug');
    }

    public function render()
    {
        $templates = $this->availableTemplates();

        return view('livewire.admin.pages.edit', [
            'counties' => County::orderBy('name')->get(),
            'services' => Service::orderBy('name')->get(),
            'towns'    => Town::orderBy('name')->get(),
            'templates' => $templates,
        ])->layout('components.layouts.app');
    }
}
