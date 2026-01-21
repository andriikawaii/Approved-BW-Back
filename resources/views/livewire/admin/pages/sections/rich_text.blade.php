<div class="space-y-4">

    <div>
        <label class="text-xs text-gray-500 dark:text-zinc-400">
            Content
        </label>

        <textarea
            wire:model.defer="sections.{{ $index }}.data.content"
            rows="6"
            class="w-full mt-1 rounded-lg border
                   border-gray-300 dark:border-zinc-600
                   bg-white dark:bg-zinc-700
                   px-3 py-2 text-sm
                   text-gray-900 dark:text-white
                   focus:ring-2 focus:ring-purple-500
                   focus:border-purple-500"
            placeholder="Write your content here..."
        ></textarea>

        <p class="text-xs text-gray-400 dark:text-zinc-500 mt-1">
            Plain text for now (WYSIWYG later if needed).
        </p>
    </div>

</div>
