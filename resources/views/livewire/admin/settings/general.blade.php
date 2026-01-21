<div class="p-6 max-w-2xl space-y-6">
    <flux:heading size="lg">General Settings</flux:heading>

    <flux:input label="Company name" wire:model.defer="company_name" />
    <flux:input label="Main phone" wire:model.defer="phone_main" />
    <flux:input label="CTA text" wire:model.defer="cta_text" />

    <flux:button wire:click="save" variant="primary">
        Save Settings
    </flux:button>

    @if (session()->has('success'))
        <flux:text class="text-green-600">
            {{ session('success') }}
        </flux:text>
    @endif
</div>
