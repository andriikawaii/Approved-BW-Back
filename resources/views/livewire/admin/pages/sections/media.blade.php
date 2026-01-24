<input
    type="text"
    placeholder="Media URL"
    wire:model.defer="sections.{{ $index }}.data.{{ $field }}"
    class="w-full rounded-lg border px-3 py-2 bg-white dark:bg-zinc-800"
/>
