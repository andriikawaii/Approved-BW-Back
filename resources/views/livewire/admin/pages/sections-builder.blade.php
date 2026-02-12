@php
    // Color accents per section category
    $typeColors = [
        'hero'                 => 'border-l-purple-500',
        'hero_slider'          => 'border-l-purple-500',
        'rich_text'            => 'border-l-blue-500',
        'local_context'        => 'border-l-blue-500',
        'trust_bar'            => 'border-l-amber-500',
        'stats_counter'        => 'border-l-amber-500',
        'testimonials'         => 'border-l-amber-500',
        'project_highlights'   => 'border-l-amber-500',
        'logo_strip'           => 'border-l-amber-500',
        'before_after'         => 'border-l-amber-500',
        'services_grid'        => 'border-l-cyan-500',
        'service_includes'     => 'border-l-cyan-500',
        'pricing_table'        => 'border-l-cyan-500',
        'timeline_block'       => 'border-l-cyan-500',
        'process_steps'        => 'border-l-cyan-500',
        'service_area_links'   => 'border-l-cyan-500',
        'cta_block'            => 'border-l-emerald-500',
        'lead_form'            => 'border-l-emerald-500',
        'image_gallery'        => 'border-l-pink-500',
        'map_embed'            => 'border-l-pink-500',
        'areas_we_serve_cards' => 'border-l-orange-500',
        'town_list'            => 'border-l-orange-500',
        'faq_list'             => 'border-l-indigo-500',
        'case_study_header'    => 'border-l-rose-500',
        'case_study_meta'      => 'border-l-rose-500',
        'case_study_body'      => 'border-l-rose-500',
        'case_study_gallery'   => 'border-l-rose-500',
    ];

    $categoryIcons = [
        'Hero'          => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5a1.5 1.5 0 001.5-1.5V4.5A1.5 1.5 0 0020.25 3H3.75A1.5 1.5 0 002.25 4.5v15A1.5 1.5 0 003.75 21z"/>',
        'Content'       => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12"/>',
        'Social Proof'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>',
        'Services'      => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>',
        'CTA & Forms'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.042 21.672L13.684 16.6m0 0l-2.51 2.225.569-9.47 5.227 7.917-3.286-.672zM12 2.25V4.5m5.834.166l-1.591 1.591M20.25 10.5H18M7.757 14.743l-1.59 1.59M6 10.5H3.75m4.007-4.243l-1.59-1.59"/>',
        'Media'         => '<path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z"/><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z"/>',
        'Location'      => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>',
        'Case Studies'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>',
        'Other'         => '<path stroke-linecap="round" stroke-linejoin="round" d="M6.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM12.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM18.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/>',
    ];
@endphp

