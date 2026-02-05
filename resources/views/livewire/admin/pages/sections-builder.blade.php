<div class="relative pb-20">

    {{-- FLASH MESSAGES --}}
    @if (session()->has('success'))
        <div class="mb-4 flex items-center gap-2 rounded-lg border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-300">
            <svg class="h-4 w-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 flex items-center gap-2 rounded-lg border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-300">
            <svg class="h-4 w-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- SECTION COUNT & ADD BUTTON --}}
    <div class="mb-4 flex items-center justify-between">
        <div class="text-sm text-zinc-400">
            {{ count($sections) }} {{ Str::plural('section', count($sections)) }}
            @if (!empty($requiredTypes))
                <span class="text-zinc-600">&middot;</span>
                <span class="text-zinc-500">{{ count($requiredTypes) }} required</span>
            @endif
        </div>
        <button type="button"
                wire:click="$toggle('showAddPanel')"
                class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-medium text-white transition hover:bg-emerald-500">
            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Add Section
        </button>
    </div>

    {{-- ADD SECTION PANEL (Grouped) --}}
    @if ($showAddPanel)
        <div class="mb-6 rounded-xl border border-zinc-700/60 bg-zinc-900/80 overflow-hidden">
            <div class="flex items-center justify-between border-b border-zinc-800 px-4 py-3">
                <h3 class="text-sm font-semibold text-white">Add Section</h3>
                <button type="button" wire:click="$set('showAddPanel', false)" class="text-zinc-500 hover:text-zinc-300 transition">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Search filter --}}
            <div class="border-b border-zinc-800 px-4 py-2">
                <input type="text"
                       wire:model.live.debounce.200ms="addPanelSearch"
                       placeholder="Filter sections..."
                       class="w-full rounded-md border border-zinc-700 bg-zinc-800/50 px-3 py-1.5 text-sm text-white placeholder-zinc-500 focus:border-emerald-500/50 focus:outline-none focus:ring-1 focus:ring-emerald-500/30" />
            </div>

            <div class="max-h-80 overflow-y-auto p-4 space-y-4">
                @foreach ($groupedSections as $category => $items)
                    @php
                        $search = strtolower($addPanelSearch);
                        $visibleItems = $search === ''
                            ? $items
                            : array_filter($items, fn($i) => str_contains(strtolower($i['label']), $search));
                    @endphp

                    @if (!empty($visibleItems))
                        <div class="space-y-1.5">
                            <div class="text-[10px] font-bold uppercase tracking-widest text-zinc-500 px-1">{{ $category }}</div>
                            @foreach ($visibleItems as $item)
                                @if ($item['allowed'])
                                    <button type="button"
                                            wire:click="addSection('{{ $item['type'] }}')"
                                            class="group flex w-full items-start gap-3 rounded-lg px-3 py-2 text-left transition hover:bg-zinc-800">
                                        <div class="mt-0.5 flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-md bg-emerald-500/10 text-emerald-400 transition group-hover:bg-emerald-500/20">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-sm font-medium text-zinc-200">{{ $item['label'] }}</div>
                                            <div class="text-xs text-zinc-500 truncate">{{ $item['description'] }}</div>
                                        </div>
                                    </button>
                                @else
                                    <div class="group relative flex w-full items-start gap-3 rounded-lg px-3 py-2 opacity-40 cursor-not-allowed"
                                         title="Not available for {{ Str::headline($page->template_key) }} template">
                                        <div class="mt-0.5 flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-md bg-zinc-800 text-zinc-600">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-sm font-medium text-zinc-500">{{ $item['label'] }}</div>
                                            <div class="text-xs text-zinc-600 truncate">Not available for this template</div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endif

    {{-- VALIDATION ERRORS --}}
    @error('newSectionType')
        <div class="mb-4 rounded-lg border border-red-500/30 bg-red-500/10 px-4 py-2 text-sm text-red-300">{{ $message }}</div>
    @enderror

    {{-- SECTIONS LIST --}}
    <div class="space-y-2">
        @forelse ($sections as $index => $section)
            @php
                $isCollapsed = $collapsed[$index] ?? true;
                $isActive = $section['is_active'] ?? true;
                $isRequired = in_array($section['type'], $requiredTypes ?? [], true);
                $label = $sectionRegistry[$section['type']]['label'] ?? $section['type'];
                $desc = $sectionRegistry[$section['type']]['description'] ?? '';
            @endphp

            <div wire:key="section-{{ $index }}-{{ $section['type'] }}"
                 class="group rounded-xl border transition-all duration-200
                        {{ $isActive
                            ? 'border-zinc-700/60 bg-zinc-900/80'
                            : 'border-zinc-800/40 bg-zinc-950/60' }}">

                {{-- SECTION HEADER --}}
                <div class="flex items-center gap-3 px-4 py-3 cursor-pointer select-none"
                     wire:click="toggleCollapse({{ $index }})">

                    {{-- Drag handle visual --}}
                    <div class="flex flex-col gap-0.5 text-zinc-600 opacity-0 group-hover:opacity-100 transition">
                        <span class="block h-0.5 w-3 rounded bg-current"></span>
                        <span class="block h-0.5 w-3 rounded bg-current"></span>
                        <span class="block h-0.5 w-3 rounded bg-current"></span>
                    </div>

                    {{-- Order number --}}
                    <div class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-md text-[10px] font-bold
                                {{ $isActive ? 'bg-zinc-800 text-zinc-400' : 'bg-zinc-900 text-zinc-600' }}">
                        {{ $index + 1 }}
                    </div>

                    {{-- Section info --}}
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-medium {{ $isActive ? 'text-white' : 'text-zinc-500' }}">
                                {{ $label }}
                            </span>
                            @if ($isRequired)
                                <span class="rounded bg-amber-500/10 px-1.5 py-0.5 text-[10px] font-medium text-amber-400">Required</span>
                            @endif
                            @if (!$isActive)
                                <span class="rounded bg-zinc-800 px-1.5 py-0.5 text-[10px] font-medium text-zinc-500">Hidden</span>
                            @endif
                        </div>
                        @if ($isCollapsed && $desc)
                            <div class="mt-0.5 text-xs text-zinc-600 truncate max-w-md">{{ $desc }}</div>
                        @endif
                    </div>

                    {{-- Status dot --}}
                    <div class="h-2 w-2 rounded-full flex-shrink-0 {{ $isActive ? 'bg-emerald-400' : 'bg-zinc-600' }}"
                         title="{{ $isActive ? 'Active' : 'Inactive' }}"></div>

                    {{-- Collapse chevron --}}
                    <svg class="h-4 w-4 text-zinc-500 transition-transform duration-200 {{ $isCollapsed ? '' : 'rotate-180' }}"
                         fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>

                {{-- SECTION TOOLBAR (visible on hover or when expanded) --}}
                <div class="flex items-center gap-1 border-t border-zinc-800/50 px-4 py-1.5
                            {{ $isCollapsed ? 'hidden group-hover:flex' : 'flex' }}">

                    {{-- Move Up --}}
                    <button type="button" wire:click.stop="moveUp({{ $index }})"
                            @if($index === 0) disabled @endif
                            class="rounded-md p-1.5 text-zinc-500 transition hover:bg-zinc-800 hover:text-zinc-300 disabled:opacity-25 disabled:cursor-not-allowed"
                            title="Move up">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
                    </button>

                    {{-- Move Down --}}
                    <button type="button" wire:click.stop="moveDown({{ $index }})"
                            @if($index >= count($sections) - 1) disabled @endif
                            class="rounded-md p-1.5 text-zinc-500 transition hover:bg-zinc-800 hover:text-zinc-300 disabled:opacity-25 disabled:cursor-not-allowed"
                            title="Move down">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </button>

                    <div class="mx-1 h-4 w-px bg-zinc-800"></div>

                    {{-- Toggle Active --}}
                    <button type="button" wire:click.stop="toggleActive({{ $index }})"
                            class="inline-flex items-center gap-1 rounded-md px-2 py-1 text-xs transition
                                   {{ $isActive
                                       ? 'text-emerald-400 hover:bg-emerald-500/10'
                                       : 'text-zinc-500 hover:bg-zinc-800' }}"
                            title="{{ $isActive ? 'Set inactive' : 'Set active' }}">
                        @if ($isActive)
                            <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            Active
                        @else
                            <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd"/><path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z"/></svg>
                            Hidden
                        @endif
                    </button>

                    {{-- Duplicate --}}
                    <button type="button" wire:click.stop="duplicateSection({{ $index }})"
                            class="inline-flex items-center gap-1 rounded-md px-2 py-1 text-xs text-zinc-500 transition hover:bg-zinc-800 hover:text-zinc-300"
                            title="Duplicate">
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        Duplicate
                    </button>

                    <div class="flex-1"></div>

                    {{-- Delete --}}
                    @if ($confirmingDelete === $index)
                        <div class="flex items-center gap-1.5" wire:click.stop>
                            <span class="text-[11px] text-red-400">Delete this section?</span>
                            <button type="button" wire:click="deleteSection({{ $index }})"
                                    class="rounded-md bg-red-600 px-2 py-0.5 text-[11px] font-medium text-white transition hover:bg-red-500">
                                Confirm
                            </button>
                            <button type="button" wire:click="cancelDelete"
                                    class="rounded-md bg-zinc-700 px-2 py-0.5 text-[11px] font-medium text-zinc-300 transition hover:bg-zinc-600">
                                Cancel
                            </button>
                        </div>
                    @else
                        <button type="button" wire:click.stop="confirmDelete({{ $index }})"
                                class="inline-flex items-center gap-1 rounded-md px-2 py-1 text-xs text-zinc-600 transition hover:bg-red-500/10 hover:text-red-400"
                                title="Delete">
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Delete
                        </button>
                    @endif
                </div>

                {{-- SECTION BODY (always rendered, hidden via CSS to preserve Livewire state) --}}
                <div class="border-t border-zinc-800/50 {{ $isCollapsed ? 'hidden' : '' }}">
                    <div class="p-5">
                        @include('livewire.admin.pages.sections.generic', [
                            'section' => $section,
                            'index' => $index,
                        ])
                    </div>
                </div>

            </div>

        @empty
            <div class="flex flex-col items-center justify-center rounded-xl border border-dashed border-zinc-700 bg-zinc-900/40 py-16">
                <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-zinc-800">
                    <svg class="h-6 w-6 text-zinc-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                </div>
                <p class="text-sm font-medium text-zinc-400">No sections yet</p>
                <p class="mt-1 text-xs text-zinc-600">Click "Add Section" above to start building the page</p>
            </div>
        @endforelse
    </div>

    {{-- STICKY SAVE BAR --}}
    @if (count($sections))
        <div class="fixed bottom-0 left-0 right-0 z-40 border-t border-zinc-700/60 bg-zinc-900/95 backdrop-blur-md">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-3">
                <div class="text-xs text-zinc-500">
                    {{ count($sections) }} {{ Str::plural('section', count($sections)) }}
                    &middot;
                    {{ collect($sections)->where('is_active', true)->count() }} active
                </div>
                <button type="button"
                        wire:click="save"
                        wire:loading.attr="disabled"
                        wire:target="save"
                        class="inline-flex items-center gap-2 rounded-lg bg-white px-5 py-2 text-sm font-semibold text-zinc-900 shadow-lg transition hover:bg-zinc-100 disabled:opacity-50">
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

    {{-- MEDIA PICKER MODAL --}}
    @if ($showMediaModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm"
             wire:click.self="closeMediaPicker">
            <div class="w-full max-w-3xl max-h-[80vh] rounded-2xl bg-zinc-900 border border-zinc-700/60 shadow-2xl flex flex-col overflow-hidden">

                {{-- Modal header --}}
                <div class="flex items-center justify-between px-5 py-4 border-b border-zinc-800">
                    <h3 class="text-base font-semibold text-white">Select Image</h3>
                    <button type="button" wire:click="closeMediaPicker"
                            class="rounded-lg p-1 text-zinc-500 transition hover:bg-zinc-800 hover:text-zinc-300">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- Search --}}
                <div class="px-5 py-3 border-b border-zinc-800">
                    <input type="text"
                           wire:model.live.debounce.300ms="mediaSearch"
                           placeholder="Search media..."
                           class="w-full rounded-lg border border-zinc-700 bg-zinc-800/50 px-3 py-2 text-sm text-white placeholder-zinc-500 focus:border-emerald-500/50 focus:outline-none focus:ring-1 focus:ring-emerald-500/30" />
                </div>

                {{-- Grid --}}
                <div class="flex-1 overflow-y-auto p-5">
                    @if ($mediaItems->count())
                        <div class="grid grid-cols-4 sm:grid-cols-5 md:grid-cols-6 gap-2">
                            @foreach ($mediaItems as $media)
                                <button type="button"
                                        wire:click="selectMedia({{ $media->id }})"
                                        class="group relative aspect-square rounded-lg overflow-hidden border-2 border-transparent hover:border-emerald-500 focus:border-emerald-500 bg-zinc-800 transition">
                                    <img src="{{ $media->url }}"
                                         alt="{{ $media->alt_text ?? $media->file_name }}"
                                         class="w-full h-full object-cover transition group-hover:scale-105" loading="lazy" />
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition">
                                        <div class="absolute inset-x-0 bottom-0 px-1.5 py-1 text-[10px] text-zinc-200 truncate">
                                            {{ $media->file_name }}
                                        </div>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-16">
                            <svg class="h-8 w-8 text-zinc-600 mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5a1.5 1.5 0 001.5-1.5V4.5A1.5 1.5 0 0020.25 3H3.75A1.5 1.5 0 002.25 4.5v15A1.5 1.5 0 003.75 21z"/></svg>
                            <p class="text-sm text-zinc-500">No images found</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

</div>
