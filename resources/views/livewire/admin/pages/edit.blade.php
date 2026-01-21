<div class="w-full max-w-3xl mx-auto px-4 sm:px-6 py-8 bg-gray-50 dark:bg-zinc-900">

    {{-- Header --}}
    <div class="mb-10 text-center">
        <div class="inline-flex items-center justify-center h-12 w-12 rounded-xl
                    bg-purple-100 dark:bg-purple-900/30 mb-4 mx-auto">
            <svg class="h-6 w-6 text-purple-600 dark:text-purple-400"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5
                         m-1.414-9.414a2 2 0 112.828 2.828
                         L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
        </div>

        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
            Edit Page
        </h1>

        <p class="text-sm text-gray-500 dark:text-zinc-400 mt-2 max-w-md mx-auto">
            Update page structure, status and visibility.
        </p>
    </div>
    {{-- Global Media Picker --}}
    <livewire:admin.media.picker />
    {{-- Card --}}
    <div class="bg-white dark:bg-zinc-800 rounded-2xl border
                border-gray-200 dark:border-zinc-700 shadow-sm overflow-hidden">

        <form wire:submit.prevent="save" class="p-6 space-y-8">

            {{-- Page Meta --}}
            <div class="flex items-center justify-between pb-4 border-b
                        border-gray-200 dark:border-zinc-700">
                <div>
                    <p class="text-xs text-gray-500 dark:text-zinc-400">Page ID</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">
                        #{{ $page->id }}
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        {{ $page->status === 'published'
                            ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300'
                            : 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300' }}">
                        {{ ucfirst($page->status) }}
                    </span>

                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full
                                 text-xs font-medium bg-blue-100 text-blue-800
                                 dark:bg-blue-900/30 dark:text-blue-300">
                        {{ Str::headline($page->template_key) }}
                    </span>
                </div>
            </div>

            {{-- Full Path --}}
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300">
                    Full path <span class="text-rose-500">*</span>
                </label>

                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2
                                 text-gray-400 dark:text-zinc-500 text-sm">/</span>

                    <input
                        type="text"
                        wire:model.defer="full_path"
                        class="w-full pl-7 rounded-lg border
                               border-gray-300 dark:border-zinc-600
                               bg-white dark:bg-zinc-700
                               px-4 py-2.5 text-sm
                               text-gray-900 dark:text-white
                               focus:ring-2 focus:ring-purple-500
                               focus:border-purple-500 transition"
                        placeholder="kitchen-remodeling/greenwich-ct"
                    >
                </div>

                @error('full_path')
                <p class="text-xs text-rose-600 dark:text-rose-400">
                    {{ $message }}
                </p>
                @enderror
            </div>

            {{-- Status --}}
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300">
                    Status
                </label>

                <select
                    wire:model.defer="status"
                    class="w-full rounded-lg border
                           border-gray-300 dark:border-zinc-600
                           bg-white dark:bg-zinc-700
                           px-4 py-2.5 text-sm
                           text-gray-900 dark:text-white
                           focus:ring-2 focus:ring-purple-500
                           focus:border-purple-500"
                >
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                </select>
            </div>

            {{-- County --}}
            <div class="space-y-2 pt-6 border-t border-gray-200 dark:border-zinc-700">
                <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300">
                    County <span class="text-gray-400">(optional)</span>
                </label>

                <select
                    wire:model.defer="county_id"
                    class="w-full rounded-lg border
                           border-gray-300 dark:border-zinc-600
                           bg-white dark:bg-zinc-700
                           px-4 py-2.5 text-sm
                           text-gray-900 dark:text-white
                           focus:ring-2 focus:ring-purple-500
                           focus:border-purple-500"
                >
                    <option value="">Global (no county)</option>

                    @foreach($counties as $county)
                        <option value="{{ $county->id }}">
                            {{ $county->name }}
                        </option>
                    @endforeach
                </select>

                <p class="text-xs text-gray-500 dark:text-zinc-400">
                    Leave empty to make this page globally visible.
                </p>
            </div>

            {{-- Footer --}}
            <div class="pt-6 border-t border-gray-200 dark:border-zinc-700
                        flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                <p class="text-xs text-gray-500 dark:text-zinc-400">
                    Last updated {{ $page->updated_at->format('M d, Y H:i') }}
                </p>

                <div class="flex gap-3">
                    <flux:button
                        href="{{ route('admin.pages.index') }}"
                        wire:navigate
                        variant="outline"
                    >
                        Cancel
                    </flux:button>

                    <flux:button
                        type="submit"
                        variant="primary"
                        class="bg-purple-600 hover:bg-purple-700"
                    >
                        Update Page
                    </flux:button>
                </div>
            </div>

        </form>
    </div>

    {{-- SEO Warning --}}
    <div class="mt-8 bg-amber-50 dark:bg-amber-900/20
                border border-amber-200 dark:border-amber-800
                rounded-xl p-5 text-sm">
        <p class="font-medium text-amber-800 dark:text-amber-200">
            SEO Notice
        </p>
        <p class="text-amber-700 dark:text-amber-300 mt-1">
            Changing the URL path may require a 301 redirect to preserve rankings.
        </p>
    </div>
    {{-- Page Builder --}}
    <div class="mt-10 bg-white dark:bg-zinc-800 rounded-2xl border
            border-gray-200 dark:border-zinc-700 shadow-sm">

        <div class="p-6 border-b border-gray-200 dark:border-zinc-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                Page Builder
            </h2>

            <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1">
                Manage page sections and their content.
            </p>
        </div>

        <div class="p-6">
            <livewire:admin.pages.sections-builder :page="$page" />
        </div>
    </div>


</div>
