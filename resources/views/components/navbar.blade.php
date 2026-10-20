@php
    $links = [
        ['Home', 'home'],
        ['About', 'about'],
        ['Skills', 'skills'],
        ['Projects', 'projects'],
        ['Education & Experience', 'education'],
        ['Contact', 'contact'],
    ];
@endphp

<header data-navbar class="fixed top-3 sm:top-5 inset-x-0 z-50 mx-auto w-[calc(100%-1.5rem)] sm:w-[calc(100%-3rem)] max-w-7xl transition-all duration-300">
    <div class="relative flex h-14 sm:h-16 items-center justify-between rounded-full border border-white/15 bg-[#121217]/80 px-5 sm:px-8 shadow-[0_8px_32px_rgba(0,0,0,0.45)] backdrop-blur-xl">
        {{-- Brand Logo --}}
        <a href="#home" data-nav-link="home" class="flex items-center gap-1 pl-1 text-base sm:text-lg font-bold tracking-tight text-white transition hover:text-white/90 after:hidden" aria-label="{{ setting('identity.short_name', config('portfolio.short_name', 'Rayhan')) }}, kembali ke atas">
            <span>{{ setting('identity.short_name', config('portfolio.short_name', 'Rayhan')) }}</span><span class="text-coral">.</span>
        </a>

        {{-- Center Desktop Links --}}
        <nav aria-label="Navigasi utama" class="hidden lg:block">
            <ul class="flex items-center gap-7 xl:gap-10 text-xs xl:text-sm font-medium">
                @foreach ($links as [$label, $id])
                    <li>
                        <a href="#{{ $id }}" data-nav-link="{{ $id }}" class="py-2 text-white/70 transition hover:text-white">{{ $label }}</a>
                    </li>
                @endforeach
            </ul>
        </nav>

        {{-- Right Action Buttons --}}
        <div class="flex items-center gap-2.5">
            <a href="#contact" class="hidden sm:inline-flex items-center gap-1.5 rounded-full bg-coral px-4 py-2 text-xs font-semibold text-white shadow-lg shadow-coral/25 transition hover:bg-coral-dark">
                <span>Let's Talk</span>
                <x-icon name="arrow-up-right" class="size-3.5" />
            </a>

            {{-- Mobile Menu Trigger --}}
            <button type="button" data-menu-button aria-expanded="false" aria-controls="mobile-menu" aria-label="Buka menu"
                class="inline-flex size-10 items-center justify-center rounded-full border border-white/20 text-white transition hover:bg-white/10 lg:hidden">
                <x-icon name="menu" class="size-4" />
            </button>
        </div>
    </div>

    {{-- Floating Rounded Mobile Menu Dropdown --}}
    <div id="mobile-menu" data-mobile-menu class="hidden mt-2.5 overflow-hidden rounded-3xl border border-white/15 bg-[#121217]/95 p-6 shadow-2xl backdrop-blur-2xl lg:hidden transition-all duration-300">
        <nav aria-label="Navigasi seluler">
            <ul class="divide-y divide-white/10">
                @foreach ($links as $i => [$label, $id])
                    <li>
                        <a href="#{{ $id }}" data-nav-link="{{ $id }}" class="flex items-center justify-between py-3.5 text-base font-medium text-white/80 transition hover:text-white after:hidden">
                            <span>{{ $label }}</span>
                            <span class="font-mono text-xs text-white/40">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
            <a href="#contact" class="btn-coral mt-6 w-full justify-center">
                <span>Let's Talk</span>
                <x-icon name="arrow-up-right" class="size-4" />
            </a>
        </nav>
    </div>
</header>
