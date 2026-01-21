<div class="space-y-4">

    {{-- Title --}}
    <div>
        <label class="text-xs text-gray-500 dark:text-zinc-400">
            Title
        </label>
        <input
            type="text"
            wire:model.defer="sections.{{ $index }}.data.title"
            class="w-full mt-1 rounded-lg border
                   border-gray-300 dark:border-zinc-600
                   bg-white dark:bg-zinc-700
                   px-3 py-2 text-sm
                   text-gray-900 dark:text-white"
            placeholder="Ready to start your project?"
        >
    </div>

    {{-- Button --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

        <div>
            <label class="text-xs text-gray-500 dark:text-zinc-400">
                Button Label
            </label>
            <input
                type="text"
                wire:model.defer="sections.{{ $index }}.data.button_label"
                class="w-full mt-1 rounded-lg border
                       border-gray-300 dark:border-zinc-600
                       bg-white dark:bg-zinc-700
                       px-3 py-2 text-sm
                       text-gray-900 dark:text-white"
                placeholder="Get a Free Quote"
            >
        </div>

        <div>
            <label class="text-xs text-gray-500 dark:text-zinc-400">
                Button URL
            </label>
            <input
                type="text"
                wire:model.defer="sections.{{ $index }}.data.button_url"
                class="w-full mt-1 rounded-lg border
                       border-gray-300 dark:border-zinc-600
                       bg-white dark:bg-zinc-700
                       px-3 py-2 text-sm
                       text-gray-900 dark:text-white"
                placeholder="/contact"
            >
        </div>

    </div>

</div>
