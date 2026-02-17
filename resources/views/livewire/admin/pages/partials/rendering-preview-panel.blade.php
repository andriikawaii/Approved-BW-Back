@php
    use Illuminate\Support\Str;
@endphp

<div class="rounded-xl border border-edge bg-surface">
    <div class="flex items-center justify-between border-b border-edge px-5 py-3">
        <h2 class="text-xs font-bold uppercase tracking-wider text-ink-faint">Rendering Summary</h2>
        <span class="inline-flex items-center gap-1.5 text-[10px] font-semibold uppercase tracking-wider text-ink-faint">
            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
            Live
        </span>
    </div>

    <div class="grid grid-cols-2 gap-px bg-edge/30 sm:grid-cols-3 lg:grid-cols-5">
        @foreach ([
            ['label' => 'Template', 'value' => Str::headline($templateKey)],
            ['label' => 'Footer', 'value' => 'Variant ' . $footerVariant],
            ['label' => 'Phone', 'value' => $phoneLogic],
            ['label' => 'Depth', 'value' => $urlDepth . ' level' . ($urlDepth === 1 ? '' : 's')],
            ['label' => 'Status', 'value' => $publishStatus, 'badge' => true],
        ] as $stat)
            <div class="bg-surface px-4 py-3">
                <p class="text-[10px] font-bold uppercase tracking-wider text-ink-faint">{{ $stat['label'] }}</p>
                @if ($stat['badge'] ?? false)
                    <p class="mt-1.5 inline-flex items-center rounded-md px-1.5 py-0.5 text-xs font-semibold {{ strtolower($publishStatus) === 'published' ? 'bg-emerald-500/15 text-emerald-400 ring-1 ring-emerald-500/25' : 'bg-amber-500/15 text-amber-400 ring-1 ring-amber-500/25' }}">
                        {{ $stat['value'] }}
                    </p>
                @else
                    <p class="mt-1.5 truncate text-sm font-medium text-ink">{{ $stat['value'] }}</p>
                @endif
            </div>
        @endforeach
    </div>
</div>
