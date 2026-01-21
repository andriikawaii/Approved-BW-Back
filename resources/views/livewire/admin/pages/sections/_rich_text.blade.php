<div class="space-y-6">
    <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4">
        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Rich Text</h3>
        <p class="text-xs text-gray-500 dark:text-zinc-400 mt-1">
            Plain text for now. You can add a WYSIWYG later without changing frontend contract.
        </p>
    </div>

    <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4 space-y-4">
        <div>
            <label class="text-xs text-gray-500 dark:text-zinc-400">Title (optional)</label>
            <input
                type="text"
                wire:model.defer="sections.{{ $index }}.data.title"
                class="w-full mt-1 rounded-lg border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 px-3 py-2 text-sm text-gray-900 dark:text-white"
                placeholder="About BuiltWell"
            >
        </div>

        <div>
            <label class="text-xs text-gray-500 dark:text-zinc-400">Content <span class="text-red-500">*</span></label>
            <textarea
                wire:model.defer="sections.{{ $index }}.data.content"
                rows="8"
                class="w-full mt-1 rounded-lg border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 px-3 py-2 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                placeholder="Write your content here..."
            ></textarea>

            <p class="text-xs text-gray-400 dark:text-zinc-500 mt-2">
                Tip: keep paragraphs short for better readability on mobile.
            </p>
        </div>
    </div>
</div>