<div class="relative pb-24">

    {{-- ================ --}}
    {{-- FLASH MESSAGES   --}}
    {{-- ================ --}}
    @if (session()->has('success'))
        <div class="mb-5 flex items-center gap-2.5 rounded-lg border border-emerald-200 dark:border-emerald-500/30
                    bg-emerald-50 dark:bg-emerald-500/10 px-4 py-3 text-sm text-emerald-700 dark:text-emerald-300"
             x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <svg class="h-4 w-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-5 flex items-center gap-2.5 rounded-lg border border-red-200 dark:border-red-500/30
                    bg-red-50 dark:bg-red-500/10 px-4 py-3 text-sm text-red-700 dark:text-red-300">
            <svg class="h-4 w-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
            {{ session('error') }}
        </div>
    @endif

    @error('newSectionType')
        <div class="mb-5 rounded-lg border border-red-200 dark:border-red-500/30 bg-red-50 dark:bg-red-500/10 px-4 py-2.5 text-sm text-red-700 dark:text-red-300">{{ $message }}</div>
    @enderror

    {{-- ==================== --}}
    {{-- TOP BAR: count + add --}}
    {{-- ==================== --}}
    <div class="mb-5 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-zinc-400">
                <span class="font-semibold text-gray-800 dark:text-zinc-200">{{ count($sections) }}</span>
                {{ Str::plural('section', count($sections)) }}
            </div>
            @if (!empty($requiredTypes))
                <span class="text-xs text-gray-400 dark:text-zinc-600">&middot;</span>
                <span class="text-xs text-amber-600 dark:text-amber-400 font-medium">{{ count($requiredTypes) }} required</span>
            @endif
        </div>
        <button type="button"
                wire:click="$toggle('showAddPanel')"
                class="inline-flex items-center gap-2 rounded-lg bg-purple-600 px-4 py-2 text-sm font-medium text-white
                       shadow-sm hover:bg-purple-700 transition active:scale-[0.98]">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Add Section
        </button>
    </div>

    {{-- ========================= --}}
    {{-- ADD SECTION PANEL (modal) --}}
    {{-- ========================= --}}
    @if ($showAddPanel)
        <div class="fixed inset-0 z-50 flex items-start justify-center pt-[10vh] bg-black/50 dark:bg-black/70 backdrop-blur-sm"
             wire:click.self="$set('showAddPanel', false)"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">

            <div class="w-full max-w-lg rounded-2xl bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700
                        shadow-2xl overflow-hidden" @click.stop>

                {{-- Header --}}
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-zinc-800">
                    <h3 class="text-base font-bold text-gray-900 dark:text-white">Add Section</h3>
                    <button type="button" wire:click="$set('showAddPanel', false)"
                            class="rounded-lg p-1.5 text-gray-400 dark:text-zinc-500 hover:bg-gray-100 dark:hover:bg-zinc-800 transition">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- Search --}}
                <div class="px-5 py-3 border-b border-gray-100 dark:border-zinc-800">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400 dark:text-zinc-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                        <input type="text"
                               wire:model.live.debounce.200ms="addPanelSearch"
                               placeholder="Search sections..."
                               class="w-full pl-10 rounded-lg border border-gray-200 dark:border-zinc-700
                                      bg-gray-50 dark:bg-zinc-800/50 px-3 py-2.5 text-sm
                                      text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500
                                      focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-500/20" />
                    </div>
                </div>

                {{-- Grouped sections --}}
                <div class="max-h-[50vh] overflow-y-auto p-3">
                    @foreach ($groupedSections as $category => $items)
                        @php
                            $search = strtolower($addPanelSearch);
                            $visibleItems = $search === ''
                                ? $items
                                : array_filter($items, fn($i) => str_contains(strtolower($i['label']), $search));
                        @endphp

                        @if (!empty($visibleItems))
                            <div class="mb-3">
                                <div class="flex items-center gap-2 px-2 py-1.5 mb-1">
                                    <svg class="h-3.5 w-3.5 text-gray-400 dark:text-zinc-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        {!! $categoryIcons[$category] ?? $categoryIcons['Other'] !!}
                                    </svg>
                                    <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400 dark:text-zinc-500">{{ $category }}</span>
                                </div>

                                @foreach ($visibleItems as $item)
                                    @if ($item['allowed'])
                                        <button type="button"
                                                wire:click="addSection('{{ $item['type'] }}')"
                                                class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-left transition
                                                       hover:bg-purple-50 dark:hover:bg-purple-500/10 group">
                                            <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg
                                                        bg-gray-100 dark:bg-zinc-800 text-gray-500 dark:text-zinc-400
                                                        group-hover:bg-purple-100 dark:group-hover:bg-purple-500/20
                                                        group-hover:text-purple-600 dark:group-hover:text-purple-400 transition">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="text-sm font-medium text-gray-800 dark:text-zinc-200">{{ $item['label'] }}</div>
                                                @if ($item['description'])
                                                    <div class="text-xs text-gray-400 dark:text-zinc-500 truncate">{{ $item['description'] }}</div>
                                                @endif
                                            </div>
                                        </button>
                                    @else
                                        <div class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 opacity-35 cursor-not-allowed"
                                             title="Not available for {{ Str::headline($page->template_key) }}">
                                            <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg bg-gray-100 dark:bg-zinc-800 text-gray-400 dark:text-zinc-600">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                            </div>
                                            <div class="min-w-0">
                                                <div class="text-sm font-medium text-gray-400 dark:text-zinc-600">{{ $item['label'] }}</div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    @endforeach
                </div>

            </div>
        </div>
    @endif

    {{-- ================ --}}
    {{-- SECTIONS LIST    --}}
    {{-- ================ --}}
    <div class="space-y-3">
        @forelse ($sections as $index => $section)
            @php
                $isCollapsed = $collapsed[$index] ?? true;
                $isActive = $section['is_active'] ?? true;
                $isRequired = in_array($section['type'], $requiredTypes ?? [], true);
                $label = $sectionRegistry[$section['type']]['label'] ?? $section['type'];
                $desc = $sectionRegistry[$section['type']]['description'] ?? '';
                $borderColor = $typeColors[$section['type']] ?? 'border-l-gray-400';
            @endphp

            <div wire:key="section-{{ $index }}-{{ $section['type'] }}"
                 class="rounded-xl border border-l-[3px] overflow-hidden transition-all duration-200
                        {{ $borderColor }}
                        {{ $isActive
                            ? 'border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800/80'
                            : 'border-gray-100 dark:border-zinc-800 bg-gray-50 dark:bg-zinc-900/60 opacity-60' }}">

                {{-- SECTION HEADER --}}
                <div class="flex items-center gap-3 px-4 py-3.5 cursor-pointer select-none
                            hover:bg-gray-50 dark:hover:bg-zinc-700/30 transition"
                     wire:click="toggleCollapse({{ $index }})">

                    {{-- Order number --}}
                    <div class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-lg text-xs font-bold
                                {{ $isActive
                                    ? 'bg-gray-100 dark:bg-zinc-700 text-gray-600 dark:text-zinc-300'
                                    : 'bg-gray-50 dark:bg-zinc-800 text-gray-400 dark:text-zinc-600' }}">
                        {{ $index + 1 }}
                    </div>

                    {{-- Section info --}}
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-semibold {{ $isActive ? 'text-gray-800 dark:text-white' : 'text-gray-400 dark:text-zinc-500' }}">
                                {{ $label }}
                            </span>
                            @if ($isRequired)
                                <span class="rounded-md bg-amber-50 dark:bg-amber-500/10 px-1.5 py-0.5 text-[10px] font-semibold text-amber-600 dark:text-amber-400 ring-1 ring-amber-200 dark:ring-amber-500/20">REQ</span>
                            @endif
                            @if (!$isActive)
                                <span class="rounded-md bg-gray-100 dark:bg-zinc-800 px-1.5 py-0.5 text-[10px] font-medium text-gray-400 dark:text-zinc-500">HIDDEN</span>
                            @endif
                        </div>
                    </div>

                    {{-- Quick actions (always visible) --}}
                    <div class="flex items-center gap-0.5" wire:click.stop>
                        {{-- Move Up --}}
                        <button type="button" wire:click="moveUp({{ $index }})"
                                @if($index === 0) disabled @endif
                                class="rounded-md p-1.5 text-gray-400 dark:text-zinc-500 transition
                                       hover:bg-gray-100 dark:hover:bg-zinc-700 hover:text-gray-600 dark:hover:text-zinc-300
                                       disabled:opacity-20 disabled:cursor-not-allowed"
                                title="Move up">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
                        </button>

                        {{-- Move Down --}}
                        <button type="button" wire:click="moveDown({{ $index }})"
                                @if($index >= count($sections) - 1) disabled @endif
                                class="rounded-md p-1.5 text-gray-400 dark:text-zinc-500 transition
                                       hover:bg-gray-100 dark:hover:bg-zinc-700 hover:text-gray-600 dark:hover:text-zinc-300
                                       disabled:opacity-20 disabled:cursor-not-allowed"
                                title="Move down">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>

                        <div class="mx-0.5 h-4 w-px bg-gray-200 dark:bg-zinc-700"></div>

                        {{-- Toggle active --}}
                        <button type="button" wire:click="toggleActive({{ $index }})"
                                class="rounded-md p-1.5 transition
                                       {{ $isActive
                                           ? 'text-emerald-500 hover:bg-emerald-50 dark:hover:bg-emerald-500/10'
                                           : 'text-gray-400 dark:text-zinc-600 hover:bg-gray-100 dark:hover:bg-zinc-700' }}"
                                title="{{ $isActive ? 'Hide section' : 'Show section' }}">
                            @if ($isActive)
                                <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z"/><path fill-rule="evenodd" d="M.664 10.59a1.651 1.651 0 010-1.186A10.004 10.004 0 0110 3c4.257 0 7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0110 17c-4.257 0-7.893-2.66-9.336-6.41zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>
                            @else
                                <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd"/><path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z"/></svg>
                            @endif
                        </button>

                        {{-- Duplicate --}}
                        <button type="button" wire:click="duplicateSection({{ $index }})"
                                class="rounded-md p-1.5 text-gray-400 dark:text-zinc-500 transition
                                       hover:bg-gray-100 dark:hover:bg-zinc-700 hover:text-gray-600 dark:hover:text-zinc-300"
                                title="Duplicate">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75"/></svg>
                        </button>

                        <div class="mx-0.5 h-4 w-px bg-gray-200 dark:bg-zinc-700"></div>

                        {{-- Delete --}}
                        @if ($confirmingDelete === $index)
                            <div class="flex items-center gap-1.5 ml-1">
                                <span class="text-[11px] text-red-500 font-medium">Delete?</span>
                                <button type="button" wire:click="deleteSection({{ $index }})"
                                        class="rounded-md bg-red-500 px-2 py-0.5 text-[11px] font-medium text-white hover:bg-red-600 transition">
                                    Yes
                                </button>
                                <button type="button" wire:click="cancelDelete"
                                        class="rounded-md bg-gray-200 dark:bg-zinc-700 px-2 py-0.5 text-[11px] font-medium
                                               text-gray-600 dark:text-zinc-300 hover:bg-gray-300 dark:hover:bg-zinc-600 transition">
                                    No
                                </button>
                            </div>
                        @else
                            <button type="button" wire:click="confirmDelete({{ $index }})"
                                    class="rounded-md p-1.5 text-gray-400 dark:text-zinc-600 transition
                                           hover:bg-red-50 dark:hover:bg-red-500/10 hover:text-red-500"
                                    title="Delete">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                            </button>
                        @endif
                    </div>

                    {{-- Collapse chevron --}}
                    <svg class="h-4 w-4 text-gray-400 dark:text-zinc-500 transition-transform duration-200 flex-shrink-0
                                {{ $isCollapsed ? '' : 'rotate-180' }}"
                         fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>

                {{-- SECTION BODY --}}
                <div class="border-t border-gray-100 dark:border-zinc-700/50 {{ $isCollapsed ? 'hidden' : '' }}">
                    <div class="p-5 bg-gray-50/50 dark:bg-zinc-900/30">
                        @include('livewire.admin.pages.sections.generic', [
                            'section' => $section,
                            'index' => $index,
                        ])
                    </div>
                </div>

            </div>

        @empty
            {{-- EMPTY STATE --}}
            <div class="flex flex-col items-center justify-center rounded-2xl border-2 border-dashed
                        border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800/40 py-20 px-6">
                <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-2xl
                            bg-purple-50 dark:bg-purple-900/20">
                    <svg class="h-8 w-8 text-purple-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                </div>
                <p class="text-base font-semibold text-gray-700 dark:text-zinc-300 mb-1">No sections yet</p>
                <p class="text-sm text-gray-400 dark:text-zinc-500 mb-5 text-center max-w-xs">
                    Start building your page by adding the first section
                </p>
                <button type="button"
                        wire:click="$toggle('showAddPanel')"
                        class="inline-flex items-center gap-2 rounded-lg bg-purple-600 px-5 py-2.5 text-sm font-medium text-white
                               hover:bg-purple-700 transition active:scale-[0.98]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Add First Section
                </button>
            </div>
        @endforelse
    </div>

    {{-- ================ --}}
    {{-- STICKY SAVE BAR  --}}
    {{-- ================ --}}
    @if (count($sections))
        <div class="fixed bottom-0 left-0 right-0 z-40 border-t border-gray-200 dark:border-zinc-700
                    bg-white/95 dark:bg-zinc-900/95 backdrop-blur-md shadow-[0_-4px_20px_rgba(0,0,0,0.08)]">
            <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-3.5">
                <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-zinc-400">
                    <span><span class="font-semibold text-gray-700 dark:text-zinc-200">{{ count($sections) }}</span> {{ Str::plural('section', count($sections)) }}</span>
                    <span class="text-gray-300 dark:text-zinc-700">&middot;</span>
                    <span><span class="font-semibold text-emerald-600 dark:text-emerald-400">{{ collect($sections)->where('is_active', true)->count() }}</span> active</span>
                </div>
                <button type="button"
                        wire:click="save"
                        wire:loading.attr="disabled"
                        wire:target="save"
                        class="inline-flex items-center gap-2 rounded-lg bg-purple-600 px-6 py-2.5 text-sm font-semibold text-white
                               shadow-lg shadow-purple-500/20 hover:bg-purple-700 transition active:scale-[0.98]
                               disabled:opacity-60 disabled:cursor-not-allowed">
                    <span wire:loading.remove wire:target="save">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    </span>
                    <span wire:loading wire:target="save">
                        <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                    </span>
                    <span wire:loading.remove wire:target="save">Save Sections</span>
                    <span wire:loading wire:target="save">Saving...</span>
                </button>
            </div>
        </div>
    @endif

    {{-- =================== --}}
    {{-- MEDIA PICKER MODAL  --}}
    {{-- =================== --}}
    @if ($showMediaModal)
        <div wire:key="media-picker-modal"
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 dark:bg-black/70 backdrop-blur-sm"
             wire:click.self="closeMediaPicker">
            <div class="w-full max-w-3xl max-h-[80vh] rounded-2xl bg-white dark:bg-zinc-900
                        border border-gray-200 dark:border-zinc-700 shadow-2xl flex flex-col overflow-hidden"
                 @click.stop>

                {{-- Header --}}
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-zinc-800">
                    <div class="flex items-center gap-3">
                        <h3 class="text-base font-bold text-gray-900 dark:text-white">
                            {{ $showMediaModal && $isMultiSelectMode ? 'Select Multiple Images' : 'Select Image' }}
                        </h3>
                        @if ($showMediaModal && $isMultiSelectMode && count($selectedMediaIds) > 0)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold
                                       bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-300">
                                {{ count($selectedMediaIds) }} selected
                            </span>
                        @endif
                    </div>
                    <button type="button" wire:click="closeMediaPicker"
                            class="rounded-lg p-1.5 text-gray-400 dark:text-zinc-500 hover:bg-gray-100 dark:hover:bg-zinc-800 transition">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- Search --}}
                <div class="px-5 py-3 border-b border-gray-100 dark:border-zinc-800">
                    <input type="text"
                           wire:model.live.debounce.300ms="mediaSearch"
                           placeholder="Search media..."
                           class="w-full rounded-lg border border-gray-200 dark:border-zinc-700
                                  bg-gray-50 dark:bg-zinc-800/50 px-3 py-2.5 text-sm
                                  text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500
                                  focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-500/20" />
                </div>

                {{-- Grid --}}
                <div class="flex-1 overflow-y-auto p-5">
                    @if ($mediaItems->count())
                        <div class="grid grid-cols-4 sm:grid-cols-5 md:grid-cols-6 gap-2">
                            @foreach ($mediaItems as $media)
                                @php
                                    $isSelected = in_array($media->id, $selectedMediaIds ?? [], true);
                                @endphp
                                <button type="button"
                                        wire:key="media-btn-{{ $media->id }}"
                                        wire:click="selectMedia({{ $media->id }})"
                                        class="group relative aspect-square rounded-lg overflow-hidden border-2 transition
                                               {{ $isMultiSelectMode && $isSelected
                                                   ? 'border-purple-500 bg-purple-50 dark:bg-purple-900/20'
                                                   : 'border-transparent hover:border-purple-500 focus:border-purple-500 bg-gray-100 dark:bg-zinc-800' }}">
                                    <img src="{{ $media->url }}"
                                         alt="{{ $media->alt_text ?? $media->file_name }}"
                                         class="w-full h-full object-cover transition group-hover:scale-105" loading="lazy" />

                                    {{-- Multi-select checkbox --}}
                                    @if ($isMultiSelectMode)
                                        <div class="absolute top-2 right-2">
                                            <div class="flex items-center justify-center h-5 w-5 rounded border-2
                                                       {{ $isSelected
                                                           ? 'border-purple-500 bg-purple-500'
                                                           : 'border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-800' }}">
                                                @if ($isSelected)
                                                    <svg class="h-3 w-3 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition">
                                        <div class="absolute inset-x-0 bottom-0 px-1.5 py-1 text-[10px] text-white truncate">
                                            {{ $media->file_name }}
                                        </div>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-16">
                            <svg class="h-8 w-8 text-gray-300 dark:text-zinc-600 mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5a1.5 1.5 0 001.5-1.5V4.5A1.5 1.5 0 0020.25 3H3.75A1.5 1.5 0 002.25 4.5v15A1.5 1.5 0 003.75 21z"/></svg>
                            <p class="text-sm text-gray-400 dark:text-zinc-500">No images found</p>
                        </div>
                    @endif
                </div>

                {{-- Footer actions (for multi-select) --}}
                @if ($showMediaModal && $isMultiSelectMode)
                    <div class="flex items-center justify-between px-5 py-4 border-t border-gray-100 dark:border-zinc-800">
                        <button type="button" wire:click="closeMediaPicker"
                                class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium text-gray-700 dark:text-zinc-300
                                       hover:bg-gray-100 dark:hover:bg-zinc-800 transition">
                            Cancel
                        </button>
                        <button type="button"
                                wire:click="applyMultipleMedia"
                                @if(empty($selectedMediaIds)) disabled @endif
                                class="inline-flex items-center gap-2 rounded-lg bg-purple-600 px-4 py-2 text-sm font-medium text-white
                                       shadow-sm hover:bg-purple-700 transition active:scale-[0.98]
                                       disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            Add {{ count($selectedMediaIds) }} Image{{ count($selectedMediaIds) !== 1 ? 's' : '' }}
                        </button>
                    </div>
                @endif
            </div>
        </div>
    @endif

</div>
