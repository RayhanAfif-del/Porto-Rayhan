<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Admin') — Portofolio Rayhan</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-[#0d0d11] text-white antialiased selection:bg-coral selection:text-white">
    @php
        $nav = [
            'admin.dashboard' => ['label' => 'Dashboard', 'icon' => 'dashboard'],
            'admin.projects.index' => ['label' => 'Proyek', 'icon' => 'folder'],
            'admin.education.index' => ['label' => 'Pendidikan', 'icon' => 'graduation-cap'],
            'admin.certificates.index' => ['label' => 'Sertifikat', 'icon' => 'badge-check'],
            'admin.messages.index' => ['label' => 'Pesan', 'icon' => 'mail'],
        ];
    @endphp

    <div class="flex min-h-screen flex-col md:flex-row">
        {{-- Sidebar --}}
        <aside class="flex flex-col justify-between border-b border-white/5 bg-[#121217] md:w-64 md:shrink-0 md:border-b-0 md:border-r">
            <div>
                {{-- Logo / Header --}}
                <div class="flex items-center justify-between px-6 py-5">
                    <a href="{{ route('admin.dashboard') }}" class="group block">
                        <div class="flex items-center gap-2">
                            <span class="text-xl font-bold tracking-tight text-white">Rayhan<span class="text-coral">.</span></span>
                        </div>
                        <span class="block font-mono text-[10px] tracking-[0.25em] text-white/40">ADMIN</span>
                    </a>
                </div>

                {{-- Navigation --}}
                <nav aria-label="Menu admin" class="flex gap-1 overflow-x-auto px-3 pb-3 md:flex-col md:space-y-1.5 md:px-4 md:pb-0">
                    @foreach ($nav as $route => $item)
                        @php
                            $isActive = $route === 'admin.dashboard'
                                ? request()->routeIs('admin.dashboard')
                                : request()->routeIs(Str::beforeLast($route, '.').'.*');
                        @endphp
                        <a href="{{ route($route) }}"
                            class="flex items-center gap-3 whitespace-nowrap rounded-xl px-3.5 py-2.5 text-xs font-medium transition {{ $isActive ? 'bg-coral text-white shadow-lg shadow-coral/25' : 'text-white/60 hover:bg-white/[0.04] hover:text-white' }}">
                            <x-icon :name="$item['icon']" class="size-4 shrink-0 {{ $isActive ? 'text-white' : 'text-white/60' }}" />
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach

                    <div class="my-3 hidden border-t border-white/5 md:block"></div>

                    <a href="{{ route('home') }}" target="_blank" rel="noopener"
                        class="hidden items-center gap-3 whitespace-nowrap rounded-xl px-3.5 py-2.5 text-xs font-medium text-white/60 transition hover:bg-white/[0.04] hover:text-white md:flex">
                        <x-icon name="external-link" class="size-4 shrink-0 text-white/50" />
                        <span>Lihat situs</span>
                    </a>
                </nav>
            </div>

            {{-- Logout button at bottom --}}
            <div class="hidden border-t border-white/5 p-4 md:block">
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex w-full items-center gap-3 rounded-xl px-3.5 py-2.5 text-xs font-medium text-white/50 transition hover:bg-red-500/10 hover:text-red-400">
                        <x-icon name="log-out" class="size-4 shrink-0" />
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main Area --}}
        <div class="flex min-w-0 flex-1 flex-col">
            {{-- Topbar --}}
            <header class="sticky top-0 z-30 flex items-center justify-between border-b border-white/5 bg-[#121217]/80 px-6 py-3.5 backdrop-blur-md">
                {{-- Search & Live Console Pill --}}
                <div class="flex items-center gap-4">
                    <div class="relative hidden sm:block">
                        <x-icon name="search" class="pointer-events-none absolute left-3.5 top-1/2 size-3.5 -translate-y-1/2 text-white/35" />
                        <input type="text" placeholder="Cari modul atau data..."
                            class="w-64 rounded-xl border border-white/10 bg-white/[0.03] py-2 pl-9 pr-4 text-xs text-white placeholder:text-white/35 focus:border-white/20 focus:outline-none sm:w-72">
                    </div>
                    <div class="flex items-center gap-2 rounded-full border border-white/10 bg-white/[0.03] px-3 py-1 font-mono text-[11px] text-white/70">
                        <span class="size-1.5 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.8)]"></span>
                        <span>Live Console</span>
                    </div>
                </div>

                {{-- Notifications & Profile --}}
                <div class="flex items-center gap-4">
                    <button type="button" aria-label="Notifikasi"
                        class="relative flex size-9 items-center justify-center rounded-xl border border-white/10 bg-white/[0.03] text-white/60 transition hover:bg-white/[0.08] hover:text-white">
                        <x-icon name="bell" class="size-4" />
                        @if (isset($unreadMessagesCount) && $unreadMessagesCount > 0)
                            <span class="absolute -top-1 -right-1 flex size-4 items-center justify-center rounded-full bg-coral text-[10px] font-bold text-white">{{ $unreadMessagesCount }}</span>
                        @endif
                    </button>

                    <div class="flex items-center gap-3 pl-2">
                        <div class="text-right">
                            <p class="text-xs font-semibold leading-tight text-white">{{ Auth::user()->name ?? 'Rayhan' }}</p>
                            <p class="text-[10px] text-white/40">Superadmin</p>
                        </div>
                        <div class="flex size-9 items-center justify-center rounded-full border border-white/15 bg-gradient-to-tr from-coral/40 to-white/10 font-semibold text-white shadow-inner">
                            {{ strtoupper(substr(Auth::user()->name ?? 'R', 0, 1)) }}
                        </div>
                    </div>
                </div>
            </header>

            {{-- Main Content --}}
            <main class="min-w-0 flex-1 p-5 md:p-8">
                @if (session('status'))
                    <div role="status" class="mb-6 flex items-center gap-3 rounded-2xl border border-emerald-500/20 bg-emerald-500/10 px-4 py-3 text-xs text-emerald-300">
                        <x-icon name="check" class="size-4 text-emerald-400" />
                        <span>{{ session('status') }}</span>
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
