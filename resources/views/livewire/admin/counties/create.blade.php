<div class="max-w-2xl mx-auto px-6 py-10 space-y-8">

    {{-- Header --}}
    <div class="space-y-1">
        <h1 class="text-3xl font-semibold text-zinc-900 dark:text-zinc-100">
            Create County
        </h1>
        <p class="text-sm admin-muted">
            Add a new county used for service and location-based pages.
        </p>
    </div>
    <br><br>
    {{-- Form Card --}}
    <div class="admin-surface px-8 py-7 space-y-6">

        <form wire:submit.prevent="save" class="space-y-5">

            {{-- Fields --}}
            <x-input
                label="County name"
                wire:model.defer="name"
                required
            />

            <x-input
                label="Slug"
                wire:model.defer="slug"
                hint="Used in URLs. Lowercase, hyphen-separated."
                required
            />

            <x-input
                label="Phone"
                wire:model.defer="phone"
                hint="Optional. Displayed on location-based pages."
            />

            <x-input
                label="Sort order"
                type="number"
                wire:model.defer="sort_order"
                hint="Lower numbers appear first."
            />

            {{-- Active toggle --}}
            <label class="flex items-center justify-between rounded-lg border px-4 py-3
                border-zinc-200 dark:border-zinc-700 cursor-pointer">

                <div>
                    <div class="font-medium text-zinc-900 dark:text-zinc-100">
                        Active
                    </div>
                    <div class="text-sm admin-muted">
                        Inactive counties will not be used on public pages.
                    </div>
                </div>

                <input
                    type="checkbox"
                    wire:model.defer="is_active"
                    class="h-5 w-5 rounded border-zinc-300 dark:border-zinc-600"
                />
            </label>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 pt-4">

                <a
                    href="{{ route('admin.counties.index') }}"
                    class="px-4 py-2 rounded-md border border-zinc-300 dark:border-zinc-600
                           text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800"
                >
                    Cancel
                </a>

                <flux:button type="submit" variant="primary">
                    Save County
                </flux:button>

            </div>
        </form>
    </div>
</div>
