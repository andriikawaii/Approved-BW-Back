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
                Manage URL, publishing, relations and content.
            </p>
        </div>

        {{-- ===================== --}}
        {{-- PAGE SETTINGS --}}
        {{-- ===================== --}}
        <div class="bg-white dark:bg-zinc-800 rounded-2xl border
                    border-gray-200 dark:border-zinc-700 shadow-sm overflow-hidden">

            <form wire:submit.prevent="save" class="p-6 space-y-8">

                {{-- META --}}
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

                {{-- FULL PATH --}}
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700 dark:text-zinc-300">
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
                        >
                    </div>
                </div>

                {{-- STATUS + PUBLISHED AT --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t
                            border-gray-200 dark:border-zinc-700">
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-zinc-300">
                            Status
                        </label>
                        <select
                            wire:model.defer="status"
                            class="w-full rounded-lg border
           border-gray-300 dark:border-zinc-600
           bg-white dark:bg-zinc-700
           px-4 py-2.5 text-sm
           text-gray-900 dark:text-white
           focus:ring-2 focus:ring-purple-500 focus:border-purple-500
           appearance-none">
                            <option value="draft" class="bg-white text-gray-900 dark:bg-zinc-800 dark:text-white">Draft</option>
                            <option value="published" class="bg-white text-gray-900 dark:bg-zinc-800 dark:text-white">Published</option>
                        </select>

                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-zinc-300">
                            Publish at (optional)
                        </label>
                        <input
                            type="datetime-local"
                            wire:model.defer="published_at"
                            class="w-full rounded-lg border px-4 py-2.5 text-sm">
                    </div>
                </div>

                {{-- RELATIONS --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-4 border-t
                            border-gray-200 dark:border-zinc-700">
                    <div>
                        <label class="text-sm font-medium">Service</label>
                        <select
                            wire:model.defer="service_id"
                            class="w-full rounded-lg border
           border-gray-300 dark:border-zinc-600
           bg-white dark:bg-zinc-700
           px-4 py-2.5 text-sm
           text-gray-900 dark:text-white
           focus:ring-2 focus:ring-purple-500 focus:border-purple-500
           appearance-none">
                            <option value="" class="bg-white text-gray-900 dark:bg-zinc-800 dark:text-white">— None —</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" class="bg-white text-gray-900 dark:bg-zinc-800 dark:text-white">
                                    {{ $service->name }}
                                </option>
                            @endforeach
                        </select>

                    </div>

                    <div>
                        <label class="text-sm font-medium">County</label>
                        <select
                            wire:model.defer="county_id"
                            class="w-full rounded-lg border
           border-gray-300 dark:border-zinc-600
           bg-white dark:bg-zinc-700
           px-4 py-2.5 text-sm
           text-gray-900 dark:text-white
           focus:ring-2 focus:ring-purple-500 focus:border-purple-500
           appearance-none">
                            <option value="" class="bg-white text-gray-900 dark:bg-zinc-800 dark:text-white">Global</option>
                            @foreach($counties as $county)
                                <option value="{{ $county->id }}" class="bg-white text-gray-900 dark:bg-zinc-800 dark:text-white">
                                    {{ $county->name }}
                                </option>
                            @endforeach
                        </select>

                    </div>

                    <div>
                        <label class="text-sm font-medium">Town</label>
                        <select
                            wire:model.defer="town_id"
                            class="w-full rounded-lg border
           border-gray-300 dark:border-zinc-600
           bg-white dark:bg-zinc-700
           px-4 py-2.5 text-sm
           text-gray-900 dark:text-white
           focus:ring-2 focus:ring-purple-500 focus:border-purple-500
           appearance-none">
                            <option value="" class="bg-white text-gray-900 dark:bg-zinc-800 dark:text-white">—</option>
                            @foreach($towns as $town)
                                <option value="{{ $town->id }}" class="bg-white text-gray-900 dark:bg-zinc-800 dark:text-white">
                                    {{ $town->name }}
                                </option>
                            @endforeach
                        </select>

                    </div>
                </div>

                {{-- FOOTER --}}
                <div class="pt-6 border-t border-gray-200 dark:border-zinc-700
                            flex justify-between items-center">
                    <p class="text-xs text-gray-500 dark:text-zinc-400">
                        Last updated {{ $page->updated_at->format('M d, Y H:i') }}
                    </p>
                    <div class="flex gap-3">
                        <flux:button href="{{ route('admin.pages.index') }}" wire:navigate variant="outline">
                            Cancel
                        </flux:button>
                        <flux:button type="submit" variant="primary" class="bg-purple-600 hover:bg-purple-700">
                            Update Page
                        </flux:button>
                    </div>
                </div>

            </form>
        </div>

        {{-- SEO --}}
        <div class="mt-10 bg-white dark:bg-zinc-800 rounded-2xl border
                    border-gray-200 dark:border-zinc-700 shadow-sm">
            <div class="p-6">
                <livewire:admin.pages.page-seo-editor :page="$page" />
            </div>
        </div>
    </div>

    {{-- PAGE BUILDER --}}
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
