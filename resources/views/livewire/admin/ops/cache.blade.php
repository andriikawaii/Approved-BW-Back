<div class="p-6 md:p-8 space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Cache Tools</h1>
        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">
            Brzo čišćenje keša kad radiš deploy ili menjaš SEO/Pages.
        </p>
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 dark:border-emerald-900 bg-emerald-50 dark:bg-emerald-950/30 px-4 py-3 text-sm text-emerald-800 dark:text-emerald-200">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <button wire:click="clearApp" class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5 text-left hover:border-amber-300 dark:hover:border-amber-800 transition">
            <div class="font-semibold text-zinc-900 dark:text-white">cache:clear</div>
            <div class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Application cache</div>
        </button>

        <button wire:click="clearView" class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5 text-left hover:border-amber-300 dark:hover:border-amber-800 transition">
            <div class="font-semibold text-zinc-900 dark:text-white">view:clear</div>
            <div class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Blade compiled views</div>
        </button>

        <button wire:click="clearConfig" class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5 text-left hover:border-amber-300 dark:hover:border-amber-800 transition">
            <div class="font-semibold text-zinc-900 dark:text-white">config:clear</div>
            <div class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Configuration cache</div>
        </button>

        <button wire:click="clearRoute" class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5 text-left hover:border-amber-300 dark:hover:border-amber-800 transition">
            <div class="font-semibold text-zinc-900 dark:text-white">route:clear</div>
            <div class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Routes cache</div>
        </button>

        <button wire:click="clearSeoKeys" class="md:col-span-2 rounded-2xl border border-amber-200 dark:border-amber-900 bg-amber-50 dark:bg-amber-950/20 p-5 text-left hover:border-amber-300 dark:hover:border-amber-800 transition">
            <div class="font-semibold text-zinc-900 dark:text-white">Clear SEO keys</div>
            <div class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">sitemap.xml / sitemap.html / robots.txt / llms.txt</div>
        </button>
    </div>
</div>
