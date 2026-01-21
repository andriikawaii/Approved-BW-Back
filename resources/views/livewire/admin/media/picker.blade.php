<div>
    @if($open)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60">
            <div class="bg-white dark:bg-zinc-800 rounded-xl w-full max-w-4xl p-6">

                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Select Media
                    </h3>
                    <button
                        type="button"
                        wire:click="$set('open', false)"
                        class="text-gray-500 hover:text-gray-900 dark:hover:text-white"
                    >
                        ✕
                    </button>
                </div>

                <input
                    type="text"
                    wire:model.debounce.300ms="search"
                    placeholder="Search media..."
                    class="w-full mb-4 rounded-lg border px-3 py-2
                           bg-white dark:bg-zinc-700
                           text-gray-900 dark:text-white"
                >

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @forelse($media as $item)
                        <button
                            type="button"
                            wire:click="select({{ $item->id }})"
                            class="border rounded-lg overflow-hidden
                                   hover:ring-2 ring-purple-500"
                        >
                            <img
                                src="{{ asset('storage/' . $item->file_path) }}"
                                class="w-full h-32 object-cover"
                                alt=""
                            >
                        </button>
                    @empty
                        <p class="text-sm text-gray-500 col-span-full">
                            No media found.
                        </p>
                    @endforelse
                </div>

            </div>
        </div>
    @endif
</div>
