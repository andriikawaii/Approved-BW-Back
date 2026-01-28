@php
    $currentValue = $section['data'][$field] ?? null;
@endphp

<div class="flex items-start gap-3">
    {{-- Thumbnail preview --}}
    @if ($currentValue)
        <div class="w-24 h-24 rounded-lg overflow-hidden border border-zinc-700 bg-zinc-800 flex-shrink-0">
            <img src="{{ $currentValue }}" alt="" class="w-full h-full object-cover" />
        </div>
    @else
        <div class="w-24 h-24 rounded-lg border border-dashed border-zinc-600 bg-zinc-800 flex items-center justify-center flex-shrink-0">
            <span class="text-xs text-zinc-500">No image</span>
        </div>
    @endif

    <div class="flex flex-col gap-2">
        <button type="button"
                wire:click="openMediaPicker({{ $index }}, '{{ $field }}')"
                class="px-3 py-1.5 text-xs rounded-md bg-zinc-700 text-white hover:bg-zinc-600">
            {{ $currentValue ? 'Change Image' : 'Select Image' }}
        </button>

        @if ($currentValue)
            <button type="button"
                    wire:click="$set('sections.{{ $index }}.data.{{ $field }}', null)"
                    class="px-3 py-1.5 text-xs rounded-md bg-red-600/20 text-red-400 hover:bg-red-600/30">
                Remove
            </button>
        @endif

        {{-- Fallback: manual URL input --}}
        <input type="text"
               wire:model.live="sections.{{ $index }}.data.{{ $field }}"
               placeholder="Or paste image URL..."
               class="rounded-md border border-zinc-700 bg-zinc-800 px-3 py-1.5 text-xs text-white w-64" />
    </div>
</div>
