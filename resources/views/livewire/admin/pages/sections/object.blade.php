@php
    $currentValue = $section['data'][$field] ?? [];
    if (!is_array($currentValue)) $currentValue = [];

    $inputClasses = 'w-full rounded-lg border border-zinc-700/60 bg-zinc-800/40 px-3 py-2 text-sm text-white placeholder-zinc-600 transition focus:border-emerald-500/50 focus:outline-none focus:ring-1 focus:ring-emerald-500/20';
@endphp

<div class="space-y-3 rounded-lg border border-zinc-800/60 bg-zinc-900/50 p-4">
    @forelse ($currentValue as $key => $value)
        <div class="space-y-1">
            <label class="text-[11px] font-medium text-zinc-500 uppercase">
                {{ \Illuminate\Support\Str::headline($key) }}
            </label>

            @if (is_array($value))
                {{-- Nested object --}}
                <div class="space-y-2 rounded-lg border-l-2 border-zinc-700/40 pl-3">
                    @foreach ($value as $nk => $nv)
                        <div class="space-y-1">
                            <div class="text-[10px] font-medium text-zinc-600">{{ \Illuminate\Support\Str::headline($nk) }}</div>
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
        <div class="text-center py-4 text-sm text-zinc-600">
            No fields available
        </div>
    @endforelse
</div>
