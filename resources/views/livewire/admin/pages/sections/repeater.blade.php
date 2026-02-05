@php
    $raw = $section['data'][$field] ?? [];
    if (!is_array($raw)) $raw = [];
    $items = array_values($raw);

    // Detect if items contain image subfields
    $imageSubfields = [];
    if (!empty($items) && is_array($items[0] ?? null)) {
        foreach (array_keys($items[0]) as $k) {
            if (str_contains($k, 'image') || str_contains($k, 'logo') || str_contains($k, 'avatar') || str_contains($k, 'cover')) {
                $imageSubfields[] = $k;
            }
        }
    }

    $inputClasses = 'w-full rounded-lg border border-zinc-700/60 bg-zinc-800/40 px-3 py-2 text-sm text-white placeholder-zinc-600 transition focus:border-emerald-500/50 focus:outline-none focus:ring-1 focus:ring-emerald-500/20';
@endphp

<div class="space-y-2">

    @forelse($items as $i => $item)
        <div wire:key="repeater-{{ $index }}-{{ $field }}-{{ $i }}"
             class="rounded-lg border border-zinc-800/60 bg-zinc-900/50 overflow-hidden">

            {{-- Item header --}}
            <div class="flex items-center justify-between px-3 py-2 bg-zinc-800/20">
                <span class="text-[11px] font-medium text-zinc-500">
                    #{{ $i + 1 }}
                </span>
                <button type="button"
                    wire:click="removeRepeaterItem({{ $index }}, '{{ $field }}', {{ $i }})"
                    class="inline-flex items-center gap-1 rounded-md px-1.5 py-0.5 text-[11px] text-zinc-600 transition hover:bg-red-500/10 hover:text-red-400"
                >
                    <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    Remove
                </button>
            </div>

            <div class="px-3 py-3">
                @if (!is_array($item))
                    {{-- SCALAR ITEM --}}
                    <input
                        type="text"
                        wire:model.live="sections.{{ $index }}.data.{{ $field }}.{{ $i }}"
                        class="{{ $inputClasses }}"
                        placeholder="Enter value..."
                    />
                @else
                    {{-- OBJECT ITEM --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($item as $k => $v)
                            <div class="space-y-1 {{ in_array($k, $imageSubfields) ? 'md:col-span-2' : '' }}">
                                <div class="text-[11px] font-medium text-zinc-500">
                                    {{ \Illuminate\Support\Str::headline($k) }}
                                </div>

                                @if (in_array($k, $imageSubfields))
                                    {{-- Image subfield with picker + preview --}}
                                    <div class="flex items-start gap-3">
                                        @if ($v)
                                            <div class="relative w-16 h-16 rounded-lg overflow-hidden border border-zinc-700/60 bg-zinc-800 flex-shrink-0 group">
                                                <img src="{{ $v }}" alt="" class="w-full h-full object-cover" />
                                            </div>
                                        @else
                                            <div class="w-16 h-16 rounded-lg border border-dashed border-zinc-600/60 bg-zinc-800/30 flex items-center justify-center flex-shrink-0">
                                                <svg class="h-4 w-4 text-zinc-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5a1.5 1.5 0 001.5-1.5V4.5A1.5 1.5 0 0020.25 3H3.75A1.5 1.5 0 002.25 4.5v15A1.5 1.5 0 003.75 21z"/></svg>
                                            </div>
                                        @endif
                                        <div class="flex flex-col gap-1.5">
                                            <button type="button"
                                                    wire:click="openMediaPicker({{ $index }}, '{{ $field }}', {{ $i }}, '{{ $k }}')"
                                                    class="inline-flex items-center gap-1 px-2 py-1 text-[11px] rounded-md border border-zinc-700/60 bg-zinc-800/40 text-zinc-400 transition hover:bg-zinc-700/60 hover:text-white">
                                                <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5a1.5 1.5 0 001.5-1.5V4.5A1.5 1.5 0 0020.25 3H3.75A1.5 1.5 0 002.25 4.5v15A1.5 1.5 0 003.75 21z"/></svg>
                                                {{ $v ? 'Change' : 'Select' }}
                                            </button>
                                            <input
                                                type="text"
                                                wire:model.live="sections.{{ $index }}.data.{{ $field }}.{{ $i }}.{{ $k }}"
                                                class="rounded-lg border border-zinc-700/60 bg-zinc-800/40 px-2 py-1 text-xs text-white placeholder-zinc-600 w-48 transition focus:border-emerald-500/50 focus:outline-none focus:ring-1 focus:ring-emerald-500/20"
                                                placeholder="Or paste URL..."
                                            />
                                        </div>
                                    </div>
                                @elseif (is_array($v))
                                    {{-- Nested object (e.g. quote) --}}
                                    <div class="space-y-2 rounded-lg border-l-2 border-zinc-700/40 pl-3">
                                        @foreach ($v as $nk => $nv)
                                            <div class="space-y-1">
                                                <div class="text-[10px] font-medium text-zinc-600">{{ \Illuminate\Support\Str::headline($nk) }}</div>
                                                <input
                                                    type="text"
                                                    wire:model.live="sections.{{ $index }}.data.{{ $field }}.{{ $i }}.{{ $k }}.{{ $nk }}"
                                                    class="{{ $inputClasses }}"
                                                    placeholder="{{ $nk }}..."
                                                />
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <input
                                        type="text"
                                        wire:model.live="sections.{{ $index }}.data.{{ $field }}.{{ $i }}.{{ $k }}"
                                        class="{{ $inputClasses }}"
                                        placeholder="{{ $k }}..."
                                    />
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

    @empty
        <div class="rounded-lg border border-dashed border-zinc-700/40 bg-zinc-900/30 px-4 py-6 text-center text-sm text-zinc-600">
            No items yet
        </div>
    @endforelse

    {{-- ADD ITEM --}}
    <button type="button"
        wire:click="addRepeaterItem({{ $index }}, '{{ $field }}')"
        class="inline-flex items-center gap-1.5 rounded-lg border border-dashed border-zinc-700/60 bg-zinc-800/20 px-3 py-2 text-xs text-zinc-400 transition hover:border-emerald-500/40 hover:bg-emerald-500/5 hover:text-emerald-400"
    >
        <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Add item
    </button>

</div>
