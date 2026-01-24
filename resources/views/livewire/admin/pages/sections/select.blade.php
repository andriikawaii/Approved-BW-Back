@php
    preg_match('/in:([^|]+)/', $rules, $m);
    $options = explode(',', $m[1] ?? '');
@endphp

<select
    wire:model.defer="sections.{{ $index }}.data.{{ $field }}"
    class="w-full rounded-lg border px-3 py-2 bg-white dark:bg-zinc-800"
>
    @foreach ($options as $opt)
        <option value="{{ $opt }}">{{ ucfirst($opt) }}</option>
    @endforeach
</select>
