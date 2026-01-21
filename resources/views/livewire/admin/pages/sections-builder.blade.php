<div class="space-y-6">
    @if (session()->has('success'))
        <div class="rounded-lg bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300 px-4 py-3 text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    {{-- Add Section --}}
    <div class="flex items-center gap-3">
        <select
            wire:model="newSectionType"
            class="w-full rounded-lg border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 px-3 py-2 text-sm text-gray-900 dark:text-white"
        >
            <option value="">Add section…</option>

            @foreach($allowedSections as $type)
                <option value="{{ $type }}">
                    {{ $sectionRegistry[$type]['label'] ?? $type }}
                </option>
            @endforeach
        </select>

        <button
            type="button"
            wire:click="addSection"
            class="px-4 py-2 text-sm font-medium rounded-lg bg-purple-600 text-white hover:bg-purple-700"
        >
            Add
        </button>
    </div>

    {{-- Sections List --}}
    @foreach($sections as $index => $section)
        @php
            $isRequired = in_array(
                $section['type'],
                config("page-template-sections.{$page->template_key}.required", []),
                true
            );

            $partial = "livewire.admin.pages.sections.{$section['type']}";
            $label = $sectionRegistry[$section['type']]['label'] ?? $section['type'];
            $active = (bool)($section['is_active'] ?? true);
        @endphp

        <div
            x-data="{ open: false }"
            class="rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-900 overflow-hidden"
        >
            {{-- Header --}}
            <button
                type="button"
                @click="open = !open"
                class="w-full flex items-center justify-between p-4 bg-white/40 dark:bg-zinc-800/40 hover:bg-white/70 dark:hover:bg-zinc-800/70 transition"
            >
                <div class="flex items-center gap-3 text-left">
                    {{-- Icon --}}
                    <div class="h-9 w-9 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                        <span class="text-sm font-bold text-purple-700 dark:text-purple-300">
                            {{ strtoupper(substr($section['type'], 0, 1)) }}
                        </span>
                    </div>

                    {{-- Title + badges + preview --}}
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="font-semibold text-gray-900 dark:text-white">
                                {{ $label }}
                            </span>

                            @if($isRequired)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300">
                                    Required
                                </span>
                            @endif

                            @if(!$active)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-zinc-200 text-zinc-700 dark:bg-zinc-700 dark:text-zinc-200">
                                    Hidden
                                </span>
                            @endif
                        </div>

                        {{-- small preview --}}
                        @php
                            $preview =
                                $section['data']['headline']
                                ?? $section['data']['title']
                                ?? $section['data']['items'][0]['title']
                                ?? null;
                        @endphp

                        @if(!empty($preview))
                            <p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5">
                                {{ \Illuminate\Support\Str::limit($preview, 70) }}
                            </p>
                        @endif
                    </div>
                </div>

                {{-- Right --}}
                <div class="flex items-center gap-3 text-sm text-gray-500">
                    <span x-text="open ? 'Hide' : 'Edit'"></span>

                    <svg x-show="!open" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>

                    <svg x-show="open" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5" />
                    </svg>
                </div>
            </button>

            {{-- Editor --}}
            <div x-show="open" x-collapse class="p-4 border-t border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-900">
                {{-- Action bar --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                    <div class="flex items-center gap-2">
                        <button
                            type="button"
                            wire:click="moveUp({{ $index }})"
                            class="text-xs px-2 py-1 rounded bg-gray-200 dark:bg-zinc-700"
                            title="Move up"
                        >↑</button>

                        <button
                            type="button"
                            wire:click="moveDown({{ $index }})"
                            class="text-xs px-2 py-1 rounded bg-gray-200 dark:bg-zinc-700"
                            title="Move down"
                        >↓</button>

                        <button
                            type="button"
                            wire:click="duplicateSection({{ $index }})"
                            class="text-xs px-2 py-1 rounded bg-blue-600 text-white hover:bg-blue-700"
                            title="Duplicate section"
                        >
                            Duplicate
                        </button>

                        <button
                            type="button"
                            wire:click="resetSection({{ $index }})"
                            class="text-xs px-2 py-1 rounded bg-amber-500 text-white hover:bg-amber-600"
                            title="Reset data to defaults"
                        >
                            Reset
                        </button>
                    </div>

                    <div class="flex items-center gap-2">
                        {{-- Active toggle --}}
                        <button
                            type="button"
                            wire:click="toggleActive({{ $index }})"
                            class="text-xs px-2 py-1 rounded {{ $active ? 'bg-emerald-600 hover:bg-emerald-700 text-white' : 'bg-zinc-300 dark:bg-zinc-700 text-zinc-900 dark:text-white' }}"
                            title="Toggle visible/hidden"
                        >
                            {{ $active ? 'Visible' : 'Hidden' }}
                        </button>

                        {{-- Delete --}}
                        <button
                            type="button"
                            wire:click="deleteSection({{ $index }})"
                            @if($isRequired) disabled @endif
                            class="text-xs px-2 py-1 rounded bg-red-500 text-white disabled:opacity-40 disabled:cursor-not-allowed hover:bg-red-600"
                            title="Delete section"
                        >
                            Delete
                        </button>
                    </div>
                </div>

                {{-- Type-specific partial --}}
                @if(view()->exists($partial))
                    @include($partial, [
                        'index' => $index,
                        'section' => $section,
                        'mediaAssets' => $mediaAssets,
                    ])
                @else
                    <div class="text-sm text-amber-600 dark:text-amber-400">
                        No editor defined for section type:
                        <strong>{{ $section['type'] }}</strong>
                    </div>
                @endif
            </div>
        </div>
    @endforeach

    {{-- Save --}}
    <div class="flex justify-end">
        <button
            type="button"
            wire:click="save"
            class="px-5 py-2 rounded-lg bg-purple-600 text-white hover:bg-purple-700"
        >
            Save Sections
        </button>
    </div>
</div>
