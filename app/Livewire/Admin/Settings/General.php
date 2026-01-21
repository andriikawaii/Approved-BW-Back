<?php

namespace App\Livewire\Admin\Settings;

use Livewire\Component;
use App\Models\Setting;

class General extends Component
{
    public string $phone_main = '';
    public string $cta_text = '';
    public string $company_name = '';

    public function mount()
    {
        $this->phone_main = Setting::where('key', 'phone_main')->value('value') ?? '';
        $this->cta_text = Setting::where('key', 'cta_text')->value('value') ?? '';
        $this->company_name = Setting::where('key', 'company_name')->value('value') ?? '';
    }

    public function save()
    {
        $this->saveSetting('phone_main', $this->phone_main);
        $this->saveSetting('cta_text', $this->cta_text);
        $this->saveSetting('company_name', $this->company_name);

        session()->flash('success', 'Settings saved.');
    }

    protected function saveSetting(string $key, string $value): void
    {
        Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    public function render()
    {
        return view('livewire.admin.settings.general')
            ->layout('components.layouts.app');
    }
}
