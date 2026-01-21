<div class="w-full px-4 sm:px-6 py-6 bg-gray-50 dark:bg-zinc-900">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                Rules & Templates
            </h1>
            <div class="mt-2 flex flex-wrap items-center gap-2">
                <p class="text-sm text-gray-600 dark:text-zinc-300">
                    This module is <span class="font-semibold text-rose-600 dark:text-rose-400">LOCKED</span>. Templates and core rules are enforced by the backend and cannot be edited from the UI.
                </p>
                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium
                             bg-gray-100 text-gray-700 dark:bg-zinc-800 dark:text-zinc-300 border border-gray-200 dark:border-zinc-700">
                    <svg class="h-3 w-3 mr-1.5 text-gray-400 dark:text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Read-only
                </span>
            </div>
        </div>
    </div>

    {{-- Notice --}}
    <div class="rounded-xl border border-amber-200 dark:border-amber-900/30 bg-amber-50 dark:bg-amber-900/10 p-5 mb-8">
        <div class="flex items-start gap-4">
            <div class="mt-1 flex-shrink-0 flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100 dark:bg-amber-900/20">
                <svg class="h-5 w-5 text-amber-800 dark:text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v3m0 4h.01M10.29 3.86l-7.4 12.8A2 2 0 004.62 20h14.76a2 2 0 001.73-3.34l-7.4-12.8a2 2 0 00-3.42 0z" />
                </svg>
            </div>

            <div class="flex-1">
                <p class="text-sm font-medium text-amber-800 dark:text-amber-200">
                    Backend-enforced governance
                </p>
                <p class="text-sm text-amber-700 dark:text-amber-300 mt-1">
                    This area is intentionally read-only to protect SEO integrity and prevent accidental changes to locked templates and global rules. Changes require backend developer intervention.
                </p>
                <div class="mt-3 flex flex-wrap gap-2">
                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-200">
                        SEO Protection
                    </span>
                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-200">
                        System Integrity
                    </span>
                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-200">
                        Version Control
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Locked templates --}}
    <div class="bg-white dark:bg-zinc-800 rounded-xl border border-gray-200 dark:border-zinc-700 overflow-hidden mb-8">
        <div class="px-6 py-5 border-b border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/50">
            <div class="flex items-center gap-3 mb-1">
                <svg class="h-5 w-5 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Locked Page Templates
                </h2>
            </div>
            <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1">
                Allowed values for <code class="px-1.5 py-0.5 rounded bg-gray-100 dark:bg-zinc-700/50 text-gray-700 dark:text-zinc-300 text-xs font-mono">pages.template_key</code>
            </p>
        </div>

        @php
            $templates = (array) config('page-templates', []);
        @endphp

        <div class="divide-y divide-gray-200 dark:divide-zinc-700">
            @forelse($templates as $key)
                <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition-colors">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 dark:bg-blue-900/30">
                            <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>

                        <div>
                            <div class="font-medium text-gray-900 dark:text-white text-lg">
                                {{ $key }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-zinc-400">
                                Template identifier
                            </div>
                        </div>
                    </div>

                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium
                                 bg-gray-100 text-gray-700 dark:bg-zinc-700 dark:text-zinc-300 border border-gray-200 dark:border-zinc-600">
                        <svg class="h-3 w-3 mr-1 text-gray-400 dark:text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Locked
                    </span>
                </div>
            @empty
                <div class="px-6 py-12 text-center">
                    <div class="inline-flex items-center justify-center h-12 w-12 rounded-xl bg-gray-100 dark:bg-zinc-700 mx-auto mb-4">
                        <svg class="h-6 w-6 text-gray-400 dark:text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">No templates defined</h3>
                    <p class="text-gray-500 dark:text-zinc-400">
                        No templates found in <code class="px-1.5 py-0.5 rounded bg-gray-100 dark:bg-zinc-700/50 text-xs font-mono">config/page-templates.php</code>
                    </p>
                    <p class="text-sm text-gray-400 dark:text-zinc-500 mt-2">
                        Contact your system administrator to configure page templates
                    </p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Roadmap --}}
    <div class="bg-white dark:bg-zinc-800 rounded-xl border border-dashed border-gray-300 dark:border-zinc-700 p-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="flex-shrink-0 flex h-8 w-8 items-center justify-center rounded-lg bg-purple-50 dark:bg-purple-900/30">
                <svg class="h-4 w-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
            </div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                Future Enhancements
            </h2>
        </div>

        <p class="text-sm text-gray-600 dark:text-zinc-300 mb-4">
            Planned features to enhance template governance and rule visibility:
        </p>

        <ul class="space-y-3">
            @foreach([
                'Rules visualization (read-only interface for viewing all SEO and content rules)',
                'Validation matrix per template (visual representation of field requirements)',
                'SEO rule audit checks (automated validation against best practices)',
                'Template version history (track changes to template configurations)',
                'Rule conflict detection (identify overlapping or conflicting rules)'
            ] as $item)
                <li class="flex items-start gap-3">
                    <div class="mt-1 flex-shrink-0 h-2 w-2 rounded-full bg-purple-500"></div>
                    <span class="text-sm text-gray-700 dark:text-zinc-300">{{ $item }}</span>
                </li>
            @endforeach
        </ul>

        <div class="mt-6 pt-4 border-t border-gray-200 dark:border-zinc-700 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex items-center text-xs text-gray-500 dark:text-zinc-400">
                <svg class="h-4 w-4 text-emerald-500 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>Last updated: {{ now()->format('F j, Y') }}</span>
            </div>
            <flux:button
                variant="outline"
                class="border-gray-300 dark:border-zinc-600 text-gray-700 dark:text-zinc-300 hover:bg-gray-50 dark:hover:bg-zinc-700/50"
            >
                <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                View Implementation Docs
            </flux:button>
        </div>
    </div>
</div>
