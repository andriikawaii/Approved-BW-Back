<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-panel text-ink">

<flux:sidebar
    sticky
    collapsible="mobile"
    class="border-r border-edge bg-surface text-ink"
>

    {{-- ================= BRAND ================= --}}
    <flux:sidebar.header class="border-b border-edge px-4 py-4">
        <div class="flex items-center justify-between w-full">
            <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl border border-edge-strong bg-surface-alt shadow-sm">
                    <span class="text-lg font-extrabold text-tint">BW</span>
                </div>

                <div class="leading-tight">
                    <div class="text-base font-bold text-ink">
                        BuiltWell
                    </div>
                    <div class="text-xs font-medium text-ink-muted">
                        Admin Panel
                    </div>
                </div>
            </a>

            <flux:sidebar.collapse class="text-ink-muted transition hover:text-ink lg:hidden" />
        </div>
    </flux:sidebar.header>

    {{-- ================= NAV ================= --}}
    <flux:sidebar.nav>

        {{-- PLATFORM --}}
        <flux:sidebar.group heading="Platform">
            <flux:sidebar.item icon="home" :href="route('dashboard')" wire:navigate>
                Dashboard
            </flux:sidebar.item>
        </flux:sidebar.group>

        {{-- CONTENT --}}
        <flux:sidebar.group heading="Content" class="mt-4">
            <flux:sidebar.item icon="document-text" :href="route('admin.pages.index')" wire:navigate>
                Pages
            </flux:sidebar.item>

            @role('super_admin')
            <flux:sidebar.item icon="squares-plus" :href="route('admin.sections.index')" wire:navigate>
                Section Library
            </flux:sidebar.item>

            <flux:sidebar.item icon="shield-check" :href="route('admin.rules.index')" wire:navigate>
                Content Rules
            </flux:sidebar.item>
            @endrole
        </flux:sidebar.group>

        {{-- REFERENCES --}}
        <flux:sidebar.group heading="References" class="mt-4">
            <flux:sidebar.item icon="wrench" :href="route('admin.services.index')" wire:navigate>
                Services
            </flux:sidebar.item>

            <flux:sidebar.item icon="map" :href="route('admin.counties.index')" wire:navigate>
                Counties
            </flux:sidebar.item>

            <flux:sidebar.item icon="map-pin" :href="route('admin.towns.index')" wire:navigate>
                Towns
            </flux:sidebar.item>

            <flux:sidebar.item icon="photo" :href="route('admin.media.index')" wire:navigate>
                Media Assets
            </flux:sidebar.item>
        </flux:sidebar.group>

        {{-- SOCIAL PROOF --}}
        <flux:sidebar.group heading="Social Proof" class="mt-4">
            <flux:sidebar.item icon="star" :href="route('admin.testimonials.index')" wire:navigate>
                Testimonials
            </flux:sidebar.item>

            <flux:sidebar.item icon="briefcase" :href="route('admin.projects.index')" wire:navigate>
                Case Studies
            </flux:sidebar.item>
        </flux:sidebar.group>

        {{-- SEO (P0) --}}
        @role('seo_manager|super_admin')
        <flux:sidebar.group heading="SEO" class="mt-4">
            <flux:sidebar.item icon="arrow-path" :href="route('admin.redirects.index')" wire:navigate>
                Redirects
            </flux:sidebar.item>

            <flux:sidebar.item icon="globe-alt" :href="route('admin.seo.sitemap')" wire:navigate>
                Sitemap (XML)
            </flux:sidebar.item>

            <flux:sidebar.item icon="document" :href="route('admin.seo.html-sitemap')" wire:navigate>
                HTML Sitemap
            </flux:sidebar.item>

            <flux:sidebar.item icon="code-bracket" :href="route('admin.seo.robots')" wire:navigate>
                robots.txt
            </flux:sidebar.item>

            <flux:sidebar.item icon="sparkles" :href="route('admin.seo.llms')" wire:navigate>
                llms.txt
            </flux:sidebar.item>

            <flux:sidebar.item icon="adjustments-horizontal" :href="route('admin.settings.general')" wire:navigate>
                SEO Settings
            </flux:sidebar.item>
        </flux:sidebar.group>
        @endrole

        {{-- OPS (P0/P1) --}}
        <flux:sidebar.group heading="Ops" class="mt-4">
            @role('super_admin')
            <flux:sidebar.item icon="bolt" :href="route('admin.ops.cache')" wire:navigate>
                Cache & Rebuild
            </flux:sidebar.item>

            <flux:sidebar.item icon="clipboard-document-list" :href="route('admin.ops.activity')" wire:navigate>
                Activity Log
            </flux:sidebar.item>
            @endrole
        </flux:sidebar.group>

        {{-- SYSTEM --}}
        <flux:sidebar.group heading="System" class="mt-4">
            @role('super_admin')
            <flux:sidebar.item icon="users" :href="route('admin.users.index')" wire:navigate>
                Admin Users
            </flux:sidebar.item>

            <flux:sidebar.item icon="key" :href="route('admin.system.roles')" wire:navigate>
                Roles & Permissions
            </flux:sidebar.item>
            @endrole
        </flux:sidebar.group>

    </flux:sidebar.nav>

    <flux:spacer />

    {{-- ================= FOOTER LINKS ================= --}}
    <flux:sidebar.nav>
        <flux:sidebar.item icon="globe-alt" href="{{ config('app.frontend_url', '/') }}" target="_blank">
            View Website
        </flux:sidebar.item>

        @role('super_admin')
        <flux:sidebar.item icon="folder-git-2" href="https://github.com/petar2020/builtwell-backend" target="_blank">
            Repository
        </flux:sidebar.item>
        @endrole
    </flux:sidebar.nav>

    {{-- ================= THEME TOGGLE ================= --}}
    <div class="border-t border-edge px-4 py-3" x-data="{ current: localStorage.getItem('flux.appearance') || 'system' }">
        <div class="flex items-center rounded-lg bg-surface-alt p-1">
            <template x-for="opt in ['light', 'system', 'dark']" :key="opt">
                <button
                    type="button"
                    @click="current = opt; Flux.appearance = opt"
                    :class="current === opt ? 'bg-surface text-ink shadow-sm' : 'text-ink-muted hover:text-ink'"
                    class="flex-1 rounded-md px-2 py-1.5 text-center text-xs font-medium transition"
                    x-text="opt.charAt(0).toUpperCase() + opt.slice(1)"
                ></button>
            </template>
        </div>
    </div>

    <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
</flux:sidebar>

{{-- ================= MOBILE HEADER ================= --}}
<flux:header class="border-b border-edge bg-surface text-ink lg:hidden">
    <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
    <flux:spacer />

    <div class="flex items-center gap-2">
        <div class="flex h-8 w-8 items-center justify-center rounded-lg border border-edge-strong bg-surface-alt">
            <span class="text-sm font-extrabold text-tint">BW</span>
        </div>
        <span class="text-sm font-bold text-ink">BuiltWell</span>
    </div>

    <flux:spacer />

    <flux:dropdown position="top" align="end">
        <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />

        <flux:menu>
            <div class="px-3 py-2">
                <div class="text-sm font-semibold text-ink">{{ auth()->user()->name }}</div>
                <div class="text-xs text-ink-muted">{{ auth()->user()->email }}</div>
            </div>

            <flux:menu.separator />

            <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                Profile
            </flux:menu.item>

            <flux:menu.separator />

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle">
                    Log Out
                </flux:menu.item>
            </form>
        </flux:menu>
    </flux:dropdown>
</flux:header>

{{ $slot }}

@fluxScripts
</body>
</html>
