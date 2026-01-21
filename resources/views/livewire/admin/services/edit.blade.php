<div class="p-6 max-w-2xl space-y-6">
    <flux:heading size="lg">Edit Service</flux:heading>

    <flux:input label="Service name" wire:model.defer="name" />
    <flux:input label="Slug" wire:model.defer="slug" />

    <flux:textarea label="Description" wire:model.defer="description" />

    <flux:select label="Status" wire:model.defer="status">
        <option value="active">Active</option>
        <option value="inactive">Inactive</option>
    </flux:select>

    <div class="flex gap-2">
        <flux:button wire:click="save" variant="primary">
            Update
        </flux:button>

        <flux:button
            href="{{ route('admin.services.index') }}"
            wire:navigate
            variant="ghost"
        >
            Back
        </flux:button>
    </div>
</div>
