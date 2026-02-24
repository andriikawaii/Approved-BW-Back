<?php

namespace App\Livewire\Admin\Seo;

use App\Models\Setting;
use Livewire\Component;

class Settings extends Component
{
    public string $site_name = '';
    public string $logo_path = '';
    public string $phone_fairfield = '';
    public string $phone_new_haven = '';
    public string $office_address = '';
    public string $default_title_suffix = '';
    public string $canonical_base = '';

    public function mount()
    {
        $this->site_name = $this->get('site_name', 'BuiltWell');
        $this->logo_path = $this->get('logo_path', '/images/logo.png');
        $this->phone_fairfield = $this->get('phone_fairfield', '(203) 919-9616');
        $this->phone_new_haven = $this->get('phone_new_haven', '(203) 466-9148');
        $this->office_address = $this->get('office_address', '206A Boston Post Road, Orange, CT 06477');
        $this->default_title_suffix = $this->get('default_title_suffix', ' | BuiltWell CT');
        $this->canonical_base = $this->get('canonical_base', config('app.frontend_url', config('app.url')));
    }

    public function save()
    {
        $this->validate([
            'site_name' => 'required|string|max:100',
            'logo_path' => 'required|string|max:255',
            'phone_fairfield' => 'required|string|max:30',
            'phone_new_haven' => 'required|string|max:30',
            'office_address' => 'required|string|max:255',
            'default_title_suffix' => 'nullable|string|max:50',
            'canonical_base' => 'required|url|max:255',
        ]);

        foreach ([
            'site_name', 'logo_path', 'phone_fairfield', 'phone_new_haven',
            'office_address', 'default_title_suffix', 'canonical_base',
        ] as $key) {
            Setting::updateOrCreate(['key' => $key], ['value' => $this->{$key}]);
        }

        session()->flash('success', 'SEO settings saved.');
    }

    protected function get(string $key, string $default = ''): string
    {
        return Setting::where('key', $key)->value('value') ?? $default;
    }

    public function render()
    {
        return view('livewire.admin.seo.settings')
            ->layout('components.layouts.app')
            ->title('SEO • Settings');
    }
}
