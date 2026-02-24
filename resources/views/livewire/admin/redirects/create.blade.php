<div class="min-h-screen bg-gray-50 dark:bg-zinc-900">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 py-10">

        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Create Redirect</h1>
                <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1">
                    From path must be a relative path. To can be a path or full URL.
                </p>
            </div>

            <flux:button href="{{ route('admin.redirects.index') }}" wire:navigate variant="outline">
                Back
            </flux:button>
        </div>

        <div class="bg-white dark:bg-zinc-800 rounded-2xl border border-gray-200 dark:border-zinc-700 shadow-sm p-6">
            <form wire:submit.prevent="save" class="space-y-5">

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-zinc-200 mb-1">From path</label>
                    <input
                        type="text"
                        wire:model.defer="from_path"
                        placeholder="/old-path"
                        class="w-full rounded-lg border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-700
                               px-4 py-2.5 text-sm text-gray-900 dark:text-white"
                    />
                    @error('from_path') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-zinc-200 mb-1">To path / URL</label>
                    <input
                        type="text"
                        wire:model.defer="to_path"
                        placeholder="/new-path or https://example.com/new-path"
                        class="w-full rounded-lg border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-700
                               px-4 py-2.5 text-sm text-gray-900 dark:text-white"
                    />
                    @error('to_path') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-zinc-200 mb-1">Type</label>
                        <select
                            wire:model.defer="type"
                            class="w-full rounded-lg border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-700
                                   px-4 py-2.5 text-sm text-gray-900 dark:text-white"
                        >
                            <option value="301">301 (Permanent)</option>
                            <option value="302">302 (Temporary)</option>
                        </select>
                        @error('type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-end">
                        <label class="inline-flex items-center gap-2 text-sm text-gray-700 dark:text-zinc-200">
                            <input type="checkbox" wire:model.defer="is_active" class="rounded border-gray-300 dark:border-zinc-600">
                            Active
                        </label>
                        @error('is_active') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="pt-2 flex items-center gap-3">
                    <flux:button type="submit" variant="primary" class="bg-purple-600 hover:bg-purple-700">
                        Save
                    </flux:button>

                    <flux:button href="{{ route('admin.redirects.index') }}" wire:navigate variant="outline">
                        Cancel
                    </flux:button>
                </div>

            </form>
        </div>
    </div>
</div>
