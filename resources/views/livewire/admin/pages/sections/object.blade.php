@php
    $currentValue = $section['data'][$field] ?? [];
    if (!is_array($currentValue)) $currentValue = [];

    $inputClasses = 'w-full rounded-lg border border-edge bg-surface-alt px-3 py-2.5 text-sm text-ink placeholder-ink-faint transition focus:border-tint focus:outline-none focus:ring-2 focus:ring-tint/30';
@endphp

<div class="space-y-3 rounded-lg border border-edge bg-surface-alt p-4">
    @forelse ($currentValue as $key => $value)
        <div>
            <label class="mb-1.5 block text-[11px] font-bold uppercase tracking-wider text-ink-faint">
                {{ \Illuminate\Support\Str::headline($key) }}
            </label>

            @if (is_array($value))
                <div class="space-y-2 rounded-lg border-l-2 border-edge pl-3">
                    @foreach ($value as $nk => $nv)
                        <div>
                            <div class="mb-1 text-[10px] font-bold uppercase tracking-wider text-ink-faint">{{ \Illuminate\Support\Str::headline($nk) }}</div>
                            <input
                                type="text"
                                wire:model.live="sections.{{ $index }}.data.{{ $field }}.{{ $key }}.{{ $nk }}"
                                class="{{ $inputClasses }}"
                                placeholder="{{ $nk }}..."
                            />
                        </div>
                    @endforeach
                </div>
            @else
                <input
                    type="text"
                    wire:model.live="sections.{{ $index }}.data.{{ $field }}.{{ $key }}"
                    class="{{ $inputClasses }}"
                    placeholder="{{ $key }}..."
                />
            @endif
        </div>
    @empty
        <div class="py-3 text-center text-xs text-ink-faint">No fields available</div>
    @endforelse
</div>
