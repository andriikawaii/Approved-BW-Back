<div class="p-6 md:p-8 space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">SEO Settings</h1>
        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">
            Company-level SEO defaults used by schema generators, canonical URLs, and phone resolvers.
        </p>
    </div>

    @if (session('success'))
        <div class="rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 px-4 py-3 text-sm text-green-700 dark:text-green-400">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit="save" class="space-y-6">

        {{-- Company Info --}}
        <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5 space-y-4">
            <h2 class="text-sm font-semibold text-zinc-900 dark:text-white">Company Information</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Site Name</label>
                    <input type="text" wire:model="site_name" class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-3 py-2 text-sm text-zinc-900 dark:text-white" />
                    @error('site_name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Logo Path</label>
                    <input type="text" wire:model="logo_path" class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-3 py-2 text-sm text-zinc-900 dark:text-white" />
                    @error('logo_path') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Office Address</label>
                <input type="text" wire:model="office_address" class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-3 py-2 text-sm text-zinc-900 dark:text-white" />
                @error('office_address') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
        </div>

        {{-- Phone Numbers --}}
        <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5 space-y-4">
            <h2 class="text-sm font-semibold text-zinc-900 dark:text-white">Phone Numbers</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Fairfield County</label>
                    <input type="text" wire:model="phone_fairfield" class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-3 py-2 text-sm text-zinc-900 dark:text-white" />
                    @error('phone_fairfield') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">New Haven County</label>
                    <input type="text" wire:model="phone_new_haven" class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-3 py-2 text-sm text-zinc-900 dark:text-white" />
                    @error('phone_new_haven') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        {{-- SEO Defaults --}}
        <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5 space-y-4">
            <h2 class="text-sm font-semibold text-zinc-900 dark:text-white">SEO Defaults</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Default Title Suffix</label>
                    <input type="text" wire:model="default_title_suffix" placeholder=" | BuiltWell CT" class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-3 py-2 text-sm text-zinc-900 dark:text-white" />
                    @error('default_title_suffix') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Canonical Base URL</label>
                    <input type="text" wire:model="canonical_base" class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-3 py-2 text-sm text-zinc-900 dark:text-white" />
                    @error('canonical_base') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="rounded-lg bg-zinc-900 dark:bg-white px-5 py-2.5 text-sm font-medium text-white dark:text-zinc-900 hover:bg-zinc-800 dark:hover:bg-zinc-100 transition">
                Save Settings
            </button>
        </div>
    </form>
</div>
