<div class="w-full px-4 sm:px-6 py-6 bg-gray-50 dark:bg-zinc-900 min-h-screen">

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
            Content Rules
        </h1>
        <p class="text-sm text-gray-600 dark:text-zinc-300 mt-2">
            These rules are <span class="font-semibold text-rose-600 dark:text-rose-400">enforced by the backend</span> at save-time. Violations will block the save with a clear error message.
        </p>
    </div>

    <div class="space-y-6 max-w-5xl">

        {{-- ============================================================ --}}
        {{-- CTA BUTTON RULES --}}
        {{-- ============================================================ --}}
        <div class="bg-white dark:bg-zinc-800 rounded-xl border border-gray-200 dark:border-zinc-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/50">
                <div class="flex items-center gap-3">
                    <div class="h-8 w-8 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                        <svg class="h-4 w-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">CTA Button Rules</h2>
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-300">Locked</span>
                </div>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <p class="text-sm font-medium text-gray-700 dark:text-zinc-300 mb-2">Allowed button labels (exact match):</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach(\App\Support\Sections\SectionValidator::ALLOWED_CTA_LABELS as $label)
                            <span class="px-3 py-1.5 rounded-lg text-sm font-medium bg-emerald-50 text-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/30">
                                "{{ $label }}"
                            </span>
                        @endforeach
                    </div>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700 dark:text-zinc-300 mb-2">Locked subtext (cta_block):</p>
                    <span class="px-3 py-1.5 rounded-lg text-sm font-medium bg-blue-50 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300 border border-blue-200 dark:border-blue-800/30">
                        "{{ \App\Support\Sections\SectionValidator::LOCKED_CTA_SUBTEXT }}"
                    </span>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700 dark:text-zinc-300 mb-2">Applies to section types:</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach(['hero', 'hero_slider', 'cta_block'] as $type)
                            <code class="px-2 py-1 rounded bg-gray-100 dark:bg-zinc-700 text-gray-700 dark:text-zinc-300 text-xs font-mono">{{ $type }}</code>
                        @endforeach
                    </div>
                </div>
                <div class="bg-amber-50 dark:bg-amber-900/10 rounded-lg p-3 border border-amber-200 dark:border-amber-800/30">
                    <p class="text-sm text-amber-800 dark:text-amber-200">
                        Phone numbers in CTA <code class="text-xs">tel:</code> links are <strong>auto-injected</strong> by the backend based on page county. Editors never type phone numbers.
                    </p>
                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- PHONE & FOOTER RULES --}}
        {{-- ============================================================ --}}
        <div class="bg-white dark:bg-zinc-800 rounded-xl border border-gray-200 dark:border-zinc-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/50">
                <div class="flex items-center gap-3">
                    <div class="h-8 w-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                        <svg class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Phone & Footer Rules</h2>
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-300">Locked</span>
                </div>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-200 dark:border-zinc-700">
                                <th class="text-left py-2 pr-4 font-medium text-gray-600 dark:text-zinc-400">Page Type</th>
                                <th class="text-left py-2 pr-4 font-medium text-gray-600 dark:text-zinc-400">Phone</th>
                                <th class="text-left py-2 pr-4 font-medium text-gray-600 dark:text-zinc-400">Footer</th>
                                <th class="text-left py-2 font-medium text-gray-600 dark:text-zinc-400">Address</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-zinc-700/50">
                            <tr>
                                <td class="py-2.5 pr-4 text-gray-900 dark:text-white font-medium">Global (no county)</td>
                                <td class="py-2.5 pr-4 text-gray-700 dark:text-zinc-300">Both phones</td>
                                <td class="py-2.5 pr-4"><code class="px-1.5 py-0.5 rounded bg-gray-100 dark:bg-zinc-700 text-xs">A</code></td>
                                <td class="py-2.5 text-gray-500">No</td>
                            </tr>
                            <tr>
                                <td class="py-2.5 pr-4 text-gray-900 dark:text-white font-medium">Fairfield County</td>
                                <td class="py-2.5 pr-4 text-gray-700 dark:text-zinc-300">(203) 919-9616</td>
                                <td class="py-2.5 pr-4"><code class="px-1.5 py-0.5 rounded bg-gray-100 dark:bg-zinc-700 text-xs">B</code></td>
                                <td class="py-2.5 text-gray-500">No</td>
                            </tr>
                            <tr>
                                <td class="py-2.5 pr-4 text-gray-900 dark:text-white font-medium">New Haven County</td>
                                <td class="py-2.5 pr-4 text-gray-700 dark:text-zinc-300">(203) 466-9148</td>
                                <td class="py-2.5 pr-4"><code class="px-1.5 py-0.5 rounded bg-gray-100 dark:bg-zinc-700 text-xs">C</code></td>
                                <td class="py-2.5 text-gray-500">No</td>
                            </tr>
                            <tr>
                                <td class="py-2.5 pr-4 text-gray-900 dark:text-white font-medium">Orange Office</td>
                                <td class="py-2.5 pr-4 text-gray-700 dark:text-zinc-300">(203) 466-9148</td>
                                <td class="py-2.5 pr-4"><code class="px-1.5 py-0.5 rounded bg-gray-100 dark:bg-zinc-700 text-xs">D</code></td>
                                <td class="py-2.5 text-emerald-600 dark:text-emerald-400 font-medium">Yes (only page with address)</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- SCHEMA RULES --}}
        {{-- ============================================================ --}}
        <div class="bg-white dark:bg-zinc-800 rounded-xl border border-gray-200 dark:border-zinc-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/50">
                <div class="flex items-center gap-3">
                    <div class="h-8 w-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                        <svg class="h-4 w-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Schema (JSON-LD) Rules</h2>
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-300">Locked</span>
                </div>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-200 dark:border-zinc-700">
                                <th class="text-left py-2 pr-4 font-medium text-gray-600 dark:text-zinc-400">Template</th>
                                <th class="text-left py-2 pr-4 font-medium text-gray-600 dark:text-zinc-400">Schema @type</th>
                                <th class="text-left py-2 font-medium text-gray-600 dark:text-zinc-400">Notes</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-zinc-700/50">
                            @foreach([
                                ['office', 'HomeAndConstructionBusiness', 'Orange page ONLY. CT HIC #0668405 included.'],
                                ['faq', 'FAQPage', '/faq/ ONLY. Q&A extracted from faq_list sections.'],
                                ['service_global', 'Service', 'Auto-generated with provider + areaServed.'],
                                ['service_county', 'Service', 'Includes county-level areaServed.'],
                                ['service_town', 'Service', 'Includes town + county areaServed.'],
                                ['All pages', 'BreadcrumbList', 'Always present on every page.'],
                            ] as [$template, $type, $note])
                                <tr>
                                    <td class="py-2.5 pr-4">
                                        <code class="px-1.5 py-0.5 rounded bg-gray-100 dark:bg-zinc-700 text-xs font-mono text-gray-700 dark:text-zinc-300">{{ $template }}</code>
                                    </td>
                                    <td class="py-2.5 pr-4 text-gray-900 dark:text-white font-medium">{{ $type }}</td>
                                    <td class="py-2.5 text-gray-600 dark:text-zinc-400 text-xs">{{ $note }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- CONTENT POLICY --}}
        {{-- ============================================================ --}}
        <div class="bg-white dark:bg-zinc-800 rounded-xl border border-gray-200 dark:border-zinc-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/50">
                <div class="flex items-center gap-3">
                    <div class="h-8 w-8 rounded-lg bg-rose-100 dark:bg-rose-900/30 flex items-center justify-center">
                        <svg class="h-4 w-4 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Content Policy</h2>
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-300">Enforced</span>
                </div>
            </div>
            <div class="p-6 space-y-5">
                {{-- Owner name --}}
                <div class="flex items-start gap-3">
                    <div class="mt-0.5 flex-shrink-0 h-6 w-6 rounded-full bg-rose-100 dark:bg-rose-900/30 flex items-center justify-center">
                        <svg class="h-3.5 w-3.5 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Owner name restricted to /about/ only</p>
                        <p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5">
                            The owner's name cannot appear on service pages, town pages, case studies, or any other page.
                        </p>
                    </div>
                </div>

                {{-- Forbidden terms --}}
                <div class="flex items-start gap-3">
                    <div class="mt-0.5 flex-shrink-0 h-6 w-6 rounded-full bg-rose-100 dark:bg-rose-900/30 flex items-center justify-center">
                        <svg class="h-3.5 w-3.5 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Forbidden terminology</p>
                        <p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5">
                            The following phrases are blocked everywhere:
                        </p>
                        <div class="mt-2 flex flex-wrap gap-2">
                            <span class="px-2.5 py-1 rounded-lg text-xs font-medium bg-rose-50 text-rose-700 dark:bg-rose-900/20 dark:text-rose-300 border border-rose-200 dark:border-rose-800/30">
                                "Stamford Service Area Team"
                            </span>
                        </div>
                        <p class="text-xs text-emerald-600 dark:text-emerald-400 mt-2">
                            Use instead: "Fairfield County Service Area Team" or "New Haven County Service Area Team"
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- LINK DIRECTION RULES --}}
        {{-- ============================================================ --}}
        <div class="bg-white dark:bg-zinc-800 rounded-xl border border-gray-200 dark:border-zinc-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/50">
                <div class="flex items-center gap-3">
                    <div class="h-8 w-8 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                        <svg class="h-4 w-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Hub & Spoke Link Direction</h2>
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-300">Enforced</span>
                </div>
            </div>
            <div class="p-6">
                <p class="text-sm text-gray-600 dark:text-zinc-300 mb-4">
                    Internal links must follow the Hub-and-Spoke model. The backend validates link direction on save.
                </p>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-200 dark:border-zinc-700">
                                <th class="text-left py-2 pr-4 font-medium text-gray-600 dark:text-zinc-400">From</th>
                                <th class="text-left py-2 pr-4 font-medium text-gray-600 dark:text-zinc-400">To</th>
                                <th class="text-center py-2 font-medium text-gray-600 dark:text-zinc-400">Allowed?</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-zinc-700/50">
                            @foreach([
                                ['Service x Town', 'Global Service', true],
                                ['Global Service', 'Service x Town', false],
                                ['Global Service', 'County Hub', true],
                                ['Town Hub', 'Service x Town', true],
                            ] as [$from, $to, $allowed])
                                <tr>
                                    <td class="py-2.5 pr-4 text-gray-900 dark:text-white">{{ $from }}</td>
                                    <td class="py-2.5 pr-4 text-gray-900 dark:text-white">{{ $to }}</td>
                                    <td class="py-2.5 text-center">
                                        @if($allowed)
                                            <span class="inline-flex items-center gap-1 text-emerald-600 dark:text-emerald-400 text-xs font-medium">
                                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                Yes
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 text-rose-600 dark:text-rose-400 text-xs font-medium">
                                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                Blocked
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- LOCKED TEMPLATES --}}
        {{-- ============================================================ --}}
        <div class="bg-white dark:bg-zinc-800 rounded-xl border border-gray-200 dark:border-zinc-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/50">
                <div class="flex items-center gap-3">
                    <div class="h-8 w-8 rounded-lg bg-zinc-100 dark:bg-zinc-700 flex items-center justify-center">
                        <svg class="h-4 w-4 text-zinc-600 dark:text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Page Templates</h2>
                </div>
            </div>
            <div class="p-6">
                @php
                    $templates = (array) config('page-templates', []);
                    $templateSections = config('page-template-sections', []);
                @endphp

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($templates as $tpl)
                        @php
                            $conf = $templateSections[$tpl] ?? [];
                            $allowedCount = count($conf['allowed'] ?? []);
                            $requiredCount = count($conf['required'] ?? []);
                        @endphp
                        <div class="rounded-lg border border-gray-200 dark:border-zinc-700 p-4 hover:border-blue-300 dark:hover:border-blue-700/50 transition-colors">
                            <div class="flex items-center justify-between mb-2">
                                <code class="text-sm font-mono font-medium text-gray-900 dark:text-white">{{ $tpl }}</code>
                                <svg class="h-3.5 w-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <div class="flex items-center gap-3 text-xs text-gray-500 dark:text-zinc-400">
                                <span>{{ $allowedCount }} sections</span>
                                @if($requiredCount > 0)
                                    <span class="text-amber-600 dark:text-amber-400">{{ $requiredCount }} required</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                @if(empty($templates))
                    <p class="text-sm text-gray-500 dark:text-zinc-400 text-center py-6">
                        No templates configured.
                    </p>
                @endif
            </div>
        </div>

    </div>
</div>
