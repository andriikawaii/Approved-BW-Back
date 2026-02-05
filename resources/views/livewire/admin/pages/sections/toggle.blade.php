<label class="relative inline-flex cursor-pointer items-center gap-2.5">
    <input
        type="checkbox"
        wire:model.live="sections.{{ $index }}.data.{{ $field }}"
        class="peer sr-only"
    >
    <div class="h-5 w-9 rounded-full bg-zinc-700 transition-colors after:absolute after:left-[2px] after:top-[2px] after:h-4 after:w-4 after:rounded-full after:bg-zinc-400 after:transition-all peer-checked:bg-emerald-600 peer-checked:after:translate-x-full peer-checked:after:bg-white"></div>
    <span class="text-sm text-zinc-300">Enabled</span>
</label>
