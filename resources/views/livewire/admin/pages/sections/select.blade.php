@php
    preg_match('/in:([^|]+)/', $rules, $m);
    $options = explode(',', $m[1] ?? '');
@endphp

<select
    wire:model.live="sections.{{ $index }}.data.{{ $field }}"
    class="w-full rounded-md border border-zinc-700 bg-zinc-800 px-3 py-2 text-sm text-white"
>
    <option value="">-- Select --</option>
    @foreach ($options as $opt)
        <option value="{{ $opt }}">{{ ucfirst($opt) }}</option>
    @endforeach
</select>
