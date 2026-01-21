<x-layouts.app :title="__('Dashboard')">
    <div class="w-full px-4 sm:px-6 py-6 space-y-8">
        {{-- HEADER WITH USER CONTEXT --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-zinc-900 dark:text-white bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-emerald-500">
                    Control Center
                </h1>
                <p class="text-sm md:text-base text-zinc-600 dark:text-zinc-300 mt-1 flex items-center gap-2">
                    <span>Hi, {{ Str::afterLast(auth()->user()->name, ' ') }} 👋</span>
                    <span class="hidden sm:inline">|</span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300">
                        <svg class="h-2 w-2" fill="currentColor" viewBox="0 0 8 8">
                            <circle cx="4" cy="4" r="3" class="text-emerald-500"/>
                        </svg>
                        Active now
                    </span>
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <div class="flex items-center bg-zinc-100 dark:bg-zinc-800 rounded-lg px-3 py-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-zinc-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="text-sm font-medium text-zinc-700 dark:text-zinc-200">
                        {{ now()->format('l, F j') }}
                    </span>
                </div>
                <button class="relative flex items-center justify-center h-10 w-10 rounded-lg bg-gradient-to-br from-blue-500 to-emerald-600 text-white hover:opacity-90 transition-opacity">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span class="absolute top-1 right-1 flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-rose-500"></span>
                    </span>
                </button>
            </div>
        </div>

        {{-- ENHANCED KPI ROW --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 w-full">
            {{-- Pages with meaningful context --}}
            <div class="relative rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900/60 backdrop-blur-sm p-6 flex flex-col justify-between transition-all duration-300 hover:border-blue-300 dark:hover:border-blue-700/50">
                <div>
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <span class="text-sm font-medium text-zinc-500 dark:text-zinc-400 flex items-center gap-1.5">
                                <svg class="h-4 w-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Pages
                            </span>
                        </div>
                        <span class="text-xs px-2 py-0.5 rounded-full bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 font-medium">
                            CMS
                        </span>
                    </div>

                    <div class="text-4xl font-bold text-zinc-900 dark:text-white mb-1">
                        106
                    </div>
                    <p class="text-zinc-600 dark:text-zinc-300 mb-4">
                        Total planned URLs
                    </p>
                </div>

                <div class="mt-2">
                    <div class="flex justify-between text-xs mb-1">
                        <span class="text-zinc-500">Progress</span>
                        <span class="font-medium text-zinc-900 dark:text-white">74%</span>
                    </div>
                    <div class="h-2.5 rounded-full bg-zinc-200 dark:bg-zinc-700 overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r from-blue-500 to-emerald-500" style="width: 74%"></div>
                    </div>
                    <p class="mt-1.5 text-xs text-zinc-500 flex items-center">
                        <svg class="h-3 w-3 text-emerald-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        8 pending review
                    </p>
                </div>
            </div>

            {{-- Published with actionable insight --}}
            <div class="relative rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900/60 backdrop-blur-sm p-6 flex flex-col justify-between transition-all duration-300 hover:border-emerald-300 dark:hover:border-emerald-700/50">
                <div>
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <span class="text-sm font-medium text-zinc-500 dark:text-zinc-400 flex items-center gap-1.5">
                                <svg class="h-4 w-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Published
                            </span>
                        </div>
                        <span class="text-xs px-2 py-0.5 rounded-full bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 font-medium">
                            Live
                        </span>
                    </div>

                    <div class="text-4xl font-bold text-emerald-600 dark:text-emerald-400 mb-1">
                        12
                    </div>
                    <p class="text-zinc-600 dark:text-zinc-300 mb-4">
                        Live on frontend
                    </p>
                </div>

                <div class="mt-2">
                    <div class="flex items-center justify-between text-xs">
                        <div class="flex items-center gap-1.5">
                            <svg class="h-3.5 w-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-emerald-600 dark:text-emerald-400 font-medium">+2 this week</span>
                        </div>
                        <span class="text-zinc-500">Avg. 1.5/day</span>
                    </div>
                    <div class="mt-3 h-1.5 rounded-full bg-zinc-200 dark:bg-zinc-700 overflow-hidden">
                        <div class="h-full rounded-full bg-emerald-500" style="width: 85%"></div>
                    </div>
                </div>
            </div>

            {{-- Drafts with priority context --}}
            <div class="relative rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900/60 backdrop-blur-sm p-6 flex flex-col justify-between transition-all duration-300 hover:border-amber-300 dark:hover:border-amber-700/50">
                <div>
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <span class="text-sm font-medium text-zinc-500 dark:text-zinc-400 flex items-center gap-1.5">
                                <svg class="h-4 w-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Drafts
                            </span>
                        </div>
                        <span class="text-xs px-2 py-0.5 rounded-full bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 font-medium">
                            Attention
                        </span>
                    </div>

                    <div class="text-4xl font-bold text-amber-600 dark:text-amber-400 mb-1">
                        94
                    </div>
                    <p class="text-zinc-600 dark:text-zinc-300 mb-4">
                        Needs content / SEO
                    </p>
                </div>

                <div class="mt-2">
                    <div class="flex justify-between text-xs mb-1">
                        <span class="text-zinc-500">Priority breakdown</span>
                        <span class="font-medium text-zinc-900 dark:text-white">94 total</span>
                    </div>
                    <div class="space-y-1.5">
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-rose-500 font-medium">High (32)</span>
                            <div class="flex-1 h-1.5 rounded-full bg-rose-200 dark:bg-rose-900/30 overflow-hidden">
                                <div class="h-full rounded-full bg-rose-500" style="width: 34%"></div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-amber-500 font-medium">Medium (48)</span>
                            <div class="flex-1 h-1.5 rounded-full bg-amber-200 dark:bg-amber-900/30 overflow-hidden">
                                <div class="h-full rounded-full bg-amber-500" style="width: 51%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ENHANCED CONTENT MANAGEMENT --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 w-full">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-zinc-900/60 backdrop-blur-sm rounded-xl border border-zinc-200 dark:border-zinc-800 overflow-hidden">
                    <div class="border-b border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-900/30 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-lg font-bold text-zinc-900 dark:text-white">
                                    Content Management
                                </h2>
                                <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-0.5">
                                    Core CMS entities with real-time status
                                </p>
                            </div>
                            <button class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 flex items-center gap-1.5 transition-colors">
                                View all
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="divide-y divide-zinc-200 dark:divide-zinc-800">
                        @foreach([
                            ['label' => 'Pages', 'route' => 'admin.pages.index', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'status' => '12/106', 'progress' => 11, 'color' => 'blue'],
                            ['label' => 'Services', 'route' => 'admin.services.index', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'status' => '8/24', 'progress' => 33, 'color' => 'purple'],
                            ['label' => 'Projects', 'route' => 'admin.projects.index', 'icon' => 'M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4', 'status' => '5/18', 'progress' => 28, 'color' => 'amber'],
                            ['label' => 'Testimonials', 'route' => 'admin.testimonials.index', 'icon' => 'M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z', 'status' => '24/30', 'progress' => 80, 'color' => 'emerald'],
                        ] as $item)
                            <a href="{{ route($item['route']) }}"
                               wire:navigate
                               class="group flex items-center justify-between p-5 hover:bg-zinc-50 dark:hover:bg-zinc-800/40 transition-colors">
                                <div class="flex items-start gap-4">
                                    <div class="mt-0.5 flex-shrink-0 flex h-11 w-11 items-center justify-center rounded-lg bg-{{ $item['color'] }}-50 dark:bg-{{ $item['color'] }}-900/20">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-{{ $item['color'] }}-600 dark:text-{{ $item['color'] }}-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-zinc-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400">
                                            {{ $item['label'] }}
                                        </p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="text-xs text-zinc-500 dark:text-zinc-400 flex items-center">
                                                {{ $item['status'] }} managed
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex flex-col items-end">
                                    <span class="text-sm font-medium text-{{ $item['color'] }}-600 dark:text-{{ $item['color'] }}-400">
                                        {{ $item['progress'] }}%
                                    </span>
                                    <div class="mt-1 h-1.5 w-16 rounded-full bg-zinc-200 dark:bg-zinc-700 overflow-hidden">
                                        <div class="h-full rounded-full bg-{{ $item['color'] }}-500" style="width: {{ $item['progress'] }}%"></div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <div class="border-t border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-900/30 p-4">
                        <div class="flex flex-wrap gap-4 justify-between w-full">
                            <div class="text-center flex-1 min-w-[120px]">
                                <p class="text-2xl font-bold text-zinc-900 dark:text-white">
                                    24
                                </p>
                                <p class="text-sm text-zinc-500 dark:text-zinc-400">
                                    Active Editors
                                </p>
                            </div>
                            <div class="text-center flex-1 min-w-[120px]">
                                <p class="text-2xl font-bold text-zinc-900 dark:text-white">
                                    156
                                </p>
                                <p class="text-sm text-zinc-500 dark:text-zinc-400">
                                    Total Assets
                                </p>
                            </div>
                            <div class="text-center flex-1 min-w-[120px]">
                                <p class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-rose-500 to-amber-500">
                                    2.4h
                                </p>
                                <p class="text-sm text-zinc-500 dark:text-zinc-400">
                                    Avg. edit time
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- NEW: RECENT ACTIVITY SECTION --}}
            <div class="space-y-6 w-full">
                <div class="bg-white dark:bg-zinc-900/60 backdrop-blur-sm rounded-xl border border-zinc-200 dark:border-zinc-800 overflow-hidden">
                    <div class="border-b border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-900/30 px-6 py-4">
                        <h2 class="text-lg font-bold text-zinc-900 dark:text-white">
                            Recent Activity
                        </h2>
                    </div>

                    <div class="divide-y divide-zinc-200 dark:divide-zinc-800 max-h-[400px] overflow-y-auto">
                        @foreach([
                            ['user' => 'Marko', 'action' => 'published', 'item' => 'Homepage', 'time' => '10m ago', 'color' => 'emerald'],
                            ['user' => 'Ana', 'action' => 'updated', 'item' => 'Services page', 'time' => '23m ago', 'color' => 'blue'],
                            ['user' => 'System', 'action' => 'generated', 'item' => 'SEO report', 'time' => '1h ago', 'color' => 'purple'],
                            ['user' => 'Ivan', 'action' => 'drafted', 'item' => 'Case study', 'time' => '2h ago', 'color' => 'amber'],
                            ['user' => 'Marko', 'action' => 'commented on', 'item' => 'Blog post', 'time' => '3h ago', 'color' => 'zinc'],
                            ['user' => 'System', 'action' => 'backed up', 'item' => 'database', 'time' => '4h ago', 'color' => 'zinc'],
                            ['user' => 'Ana', 'action' => 'scheduled', 'item' => 'Newsletter', 'time' => '5h ago', 'color' => 'rose'],
                        ] as $activity)
                            <div class="p-4 hover:bg-zinc-50 dark:hover:bg-zinc-800/40 transition-colors">
                                <div class="flex items-start gap-3">
                                    <div class="mt-1 flex-shrink-0 h-2 w-2 rounded-full bg-{{ $activity['color'] }}-500"></div>
                                    <div class="flex-1">
                                        <p class="text-sm">
                                            <span class="font-medium text-zinc-900 dark:text-white">{{ $activity['user'] }}</span>
                                            <span class="text-zinc-600 dark:text-zinc-300">{{ $activity['action'] }}</span>
                                            <span class="font-medium text-zinc-900 dark:text-white">{{ $activity['item'] }}</span>
                                        </p>
                                        <p class="text-xs text-zinc-400 mt-0.5">
                                            {{ $activity['time'] }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-900/30 px-4 py-3 text-center">
                        <button class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300">
                            View all activity
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ENHANCED SYSTEM OVERVIEW --}}
        <div class="bg-white dark:bg-zinc-900/60 backdrop-blur-sm rounded-xl border border-zinc-200 dark:border-zinc-800 p-6 w-full">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-lg font-bold text-zinc-900 dark:text-white">
                        System Health
                    </h2>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-0.5">
                        Runtime performance & resource status
                    </p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300">
                        <svg class="h-1.5 w-1.5 mr-1" fill="currentColor" viewBox="0 0 6 6">
                            <circle cx="3" cy="3" r="3" class="text-emerald-500"/>
                        </svg>
                        All systems operational
                    </span>
                    <button class="inline-flex items-center gap-1.5 px-3 py-1.5 border border-zinc-300 dark:border-zinc-700 rounded-lg text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Refresh status
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-zinc-50 dark:bg-zinc-900/30 rounded-lg p-4">
                            <p class="text-xs text-zinc-500 dark:text-zinc-400 mb-1">Response Time</p>
                            <p class="text-xl font-bold text-zinc-900 dark:text-white">328ms</p>
                            <p class="text-xs text-emerald-600 dark:text-emerald-400 mt-1 flex items-center">
                                <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                </svg>
                                12% better than avg
                            </p>
                        </div>
                        <div class="bg-zinc-50 dark:bg-zinc-900/30 rounded-lg p-4">
                            <p class="text-xs text-zinc-500 dark:text-zinc-400 mb-1">Uptime</p>
                            <p class="text-xl font-bold text-zinc-900 dark:text-white">99.98%</p>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">
                                Last 30 days
                            </p>
                        </div>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-zinc-900 dark:text-white mb-2">Resource Usage</p>
                        <div class="space-y-3">
                            <div>
                                <div class="flex justify-between text-xs mb-1">
                                    <span>CPU</span>
                                    <span>28%</span>
                                </div>
                                <div class="h-2 rounded-full bg-zinc-200 dark:bg-zinc-700 overflow-hidden">
                                    <div class="h-full rounded-full bg-blue-500" style="width: 28%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between text-xs mb-1">
                                    <span>Memory</span>
                                    <span>64%</span>
                                </div>
                                <div class="h-2 rounded-full bg-zinc-200 dark:bg-zinc-700 overflow-hidden">
                                    <div class="h-full rounded-full bg-purple-500" style="width: 64%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between text-xs mb-1">
                                    <span>Storage</span>
                                    <span>42%</span>
                                </div>
                                <div class="h-2 rounded-full bg-zinc-200 dark:bg-zinc-700 overflow-hidden">
                                    <div class="h-full rounded-full bg-emerald-500" style="width: 42%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl p-4 border border-blue-100 dark:border-blue-900/30">
                        <div class="flex items-start gap-3">
                            <div class="mt-0.5 flex-shrink-0 flex h-9 w-9 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/30">
                                <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-blue-800 dark:text-blue-200">Performance Tip</p>
                                <p class="text-sm text-blue-700 dark:text-blue-300 mt-1">
                                    Consider enabling OPcache for 30% faster PHP execution
                                </p>
                                <button class="mt-2 inline-flex items-center text-xs font-medium text-blue-700 dark:text-blue-300 hover:underline">
                                    Learn more
                                    <svg class="h-3 w-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-zinc-50 dark:bg-zinc-900/30 rounded-lg p-4">
                            <p class="text-xs text-zinc-500 dark:text-zinc-400 mb-1">Environment</p>
                            <p class="text-sm font-medium text-blue-600 dark:text-blue-400">
                                {{ app()->environment() }}
                            </p>
                        </div>
                        <div class="bg-zinc-50 dark:bg-zinc-900/30 rounded-lg p-4">
                            <p class="text-xs text-zinc-500 dark:text-zinc-400 mb-1">Cache Driver</p>
                            <p class="text-sm font-medium text-purple-600 dark:text-purple-400">
                                {{ config('cache.default') }}
                            </p>
                        </div>
                        <div class="bg-zinc-50 dark:bg-zinc-900/30 rounded-lg p-4">
                            <p class="text-xs text-zinc-500 dark:text-zinc-400 mb-1">Queue Driver</p>
                            <p class="text-sm font-medium text-amber-600 dark:text-amber-400">
                                {{ config('queue.default') }}
                            </p>
                        </div>
                        <div class="bg-zinc-50 dark:bg-zinc-900/30 rounded-lg p-4">
                            <p class="text-xs text-zinc-500 dark:text-zinc-400 mb-1">Your Role</p>
                            <p class="text-sm font-medium text-emerald-600 dark:text-emerald-400">
                                {{ auth()->user()->getRoleNames()->first() ?? __('User') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pt-4 border-t border-zinc-200 dark:border-zinc-800">
                <div class="flex items-center text-xs text-zinc-500 dark:text-zinc-400 gap-1.5">
                    <svg class="h-4 w-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Last system check: {{ now()->format('M j, H:i') }}</span>
                </div>
                <a href="{{ route('admin.settings.general') }}"
                   wire:navigate
                   class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium bg-gradient-to-r from-blue-600 to-emerald-600 text-white hover:from-blue-700 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors w-full sm:w-auto">
                    <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Configure System Settings
                </a>
            </div>
        </div>
    </div>
</x-layouts.app>
