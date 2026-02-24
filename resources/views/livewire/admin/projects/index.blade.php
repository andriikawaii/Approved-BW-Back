<div class="p-6 space-y-6">

    {{-- Header --}}
    <div class="flex items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-zinc-900 dark:text-white">
                Case Studies
            </h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">
                Manage case studies and completed projects.
            </p>
        </div>

        <flux:button
            href="{{ route('admin.projects.create') }}"
            wire:navigate
            variant="primary"
            class="px-6 py-3 text-base"
        >
            + New Project
        </flux:button>
    </div>

    {{-- Content Card --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 overflow-hidden">

        {{-- Mobile view --}}
        <div class="lg:hidden divide-y divide-zinc-200 dark:divide-zinc-700">
            @forelse($projects as $project)
                <div class="p-4 space-y-3">
                    <div class="flex justify-between items-start gap-3">
                        <div>
                            <h3 class="font-medium text-zinc-900 dark:text-white">
                                {{ $project->title }}
                            </h3>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">
                                {{ $project->client ?? '—' }}
                            </p>
                        </div>

                        <flux:button
                            href="{{ route('admin.projects.edit', $project) }}"
                            wire:navigate
                            variant="ghost"
                            size="sm"
                        >
                            Edit
                        </flux:button>
                    </div>

                    <div class="flex items-center justify-between">
                        <span @class([
                            'inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium',
                            'bg-emerald-500/15 text-emerald-600 dark:text-emerald-400' => $project->is_published,
                            'bg-zinc-200 text-zinc-600 dark:bg-zinc-700 dark:text-zinc-300' => !$project->is_published,
                        ])>
                            {{ $project->is_published ? 'Published' : 'Draft' }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="py-12 text-center text-sm text-zinc-500">
                    No projects found.
                </div>
            @endforelse
        </div>

        {{-- Desktop table --}}
        <div class="hidden lg:block overflow-x-auto">
            <table class="w-full">
                <thead>
                <tr class="border-b border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-950/40">
                    <th class="text-left p-4 text-sm font-medium text-zinc-600 dark:text-zinc-400">Title</th>
                    <th class="text-left p-4 text-sm font-medium text-zinc-600 dark:text-zinc-400">Client</th>
                    <th class="text-left p-4 text-sm font-medium text-zinc-600 dark:text-zinc-400">Status</th>
                    <th class="text-right p-4 text-sm font-medium text-zinc-600 dark:text-zinc-400">Actions</th>
                </tr>
                </thead>

                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                @forelse($projects as $project)
                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/40 transition-colors">
                        <td class="p-4">
                            <div class="font-medium text-zinc-900 dark:text-white">{{ $project->title }}</div>
                            @if($project->excerpt)
                                <div class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5 truncate max-w-md">{{ $project->excerpt }}</div>
                            @endif
                        </td>

                        <td class="p-4 text-sm text-zinc-700 dark:text-zinc-300">
                            {{ $project->client ?? '—' }}
                        </td>

                        <td class="p-4">
                            <span @class([
                                'inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium',
                                'bg-emerald-500/15 text-emerald-600 dark:text-emerald-400' => $project->is_published,
                                'bg-zinc-200 text-zinc-600 dark:bg-zinc-700 dark:text-zinc-300' => !$project->is_published,
                            ])>
                                {{ $project->is_published ? 'Published' : 'Draft' }}
                            </span>
                        </td>

                        <td class="p-4 text-right">
                            <flux:button
                                href="{{ route('admin.projects.edit', $project) }}"
                                wire:navigate
                                variant="ghost"
                                size="sm"
                            >
                                Edit
                            </flux:button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-10 text-center text-sm text-zinc-500">
                            No projects found.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
