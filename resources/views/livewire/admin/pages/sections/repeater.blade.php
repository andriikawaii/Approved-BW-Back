@php
    use App\Support\Sections\SectionRegistry;

    $raw = $section['data'][$field] ?? [];
    if (!is_array($raw)) $raw = [];
    $items = array_values($raw);

    $imageSubfields = [];
    if (!empty($items) && is_array($items[0] ?? null)) {
        foreach (array_keys($items[0]) as $k) {
            if (str_contains($k, 'image') || str_contains($k, 'logo') || str_contains($k, 'avatar') || str_contains($k, 'cover')) {
                $imageSubfields[] = $k;
            }
        }
    } else {
        $schema = SectionRegistry::rulesFor($section['type']);
        foreach ($schema as $key => $rules) {
            if (str_starts_with($key, $field . '.*.')) {
                $subfield = str_replace($field . '.*.', '', $key);
                if (str_contains($subfield, 'image') || str_contains($subfield, 'logo') || str_contains($subfield, 'avatar') || str_contains($subfield, 'cover')) {
                    if (!in_array($subfield, $imageSubfields)) {
                        $imageSubfields[] = $subfield;
                    }
                }
            }
        }
    }

    $inputClasses = 'w-full rounded-lg border border-edge bg-surface-alt px-3 py-2.5 text-sm text-ink placeholder-ink-faint transition focus:border-tint focus:outline-none focus:ring-2 focus:ring-tint/30';
@endphp

<div class="space-y-2">

    @forelse($items as $i => $item)
        <div wire:key="repeater-{{ $index }}-{{ $field }}-{{ $i }}"
             class="overflow-hidden rounded-lg border border-edge bg-surface-alt">

            <div class="flex items-center justify-between bg-panel px-3 py-2">
                <span class="flex h-5 w-5 items-center justify-center rounded bg-surface-alt text-[10px] font-bold text-ink-faint">
                    {{ $i + 1 }}
                </span>
                <button type="button"
                    wire:click="removeRepeaterItem({{ $index }}, '{{ $field }}', {{ $i }})"
                    class="inline-flex items-center gap-1 rounded-md px-1.5 py-0.5 text-[11px] font-medium text-danger transition hover:bg-red-500/10"
                >
                    <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    Remove
                </button>
            </div>

            <div class="px-3 py-3">
                @if (!is_array($item))
                    <input
                        type="text"
                        wire:model.live="sections.{{ $index }}.data.{{ $field }}.{{ $i }}"
                        class="{{ $inputClasses }}"
                        placeholder="Enter value..."
                    />
                @else
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                        @php
                            $allKeys = is_array($item) ? array_unique(array_merge(array_keys($item), $imageSubfields)) : [];
                        @endphp
                        @foreach($allKeys as $k)
                            @php
                                $v = $item[$k] ?? null;
                            @endphp
                            <div class="{{ in_array($k, $imageSubfields) ? 'md:col-span-2' : '' }}">
                                <div class="mb-1.5 text-[11px] font-bold uppercase tracking-wider text-ink-faint">
                                    {{ \Illuminate\Support\Str::headline($k) }}
                                </div>

                                @if (in_array($k, $imageSubfields))
                                    <div class="flex items-start gap-3">
                                        @if ($v)
                                            <div class="group relative h-14 w-14 flex-shrink-0 overflow-hidden rounded-lg border border-edge bg-surface-alt">
                                                <img src="{{ $v }}" alt="" class="h-full w-full object-cover" />
                                            </div>
                                        @else
                                            <div class="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-lg border border-dashed border-edge bg-surface-alt">
                                                <svg class="h-4 w-4 text-ink-faint" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5a1.5 1.5 0 001.5-1.5V4.5A1.5 1.5 0 0020.25 3H3.75A1.5 1.5 0 002.25 4.5v15A1.5 1.5 0 003.75 21z"/></svg>
                                            </div>
                                        @endif
                                        <div class="flex flex-col gap-1.5">
                                            <button type="button"
                                                    wire:click="openMediaPicker({{ $index }}, '{{ $field }}', {{ $i }}, '{{ $k }}')"
                                                    class="inline-flex items-center gap-1 rounded-md border border-edge bg-surface-alt px-2 py-1 text-[11px] font-medium text-ink-muted transition hover:border-edge-strong hover:text-ink">
                                                <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5a1.5 1.5 0 001.5-1.5V4.5A1.5 1.5 0 0020.25 3H3.75A1.5 1.5 0 002.25 4.5v15A1.5 1.5 0 003.75 21z"/></svg>
                                                {{ $v ? 'Change' : 'Select' }}
                                            </button>
                                            <input
                                                type="text"
                                                wire:model.live="sections.{{ $index }}.data.{{ $field }}.{{ $i }}.{{ $k }}"
                                                class="w-48 rounded-lg border border-edge bg-surface-alt px-2 py-1 text-xs text-ink placeholder-ink-faint transition focus:border-tint focus:outline-none focus:ring-2 focus:ring-tint/30"
                                                placeholder="Or paste URL..."
                                            />
                                        </div>
                                    </div>
                                @elseif (is_array($v))
                                    <div class="space-y-2 rounded-lg border-l-2 border-edge pl-3">
                                        @foreach ($v as $nk => $nv)
                                            <div>
                                                <div class="mb-1 text-[10px] font-bold uppercase tracking-wider text-ink-faint">{{ \Illuminate\Support\Str::headline($nk) }}</div>
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
        <div class="rounded-lg border border-dashed border-edge bg-surface-alt px-4 py-6 text-center text-xs text-ink-faint">
            No items yet
        </div>
    @endforelse

    <div class="flex items-center gap-2 pt-1">
        <button type="button"
            wire:click="addRepeaterItem({{ $index }}, '{{ $field }}')"
            class="inline-flex items-center gap-1.5 rounded-lg border border-edge bg-surface-alt px-3 py-2 text-xs font-medium text-ink-muted transition hover:border-edge-strong hover:bg-surface hover:text-ink"
        >
            <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Add item
        </button>

        @if (!empty($imageSubfields))
            <button type="button"
                wire:click="openMediaPicker({{ $index }}, '{{ $field }}')"
                class="inline-flex items-center gap-1.5 rounded-lg border border-edge bg-surface-alt px-3 py-2 text-xs font-medium text-ink-muted transition hover:border-edge-strong hover:bg-surface hover:text-ink"
                title="Add multiple images from gallery"
            >
                <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5a1.5 1.5 0 001.5-1.5V4.5A1.5 1.5 0 0020.25 3H3.75A1.5 1.5 0 002.25 4.5v15A1.5 1.5 0 003.75 21z"/></svg>
                Bulk add from gallery
            </button>
        @endif
    </div>

</div>
