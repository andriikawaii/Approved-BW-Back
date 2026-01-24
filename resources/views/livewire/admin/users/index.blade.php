<div class="min-h-screen bg-gradient-to-br from-zinc-50 to-white dark:from-zinc-950 dark:to-zinc-900 p-6 md:p-8">
    <div class="w-full">
        {{-- Header --}}
        <div class="mb-10">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="h-12 w-12 rounded-2xl bg-gradient-to-br from-amber-500 to-amber-600
                                    flex items-center justify-center shadow-lg">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" stroke-width="2"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl md:text-4xl font-bold bg-gradient-to-r from-zinc-900 to-zinc-700
                                        dark:from-white dark:to-zinc-300 bg-clip-text text-transparent">
                                User Management
                            </h1>
                            <p class="text-zinc-500 dark:text-zinc-400 text-sm md:text-base mt-1">
                                Manage administrators and permissions with precision
                            </p>
                        </div>
                    </div>
                </div>

                <a
                    href="{{ route('admin.users.create') }}"
                    wire:navigate
                    class="group relative inline-flex items-center gap-3 rounded-2xl bg-gradient-to-r from-amber-500 via-amber-500 to-yellow-500
                           px-6 py-3.5 text-white font-semibold shadow-lg hover:shadow-xl transition-all duration-300
                           hover:scale-[1.02] active:scale-[0.98] overflow-hidden"
                >
                    <div class="absolute inset-0 bg-gradient-to-r from-yellow-400 to-amber-600 opacity-0
                                group-hover:opacity-100 transition-opacity duration-300"></div>
                    <svg class="h-5 w-5 relative z-10" fill="none" stroke="currentColor" stroke-width="2.5"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 4v16m8-8H4"/>
                    </svg>
                    <span class="relative z-10 text-sm tracking-wide">New User</span>
                </a>
            </div>
        </div>

        {{-- Stats Overview --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
            <div class="bg-white/80 dark:bg-zinc-800/80 backdrop-blur-sm rounded-2xl p-5
                        border border-zinc-200/50 dark:border-zinc-700/50 shadow-sm">
                <div class="text-sm text-zinc-500 dark:text-zinc-400">Total Users</div>
                <div class="text-2xl font-bold text-zinc-900 dark:text-white mt-1">{{ $users->count() }}</div>
            </div>
            <div class="bg-white/80 dark:bg-zinc-800/80 backdrop-blur-sm rounded-2xl p-5
                        border border-zinc-200/50 dark:border-zinc-700/50 shadow-sm">
                <div class="text-sm text-zinc-500 dark:text-zinc-400">Active Roles</div>
                <div class="text-2xl font-bold text-zinc-900 dark:text-white mt-1">{{ $uniqueRoles }}</div>
            </div>
            <div class="bg-white/80 dark:bg-zinc-800/80 backdrop-blur-sm rounded-2xl p-5
                        border border-zinc-200/50 dark:border-zinc-700/50 shadow-sm">
                <div class="text-sm text-zinc-500 dark:text-zinc-400">Administrators</div>
                <div class="text-2xl font-bold text-zinc-900 dark:text-white mt-1">{{ $adminCount }}</div>
            </div>
        </div>

        {{-- Table Container --}}
        <div class="bg-white dark:bg-zinc-900 rounded-3xl overflow-hidden shadow-2xl
                    border border-zinc-100 dark:border-zinc-800">

            {{-- Table Header --}}
            <div class="px-6 py-4 border-b border-zinc-100 dark:border-zinc-800
                        bg-gradient-to-r from-zinc-50 to-white dark:from-zinc-900 dark:to-zinc-800">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="h-3 w-3 rounded-full bg-amber-500 animate-pulse"></div>
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">User Directory</h2>
                    </div>
                    <div class="text-xs text-zinc-500 dark:text-zinc-400">
                        Last updated: {{ now()->format('M d, Y') }}
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-zinc-50/50 to-transparent
                                  dark:from-zinc-800/50 dark:to-transparent">
                    <tr class="border-b border-zinc-100 dark:border-zinc-800">
                        <th class="px-8 py-4 text-left font-semibold text-zinc-600 dark:text-zinc-300
                                       text-sm uppercase tracking-wider">
                            <div class="flex items-center gap-2">
                                <span>User</span>
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                                </svg>
                            </div>
                        </th>
                        <th class="px-8 py-4 text-left font-semibold text-zinc-600 dark:text-zinc-300
                                       text-sm uppercase tracking-wider">Email</th>
                        <th class="px-8 py-4 text-left font-semibold text-zinc-600 dark:text-zinc-300
                                       text-sm uppercase tracking-wider">Roles</th>
                        <th class="px-8 py-4 text-right font-semibold text-zinc-600 dark:text-zinc-300
                                       text-sm uppercase tracking-wider">Actions</th>
                    </tr>
                    </thead>

                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    @foreach($users as $user)
                        <tr class="group hover:bg-gradient-to-r hover:from-amber-50/30 hover:to-transparent
                                   dark:hover:from-amber-950/10 dark:hover:to-transparent transition-all duration-300">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="relative">
                                        <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-amber-500 to-yellow-500
                                                    flex items-center justify-center font-bold text-white text-sm
                                                    shadow-lg group-hover:scale-110 transition-transform duration-300">
                                            {{ $user->initials() }}
                                        </div>
                                        @if($user->is_active)
                                            <div class="absolute -bottom-1 -right-1 h-3 w-3 rounded-full
                                                        border-2 border-white dark:border-zinc-900
                                                        bg-emerald-500"></div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-semibold text-zinc-900 dark:text-white
                                                    group-hover:text-amber-600 dark:group-hover:text-amber-400
                                                    transition-colors">
                                            {{ $user->name }}
                                        </div>
                                        <div class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">
                                            Joined {{ $user->created_at->format('M d, Y') }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-8 py-5">
                                <div class="flex items-center gap-2">
                                    <svg class="h-4 w-4 text-zinc-400" fill="none" stroke="currentColor"
                                         stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="text-zinc-700 dark:text-zinc-300 font-medium">
                                        {{ $user->email }}
                                    </span>
                                </div>
                            </td>

                            <td class="px-8 py-5">
                                <div class="flex flex-wrap gap-2">
                                    @foreach($user->roles as $role)
                                        <span class="inline-flex items-center gap-1.5 rounded-full
                                                     bg-gradient-to-r from-zinc-100 to-zinc-50
                                                     dark:from-zinc-800 dark:to-zinc-700
                                                     px-3 py-1.5 text-xs font-medium
                                                     border border-zinc-200 dark:border-zinc-700
                                                     group-hover:border-amber-200 dark:group-hover:border-amber-900
                                                     transition-colors">
                                            <div class="h-1.5 w-1.5 rounded-full
                                                        @if($role->name === 'Admin') bg-amber-500
                                                        @elseif($role->name === 'Editor') bg-blue-500
                                                        @else bg-emerald-500 @endif"></div>
                                            {{ $role->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>

                            <td class="px-8 py-5">
                                <div class="flex items-center justify-end gap-3">
                                    <a
                                        href="{{ route('admin.users.edit', $user) }}"
                                        wire:navigate
                                        class="text-zinc-600 hover:text-zinc-900 dark:text-zinc-400
                                               dark:hover:text-white p-2 rounded-lg hover:bg-zinc-100
                                               dark:hover:bg-zinc-800 transition-colors"
                                        title="View Profile"
                                    >
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a
                                        href="{{ route('admin.users.edit', $user) }}"
                                        wire:navigate
                                        class="inline-flex items-center gap-2 rounded-xl
                                               bg-gradient-to-r from-amber-500/10 to-yellow-500/10
                                               dark:from-amber-500/20 dark:to-yellow-500/20
                                               px-4 py-2 text-sm font-semibold text-amber-700
                                               dark:text-amber-400 hover:text-amber-800 dark:hover:text-amber-300
                                               hover:from-amber-500/20 hover:to-yellow-500/20
                                               dark:hover:from-amber-500/30 dark:hover:to-yellow-500/30
                                               border border-amber-200 dark:border-amber-800
                                               hover:border-amber-300 dark:hover:border-amber-700
                                               transition-all duration-300 hover:shadow-lg"
                                    >
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Table Footer --}}
            <div class="px-8 py-4 border-t border-zinc-100 dark:border-zinc-800
                        bg-gradient-to-r from-white to-zinc-50 dark:from-zinc-900 dark:to-zinc-800">
                <div class="flex items-center justify-between text-sm text-zinc-500 dark:text-zinc-400">
                    <div>Showing {{ $users->count() }} users</div>
                    <div class="flex items-center gap-1">
                        <button class="p-2 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        <span class="px-3 py-1 rounded-lg bg-amber-500 text-white font-medium">1</span>
                        <button class="p-2 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
