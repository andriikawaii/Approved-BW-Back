@php
    $isLong = str_contains($field, 'content') || str_contains($field, 'description') || str_contains($field, 'body');
@endphp

@if ($isLong)
    <textarea
        wire:model.live="sections.{{ $index }}.data.{{ $field }}"
        rows="4"
        class="w-full rounded-md border border-zinc-700 bg-zinc-800 px-3 py-2 text-sm text-white"
        placeholder="{{ \Illuminate\Support\Str::headline($field) }}..."
    ></textarea>
@else
    <input
        type="text"
        wire:model.live="sections.{{ $index }}.data.{{ $field }}"
        class="w-full rounded-md border border-zinc-700 bg-zinc-800 px-3 py-2 text-sm text-white"
        placeholder="{{ \Illuminate\Support\Str::headline($field) }}..."
    />
@endif
