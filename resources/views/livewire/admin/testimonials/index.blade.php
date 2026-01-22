{{-- resources/views/livewire/admin/testimonials/index.blade.php --}}

<div class="p-6 space-y-6">

    {{-- Header --}}
    <div class="flex items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-zinc-900 dark:text-white">
                Testimonials
            </h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">
                Customer feedback shown across landing pages.
            </p>
        </div>

        <flux:button
            href="{{ route('admin.testimonials.create') }}"
            wire:navigate
            variant="primary"
            class="px-6 py-3"
        >
            + New Testimonial
        </flux:button>
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 overflow-hidden">

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-zinc-50 dark:bg-zinc-950/40 border-b border-zinc-200 dark:border-zinc-700">
                <tr>
                    <th class="p-4 text-left font-medium text-zinc-600 dark:text-zinc-400">
                        Author
                    </th>
                    <th class="p-4 text-left font-medium text-zinc-600 dark:text-zinc-400">
                        Company
                    </th>
                    <th class="p-4 text-left font-medium text-zinc-600 dark:text-zinc-400">
                        Content
                    </th>
                    <th class="p-4 text-center font-medium text-zinc-600 dark:text-zinc-400">
                        Order
                    </th>
                    <th class="p-4 text-center font-medium text-zinc-600 dark:text-zinc-400">
                        Active
                    </th>
                    <th class="p-4 text-right font-medium text-zinc-600 dark:text-zinc-400">
                        Actions
                    </th>
                </tr>
                </thead>

                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                @forelse($testimonials as $t)
                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/40 transition">
                        {{-- Author --}}
                        <td class="p-4">
                            <div class="font-medium text-zinc-900 dark:text-white">
                                {{ $t->author_name }}
                            </div>
                            @if($t->author_position)
                                <div class="text-xs text-zinc-500">
                                    {{ $t->author_position }}
                                </div>
                            @endif
                        </td>

                        {{-- Company --}}
                        <td class="p-4 text-zinc-700 dark:text-zinc-300">
                            {{ $t->company ?? '—' }}
                        </td>

                        {{-- Content --}}
                        <td class="p-4 text-zinc-700 dark:text-zinc-300 max-w-md">
                            {{ Str::limit($t->content, 90) }}
                        </td>

                        {{-- Order --}}
                        <td class="p-4 text-center text-zinc-700 dark:text-zinc-300">
                            {{ $t->sort_order }}
                        </td>

                        {{-- Active --}}
                        <td class="p-4 text-center">
                            <button
                                wire:click="toggleActive({{ $t->id }})"
                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                        {{ $t->is_active
                                            ? 'bg-emerald-500/15 text-emerald-600 dark:text-emerald-400'
                                            : 'bg-zinc-200 text-zinc-600 dark:bg-zinc-700 dark:text-zinc-300'
                                        }}"
                            >
                                {{ $t->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </td>

                        {{-- Actions --}}
                        <td class="p-4 text-right">
                            <flux:button
                                href="{{ route('admin.testimonials.edit', $t) }}"
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
                        <td colspan="6" class="p-10 text-center text-sm text-zinc-500">
                            No testimonials yet.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
