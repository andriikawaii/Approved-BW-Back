<div class="min-h-screen bg-gray-50 dark:bg-zinc-900">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 py-10">

        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">New Redirect</h1>
            <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1">
                Create a redirect (usually 301) to preserve SEO after URL changes.
            </p>
        </div>

        <div class="bg-white dark:bg-zinc-800 rounded-2xl border border-gray-200 dark:border-zinc-700 shadow-sm overflow-hidden">
            <form wire:submit.prevent="save" class="p-6 space-y-6">

                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300">From path</label>
                    <input
                        type="text"
                        wire:model.defer="from_path"
                        placeholder="/old-path"
                        class="w-full rounded-lg border border-gray-300 dark:border-zinc-600
                               bg-white dark:bg-zinc-700 px-4 py-2.5 text-sm
                               text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500"
                    />
                    @error('from_path') <p class="text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300">To path</label>
                    <input
                        type="text"
                        wire:model.defer="to_path"
                        placeholder="/new-path"
                        class="w-full rounded-lg border border-gray-300 dark:border-zinc-600
                               bg-white dark:bg-zinc-700 px-4 py-2.5 text-sm
                               text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500"
                    />
                    @error('to_path') <p class="text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300">Status code</label>
                        <select
                            wire:model.defer="status_code"
                            class="w-full rounded-lg border border-gray-300 dark:border-zinc-600
                                   bg-white dark:bg-zinc-700 px-4 py-2.5 text-sm
                                   text-gray-900 dark:text-white"
                        >
                            <option value="301">301 (Permanent)</option>
                            <option value="302">302 (Temporary)</option>
                            <option value="307">307 (Temporary)</option>
                            <option value="308">308 (Permanent)</option>
                        </select>
                        @error('status_code') <p class="text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300">Active</label>
                        <select
                            wire:model.defer="is_active"
                            class="w-full rounded-lg border border-gray-300 dark:border-zinc-600
                                   bg-white dark:bg-zinc-700 px-4 py-2.5 text-sm
                                   text-gray-900 dark:text-white"
                        >
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                        @error('is_active') <p class="text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-200 dark:border-zinc-700 flex justify-between">
                    <flux:button href="{{ route('admin.redirects.index') }}" wire:navigate variant="outline">
                        Cancel
                    </flux:button>

                    <flux:button type="submit" variant="primary" class="bg-purple-600 hover:bg-purple-700">
                        Create Redirect
                    </flux:button>
                </div>

            </form>
        </div>
    </div>
</div>
