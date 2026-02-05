@php
    $currentValue = $section['data'][$field] ?? null;
@endphp

<div class="flex items-start gap-3">
    {{-- Thumbnail preview --}}
    @if ($currentValue)
        <div class="relative w-24 h-24 rounded-lg overflow-hidden border border-zinc-700/60 bg-zinc-800 flex-shrink-0 group">
            <img src="{{ $currentValue }}" alt="" class="w-full h-full object-cover" />
            <div class="absolute inset-0 flex items-center justify-center bg-black/50 opacity-0 group-hover:opacity-100 transition">
                <button type="button"
                        wire:click="$set('sections.{{ $index }}.data.{{ $field }}', null)"
                        class="rounded-md bg-red-600/90 p-1.5 text-white transition hover:bg-red-500">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </div>
        </div>
    @else
        <div class="w-24 h-24 rounded-lg border border-dashed border-zinc-600/60 bg-zinc-800/30 flex items-center justify-center flex-shrink-0">
            <svg class="h-6 w-6 text-zinc-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5a1.5 1.5 0 001.5-1.5V4.5A1.5 1.5 0 0020.25 3H3.75A1.5 1.5 0 002.25 4.5v15A1.5 1.5 0 003.75 21z"/></svg>
        </div>
    @endif

    <div class="flex flex-col gap-2">
        <button type="button"
                wire:click="openMediaPicker({{ $index }}, '{{ $field }}')"
                class="inline-flex items-center gap-1.5 rounded-lg border border-zinc-700/60 bg-zinc-800/40 px-3 py-1.5 text-xs text-zinc-300 transition hover:bg-zinc-700/60 hover:text-white">
            <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5a1.5 1.5 0 001.5-1.5V4.5A1.5 1.5 0 0020.25 3H3.75A1.5 1.5 0 002.25 4.5v15A1.5 1.5 0 003.75 21z"/></svg>
            {{ $currentValue ? 'Change Image' : 'Select Image' }}
        </button>

        {{-- Fallback: manual URL input --}}
        <input type="text"
               wire:model.live="sections.{{ $index }}.data.{{ $field }}"
               placeholder="Or paste image URL..."
               class="rounded-lg border border-zinc-700/60 bg-zinc-800/40 px-3 py-1.5 text-xs text-white placeholder-zinc-600 w-64 transition focus:border-emerald-500/50 focus:outline-none focus:ring-1 focus:ring-emerald-500/20" />
    </div>
</div>
