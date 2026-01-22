{{-- resources/views/livewire/admin/testimonials/edit.blade.php --}}

<div class="max-w-3xl mx-auto p-6 space-y-6">
    <flux:heading size="lg">Edit Testimonial</flux:heading>

    <flux:input label="Author name" wire:model.defer="author_name" />

    <flux:input label="Author position" wire:model.defer="author_position" />

    <flux:input label="Company" wire:model.defer="company" />

    <flux:textarea label="Testimonial content" wire:model.defer="content" />

    <div class="flex items-center gap-6">
        <flux:input type="number" label="Sort order" wire:model.defer="sort_order" />

        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" wire:model.defer="is_active">
            Active
        </label>
    </div>

    <div class="flex gap-3">
        <flux:button wire:click="save" variant="primary">Update</flux:button>
        <flux:button href="{{ route('admin.testimonials.index') }}" wire:navigate variant="outline">
            Cancel
        </flux:button>
    </div>
</div>
