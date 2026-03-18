@php
    $currentValue = $section['data'][$field] ?? null;
    $isVideo = str_contains($field, 'video');
@endphp

<div class="flex items-start gap-3">
    @if ($currentValue)
        <div class="group relative h-20 w-20 flex-shrink-0 overflow-hidden rounded-lg border border-edge bg-surface-alt">
            @if ($isVideo)
                <video src="{{ $currentValue }}" muted class="h-full w-full object-cover"></video>
                <div class="absolute bottom-0.5 right-0.5 rounded bg-black/70 px-1 py-0.5 text-[9px] font-bold uppercase text-white">
                    MP4
                </div>
            @else
                <img src="{{ $currentValue }}" alt="" class="h-full w-full object-cover" />
            @endif
            <div class="absolute inset-0 flex items-center justify-center bg-black/60 opacity-0 transition group-hover:opacity-100">
                <button type="button"
                        wire:click="$set('sections.{{ $index }}.data.{{ $field }}', null)"
                        class="rounded-md bg-red-600 p-1.5 text-white transition hover:bg-red-500">
                    <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                </button>
            </div>
        </div>
    @else
        <div class="flex h-20 w-20 flex-shrink-0 items-center justify-center rounded-lg border border-dashed border-edge bg-surface-alt">
            @if ($isVideo)
                <svg class="h-5 w-5 text-ink-faint" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25Z"/></svg>
            @else
                <svg class="h-5 w-5 text-ink-faint" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5a1.5 1.5 0 001.5-1.5V4.5A1.5 1.5 0 0020.25 3H3.75A1.5 1.5 0 002.25 4.5v15A1.5 1.5 0 003.75 21z"/></svg>
            @endif
        </div>
    @endif

    <div class="flex flex-col gap-2">
        <button type="button"
                wire:click="openMediaPicker({{ $index }}, '{{ $field }}')"
                class="inline-flex items-center gap-1.5 rounded-lg border border-edge bg-surface-alt px-3 py-1.5 text-xs font-medium text-ink-muted transition hover:border-edge-strong hover:bg-surface hover:text-ink">
            @if ($isVideo)
                <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25Z"/></svg>
                {{ $currentValue ? 'Change Video' : 'Select Video' }}
            @else
                <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5a1.5 1.5 0 001.5-1.5V4.5A1.5 1.5 0 0020.25 3H3.75A1.5 1.5 0 002.25 4.5v15A1.5 1.5 0 003.75 21z"/></svg>
                {{ $currentValue ? 'Change Image' : 'Select Image' }}
            @endif
        </button>

        <input type="text"
               wire:model.live="sections.{{ $index }}.data.{{ $field }}"
               placeholder="{{ $isVideo ? 'Or paste video URL...' : 'Or paste image URL...' }}"
               class="w-64 rounded-lg border border-edge bg-surface-alt px-3 py-1.5 text-xs text-ink placeholder-ink-faint transition focus:border-tint focus:outline-none focus:ring-2 focus:ring-tint/30" />
    </div>
</div>
