<div class="p-6 md:p-8 space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Roles</h1>
        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">
            Pregled rola u sistemu (Spatie Permission).
        </p>
    </div>

    <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 overflow-hidden">
        <div class="px-5 py-4 border-b border-zinc-200 dark:border-zinc-800 text-sm font-semibold text-zinc-900 dark:text-white">
            Role list
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50 text-zinc-600 dark:text-zinc-300">
                <tr>
                    <th class="px-5 py-3 text-left">Role</th>
                    <th class="px-5 py-3 text-left">Guard</th>
                    <th class="px-5 py-3 text-right">Users</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                @foreach($roles as $r)
                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/30">
                        <td class="px-5 py-3 font-medium text-zinc-900 dark:text-white">{{ $r['name'] }}</td>
                        <td class="px-5 py-3 text-zinc-600 dark:text-zinc-300">{{ $r['guard'] }}</td>
                        <td class="px-5 py-3 text-right text-zinc-600 dark:text-zinc-300">{{ $r['users_count'] ?? '—' }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
