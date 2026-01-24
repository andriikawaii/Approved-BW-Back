<div class="p-6 md:p-8 space-y-6">
    <div class="flex items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">robots.txt</h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">
                Keširamo sadržaj radi brzine. U produkciji se generiše prema pravilima.
            </p>
        </div>

        <button
            wire:click="clear"
            class="inline-flex items-center gap-2 rounded-xl bg-amber-500 px-4 py-2 text-sm font-semibold text-black hover:bg-amber-600"
        >
            Clear cache
        </button>
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 dark:border-emerald-900 bg-emerald-50 dark:bg-emerald-950/30 px-4 py-3 text-sm text-emerald-800 dark:text-emerald-200">
            {{ session('success') }}
        </div>
    @endif

    <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5">
        <div class="text-sm text-zinc-600 dark:text-zinc-300">
            Cache key: <span class="font-mono">seo:robots.txt</span>
        </div>
        <div class="text-sm text-zinc-500 dark:text-zinc-400 mt-2">
            Public endpoint: <span class="font-mono">/robots.txt</span>
        </div>
    </div>
</div>
