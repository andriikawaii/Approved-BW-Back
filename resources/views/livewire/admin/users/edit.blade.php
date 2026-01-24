{{-- EDIT KOMPONENTA --}}
<div class="min-h-screen bg-gradient-to-br from-zinc-50 to-white dark:from-zinc-950 dark:to-zinc-900 p-6 md:p-8">
    <div class="max-w-2xl mx-auto">
        {{-- Back Navigation --}}
        <div class="mb-6">
            <a href="{{ route('admin.users.index') }}" wire:navigate
               class="inline-flex items-center gap-2 text-sm text-zinc-500 hover:text-zinc-700
                      dark:text-zinc-400 dark:hover:text-zinc-300 transition-colors group">
                <svg class="h-4 w-4 transform group-hover:-translate-x-1 transition-transform"
                     fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Users
            </a>
        </div>

        {{-- Header Card --}}
        <div class="bg-gradient-to-r from-white to-zinc-50 dark:from-zinc-900 dark:to-zinc-800
                    rounded-3xl p-8 shadow-2xl border border-zinc-100 dark:border-zinc-800 mb-8">
            <div class="flex items-center gap-4 mb-6">
                <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-amber-500 to-yellow-500
                            flex items-center justify-center shadow-lg">
                    <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-zinc-900 to-zinc-700
                                dark:from-white dark:to-zinc-300 bg-clip-text text-transparent">
                        Edit User
                    </h1>
                    <p class="text-zinc-500 dark:text-zinc-400 mt-1">
                        Update user details and permissions
                    </p>
                </div>
            </div>

            {{-- User Info Chip --}}
            <div class="inline-flex items-center gap-3 bg-gradient-to-r from-amber-50 to-yellow-50
                        dark:from-amber-900/20 dark:to-yellow-900/20 rounded-xl px-4 py-3
                        border border-amber-200 dark:border-amber-800">
                <div class="h-10 w-10 rounded-lg bg-gradient-to-br from-amber-500 to-yellow-500
                            flex items-center justify-center font-bold text-white">
                    {{ $user->initials() }}
                </div>
                <div>
                    <div class="font-semibold text-zinc-900 dark:text-white">{{ $user->name }}</div>
                    <div class="text-xs text-zinc-500 dark:text-zinc-400">{{ $user->email }}</div>
                </div>
            </div>
        </div>

        {{-- Form Container --}}
        <div class="bg-white dark:bg-zinc-900 rounded-3xl shadow-2xl overflow-hidden
                    border border-zinc-100 dark:border-zinc-800">

            {{-- Form Header --}}
            <div class="px-8 py-6 border-b border-zinc-100 dark:border-zinc-800
                        bg-gradient-to-r from-zinc-50 to-white dark:from-zinc-900 dark:to-zinc-800">
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">
                    User Information
                </h2>
                <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">
                    Update the details below
                </p>
            </div>

            <form wire:submit.prevent="save" class="p-8 space-y-6">
                {{-- Name Field --}}
                <div class="group">
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-sm font-semibold text-zinc-700 dark:text-zinc-300
                                      group-focus-within:text-amber-600 dark:group-focus-within:text-amber-400
                                      transition-colors">
                            Name
                        </label>
                        <span class="text-xs text-zinc-400 dark:text-zinc-600">Required</span>
                    </div>
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-zinc-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <input
                            wire:model.defer="name"
                            class="w-full rounded-xl border-2 border-zinc-200 dark:border-zinc-700
                                   bg-zinc-50 dark:bg-zinc-800/50 pl-12 pr-4 py-3.5 text-zinc-900
                                   dark:text-white font-medium focus:border-amber-500 focus:ring-2
                                   focus:ring-amber-500/20 focus:outline-none transition-all duration-300
                                   group-hover:border-zinc-300 dark:group-hover:border-zinc-600"
                            placeholder="Enter full name"
                        >
                    </div>
                    @error('name')
                    <div class="mt-2 flex items-center gap-2 text-sm text-red-600">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                {{-- Email Field --}}
                <div class="group">
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-sm font-semibold text-zinc-700 dark:text-zinc-300
                                      group-focus-within:text-amber-600 dark:group-focus-within:text-amber-400
                                      transition-colors">
                            Email Address
                        </label>
                        <span class="text-xs text-zinc-400 dark:text-zinc-600">Required</span>
                    </div>
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-zinc-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <input
                            wire:model.defer="email"
                            type="email"
                            class="w-full rounded-xl border-2 border-zinc-200 dark:border-zinc-700
                                   bg-zinc-50 dark:bg-zinc-800/50 pl-12 pr-4 py-3.5 text-zinc-900
                                   dark:text-white font-medium focus:border-amber-500 focus:ring-2
                                   focus:ring-amber-500/20 focus:outline-none transition-all duration-300
                                   group-hover:border-zinc-300 dark:group-hover:border-zinc-600"
                            placeholder="user@example.com"
                        >
                    </div>
                    @error('email')
                    <div class="mt-2 flex items-center gap-2 text-sm text-red-600">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                {{-- Role Field --}}
                <div class="group">
                    <label class="block text-sm font-semibold mb-2 text-zinc-700 dark:text-zinc-300
                                  group-focus-within:text-amber-600 dark:group-focus-within:text-amber-400
                                  transition-colors">
                        Role Assignment
                    </label>
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-zinc-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <select
                            wire:model.defer="role"
                            class="w-full rounded-xl border-2 border-zinc-200 dark:border-zinc-700
                                   bg-zinc-50 dark:bg-zinc-800/50 pl-12 pr-4 py-3.5 text-zinc-900
                                   dark:text-white font-medium focus:border-amber-500 focus:ring-2
                                   focus:ring-amber-500/20 focus:outline-none appearance-none
                                   transition-all duration-300 cursor-pointer
                                   group-hover:border-zinc-300 dark:group-hover:border-zinc-600"
                        >
                            @foreach($roles as $role)
                                <option value="{{ $role }}"
                                        class="py-2 dark:bg-zinc-800 dark:text-white">
                                    {{ $role }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute right-4 top-1/2 transform -translate-y-1/2 text-zinc-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                    @error('role')
                    <div class="mt-2 flex items-center gap-2 text-sm text-red-600">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                {{-- Password Field --}}
                <div class="group">
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-sm font-semibold text-zinc-700 dark:text-zinc-300
                                      group-focus-within:text-amber-600 dark:group-focus-within:text-amber-400
                                      transition-colors">
                            New Password
                        </label>
                        <span class="text-xs text-zinc-400 dark:text-zinc-600">Optional</span>
                    </div>
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-zinc-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input
                            type="password"
                            wire:model.defer="password"
                            class="w-full rounded-xl border-2 border-zinc-200 dark:border-zinc-700
                                   bg-zinc-50 dark:bg-zinc-800/50 pl-12 pr-4 py-3.5 text-zinc-900
                                   dark:text-white font-medium focus:border-amber-500 focus:ring-2
                                   focus:ring-amber-500/20 focus:outline-none transition-all duration-300
                                   group-hover:border-zinc-300 dark:group-hover:border-zinc-600"
                            placeholder="Leave blank to keep current"
                        >
                    </div>
                    @error('password')
                    <div class="mt-2 flex items-center gap-2 text-sm text-red-600">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center justify-between pt-8 border-t border-zinc-100 dark:border-zinc-800">
                    <div class="flex gap-3">
                        <button type="submit"
                                class="group relative inline-flex items-center gap-3 rounded-2xl
                                       bg-gradient-to-r from-amber-500 via-amber-500 to-yellow-500
                                       px-8 py-3.5 text-white font-semibold shadow-lg hover:shadow-xl
                                       transition-all duration-300 hover:scale-[1.02] active:scale-[0.98]
                                       overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-r from-yellow-400 to-amber-600
                                        opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <svg class="h-5 w-5 relative z-10" fill="none" stroke="currentColor" stroke-width="2.5"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="relative z-10">Save Changes</span>
                        </button>

                        <a href="{{ route('admin.users.index') }}" wire:navigate
                           class="inline-flex items-center gap-3 rounded-2xl border-2 border-zinc-200
                                  dark:border-zinc-700 px-8 py-3.5 text-sm font-semibold text-zinc-700
                                  dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800
                                  hover:border-zinc-300 dark:hover:border-zinc-600 transition-all duration-300">
                            Cancel
                        </a>
                    </div>

                    @if(auth()->id() !== $user->id)
                        <button
                            type="button"
                            wire:click="delete"
                            onclick="confirm('Are you sure you want to delete this user? This action cannot be undone.') || event.stopImmediatePropagation()"
                            class="group inline-flex items-center gap-2 rounded-xl px-4 py-2.5
                                   text-sm font-semibold text-red-600 hover:text-red-700
                                   dark:text-red-400 dark:hover:text-red-300
                                   hover:bg-red-50 dark:hover:bg-red-900/20
                                   border border-red-200 dark:border-red-800
                                   hover:border-red-300 dark:hover:border-red-700
                                   transition-all duration-300"
                        >
                            <svg class="h-4 w-4 group-hover:scale-110 transition-transform"
                                 fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Delete User
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
