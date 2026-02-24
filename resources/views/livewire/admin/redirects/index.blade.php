<div class="min-h-screen bg-gray-50 dark:bg-zinc-900">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-10">

        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Redirects</h1>
                <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1">
                    Manage 301/302 redirects for SEO and URL changes.
                </p>
            </div>

            <flux:button href="{{ route('admin.redirects.create') }}" wire:navigate variant="primary"
                         class="bg-purple-600 hover:bg-purple-700">
                + New Redirect
            </flux:button>
        </div>

        <div class="bg-white dark:bg-zinc-800 rounded-2xl border border-gray-200 dark:border-zinc-700 shadow-sm p-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div class="md:col-span-2">
                    <input
                        type="text"
                        wire:model.live="q"
                        placeholder="Search by from/to path..."
                        class="w-full rounded-lg border border-gray-300 dark:border-zinc-600
                               bg-white dark:bg-zinc-700 px-4 py-2.5 text-sm
                               text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500"
                    />
                </div>

                <div>
                    <select
                        wire:model.live="status"
                        class="w-full rounded-lg border border-gray-300 dark:border-zinc-600
                               bg-white dark:bg-zinc-700 px-4 py-2.5 text-sm
                               text-gray-900 dark:text-white"
                    >
                        <option value="all">All</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="mt-6 bg-white dark:bg-zinc-800 rounded-2xl border border-gray-200 dark:border-zinc-700 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-zinc-900/40 text-gray-600 dark:text-zinc-300">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium cursor-pointer" wire:click="setSort('from_path')">
                            From
                        </th>
                        <th class="px-4 py-3 text-left font-medium cursor-pointer" wire:click="setSort('to_path')">
                            To
                        </th>
                        <th class="px-4 py-3 text-left font-medium cursor-pointer" wire:click="setSort('type')">
                            Code
                        </th>
                        <th class="px-4 py-3 text-left font-medium">
                            Created
                        </th>
                        <th class="px-4 py-3 text-left font-medium cursor-pointer" wire:click="setSort('is_active')">
                            Active
                        </th>
                        <th class="px-4 py-3 text-right font-medium">Actions</th>
                    </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                    @forelse($redirects as $r)
                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-900/40">
                            <td class="px-4 py-3 text-gray-900 dark:text-white font-medium">
                                {{ $r->from_path }}
                            </td>
                            <td class="px-4 py-3 text-gray-700 dark:text-zinc-200">
                                {{ $r->to_path }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                    bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                    {{ $r->type }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-700 dark:text-zinc-200">
                                {{ $r->created_at?->format('M j, Y') ?? '—' }}
                            </td>
                            <td class="px-4 py-3">
                                <button
                                    wire:click="toggleActive({{ $r->id }})"
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                        {{ $r->is_active
                                            ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300'
                                            : 'bg-gray-200 text-gray-700 dark:bg-zinc-700 dark:text-zinc-200' }}"
                                >
                                    {{ $r->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </td>
                            <td class="px-4 py-3 text-right space-x-2">
                                <flux:button
                                    href="{{ route('admin.redirects.edit', $r) }}"
                                    wire:navigate
                                    variant="outline"
                                >
                                    Edit
                                </flux:button>

                                <button
                                    onclick="if(!confirm('Delete redirect: {{ addslashes($r->from_path) }} -> {{ addslashes($r->to_path) }} ?')) { event.preventDefault(); event.stopImmediatePropagation(); }"
                                    wire:click="delete({{ $r->id }})"
                                    class="inline-flex items-center justify-center rounded-lg border px-3 py-2 text-sm
                                           border-red-300 text-red-700 hover:bg-red-50
                                           dark:border-red-700 dark:text-red-300 dark:hover:bg-red-900/20"
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-gray-500 dark:text-zinc-400">
                                No redirects found.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-gray-200 dark:border-zinc-700">
                {{ $redirects->links() }}
            </div>
        </div>
    </div>
</div>
