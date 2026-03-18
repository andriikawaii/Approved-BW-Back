@php
    use Illuminate\Support\Str;
@endphp

<div x-data="sectionsBuilderDnD($wire)" x-init="init()" class="relative space-y-4 pb-24">

    {{-- Flash messages --}}
    @if (session()->has('success'))
        <div class="flex items-center gap-3 rounded-lg border border-emerald-500/20 bg-emerald-500/10 px-4 py-3 text-sm font-medium text-emerald-400" role="status">
            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="flex items-center gap-3 rounded-lg border border-red-500/20 bg-red-500/10 px-4 py-3 text-sm font-medium text-red-400" role="alert">
            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
            {{ session('error') }}
        </div>
    @endif

    @error('newSectionType')
        <div class="flex items-center gap-3 rounded-lg border border-red-500/20 bg-red-500/10 px-4 py-3 text-sm font-medium text-red-400">
            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
            {{ $message }}
        </div>
    @enderror

    {{-- ===== HEADER CARD ===== --}}
    <div class="flex flex-wrap items-center justify-between gap-3 rounded-xl border border-edge bg-surface px-5 py-4">
        <div>
            <h2 class="text-base font-semibold text-ink">Sections</h2>
            <p class="mt-0.5 text-xs text-ink-faint">
                {{ count($sections) }} section{{ count($sections) === 1 ? '' : 's' }}
                @if (!empty($requiredTypes))
                    &middot; {{ count($requiredTypes) }} required
                @endif
            </p>
        </div>

        <button
            type="button"
            wire:click="$set('showAddPanel', true)"
            class="inline-flex items-center gap-2 rounded-lg bg-tint px-4 py-2 text-sm font-medium text-tint-on shadow-lg shadow-tint/20 transition hover:bg-tint-hover focus:outline-none focus:ring-2 focus:ring-tint/40"
        >
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Add Section
        </button>
    </div>

    {{-- ===== ADD SECTION MODAL ===== --}}
    @if ($showAddPanel)
        <div class="fixed inset-0 z-50 flex items-start justify-center bg-overlay px-4 pt-[10vh] backdrop-blur-sm" wire:click.self="$set('showAddPanel', false)">
            <div class="w-full max-w-2xl overflow-hidden rounded-2xl border border-edge-strong bg-surface shadow-2xl" @click.stop>
                <div class="flex items-center justify-between border-b border-edge px-6 py-4">
                    <div>
                        <h3 class="text-base font-semibold text-ink">Add Section</h3>
                        <p class="mt-0.5 text-xs text-ink-faint">Choose a section type to add to this page.</p>
                    </div>
                    <button
                        type="button"
                        wire:click="$set('showAddPanel', false)"
                        class="rounded-lg p-2 text-ink-muted transition hover:bg-surface-alt hover:text-ink focus:outline-none"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="border-b border-edge px-6 py-3">
                    <div class="relative">
                        <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-ink-faint" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                        <input
                            type="text"
                            wire:model.live.debounce.250ms="addPanelSearch"
                            placeholder="Search section types..."
                            class="w-full rounded-lg border border-edge bg-surface-alt py-2.5 pl-10 pr-3 text-sm text-ink placeholder-ink-faint transition focus:border-tint focus:outline-none focus:ring-2 focus:ring-tint/30"
                        />
                    </div>
                </div>

                <div class="max-h-[55vh] space-y-5 overflow-y-auto p-6">
                    @foreach ($groupedSections as $category => $items)
                        @php
                            $search = strtolower(trim($addPanelSearch));
                            $visibleItems = $search === ''
                                ? $items
                                : array_filter($items, fn ($item) => str_contains(strtolower($item['label']), $search));
                        @endphp

                        @if (!empty($visibleItems))
                            <div>
                                <h4 class="mb-2.5 text-[11px] font-bold uppercase tracking-wider text-ink-faint">{{ $category }}</h4>
                                <div class="grid gap-2 sm:grid-cols-2">
                                    @foreach ($visibleItems as $item)
                                        @if ($item['allowed'])
                                            <button
                                                type="button"
                                                wire:click="addSection('{{ $item['type'] }}')"
                                                class="group rounded-xl border border-edge bg-surface-alt p-3.5 text-left transition hover:border-tint/40 hover:bg-tint-soft focus:outline-none focus:ring-2 focus:ring-tint/40"
                                            >
                                                <p class="text-sm font-medium text-ink transition group-hover:text-ink">{{ $item['label'] }}</p>
                                                @if ($item['description'])
                                                    <p class="mt-1 text-xs text-ink-faint">{{ $item['description'] }}</p>
                                                @endif
                                            </button>
                                        @else
                                            <div class="rounded-xl border border-edge/50 bg-surface-alt/50 p-3.5 opacity-50">
                                                <p class="text-sm font-medium text-ink-muted">{{ $item['label'] }}</p>
                                                <p class="mt-1 text-xs text-ink-faint">Not available for {{ Str::headline($page->template_key) }}</p>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- ===== SECTIONS LIST ===== --}}
    <div x-ref="sectionList" class="space-y-3">
        @forelse ($sections as $index => $section)
            @php
                $isCollapsed = $collapsed[$index] ?? true;
                $isActive = (bool) ($section['is_active'] ?? true);
                $isRequired = in_array($section['type'], $requiredTypes ?? [], true);
                $isSeoRelevant = in_array($section['type'], $seoRelevantTypes ?? [], true);
                $label = $sectionRegistry[$section['type']]['label'] ?? Str::headline($section['type']);
            @endphp

            <article
                wire:key="section-{{ $section['id'] ?? 'new' }}-{{ $index }}"
                draggable="true"
                data-section-index="{{ $index }}"
                @dragstart="dragStart($event)"
                @dragover.prevent="dragOver($event)"
                @drop.prevent="drop($event)"
                @dragend="dragEnd()"
                class="group/card overflow-hidden rounded-xl border transition {{ $isCollapsed ? 'border-edge bg-surface hover:border-edge-strong' : 'border-tint/20 bg-surface' }}"
            >
                {{-- Section header --}}
                <header class="flex items-center gap-3 px-4 py-3">
                    {{-- Drag handle --}}
                    <button
                        type="button"
                        data-drag-handle
                        aria-label="Drag to reorder"
                        class="inline-flex h-8 w-6 cursor-grab items-center justify-center rounded text-ink-faint transition hover:text-ink-muted active:cursor-grabbing"
                    >
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 16 16"><circle cx="5.5" cy="3.5" r="1.5"/><circle cx="10.5" cy="3.5" r="1.5"/><circle cx="5.5" cy="8" r="1.5"/><circle cx="10.5" cy="8" r="1.5"/><circle cx="5.5" cy="12.5" r="1.5"/><circle cx="10.5" cy="12.5" r="1.5"/></svg>
                    </button>

                    {{-- Section number --}}
                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-md bg-surface-alt text-[11px] font-bold text-ink-muted">
                        {{ $index + 1 }}
                    </span>

                    {{-- Info --}}
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2">
                            <p class="truncate text-sm font-semibold text-ink">{{ $label }}</p>
                            @if ($isRequired)
                                <span class="inline-flex shrink-0 items-center rounded px-1.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-blue-400 ring-1 ring-blue-500/25">Req</span>
                            @endif
                            @if ($isSeoRelevant)
                                <span class="inline-flex shrink-0 items-center rounded px-1.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-emerald-400 ring-1 ring-emerald-500/25">SEO</span>
                            @endif
                            @unless ($isActive)
                                <span class="inline-flex shrink-0 items-center rounded px-1.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-amber-400 ring-1 ring-amber-500/25">Off</span>
                            @endunless
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-1">
                        <button
                            type="button"
                            wire:click="moveUp({{ $index }})"
                            @if($index === 0) disabled @endif
                            class="inline-flex h-7 w-7 items-center justify-center rounded-md text-ink-faint transition hover:bg-surface-alt hover:text-ink disabled:pointer-events-none disabled:opacity-30"
                        >
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
                        </button>

                        <button
                            type="button"
                            wire:click="moveDown({{ $index }})"
                            @if($index === count($sections) - 1) disabled @endif
                            class="inline-flex h-7 w-7 items-center justify-center rounded-md text-ink-faint transition hover:bg-surface-alt hover:text-ink disabled:pointer-events-none disabled:opacity-30"
                        >
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>

                        <button
                            type="button"
                            wire:click="toggleActive({{ $index }})"
                            class="inline-flex h-7 w-7 items-center justify-center rounded-md transition {{ $isActive ? 'text-emerald-400 hover:bg-emerald-500/10' : 'text-ink-faint hover:bg-surface-alt hover:text-ink' }}"
                            title="{{ $isActive ? 'Hide section' : 'Show section' }}"
                        >
                            @if ($isActive)
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.522 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            @else
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                            @endif
                        </button>

                        <div class="mx-1 h-4 w-px bg-edge"></div>

                        <button
                            type="button"
                            wire:click="toggleCollapse({{ $index }})"
                            class="inline-flex h-7 items-center gap-1.5 rounded-md px-2.5 text-xs font-medium transition {{ $isCollapsed ? 'text-ink-muted hover:bg-surface-alt hover:text-ink' : 'bg-tint-soft text-tint hover:bg-tint-soft' }}"
                        >
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $isCollapsed ? 'M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10' : 'M6 18L18 6M6 6l12 12' }}"/></svg>
                            {{ $isCollapsed ? 'Edit' : 'Close' }}
                        </button>

                        @if ($confirmingDelete === $index)
                            <button
                                type="button"
                                wire:click="deleteSection({{ $index }})"
                                class="inline-flex h-7 items-center rounded-md bg-red-600 px-2.5 text-xs font-medium text-white transition hover:bg-red-500"
                            >
                                Confirm
                            </button>
                            <button
                                type="button"
                                wire:click="cancelDelete"
                                class="inline-flex h-7 items-center rounded-md px-2 text-xs font-medium text-ink-muted transition hover:text-ink"
                            >
                                Cancel
                            </button>
                        @else
                            <button
                                type="button"
                                wire:click="confirmDelete({{ $index }})"
                                class="inline-flex h-7 w-7 items-center justify-center rounded-md text-ink-faint transition hover:bg-red-500/10 hover:text-danger"
                                title="Delete section"
                            >
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                            </button>
                        @endif
                    </div>
                </header>

                {{-- Expanded editor panel --}}
                @if (!$isCollapsed)
                    <div class="border-t border-edge bg-panel px-5 py-5">
                        @include('livewire.admin.pages.sections.generic', [
                            'section' => $section,
                            'index' => $index,
                        ])
                    </div>
                @endif
            </article>
        @empty
            {{-- Empty state --}}
            <div class="rounded-xl border-2 border-dashed border-edge px-6 py-16 text-center">
                <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-surface-alt">
                    <svg class="h-6 w-6 text-ink-faint" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25a2.25 2.25 0 01-2.25-2.25v-2.25z"/></svg>
                </div>
                <p class="text-sm font-medium text-ink-muted">No sections yet</p>
                <p class="mt-1 text-xs text-ink-faint">Add a section to start building this page.</p>
                <button
                    type="button"
                    wire:click="$set('showAddPanel', true)"
                    class="mt-5 inline-flex items-center gap-2 rounded-lg bg-tint px-4 py-2 text-sm font-medium text-tint-on shadow-lg shadow-tint/20 transition hover:bg-tint-hover focus:outline-none focus:ring-2 focus:ring-tint/40"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Add Section
                </button>
            </div>
        @endforelse
    </div>

    {{-- ===== STICKY SAVE BAR ===== --}}
    @if (count($sections) > 0)
        <div class="fixed inset-x-0 bottom-0 z-40 border-t border-edge bg-surface/90 backdrop-blur-xl">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
                <p class="text-xs text-ink-faint">
                    {{ count($sections) }} section{{ count($sections) === 1 ? '' : 's' }}
                    &middot;
                    {{ collect($sections)->where('is_active', true)->count() }} active
                </p>
                <button
                    type="button"
                    wire:click="save"
                    wire:loading.attr="disabled"
                    wire:target="save"
                    class="inline-flex items-center gap-2 rounded-lg bg-tint px-5 py-2 text-sm font-medium text-tint-on shadow-lg shadow-tint/20 transition hover:bg-tint-hover focus:outline-none focus:ring-2 focus:ring-tint/40 disabled:opacity-60"
                >
                    <span wire:loading.remove wire:target="save">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
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

    {{-- ===== MEDIA PICKER MODAL ===== --}}
    @if ($showMediaModal)
        <div wire:key="media-picker-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-overlay px-4 backdrop-blur-sm" wire:click.self="closeMediaPicker">
            <div class="flex max-h-[80vh] w-full max-w-4xl flex-col overflow-hidden rounded-2xl border border-edge-strong bg-surface shadow-2xl" @click.stop>
                <div class="flex items-center justify-between border-b border-edge px-6 py-4">
                    @php $isVideoPicker = str_contains($mediaTargetField ?? '', 'video'); @endphp
                    <div class="flex items-center gap-3">
                        <h3 class="text-base font-semibold text-ink">{{ $isVideoPicker ? 'Select Video' : ($isMultiSelectMode ? 'Select Multiple Images' : 'Select Image') }}</h3>
                        @if ($isMultiSelectMode && count($selectedMediaIds) > 0)
                            <span class="inline-flex items-center rounded-md bg-tint-soft px-2 py-0.5 text-xs font-semibold text-tint ring-1 ring-tint/25">{{ count($selectedMediaIds) }} selected</span>
                        @endif
                    </div>
                    <button type="button" wire:click="closeMediaPicker" class="rounded-lg p-2 text-ink-muted transition hover:bg-surface-alt hover:text-ink focus:outline-none">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="border-b border-edge px-6 py-3">
                    <div class="relative">
                        <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-ink-faint" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="mediaSearch"
                            placeholder="Search media..."
                            class="w-full rounded-lg border border-edge bg-surface-alt py-2.5 pl-10 pr-3 text-sm text-ink placeholder-ink-faint transition focus:border-tint focus:outline-none focus:ring-2 focus:ring-tint/30"
                        />
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto p-6">
                    @if ($mediaItems->count())
                        <div class="grid grid-cols-3 gap-2 sm:grid-cols-4 md:grid-cols-6">
                            @foreach ($mediaItems as $media)
                                @php $isSelected = in_array($media->id, $selectedMediaIds ?? [], true); @endphp
                                <button
                                    type="button"
                                    wire:key="media-btn-{{ $media->id }}"
                                    wire:click="selectMedia({{ $media->id }})"
                                    class="group relative aspect-square overflow-hidden rounded-lg border-2 transition {{ $isMultiSelectMode && $isSelected ? 'border-tint ring-2 ring-tint/30' : 'border-transparent hover:border-tint/50' }}"
                                >
                                    @if ($isVideoPicker)
                                        <video src="{{ $media->url }}" muted class="h-full w-full object-cover transition group-hover:scale-105"></video>
                                    @else
                                        <img src="{{ $media->url }}" alt="{{ $media->alt_text ?? $media->file_name }}" class="h-full w-full object-cover transition group-hover:scale-105" loading="lazy" />
                                    @endif
                                    @if ($isMultiSelectMode)
                                        <div class="absolute right-1.5 top-1.5 flex h-5 w-5 items-center justify-center rounded border {{ $isSelected ? 'border-tint bg-tint' : 'border-ink-muted bg-surface/70' }}">
                                            @if ($isSelected)
                                                <svg class="h-3 w-3 text-tint-on" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                            @endif
                                        </div>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    @else
                        <div class="py-8 text-center text-sm text-ink-faint">{{ $isVideoPicker ? 'No videos found.' : 'No images found.' }}</div>
                    @endif
                </div>

                @if ($isMultiSelectMode)
                    <div class="flex items-center justify-between border-t border-edge px-6 py-4">
                        <button type="button" wire:click="closeMediaPicker" class="rounded-lg border border-edge bg-surface-alt px-4 py-2 text-sm font-medium text-ink-muted transition hover:bg-surface hover:text-ink">
                            Cancel
                        </button>
                        <button
                            type="button"
                            wire:click="applyMultipleMedia"
                            @if(empty($selectedMediaIds)) disabled @endif
                            class="rounded-lg bg-tint px-4 py-2 text-sm font-medium text-tint-on transition hover:bg-tint-hover disabled:opacity-40"
                        >
                            Add {{ count($selectedMediaIds) }} Image{{ count($selectedMediaIds) === 1 ? '' : 's' }}
                        </button>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>

