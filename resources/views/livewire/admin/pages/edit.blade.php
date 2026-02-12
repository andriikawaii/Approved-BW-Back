<div class="w-full min-h-screen bg-gray-50 dark:bg-zinc-900">

    {{-- Global Media Picker --}}
    <livewire:admin.media.picker />

    {{-- ===================== --}}
    {{-- COMPACT PAGE HEADER   --}}
    {{-- ===================== --}}
    <div class="border-b border-gray-200 dark:border-zinc-800 bg-white dark:bg-zinc-900/80">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-5">

            {{-- Top row: back + title + badges --}}
            <div class="flex items-center gap-4 mb-4">
                <a href="{{ route('admin.pages.index') }}"
                   wire:navigate
                   class="flex items-center justify-center h-9 w-9 rounded-lg
                          border border-gray-200 dark:border-zinc-700
                          bg-white dark:bg-zinc-800 text-gray-500 dark:text-zinc-400
                          hover:bg-gray-50 dark:hover:bg-zinc-700 transition">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </a>

                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-3 flex-wrap">
                        <h1 class="text-xl font-bold text-gray-900 dark:text-white truncate">
                            {{ $page->full_path === '/' ? 'Home Page' : $page->full_path }}
                        </h1>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold
                            {{ $page->status === 'published'
                                ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400'
                                : 'bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' }}">
                            <span class="h-1.5 w-1.5 rounded-full
                                {{ $page->status === 'published' ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                            {{ ucfirst($page->status) }}
                        </span>
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium
                                     bg-gray-100 text-gray-600 dark:bg-zinc-800 dark:text-zinc-400">
                            {{ Str::headline($page->template_key) }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-zinc-500 mt-1">
                        ID #{{ $page->id }} &middot; Updated {{ $page->updated_at->diffForHumans() }}
                    </p>
                </div>

                <a href="{{ \App\Support\PreviewUrl::forPage($page) }}"
                   target="_blank"
                   class="hidden sm:inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium
                          border border-gray-200 dark:border-zinc-700
                          bg-white dark:bg-zinc-800 text-gray-700 dark:text-zinc-300
                          hover:bg-gray-50 dark:hover:bg-zinc-700 transition">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                    Preview
                </a>
            </div>

            {{-- Tab navigation --}}
            <div class="flex gap-1">
                <button type="button" wire:click="$set('tab', 'builder')"
                        class="px-4 py-2.5 text-sm font-medium rounded-lg border-b-2 transition-colors
                        {{ $tab === 'builder'
                            ? 'border-purple-500 text-purple-600 dark:text-purple-400 bg-purple-50 dark:bg-purple-900/20'
                            : 'border-transparent text-gray-500 dark:text-zinc-400 hover:text-gray-700 dark:hover:text-zinc-300 hover:bg-gray-50 dark:hover:bg-zinc-800' }}">
                    Page Builder
                </button>
                <button type="button" wire:click="$set('tab', 'settings')"
                        class="px-4 py-2.5 text-sm font-medium rounded-lg border-b-2 transition-colors
                        {{ $tab === 'settings'
                            ? 'border-purple-500 text-purple-600 dark:text-purple-400 bg-purple-50 dark:bg-purple-900/20'
                            : 'border-transparent text-gray-500 dark:text-zinc-400 hover:text-gray-700 dark:hover:text-zinc-300 hover:bg-gray-50 dark:hover:bg-zinc-800' }}">
                    Settings
                </button>
                <button type="button" wire:click="$set('tab', 'seo')"
                        class="px-4 py-2.5 text-sm font-medium rounded-lg border-b-2 transition-colors
                        {{ $tab === 'seo'
                            ? 'border-purple-500 text-purple-600 dark:text-purple-400 bg-purple-50 dark:bg-purple-900/20'
                            : 'border-transparent text-gray-500 dark:text-zinc-400 hover:text-gray-700 dark:hover:text-zinc-300 hover:bg-gray-50 dark:hover:bg-zinc-800' }}">
                    SEO
                </button>
            </div>
        </div>
    </div>

    {{-- ===================== --}}
    {{-- TAB CONTENT           --}}
    {{-- ===================== --}}
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-8">

        {{-- ===== PAGE BUILDER TAB ===== --}}
        @if ($tab === 'builder')
            <livewire:admin.pages.sections-builder :page="$page" />
        @endif

        {{-- ===== SETTINGS TAB ===== --}}
        @if ($tab === 'settings')
            <div class="max-w-3xl">
                <div class="bg-white dark:bg-zinc-800 rounded-xl border border-gray-200 dark:border-zinc-700 shadow-sm">
                    <form wire:submit.prevent="save" class="divide-y divide-gray-100 dark:divide-zinc-700/50">

                        {{-- Full Path --}}
                        <div class="p-6">
                            <label class="block text-sm font-semibold text-gray-800 dark:text-zinc-200 mb-1.5">
                                URL Path <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 dark:text-zinc-500 text-sm font-mono">/</span>
                                <input type="text"
                                       wire:model.defer="full_path"
                                       class="w-full pl-7 rounded-lg border border-gray-300 dark:border-zinc-600
                                              bg-white dark:bg-zinc-700/50 px-4 py-2.5 text-sm font-mono
                                              text-gray-900 dark:text-white
                                              focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition"
                                       placeholder="your-page-url" />
                            </div>
                        </div>

                        {{-- Status + Published At --}}
                        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 dark:text-zinc-200 mb-1.5">
                                    Status
                                </label>
                                <select wire:model.defer="status"
                                        class="w-full rounded-lg border border-gray-300 dark:border-zinc-600
                                               bg-white dark:bg-zinc-700/50 px-4 py-2.5 text-sm
                                               text-gray-900 dark:text-white
                                               focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition">
                                    <option value="draft">Draft</option>
                                    <option value="published">Published</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 dark:text-zinc-200 mb-1.5">
                                    Schedule <span class="text-xs text-gray-400 dark:text-zinc-500 font-normal">(optional)</span>
                                </label>
                                <input type="datetime-local"
                                       wire:model.defer="published_at"
                                       class="w-full rounded-lg border border-gray-300 dark:border-zinc-600
                                              bg-white dark:bg-zinc-700/50 px-4 py-2.5 text-sm
                                              text-gray-900 dark:text-white
                                              focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition" />
                            </div>
                        </div>

                        {{-- Relations --}}
                        <div class="p-6">
                            <h3 class="text-sm font-semibold text-gray-800 dark:text-zinc-200 mb-4">
                                Content Relations
                            </h3>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 dark:text-zinc-400 mb-1">Service</label>
                                    <select wire:model.defer="service_id"
                                            class="w-full rounded-lg border border-gray-300 dark:border-zinc-600
                                                   bg-white dark:bg-zinc-700/50 px-3 py-2 text-sm
                                                   text-gray-900 dark:text-white
                                                   focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition">
                                        <option value="">None</option>
                                        @foreach($services as $service)
                                            <option value="{{ $service->id }}">{{ $service->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 dark:text-zinc-400 mb-1">County</label>
                                    <select wire:model.defer="county_id"
                                            class="w-full rounded-lg border border-gray-300 dark:border-zinc-600
                                                   bg-white dark:bg-zinc-700/50 px-3 py-2 text-sm
                                                   text-gray-900 dark:text-white
                                                   focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition">
                                        <option value="">All counties</option>
                                        @foreach($counties as $county)
                                            <option value="{{ $county->id }}">{{ $county->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 dark:text-zinc-400 mb-1">Town</label>
                                    <select wire:model.defer="town_id"
                                            class="w-full rounded-lg border border-gray-300 dark:border-zinc-600
                                                   bg-white dark:bg-zinc-700/50 px-3 py-2 text-sm
                                                   text-gray-900 dark:text-white
                                                   focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition">
                                        <option value="">Select town</option>
                                        @foreach($towns as $town)
                                            <option value="{{ $town->id }}">{{ $town->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="p-6 flex items-center justify-between">
                            <p class="text-xs text-gray-400 dark:text-zinc-500">
                                Last saved {{ $page->updated_at->format('M d, Y H:i') }}
                            </p>
                            <div class="flex gap-3">
                                <flux:button href="{{ route('admin.pages.index') }}" wire:navigate variant="outline">
                                    Cancel
                                </flux:button>
                                <flux:button type="submit" variant="primary"
                                             class="bg-purple-600 hover:bg-purple-700">
                                    Save Settings
                                </flux:button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        {{-- ===== SEO TAB ===== --}}
        @if ($tab === 'seo')
            <div class="max-w-3xl">
                <div class="bg-white dark:bg-zinc-800 rounded-xl border border-gray-200 dark:border-zinc-700 shadow-sm p-6">
                    <livewire:admin.pages.page-seo-editor :page="$page" />
                </div>
            </div>
        @endif

    </div>

</div>
