<div class="max-w-3xl mx-auto p-6 space-y-6">
    <div class="flex items-center justify-between">
        <flux:heading size="lg">Add Testimonial</flux:heading>
        <flux:button href="{{ route('admin.testimonials.index') }}" wire:navigate variant="ghost">Back</flux:button>
    </div>

    <form wire:submit="save" class="space-y-5">
        <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5 space-y-4">
            <h2 class="text-sm font-semibold text-zinc-900 dark:text-white">Author</h2>

            <flux:input label="Name" wire:model="author_name" placeholder="e.g. Michael R." required />
            @error('author_name') <p class="text-xs text-red-500">{{ $message }}</p> @enderror

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <flux:input label="Position / Title" wire:model="author_position" placeholder="e.g. Homeowner" />
                </div>
                <div>
                    <flux:input label="Company" wire:model="company" placeholder="Optional" />
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5 space-y-4">
            <h2 class="text-sm font-semibold text-zinc-900 dark:text-white">Review</h2>

            <flux:textarea label="Testimonial Content" wire:model="content" placeholder="The full testimonial quote..." rows="4" required />
            @error('content') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5 space-y-4">
            <h2 class="text-sm font-semibold text-zinc-900 dark:text-white">Display</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:input type="number" label="Sort Order" wire:model="sort_order" min="0" />
                <div class="flex items-end pb-2">
                    <label class="flex items-center gap-2 text-sm font-medium text-zinc-900 dark:text-white">
                        <input type="checkbox" wire:model="is_active" class="rounded border-zinc-300 dark:border-zinc-600" checked>
                        Active (visible on site)
                    </label>
                </div>
            </div>
            <p class="text-xs text-zinc-500 dark:text-zinc-400">Lower sort order appears first. Inactive testimonials are hidden from the public site.</p>
        </div>

        <div class="flex gap-3">
            <flux:button type="submit" variant="primary">Save</flux:button>
            <flux:button href="{{ route('admin.testimonials.index') }}" wire:navigate variant="ghost">Cancel</flux:button>
        </div>
    </form>
</div>
