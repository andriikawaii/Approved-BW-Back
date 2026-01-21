<x-layouts.auth>

    <!-- FULLSCREEN BACKGROUND -->
    <div class="fixed inset-0 bg-gradient-to-br from-gray-900 via-slate-900 to-black"></div>

    <!-- CENTERED LOGIN -->
    <div class="fixed inset-0 z-10 flex items-center justify-center">

        <div class="w-full max-w-md">

            <div class="bg-slate-800/90 backdrop-blur rounded-2xl shadow-2xl border border-slate-700">

                <!-- Header -->
                <div class="p-8 border-b border-slate-700">
                    <div class="flex items-center gap-4 justify-center">
                        <div class="h-12 w-12 bg-amber-500 rounded-xl flex items-center justify-center font-bold text-black">
                            BW
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-white">BuiltWell</h1>
                            <p class="text-sm text-slate-400">Admin Control Panel</p>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <div class="p-8 space-y-6">

                    <div class="text-center">
                        <h2 class="text-xl font-semibold text-white">
                            Administrator Access
                        </h2>
                        <p class="text-xs text-slate-400 mt-1">
                            Restricted to authorized personnel only
                        </p>
                    </div>

                    <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
                        @csrf

                        <!-- Email -->
                        <div>
                            <label class="block text-sm text-slate-300 mb-1">Email</label>
                            <input
                                type="email"
                                name="email"
                                required
                                class="w-full rounded-lg bg-slate-700 border border-slate-600 px-4 py-3 text-white focus:ring-2 focus:ring-amber-500"
                            />
                        </div>

                        <!-- Password -->
                        <div>
                            <label class="block text-sm text-slate-300 mb-1">Password</label>
                            <input
                                type="password"
                                name="password"
                                required
                                class="w-full rounded-lg bg-slate-700 border border-slate-600 px-4 py-3 text-white focus:ring-2 focus:ring-amber-500"
                            />
                        </div>

                        <!-- Remember -->
                        <div class="flex items-center justify-between text-sm">
                            <label class="flex items-center gap-2 text-slate-400">
                                <input type="checkbox" name="remember" class="rounded">
                                Keep me logged in
                            </label>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-amber-400 hover:underline">
                                    Forgot password?
                                </a>
                            @endif
                        </div>

                        <!-- Submit -->
                        <button
                            type="submit"
                            class="w-full py-3 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 text-black font-semibold hover:opacity-90 transition"
                        >
                            Sign In
                        </button>
                    </form>

                    <div class="text-center text-xs text-slate-400 pt-4 border-t border-slate-700">
                        Administrator & SEO Manager access only
                    </div>
                </div>
            </div>

            <p class="mt-6 text-center text-xs text-slate-500">
                © {{ date('Y') }} BuiltWell Contracting
            </p>

        </div>
    </div>

</x-layouts.auth>
