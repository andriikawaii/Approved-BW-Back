<x-layouts.app.sidebar :title="$title ?? null">
    <flux:main class="min-h-screen bg-panel text-ink">
        {{ $slot }}
    </flux:main>
</x-layouts.app.sidebar>
