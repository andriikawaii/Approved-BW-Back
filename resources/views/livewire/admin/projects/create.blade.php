<div class="p-6 max-w-3xl space-y-6">
    <div class="flex items-center justify-between">
        <flux:heading size="lg">Create Case Study</flux:heading>
        <flux:button href="{{ route('admin.projects.index') }}" wire:navigate variant="ghost">
            Back
        </flux:button>
    </div>

    <form wire:submit="save" class="space-y-5">
        {{-- Core --}}
        <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5 space-y-4">
            <h2 class="text-sm font-semibold text-zinc-900 dark:text-white">Project Details</h2>

            <flux:input label="Title" wire:model.live="title" placeholder="e.g. Kitchen Remodel in Greenwich" required />
            @error('title') <p class="text-xs text-red-500">{{ $message }}</p> @enderror

            <flux:input label="Slug" wire:model="slug" placeholder="auto-generated from title" required />
            @error('slug') <p class="text-xs text-red-500">{{ $message }}</p> @enderror

            <flux:input label="Client" wire:model="client" placeholder="Client name (optional)" />

            <flux:input type="date" label="Completed" wire:model="completed_at" />

            <flux:textarea label="Short Excerpt" wire:model="excerpt" placeholder="Brief summary for cards and search results" rows="2" />

            <flux:textarea label="Full Description" wire:model="description" placeholder="Detailed project write-up" rows="6" />
        </div>

        {{-- SEO --}}
        <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5 space-y-4">
            <h2 class="text-sm font-semibold text-zinc-900 dark:text-white">SEO</h2>

            <flux:input label="Meta Title" wire:model="meta_title" placeholder="Leave blank to use project title" />
            <flux:textarea label="Meta Description" wire:model="meta_description" placeholder="Short description for search engines (max 160 chars)" rows="2" />
        </div>

        {{-- Status --}}
        <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5">
            <label class="flex items-center gap-3 text-sm font-medium text-zinc-900 dark:text-white">
                <input type="checkbox" wire:model="is_published" class="rounded border-zinc-300 dark:border-zinc-600">
                Published
            </label>
            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">Unpublished projects are hidden from the public site.</p>
        </div>

        <div class="flex gap-3">
            <flux:button type="submit" variant="primary">Save</flux:button>
            <flux:button href="{{ route('admin.projects.index') }}" wire:navigate variant="ghost">Cancel</flux:button>
        </div>
    </form>
</div>
