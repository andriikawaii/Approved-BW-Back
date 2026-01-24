<div class="w-full bg-gray-50 dark:bg-zinc-900 min-h-screen">

    {{-- Global Media Picker --}}
    <livewire:admin.media.picker />

    {{-- ===================== --}}
    {{-- PAGE HEADER --}}
    {{-- ===================== --}}
    <div class="w-full px-4 sm:px-6 py-10">
        <div class="max-w-5xl mx-auto">
            <div class="mb-12 text-center">
                <div class="inline-flex items-center justify-center h-14 w-14 rounded-xl
                            bg-gradient-to-br from-purple-50 to-white dark:from-purple-900/20 dark:to-zinc-800
                            border border-purple-100 dark:border-purple-800/30 mb-5 mx-auto
                            shadow-sm">
                    <svg class="h-7 w-7 text-purple-600 dark:text-purple-400"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5
                                 m-1.414-9.414a2 2 0 112.828 2.828
                                 L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>

                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-3">
                    Edit Page
                </h1>

                <p class="text-sm text-gray-600 dark:text-zinc-400 max-w-lg mx-auto leading-relaxed">
                    Manage URL, publishing status, content relations, and page sections.
                </p>
            </div>
        </div>

        {{-- ===================== --}}
        {{-- PAGE SETTINGS CARD --}}
        {{-- ===================== --}}
        <div class="w-full px-4 sm:px-6">
            <div class="max-w-5xl mx-auto">
                <div class="bg-white dark:bg-zinc-800 rounded-2xl border
                            border-gray-200 dark:border-zinc-700 shadow-sm overflow-hidden mb-12
                            hover:shadow-md transition-shadow duration-300">

                    <!-- Card Header -->
                    <div class="px-8 py-6 border-b border-gray-200 dark:border-zinc-700
                                bg-gradient-to-r from-gray-50 to-white dark:from-zinc-900/50 dark:to-zinc-800">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-lg bg-purple-100 dark:bg-purple-900/30
                                            flex items-center justify-center">
                                    <svg class="h-5 w-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                                        Page Settings
                                    </h2>
                                    <p class="text-sm text-gray-500 dark:text-zinc-400">
                                        Configure basic page properties
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <span class="text-xs text-gray-500 dark:text-zinc-400">
                                    ID: <span class="font-semibold text-gray-700 dark:text-zinc-300">#{{ $page->id }}</span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <form wire:submit.prevent="save" class="p-8 space-y-8">
                        {{-- META BADGES --}}
                        <div class="flex flex-wrap items-center gap-4 pb-6 border-b
                                    border-gray-200 dark:border-zinc-700">
                            <div class="flex items-center gap-2">
                                <span class="text-sm text-gray-500 dark:text-zinc-400">Status:</span>
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium
                                    {{ $page->status === 'published'
                                        ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/30'
                                        : 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300 border border-amber-200 dark:border-amber-800/30' }}">
                                    <span class="h-2 w-2 rounded-full mr-2
                                        {{ $page->status === 'published' ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                                    {{ ucfirst($page->status) }}
                                </span>
                            </div>

                            <div class="flex items-center gap-2">
                                <span class="text-sm text-gray-500 dark:text-zinc-400">Template:</span>
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium
                                            bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                                            border border-blue-200 dark:border-blue-800/30">
                                    {{ Str::headline($page->template_key) }}
                                </span>
                            </div>
                        </div>

                        {{-- FULL PATH --}}
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <label class="text-sm font-semibold text-gray-800 dark:text-zinc-200">
                                    Full URL Path
                                </label>
                                <span class="text-xs text-rose-500 font-medium">Required</span>
                            </div>
                            <div class="relative group">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2
                                             text-gray-500 dark:text-zinc-400 text-sm font-medium">
                                    /
                                </div>
                                <input
                                    type="text"
                                    wire:model.defer="full_path"
                                    class="w-full pl-8 rounded-xl border-2
                                           border-gray-300 dark:border-zinc-600
                                           bg-white dark:bg-zinc-700/50
                                           px-5 py-3.5 text-sm font-medium
                                           text-gray-900 dark:text-white
                                           focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20
                                           transition-all duration-300
                                           hover:border-gray-400 dark:hover:border-zinc-500"
                                    placeholder="your-page-url"
                                >
                            </div>
                            <p class="text-xs text-gray-500 dark:text-zinc-400">
                                The complete URL path for this page (without domain)
                            </p>
                        </div>

                        {{-- STATUS + PUBLISHED AT --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-8 border-t
                                    border-gray-200 dark:border-zinc-700">
                            <div class="space-y-3">
                                <label class="text-sm font-semibold text-gray-800 dark:text-zinc-200">
                                    Publishing Status
                                </label>
                                <select
                                    wire:model.defer="status"
                                    class="w-full rounded-xl border-2
                                           border-gray-300 dark:border-zinc-600
                                           bg-white dark:bg-zinc-700/50
                                           px-5 py-3.5 text-sm font-medium
                                           text-gray-900 dark:text-white
                                           focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20
                                           transition-all duration-300
                                           hover:border-gray-400 dark:hover:border-zinc-500
                                           appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="%236b7280" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>')] bg-no-repeat bg-[length:1rem] bg-[right_1rem_center]"
                                >
                                <option value="draft" class="py-2">Draft</option>
                                <option value="published" class="py-2">Published</option>
                                </select>
                            </div>

                            <div class="space-y-3">
                                <label class="text-sm font-semibold text-gray-800 dark:text-zinc-200">
                                    Schedule Publishing
                                    <span class="text-xs text-gray-500 dark:text-zinc-400 font-normal">(optional)</span>
                                </label>
                                <div class="relative">
                                    <input
                                        type="datetime-local"
                                        wire:model.defer="published_at"
                                        class="w-full rounded-xl border-2
                                               border-gray-300 dark:border-zinc-600
                                               bg-white dark:bg-zinc-700/50
                                               px-5 py-3.5 text-sm font-medium
                                               text-gray-900 dark:text-white
                                               focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20
                                               transition-all duration-300
                                               hover:border-gray-400 dark:hover:border-zinc-500"
                                    >
                                </div>
                            </div>
                        </div>

                        {{-- RELATIONS --}}
                        <div class="pt-8 border-t border-gray-200 dark:border-zinc-700">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="h-10 w-10 rounded-lg bg-blue-100 dark:bg-blue-900/30
                                            flex items-center justify-center">
                                    <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                        Content Relations
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-zinc-400">
                                        Connect this page to services and locations
                                    </p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="space-y-3">
                                    <label class="text-sm font-semibold text-gray-800 dark:text-zinc-200">
                                        Service
                                    </label>
                                    <select
                                        wire:model.defer="service_id"
                                        class="w-full rounded-xl border-2
                                               border-gray-300 dark:border-zinc-600
                                               bg-white dark:bg-zinc-700/50
                                               px-5 py-3.5 text-sm font-medium
                                               text-gray-900 dark:text-white
                                               focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20
                                               transition-all duration-300
                                               hover:border-gray-400 dark:hover:border-zinc-500"
                                    >
                                        <option value="">— Not linked to service —</option>
                                        @foreach($services as $service)
                                            <option value="{{ $service->id }}">
                                                {{ $service->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="space-y-3">
                                    <label class="text-sm font-semibold text-gray-800 dark:text-zinc-200">
                                        County
                                    </label>
                                    <select
                                        wire:model.defer="county_id"
                                        class="w-full rounded-xl border-2
                                               border-gray-300 dark:border-zinc-600
                                               bg-white dark:bg-zinc-700/50
                                               px-5 py-3.5 text-sm font-medium
                                               text-gray-900 dark:text-white
                                               focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20
                                               transition-all duration-300
                                               hover:border-gray-400 dark:hover:border-zinc-500"
                                    >
                                        <option value="">Global (All counties)</option>
                                        @foreach($counties as $county)
                                            <option value="{{ $county->id }}">
                                                {{ $county->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="space-y-3">
                                    <label class="text-sm font-semibold text-gray-800 dark:text-zinc-200">
                                        Town
                                    </label>
                                    <select
                                        wire:model.defer="town_id"
                                        class="w-full rounded-xl border-2
                                               border-gray-300 dark:border-zinc-600
                                               bg-white dark:bg-zinc-700/50
                                               px-5 py-3.5 text-sm font-medium
                                               text-gray-900 dark:text-white
                                               focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20
                                               transition-all duration-300
                                               hover:border-gray-400 dark:hover:border-zinc-500"
                                    >
                                        <option value="">— Select town —</option>
                                        @foreach($towns as $town)
                                            <option value="{{ $town->id }}">
                                                {{ $town->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- FOOTER ACTIONS --}}
                        <div class="pt-8 border-t border-gray-200 dark:border-zinc-700
                                    flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6">
                            <div class="space-y-1">
                                <p class="text-sm text-gray-700 dark:text-zinc-300">
                                    Last updated
                                </p>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $page->updated_at->format('F d, Y \a\t H:i') }}
                                </p>
                            </div>
                            <div class="flex flex-wrap gap-4">
                                <flux:button
                                    href="{{ route('admin.pages.index') }}"
                                    wire:navigate
                                    variant="outline"
                                    class="border-2 border-gray-300 dark:border-zinc-600 hover:bg-gray-50 dark:hover:bg-zinc-700/50"
                                >
                                    Cancel
                                </flux:button>
                                <a
                                    href="{{ \App\Support\PreviewUrl::forPage($page) }}"
                                    target="_blank"
                                    class="inline-flex items-center px-5 py-3 rounded-xl border-2
                                           border-gray-300 dark:border-zinc-600 text-sm font-semibold
                                           hover:bg-gray-50 dark:hover:bg-zinc-700/50
                                           text-gray-700 dark:text-zinc-300
                                           transition-all duration-300 hover:border-gray-400 dark:hover:border-zinc-500"
                                >
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Preview Page
                                </a>
                                <flux:button
                                    type="submit"
                                    variant="primary"
                                    class="bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800
                                           border-2 border-transparent px-6 py-3 font-semibold"
                                >
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Update Page Settings
                                </flux:button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ===================== --}}
        {{-- SEO SECTION --}}
        {{-- ===================== --}}
        <div class="w-full px-4 sm:px-6">
            <div class="max-w-5xl mx-auto">
                <div class="bg-white dark:bg-zinc-800 rounded-2xl border
                            border-gray-200 dark:border-zinc-700 shadow-sm mb-12
                            hover:shadow-md transition-shadow duration-300">
                    <div class="px-8 py-6 border-b border-gray-200 dark:border-zinc-700
                                bg-gradient-to-r from-gray-50 to-white dark:from-zinc-900/50 dark:to-zinc-800">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-lg bg-green-100 dark:bg-green-900/30
                                        flex items-center justify-center">
                                <svg class="h-5 w-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                                    SEO Settings
                                </h2>
                                <p class="text-sm text-gray-500 dark:text-zinc-400">
                                    Optimize for search engines and social media
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="p-8">
                        <livewire:admin.pages.page-seo-editor :page="$page" />
                    </div>
                </div>
            </div>
        </div>

        {{-- ===================== --}}
        {{-- AVAILABLE SECTIONS --}}
        {{-- ===================== --}}
        @php
            $allowed = config("page-template-sections.{$page->template_key}.allowed", []);
        @endphp

        <div class="w-full px-4 sm:px-6">
            <div class="max-w-5xl mx-auto">
                <div class="bg-white dark:bg-zinc-800 rounded-2xl border border-gray-200 dark:border-zinc-700 p-8 mb-12
                            hover:shadow-md transition-shadow duration-300">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-lg bg-amber-100 dark:bg-amber-900/30
                                        flex items-center justify-center">
                                <svg class="h-5 w-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                    Available Section Types
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-zinc-400">
                                    Based on "{{ Str::headline($page->template_key) }}" template
                                </p>
                            </div>
                        </div>
                        <span class="px-3 py-1.5 rounded-full text-sm font-medium
                                  bg-gray-100 text-gray-800 dark:bg-zinc-700 dark:text-zinc-300">
                            {{ count($allowed) }} types
                        </span>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        @foreach ($allowed as $type)
                            <span class="px-4 py-2.5 text-sm rounded-xl
                                        bg-gradient-to-r from-purple-50 to-purple-100
                                        dark:from-purple-900/20 dark:to-purple-900/10
                                        text-purple-800 dark:text-purple-300
                                        font-semibold border border-purple-200 dark:border-purple-800/30
                                        shadow-sm">
                                {{ \Illuminate\Support\Str::headline($type) }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- ===================== --}}
        {{-- PAGE BUILDER --}}
        {{-- ===================== --}}
        <div class="w-full">
            <div class="bg-white dark:bg-zinc-800 border-t border-gray-200 dark:border-zinc-700">
                <div class="px-8 py-10">
                    <div class="max-w-5xl mx-auto">
                        <div class="flex items-center justify-between mb-8">
                            <div class="flex items-center gap-4">
                                <div class="h-14 w-14 rounded-xl bg-gradient-to-br from-blue-50 to-white
                                            dark:from-blue-900/20 dark:to-zinc-800
                                            border border-blue-100 dark:border-blue-800/30
                                            flex items-center justify-center shadow-sm">
                                    <svg class="h-7 w-7 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white">
                                        Page Builder
                                    </h2>
                                    <p class="text-sm text-gray-600 dark:text-zinc-400 mt-1">
                                        Drag and drop sections to build your page content
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-full px-4 sm:px-6 lg:px-8 pb-20">
                    <div class="max-w-5xl mx-auto">
                        <livewire:admin.pages.sections-builder :page="$page" />
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
