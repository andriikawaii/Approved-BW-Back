{{-- CREATE KOMPONENTA --}}
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
                <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-green-500
                            flex items-center justify-center shadow-lg">
                    <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-zinc-900 to-zinc-700
                                dark:from-white dark:to-zinc-300 bg-clip-text text-transparent">
                        Create New User
                    </h1>
                    <p class="text-zinc-500 dark:text-zinc-400 mt-1">
                        Add a new administrator to the system
                    </p>
                </div>
            </div>

            {{-- Info Chip --}}
            <div class="inline-flex items-center gap-3 bg-gradient-to-r from-emerald-50 to-green-50
                        dark:from-emerald-900/20 dark:to-green-900/20 rounded-xl px-4 py-3
                        border border-emerald-200 dark:border-emerald-800">
                <svg class="h-5 w-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor"
                     stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="text-sm text-emerald-800 dark:text-emerald-300">
                    All fields are required except optional notes
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
                    User Details
                </h2>
                <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">
                    Fill in the information below
                </p>
            </div>

            <form wire:submit.prevent="save" class="p-8 space-y-6">
                {{-- Name Field --}}
                <div class="group">
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-sm font-semibold text-zinc-700 dark:text-zinc-300
                                      group-focus-within:text-emerald-600 dark:group-focus-within:text-emerald-400
                                      transition-colors">
                            Full Name
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
                                   dark:text-white font-medium focus:border-emerald-500 focus:ring-2
                                   focus:ring-emerald-500/20 focus:outline-none transition-all duration-300
                                   group-hover:border-zinc-300 dark:group-hover:border-zinc-600"
                            placeholder="Enter full name"
                        >
                    </div>
                    @error('name')
                    <div class="mt-2 flex items-center gap-2 text-sm text-red-600 animate-pulse">
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
                                      group-focus-within:text-emerald-600 dark:group-focus-within:text-emerald-400
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
                                   dark:text-white font-medium focus:border-emerald-500 focus:ring-2
                                   focus:ring-emerald-500/20 focus:outline-none transition-all duration-300
                                   group-hover:border-zinc-300 dark:group-hover:border-zinc-600"
                            placeholder="user@example.com"
                        >
                    </div>
                    @error('email')
                    <div class="mt-2 flex items-center gap-2 text-sm text-red-600 animate-pulse">
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
                                      group-focus-within:text-emerald-600 dark:group-focus-within:text-emerald-400
                                      transition-colors">
                            Password
                        </label>
                        <span class="text-xs text-zinc-400 dark:text-zinc-600">Minimum 8 characters</span>
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
                                   dark:text-white font-medium focus:border-emerald-500 focus:ring-2
                                   focus:ring-emerald-500/20 focus:outline-none transition-all duration-300
                                   group-hover:border-zinc-300 dark:group-hover:border-zinc-600"
                            placeholder="Create a strong password"
                        >
                    </div>
                    @error('password')
                    <div class="mt-2 flex items-center gap-2 text-sm text-red-600 animate-pulse">
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
                                  group-focus-within:text-emerald-600 dark:group-focus-within:text-emerald-400
                                  transition-colors">
                        Select Role
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
                                   dark:text-white font-medium focus:border-emerald-500 focus:ring-2
                                   focus:ring-emerald-500/20 focus:outline-none appearance-none
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
                    <div class="mt-2 flex items-center gap-2 text-sm text-red-600 animate-pulse">
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
                <div class="flex gap-3 pt-8 border-t border-zinc-100 dark:border-zinc-800">
                    <button type="submit"
                            class="group relative inline-flex items-center gap-3 rounded-2xl
                                   bg-gradient-to-r from-emerald-500 via-emerald-500 to-green-500
                                   px-8 py-3.5 text-white font-semibold shadow-lg hover:shadow-xl
                                   transition-all duration-300 hover:scale-[1.02] active:scale-[0.98]
                                   overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-r from-green-400 to-emerald-600
                                    opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <svg class="h-5 w-5 relative z-10" fill="none" stroke="currentColor" stroke-width="2.5"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 4v16m8-8H4"/>
                        </svg>
                        <span class="relative z-10">Create User</span>
                    </button>

                    <a href="{{ route('admin.users.index') }}" wire:navigate
                       class="inline-flex items-center gap-3 rounded-2xl border-2 border-zinc-200
                              dark:border-zinc-700 px-8 py-3.5 text-sm font-semibold text-zinc-700
                              dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800
                              hover:border-zinc-300 dark:hover:border-zinc-600 transition-all duration-300">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

        {{-- Bottom Note --}}
        <div class="mt-8 text-center">
            <p class="text-xs text-zinc-400 dark:text-zinc-600">
                🛡️ User creation logs are recorded • Passwords are encrypted • Role-based permissions enforced
            </p>
        </div>
    </div>
</div>
