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
@endphp

<div class="space-y-3">

    @forelse($items as $i => $item)
        <div wire:key="repeater-{{ $index }}-{{ $field }}-{{ $i }}"
             class="rounded-lg border border-zinc-700 bg-zinc-900 p-3 space-y-3">

            <div class="flex items-center justify-between">
                <div class="text-xs font-medium text-zinc-400">
                    #{{ $i + 1 }}
                </div>

                <button type="button"
                    wire:click="removeRepeaterItem({{ $index }}, '{{ $field }}', {{ $i }})"
                    class="text-xs px-2 py-1 rounded-md bg-red-600/20 text-red-400 hover:bg-red-600/30"
                >
                    Remove
                </button>
            </div>

            @if (!is_array($item))
                {{-- SCALAR ITEM --}}
                <input
                    type="text"
                    wire:model.live="sections.{{ $index }}.data.{{ $field }}.{{ $i }}"
                    class="w-full rounded-md border border-zinc-700 bg-zinc-800 px-3 py-2 text-sm text-white"
                    placeholder="Enter value..."
                />
            @else
                {{-- OBJECT ITEM --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach($item as $k => $v)
                        <div class="space-y-1 {{ in_array($k, $imageSubfields) ? 'md:col-span-2' : '' }}">
                            <div class="text-xs text-zinc-400">
                                {{ \Illuminate\Support\Str::headline($k) }}
                            </div>

                            @if (in_array($k, $imageSubfields))
                                {{-- Image subfield with picker + preview --}}
                                <div class="flex items-start gap-3">
                                    @if ($v)
                                        <div class="w-20 h-20 rounded-lg overflow-hidden border border-zinc-700 bg-zinc-800 flex-shrink-0">
                                            <img src="{{ $v }}" alt="" class="w-full h-full object-cover" />
                                        </div>
                                    @else
                                        <div class="w-20 h-20 rounded-lg border border-dashed border-zinc-600 bg-zinc-800 flex items-center justify-center flex-shrink-0">
                                            <span class="text-[10px] text-zinc-500">No img</span>
                                        </div>
                                    @endif
                                    <div class="flex flex-col gap-1.5">
                                        <button type="button"
                                                wire:click="openMediaPicker({{ $index }}, '{{ $field }}', {{ $i }}, '{{ $k }}')"
                                                class="px-2 py-1 text-xs rounded-md bg-zinc-700 text-white hover:bg-zinc-600">
                                            {{ $v ? 'Change' : 'Select' }}
                                        </button>
                                        <input
                                            type="text"
                                            wire:model.live="sections.{{ $index }}.data.{{ $field }}.{{ $i }}.{{ $k }}"
                                            class="rounded-md border border-zinc-700 bg-zinc-800 px-2 py-1 text-xs text-white w-48"
                                            placeholder="Or paste URL..."
                                        />
                                    </div>
                                </div>
                            @elseif (is_array($v))
                                {{-- Nested object (e.g. quote) --}}
                                <div class="space-y-2 pl-3 border-l border-zinc-700">
                                    @foreach ($v as $nk => $nv)
                                        <div class="space-y-1">
                                            <div class="text-[10px] text-zinc-500">{{ \Illuminate\Support\Str::headline($nk) }}</div>
                                            <input
                                                type="text"
                                                wire:model.live="sections.{{ $index }}.data.{{ $field }}.{{ $i }}.{{ $k }}.{{ $nk }}"
                                                class="w-full rounded-md border border-zinc-700 bg-zinc-800 px-3 py-1.5 text-sm text-white"
                                                placeholder="{{ $nk }}..."
                                            />
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <input
                                    type="text"
                                    wire:model.live="sections.{{ $index }}.data.{{ $field }}.{{ $i }}.{{ $k }}"
                                    class="w-full rounded-md border border-zinc-700 bg-zinc-800 px-3 py-2 text-sm text-white"
                                    placeholder="{{ $k }}..."
                                />
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

        </div>

    @empty
        <div class="text-sm text-zinc-500 py-2">
            No items yet.
        </div>
    @endforelse

    {{-- ADD ITEM --}}
    <div>
        <button type="button"
            wire:click="addRepeaterItem({{ $index }}, '{{ $field }}')"
            class="text-xs px-3 py-2 rounded-md bg-zinc-700 text-white hover:bg-zinc-600"
        >
            + Add item
        </button>
    </div>

</div>
