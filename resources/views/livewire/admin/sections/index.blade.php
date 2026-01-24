<div class="p-6 md:p-8">
    <div class="flex flex-col gap-4 mb-6 md:flex-row md:items-end md:justify-between">
        <div>
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-2xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center">
                    <svg class="h-5 w-5 text-zinc-700 dark:text-zinc-200" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M4 6h16M4 12h16M4 18h10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-semibold tracking-tight text-zinc-900 dark:text-white">
                        Sections Library
                    </h1>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">
                        A list of all section types supported by the frontend. The page builder can only use these types.
                    </p>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2 text-xs text-zinc-600 dark:text-zinc-400">
            <span class="inline-flex items-center gap-1.5 rounded-full border border-zinc-200 dark:border-zinc-800 bg-white/70 dark:bg-zinc-900/70 px-3 py-1">
                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                Source
                <span class="font-mono text-zinc-800 dark:text-zinc-200">config/sections.php</span>
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        @forelse($types as $t)
            <div class="group rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm hover:shadow-md transition-shadow">
                <div class="p-5">
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-base font-semibold text-zinc-900 dark:text-white truncate">
                                    {{ $t['label'] ?? $t['type'] }}
                                </span>

                                <span class="inline-flex items-center text-xs font-mono px-2 py-1 rounded-lg
                                    bg-zinc-100 text-zinc-700 border border-zinc-200
                                    dark:bg-zinc-800 dark:text-zinc-200 dark:border-zinc-700">
                                    {{ $t['type'] ?? '-' }}
                                </span>
                            </div>

                            <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-2 leading-relaxed">
                                {{ $t['description'] ?? '—' }}
                            </p>
                        </div>

                        <div class="shrink-0">
                            <span class="inline-flex items-center rounded-xl border border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-800 px-2.5 py-1 text-xs text-zinc-700 dark:text-zinc-200">
                                Registered
                            </span>
                        </div>
                    </div>

                    @if(!empty($t['schema']) && is_array($t['schema']))
                        <div class="mt-4 pt-4 border-t border-zinc-100 dark:border-zinc-800">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                                    Validation fields
                                </span>
                                <span class="text-xs text-zinc-500 dark:text-zinc-400">
                                    {{ count($t['schema']) }} fields
                                </span>
                            </div>

                            <div class="flex flex-wrap gap-2">
                                @foreach(array_keys($t['schema']) as $key)
                                    <span class="inline-flex items-center rounded-lg bg-zinc-100 dark:bg-zinc-800 px-2 py-1 text-xs font-mono text-zinc-700 dark:text-zinc-200">
                                        {{ $key }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="lg:col-span-2 rounded-2xl border border-dashed border-zinc-300 dark:border-zinc-700 bg-white/50 dark:bg-zinc-900/40 p-8 text-center">
                <div class="mx-auto h-12 w-12 rounded-2xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center mb-3">
                    <svg class="h-6 w-6 text-zinc-700 dark:text-zinc-200" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M12 6v12M6 12h12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </div>
                <p class="text-sm font-medium text-zinc-900 dark:text-white">No sections found</p>
                <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">
                    Add section definitions in <span class="font-mono">config/sections.php</span> to populate this library.
                </p>
            </div>
        @endforelse
    </div>
</div>
