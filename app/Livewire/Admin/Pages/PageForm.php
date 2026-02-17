<?php

namespace App\Livewire\Admin\Pages;

use App\Models\County;
use App\Models\MediaAsset;
use App\Models\Page;
use App\Models\Service;
use App\Models\Town;
use App\Support\Paths\PathNormalizer;
use Livewire\Component;
use Illuminate\Validation\Rule;

class PageForm extends Component
{
    public Page $page;

    public bool $isEdit = false;

    protected function rules(): array
    {
        return [
            'page.full_path' => [
                'required',
                'string',
                'max:255',
                Rule::unique('pages', 'full_path')->ignore($this->page->id),
            ],

            'page.template_key' => [
                'required',
                'string',
                'max:100',
                Rule::in($this->availableTemplates),
            ],

            'page.status' => ['required', Rule::in(['draft', 'published'])],

            'page.published_at' => ['nullable', 'date'],

            'page.service_id' => ['nullable', 'exists:services,id'],
            'page.county_id' => ['nullable', 'exists:counties,id'],
            'page.town_id' => ['nullable', 'exists:towns,id'],

            'page.hero_media_id' => ['nullable', 'exists:media,id'],

            'page.seo_title' => ['nullable', 'required_if:page.status,published', 'string', 'max:255'],
            'page.seo_description' => ['nullable', 'required_if:page.status,published', 'string', 'max:500'],
            'page.canonical_url' => ['nullable', 'url'],

            'page.schema_type' => ['nullable', 'string', 'max:100'],
            'page.schema_overrides' => ['nullable', 'json'],

            'page.content' => ['nullable', 'json'],
        ];
    }

    public function mount(?Page $page = null): void
    {
        if ($page && $page->exists) {
            $this->page = $page;
            $this->isEdit = true;
        } else {
            $this->page = new Page([
                'status' => 'draft',
            ]);
        }
    }

    public function save()
    {
        $this->validate();

        $this->page->full_path = PathNormalizer::normalize($this->page->full_path);

        $this->page->save();

        session()->flash('success', 'Page saved successfully.');

        return redirect()->route('admin.pages.edit', $this->page);
    }

    protected function normalizePath(string $path): string
    {
        return PathNormalizer::normalize($path);
    }

    public function getAvailableTemplatesProperty(): array
    {
        $templateSections = array_keys((array) config('page-template-sections', []));
        $baseTemplates = (array) config('page-templates', []);
        $templates = array_values(array_unique(array_merge($templateSections, $baseTemplates)));
        sort($templates);

        return $templates;
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
        $templateKey = (string) ($this->page->template_key ?? '');

        return $this->templateDescriptions[$templateKey]
            ?? 'Template controls layout rules and allowed section types for this page.';
    }

    public function getUrlDepthProperty(): int
    {
        $path = PathNormalizer::normalize((string) ($this->page->full_path ?? '/'));
        $segments = array_filter(explode('/', trim($path, '/')));

        return count($segments);
    }

    public function getFooterVariantProperty(): string
    {
        $path = rtrim(PathNormalizer::normalize((string) ($this->page->full_path ?? '/')), '/') . '/';
        if ($path === '/new-haven-county/orange-ct/') {
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

    public function getRenderingPreviewProperty(): array
    {
        return [
            'template' => $this->page->template_key ? \Illuminate\Support\Str::headline($this->page->template_key) : 'Not set',
            'footer_variant' => $this->footerVariant,
            'phone_logic' => $this->phoneLogic,
            'url_depth' => $this->urlDepth,
            'publish_status' => ucfirst((string) ($this->page->status ?? 'draft')),
        ];
    }

    protected function resolvedCountySlug(): ?string
    {
        $countyId = $this->page->county_id ?? null;
        if (!$countyId) {
            return null;
        }

        return County::query()->whereKey($countyId)->value('slug');
    }

    public function render()
    {
        return view('livewire.admin.pages.page-form', [
            'services' => Service::orderBy('name')->get(),
            'counties' => County::orderBy('name')->get(),
            'towns' => Town::orderBy('name')->get(),
            'media' => MediaAsset::orderBy('id', 'desc')->limit(50)->get(),
        ]);
    }
}
