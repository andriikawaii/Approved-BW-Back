<div class="p-6 max-w-2xl space-y-6">
    <flux:heading size="lg">Create Service</flux:heading>

    <flux:input label="Service name" wire:model.defer="name" />
    <flux:input label="Slug" wire:model.defer="slug" />

    <flux:textarea
        label="Description"
        wire:model.defer="description"
        placeholder="Short description for SEO / internal usage"
    />

    <flux:select label="Status" wire:model.defer="status">
        <option value="active">Active</option>
        <option value="inactive">Inactive</option>
    </flux:select>

    <div class="flex gap-2">
        <flux:button wire:click="save" variant="primary">
            Save
        </flux:button>

        <flux:button
            href="{{ route('admin.services.index') }}"
            wire:navigate
            variant="ghost"
        >
            Cancel
        </flux:button>
    </div>
</div>
