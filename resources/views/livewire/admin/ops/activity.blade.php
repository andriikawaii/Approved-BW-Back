<div class="p-6 md:p-8 space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Activity Log</h1>
        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">
            Recent changes to pages and sections.
        </p>
    </div>

    @if($activities->isEmpty())
        <div class="rounded-2xl border border-dashed border-zinc-300 dark:border-zinc-700 p-6 text-sm text-zinc-500 dark:text-zinc-400">
            No activity recorded yet.
        </div>
    @else
        <div class="overflow-x-auto rounded-2xl border border-zinc-200 dark:border-zinc-800">
            <table class="w-full text-sm">
                <thead class="bg-zinc-50 dark:bg-zinc-900 text-zinc-500 dark:text-zinc-400">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium">When</th>
                        <th class="px-4 py-3 text-left font-medium">User</th>
                        <th class="px-4 py-3 text-left font-medium">Event</th>
                        <th class="px-4 py-3 text-left font-medium">Subject</th>
                        <th class="px-4 py-3 text-left font-medium">Changes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    @foreach($activities as $activity)
                        <tr class="bg-white dark:bg-zinc-950">
                            <td class="px-4 py-3 whitespace-nowrap text-zinc-500 dark:text-zinc-400">
                                {{ $activity->created_at->diffForHumans() }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-zinc-900 dark:text-white">
                                {{ $activity->causer?->name ?? 'System' }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span @class([
                                    'inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium',
                                    'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' => $activity->event === 'created',
                                    'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' => $activity->event === 'updated',
                                    'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' => $activity->event === 'deleted',
                                ])>
                                    {{ ucfirst($activity->event ?? $activity->description) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-zinc-900 dark:text-white">
                                {{ class_basename($activity->subject_type ?? '') }}
                                #{{ $activity->subject_id }}
                            </td>
                            <td class="px-4 py-3 text-zinc-500 dark:text-zinc-400 max-w-md truncate">
                                @php
                                    $props = $activity->properties ?? collect();
                                    $old = $props->get('old', []);
                                    $attributes = $props->get('attributes', []);
                                    $changed = array_keys(array_diff_key($attributes, ['data' => 1]));
                                @endphp
                                @if(!empty($changed))
                                    {{ implode(', ', $changed) }}
                                @elseif(!empty($attributes))
                                    {{ implode(', ', array_keys($attributes)) }}
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $activities->links() }}
        </div>
    @endif
</div>
