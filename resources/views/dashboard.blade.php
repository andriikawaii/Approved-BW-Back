<x-layouts.app :title="__('Dashboard')">
    @php
        $totalPages     = \App\Models\Page::count();
        $publishedPages = \App\Models\Page::where('status', 'published')->count();
        $draftPages     = \App\Models\Page::where('status', 'draft')->count();
        $totalServices  = \App\Models\Service::count();
        $totalCounties  = \App\Models\County::count();
        $totalTowns     = \App\Models\Town::count();
        $totalMedia      = \App\Models\MediaAsset::count();
        $totalSections  = \App\Models\Section::count();

        $progressPct = $totalPages > 0 ? round(($publishedPages / $totalPages) * 100) : 0;

        $recentPages = \App\Models\Page::with('updater')
            ->orderByDesc('updated_at')
            ->limit(8)
            ->get();
    @endphp

    <div class="w-full px-4 sm:px-6 py-6 space-y-8">

        {{-- HEADER --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-zinc-900 dark:text-white">
                    Dashboard
                </h1>
                <p class="text-sm text-zinc-600 dark:text-zinc-300 mt-1 flex items-center gap-2">
                    <span>Welcome back, {{ auth()->user()->name }}</span>
                    <span class="hidden sm:inline text-zinc-400">|</span>
                    <span class="text-xs text-zinc-500 dark:text-zinc-400">
                        {{ now()->format('l, F j, Y') }}
                    </span>
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.pages.create') }}"
                   wire:navigate
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600
                          text-black font-semibold text-sm hover:from-amber-600 hover:to-amber-700 transition-all shadow-sm">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    New Page
                </a>
            </div>
        </div>

        {{-- KPI ROW --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 w-full">

            {{-- Total Pages --}}
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900/60 p-6
                        transition-all duration-300 hover:border-blue-300 dark:hover:border-blue-700/50">
                <div class="flex justify-between items-start mb-4">
                    <span class="text-sm font-medium text-zinc-500 dark:text-zinc-400 flex items-center gap-1.5">
                        <svg class="h-4 w-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Total Pages
                    </span>
                </div>
                <div class="text-4xl font-bold text-zinc-900 dark:text-white mb-1">
                    {{ $totalPages }}
                </div>
                <div class="mt-3">
                    <div class="flex justify-between text-xs mb-1">
                        <span class="text-zinc-500">Published</span>
                        <span class="font-medium text-zinc-900 dark:text-white">{{ $progressPct }}%</span>
                    </div>
                    <div class="h-2.5 rounded-full bg-zinc-200 dark:bg-zinc-700 overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r from-blue-500 to-emerald-500" style="width: {{ $progressPct }}%"></div>
                    </div>
                </div>
            </div>

            {{-- Published --}}
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900/60 p-6
                        transition-all duration-300 hover:border-emerald-300 dark:hover:border-emerald-700/50">
                <div class="flex justify-between items-start mb-4">
                    <span class="text-sm font-medium text-zinc-500 dark:text-zinc-400 flex items-center gap-1.5">
                        <svg class="h-4 w-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Published
                    </span>
                    <span class="text-xs px-2 py-0.5 rounded-full bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 font-medium">
                        Live
                    </span>
                </div>
                <div class="text-4xl font-bold text-emerald-600 dark:text-emerald-400 mb-1">
                    {{ $publishedPages }}
                </div>
                <p class="text-sm text-zinc-600 dark:text-zinc-300">
                    Live on frontend
                </p>
            </div>

            {{-- Drafts --}}
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900/60 p-6
                        transition-all duration-300 hover:border-amber-300 dark:hover:border-amber-700/50">
                <div class="flex justify-between items-start mb-4">
                    <span class="text-sm font-medium text-zinc-500 dark:text-zinc-400 flex items-center gap-1.5">
                        <svg class="h-4 w-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Drafts
                    </span>
                    @if($draftPages > 0)
                    <span class="text-xs px-2 py-0.5 rounded-full bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 font-medium">
                        Needs work
                    </span>
                    @endif
                </div>
                <div class="text-4xl font-bold text-amber-600 dark:text-amber-400 mb-1">
                    {{ $draftPages }}
                </div>
                <p class="text-sm text-zinc-600 dark:text-zinc-300">
                    Awaiting content or review
                </p>
            </div>
        </div>

        {{-- CONTENT + RECENT ACTIVITY --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 w-full">

            {{-- Quick Links --}}
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-zinc-900/60 rounded-xl border border-zinc-200 dark:border-zinc-800 overflow-hidden">
                    <div class="border-b border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-900/30 px-6 py-4">
                        <h2 class="text-lg font-bold text-zinc-900 dark:text-white">
                            Content Overview
                        </h2>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-0.5">
                            Quick access to all content areas
                        </p>
                    </div>

                    <div class="divide-y divide-zinc-200 dark:divide-zinc-800">
                        @foreach([
                            ['label' => 'Pages',         'route' => 'admin.pages.index',        'count' => $totalPages,    'color' => 'blue'],
                            ['label' => 'Services',      'route' => 'admin.services.index',     'count' => $totalServices, 'color' => 'purple'],
                            ['label' => 'Counties',      'route' => 'admin.counties.index',     'count' => $totalCounties, 'color' => 'emerald'],
                            ['label' => 'Towns',         'route' => 'admin.towns.index',        'count' => $totalTowns,    'color' => 'amber'],
                            ['label' => 'Media Assets',  'route' => 'admin.media.index',        'count' => $totalMedia,    'color' => 'rose'],
                        ] as $item)
                            <a href="{{ route($item['route']) }}"
                               wire:navigate
                               class="group flex items-center justify-between p-5 hover:bg-zinc-50 dark:hover:bg-zinc-800/40 transition-colors">
                                <p class="font-medium text-zinc-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400">
                                    {{ $item['label'] }}
                                </p>
                                <span class="text-sm font-semibold text-{{ $item['color'] }}-600 dark:text-{{ $item['color'] }}-400">
                                    {{ $item['count'] }}
                                </span>
                            </a>
                        @endforeach
                    </div>

                    <div class="border-t border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-900/30 p-4">
                        <div class="flex flex-wrap gap-6 justify-center text-center">
                            <div>
                                <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $totalSections }}</p>
                                <p class="text-sm text-zinc-500 dark:text-zinc-400">Sections</p>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $totalMedia }}</p>
                                <p class="text-sm text-zinc-500 dark:text-zinc-400">Media Files</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent Activity (real data) --}}
            <div class="w-full">
                <div class="bg-white dark:bg-zinc-900/60 rounded-xl border border-zinc-200 dark:border-zinc-800 overflow-hidden">
                    <div class="border-b border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-900/30 px-6 py-4">
                        <h2 class="text-lg font-bold text-zinc-900 dark:text-white">
                            Recently Updated Pages
                        </h2>
                    </div>

                    <div class="divide-y divide-zinc-200 dark:divide-zinc-800 max-h-[420px] overflow-y-auto">
                        @forelse($recentPages as $recentPage)
                            <a href="{{ route('admin.pages.edit', $recentPage) }}"
                               wire:navigate
                               class="block p-4 hover:bg-zinc-50 dark:hover:bg-zinc-800/40 transition-colors">
                                <div class="flex items-start gap-3">
                                    <div class="mt-1 flex-shrink-0 h-2 w-2 rounded-full
                                        {{ $recentPage->status === 'published' ? 'bg-emerald-500' : 'bg-amber-500' }}">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-zinc-900 dark:text-white truncate">
                                            {{ $recentPage->seo_title ?: $recentPage->full_path }}
                                        </p>
                                        <p class="text-xs text-zinc-400 mt-0.5">
                                            {{ $recentPage->full_path }}
                                        </p>
                                        <p class="text-xs text-zinc-400 mt-0.5">
                                            {{ $recentPage->updated_at->diffForHumans() }}
                                            @if($recentPage->updater)
                                                by {{ $recentPage->updater->name }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="p-6 text-center text-sm text-zinc-500 dark:text-zinc-400">
                                No pages yet. Create your first page to get started.
                            </div>
                        @endforelse
                    </div>

                    <div class="border-t border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-900/30 px-4 py-3 text-center">
                        <a href="{{ route('admin.pages.index') }}"
                           wire:navigate
                           class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300">
                            View all pages
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- SYSTEM INFO (super_admin only) --}}
        @role('super_admin')
        <div class="bg-white dark:bg-zinc-900/60 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6 w-full">
            <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-4">
                System Info
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-zinc-50 dark:bg-zinc-900/30 rounded-lg p-4">
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mb-1">Environment</p>
                    <p class="text-sm font-medium text-blue-600 dark:text-blue-400">
                        {{ app()->environment() }}
                    </p>
                </div>
                <div class="bg-zinc-50 dark:bg-zinc-900/30 rounded-lg p-4">
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mb-1">Cache</p>
                    <p class="text-sm font-medium text-purple-600 dark:text-purple-400">
                        {{ config('cache.default') }}
                    </p>
                </div>
                <div class="bg-zinc-50 dark:bg-zinc-900/30 rounded-lg p-4">
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mb-1">Queue</p>
                    <p class="text-sm font-medium text-amber-600 dark:text-amber-400">
                        {{ config('queue.default') }}
                    </p>
                </div>
                <div class="bg-zinc-50 dark:bg-zinc-900/30 rounded-lg p-4">
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mb-1">Your Role</p>
                    <p class="text-sm font-medium text-emerald-600 dark:text-emerald-400">
                        {{ auth()->user()->getRoleNames()->first() ?? 'User' }}
                    </p>
                </div>
            </div>
        </div>
        @endrole
    </div>
</x-layouts.app>
