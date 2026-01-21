<div class="w-full px-4 sm:px-6 py-6">
    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-zinc-900 dark:text-white">
                Counties Management
            </h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1 flex items-center gap-2">
                <svg class="h-4 w-4 text-zinc-400 dark:text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                Manage counties used for service and location-based pages
            </p>
        </div>

        <flux:button
            href="{{ route('admin.counties.create') }}"
            wire:navigate
            variant="primary"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 text-sm font-medium transition-colors duration-200"
        >
            <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Add New County
        </flux:button>
    </div>

    {{-- ACTIVE COUNTIES --}}
    <div class="space-y-6 mb-12">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="h-8 w-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                    <svg class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2z" />
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-zinc-900 dark:text-white">
                    Active Counties
                </h2>
            </div>
            <span class="px-3 py-1 rounded-full text-sm font-medium bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400">
                {{ $counties->where('is_active', true)->count() }} active
            </span>
        </div>

        @if($counties->where('is_active', true)->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                @foreach ($counties->where('is_active', true) as $county)
                    <div class="relative rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5 transition-all duration-200 hover:border-blue-300 dark:hover:border-blue-600/50 hover:shadow-sm">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <div class="text-lg font-semibold text-zinc-900 dark:text-white">
                                    {{ $county->name }}
                                </div>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-xs px-2 py-0.5 rounded bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 border border-blue-100 dark:border-blue-800">
                                        {{ ucfirst($county->region) }}
                                    </span>
                                    <span class="text-sm text-zinc-500 dark:text-zinc-400">/{{ $county->slug }}</span>
                                </div>
                            </div>

                            <span class="flex-shrink-0 inline-flex items-center rounded-full px-2 py-1 text-xs font-medium bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                                Active
                            </span>
                        </div>

                        <div class="pt-4 border-t border-zinc-200 dark:border-zinc-700 flex flex-wrap gap-2">
                            <flux:button
                                size="sm"
                                variant="ghost"
                                class="text-rose-600 dark:text-rose-400 hover:text-rose-700 dark:hover:text-rose-300 hover:bg-rose-50 dark:hover:bg-rose-900/20"
                                wire:click="toggleActive({{ $county->id }})"
                            >
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Deactivate
                            </flux:button>

                            <flux:button
                                size="sm"
                                variant="outline"
                                class="border-zinc-300 dark:border-zinc-600 hover:border-blue-400 text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/20"
                                href="{{ route('admin.counties.edit', $county) }}"
                                wire:navigate
                            >
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                                Edit
                            </flux:button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="border-2 border-dashed rounded-lg border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-12 text-center">
                <div class="inline-flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-900/30 mb-4">
                    <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M17 20H7m0-2v-2a5 5 0 0110 0v2M7 7a5 5 0 0110 0v2a5 5 0 01-10 0V7z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-zinc-900 dark:text-white mb-1">No active counties</h3>
                <p class="text-zinc-500 dark:text-zinc-400 mb-4">Create your first county to get started</p>
                <flux:button
                    href="{{ route('admin.counties.create') }}"
                    wire:navigate
                    variant="primary"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5"
                >
                    <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add Your First County
                </flux:button>
            </div>
        @endif
    </div>

    {{-- INACTIVE COUNTIES --}}
    @if ($counties->where('is_active', false)->count())
        <div class="pt-8 border-t border-zinc-200 dark:border-zinc-800">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="h-8 w-8 rounded-lg bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center">
                        <svg class="h-4 w-4 text-zinc-500 dark:text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-semibold text-zinc-900 dark:text-white">
                        Inactive Counties
                    </h2>
                </div>
                <span class="px-3 py-1 rounded-full text-sm font-medium bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300">
                    {{ $counties->where('is_active', false)->count() }} archived
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                @foreach ($counties->where('is_active', false) as $county)
                    <div class="relative rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5 transition-all duration-200 hover:border-amber-300 dark:hover:border-amber-600/50 hover:shadow-sm">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <div class="text-lg font-medium text-zinc-500 dark:text-zinc-400">
                                    {{ $county->name }}
                                </div>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-xs px-2 py-0.5 rounded bg-zinc-100 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-300 border border-zinc-200 dark:border-zinc-600">
                                        {{ ucfirst($county->region) }}
                                    </span>
                                    <span class="text-sm text-zinc-400 dark:text-zinc-500">/{{ $county->slug }}</span>
                                </div>
                            </div>

                            <span class="flex-shrink-0 inline-flex items-center rounded-full px-2 py-1 text-xs font-medium bg-zinc-100 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-300 border border-zinc-200 dark:border-zinc-600">
                                Inactive
                            </span>
                        </div>

                        <div class="pt-4 border-t border-zinc-200 dark:border-zinc-700">
                            <flux:button
                                size="sm"
                                variant="ghost"
                                class="text-amber-600 dark:text-amber-400 hover:text-amber-700 dark:hover:text-amber-300 hover:bg-amber-50 dark:hover:bg-amber-900/20"
                                wire:click="toggleActive({{ $county->id }})"
                            >
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Activate County
                            </flux:button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- FOOTER CTA --}}
    <div class="mt-12 border-t border-zinc-200 dark:border-zinc-800 pt-8 text-center">
        <div class="inline-block">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-center gap-6">
                <div class="text-center min-w-[100px]">
                    <p class="text-2xl font-bold text-zinc-900 dark:text-white">
                        {{ $counties->count() }}
                    </p>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Total Counties</p>
                </div>
                <div class="hidden sm:block w-px bg-zinc-200 dark:bg-zinc-800 h-8"></div>
                <div class="text-center min-w-[100px]">
                    <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">
                        {{ $counties->where('is_active', true)->count() }}
                    </p>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Active</p>
                </div>
                <div class="hidden sm:block w-px bg-zinc-200 dark:bg-zinc-800 h-8"></div>
                <div class="text-center min-w-[100px]">
                    <p class="text-2xl font-bold text-amber-600 dark:text-amber-400">
                        {{ $counties->where('is_active', false)->count() }}
                    </p>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Inactive</p>
                </div>
            </div>

            <div class="mt-6">
                <flux:button
                    href="{{ route('admin.counties.create') }}"
                    wire:navigate
                    variant="primary"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 text-base font-medium"
                >
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Create New County
                </flux:button>
            </div>
        </div>
    </div>
</div>