@assets
<script>
    window.sectionsBuilderDnD = function (wire) {
        return {
            wire,
            dragging: null,
            init() {
                this.dragging = null;
            },
            dragStart(event) {
                if (!event.target.closest('[data-drag-handle]')) {
                    event.preventDefault();
                    return;
                }

                this.dragging = event.currentTarget;
                event.dataTransfer.effectAllowed = 'move';
                event.dataTransfer.setData('text/plain', event.currentTarget.dataset.sectionIndex || '');
                this.dragging.classList.add('opacity-60');
            },
            dragOver(event) {
                if (!this.dragging) return;

                const target = event.currentTarget;
                if (!target || target === this.dragging) return;

                const rect = target.getBoundingClientRect();
                const insertBefore = event.clientY < rect.top + rect.height / 2;
                const container = this.$refs.sectionList;

                if (!container) return;

                if (insertBefore) {
                    container.insertBefore(this.dragging, target);
                } else {
                    container.insertBefore(this.dragging, target.nextSibling);
                }
            },
            drop() {
                this.syncOrder();
            },
            dragEnd() {
                if (this.dragging) {
                    this.dragging.classList.remove('opacity-60');
                }

                this.syncOrder();
                this.dragging = null;
            },
            syncOrder() {
                const container = this.$refs.sectionList;
                if (!container) return;

                const order = Array.from(container.querySelectorAll('[data-section-index]'))
                    .map((item) => Number(item.dataset.sectionIndex))
                    .filter((value) => Number.isInteger(value));

                if (order.length > 0) {
                    this.wire.call('reorderSections', order);
                }
            },
        };
    };
</script>
@endassets
