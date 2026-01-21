<div class="p-6 max-w-2xl space-y-6">
    <flux:heading size="lg">Create Testimonial</flux:heading>

    <flux:input label="Author" wire:model.defer="author" />

    <flux:textarea
        label="Testimonial"
        wire:model.defer="content"
    />

    <flux:select label="Rating" wire:model.defer="rating">
        @for($i = 5; $i >= 1; $i--)
            <option value="{{ $i }}">{{ $i }}/5</option>
        @endfor
    </flux:select>

    <flux:select label="Status" wire:model.defer="status">
        <option value="draft">Draft</option>
        <option value="published">Published</option>
    </flux:select>

    <flux:button wire:click="save" variant="primary">
        Save
    </flux:button>
</div>
