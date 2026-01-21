<div class="w-full bg-gray-50 dark:bg-zinc-900 min-h-screen">

    {{-- Global Media Picker --}}
    <livewire:admin.media.picker />

    {{-- ===================== --}}
    {{-- PAGE HEADER --}}
    {{-- ===================== --}}
    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-10">

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
                Manage URL, SEO and page content.
            </p>
        </div>

        {{-- ===================== --}}
        {{-- PAGE SETTINGS CARD --}}
        {{-- ===================== --}}
        <div class="bg-white dark:bg-zinc-800 rounded-2xl border
                    border-gray-200 dark:border-zinc-700 shadow-sm overflow-hidden">

            <form wire:submit.prevent="save" class="p-6 space-y-8">

                {{-- Meta --}}
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

                {{-- Full path --}}
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
                                   focus:ring-2 focus:ring-purple-500"
                            placeholder="kitchen-remodeling/greenwich-ct"
                        >
                    </div>
                </div>

                {{-- Status --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t
                            border-gray-200 dark:border-zinc-700">

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
                                   text-gray-900 dark:text-white">
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300">
                            County (optional)
                        </label>

                        <select
                            wire:model.defer="county_id"
                            class="w-full rounded-lg border
                                   border-gray-300 dark:border-zinc-600
                                   bg-white dark:bg-zinc-700
                                   px-4 py-2.5 text-sm
                                   text-gray-900 dark:text-white">
                            <option value="">Global</option>
                            @foreach($counties as $county)
                                <option value="{{ $county->id }}">{{ $county->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="pt-6 border-t border-gray-200 dark:border-zinc-700
                            flex justify-between items-center">

                    <p class="text-xs text-gray-500 dark:text-zinc-400">
                        Last updated {{ $page->updated_at->format('M d, Y H:i') }}
                    </p>

                    <div class="flex gap-3">
                        <flux:button
                            href="{{ route('admin.pages.index') }}"
                            wire:navigate
                            variant="outline">
                            Cancel
                        </flux:button>

                        {{-- Preview (opens in new tab) --}}
                        <a
                            href="{{ route('admin.pages.preview', $page) }}"
                            target="_blank"
                            rel="noopener"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium
               border border-gray-300 dark:border-zinc-600
               bg-white dark:bg-zinc-800
               text-gray-900 dark:text-white
               hover:bg-gray-50 dark:hover:bg-zinc-700 transition"
                            title="Open frontend preview in a new tab"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7
                     -1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Preview
                        </a>

                        <flux:button
                            type="submit"
                            variant="primary"
                            class="bg-purple-600 hover:bg-purple-700">
                            Update Page
                        </flux:button>
                    </div>
                </div>

            </form>
        </div>

        {{-- ===================== --}}
        {{-- SEO SETTINGS --}}
        {{-- ===================== --}}
        <div class="mt-10 bg-white dark:bg-zinc-800 rounded-2xl border
                    border-gray-200 dark:border-zinc-700 shadow-sm">

            <div class="p-6 border-b border-gray-200 dark:border-zinc-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                    SEO Settings
                </h2>
                <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1">
                    Google preview and meta information.
                </p>
            </div>

            <div class="p-6">
                <livewire:admin.pages.page-seo-editor :page="$page" />
            </div>
        </div>
    </div>

    {{-- ===================== --}}
    {{-- PAGE BUILDER – FULL WIDTH --}}
    {{-- ===================== --}}
    <div class="mt-16 border-t border-gray-200 dark:border-zinc-800">

        <div class="px-6 py-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                Page Builder
            </h2>
            <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1">
                Build and reorder page sections.
            </p>
        </div>

        <div class="px-4 sm:px-6 lg:px-8 pb-16">
            <livewire:admin.pages.sections-builder :page="$page" />
        </div>
    </div>

</div>
