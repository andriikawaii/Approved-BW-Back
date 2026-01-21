<div class="p-6 max-w-2xl space-y-6">
    <flux:heading size="lg">Edit Project</flux:heading>

    <flux:input label="Title" wire:model.defer="title" />
    <flux:input label="Slug" wire:model.defer="slug" />
    <flux:input label="Location" wire:model.defer="location" />

    <flux:textarea label="Excerpt" wire:model.defer="excerpt" />

    <flux:select label="Status" wire:model.defer="status">
        <option value="draft">Draft</option>
        <option value="published">Published</option>
    </flux:select>

    <div class="flex gap-2">
        <flux:button wire:click="save" variant="primary">
            Update
        </flux:button>

        <flux:button
            href="{{ route('admin.projects.index') }}"
            wire:navigate
            variant="ghost"
        >
            Back
        </flux:button>
    </div>
</div>
