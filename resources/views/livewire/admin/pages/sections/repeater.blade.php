@php
    $items = is_array($items ?? null) ? $items : [];
@endphp

<div class="space-y-4">

    @foreach ($items as $i => $item)

        @php
            // ⛑️ SAFETY: item mora biti array
            if (!is_array($item)) {
                continue;
            }
        @endphp

        <div class="rounded-lg border p-4 space-y-3 bg-white dark:bg-zinc-800">

            {{-- Fields --}}
            @foreach ($item as $subKey => $value)

                @php
                    // prikazujemo samo scalar vrednosti
                    if (is_array($value)) {
                        continue;
                    }
                @endphp

                <div class="space-y-1">
                    <label class="text-xs font-medium text-gray-600 dark:text-zinc-400">
                        {{ \Illuminate\Support\Str::headline($subKey) }}
                    </label>

                    <input
                        type="text"
                        class="w-full rounded-md border px-3 py-2 text-sm
                               bg-white dark:bg-zinc-700
                               text-gray-900 dark:text-white"
                        wire:model.defer="sections.{{ $index }}.data.{{ $field }}.{{ $i }}.{{ $subKey }}"
                    >
                </div>

            @endforeach

            {{-- Remove --}}
            <button
                type="button"
                wire:click="remove{{ \Illuminate\Support\Str::studly($field) }}Item({{ $index }}, {{ $i }})"
                class="text-xs text-red-600 hover:underline"
            >
                Remove item
            </button>

        </div>
    @endforeach

    {{-- Add --}}
    <button
        type="button"
        wire:click="add{{ \Illuminate\Support\Str::studly($field) }}Item({{ $index }})"
        class="text-sm text-purple-600 hover:underline"
    >
        + Add item
    </button>

</div>
