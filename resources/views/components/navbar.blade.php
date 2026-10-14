@php
    $links = [
        ['Home', 'home'],
        ['About', 'about'],
        ['Skills', 'skills'],
        ['Projects', 'projects'],
        ['Education & Certificates', 'education'],
        ['Contact', 'contact'],
    ];
@endphp

<header data-navbar class="fixed inset-x-0 top-0 z-50">
    <div class="container-x flex h-[4.5rem] items-center justify-between">
        <a href="#home" data-nav-link="home" class="text-xl font-semibold tracking-tight text-white after:hidden" aria-label="Rayhan, kembali ke atas">
            Rayhan<span class="text-coral">.</span>
        </a>

        <nav aria-label="Navigasi utama" class="hidden lg:block">
            <ul class="flex items-center gap-8 text-sm">
                @foreach ($links as [$label, $id])
                    <li><a href="#{{ $id }}" data-nav-link="{{ $id }}" class="py-2">{{ $label }}</a></li>
                @endforeach
            </ul>
        </nav>

        <div class="flex items-center gap-3">
            <a href="#contact" class="btn-coral hidden sm:inline-flex">Let's Talk <x-icon name="arrow-up-right" class="size-4" /></a>
            <button type="button" data-menu-button aria-expanded="false" aria-controls="mobile-menu" aria-label="Buka menu"
                class="inline-flex size-11 items-center justify-center rounded-full border border-white/25 text-white lg:hidden">
                <x-icon name="menu" />
            </button>
        </div>
    </div>

    <div id="mobile-menu" data-mobile-menu class="hidden h-[calc(100dvh-4.5rem)] overflow-y-auto bg-ink lg:hidden">
        <nav aria-label="Navigasi seluler" class="container-x py-8">
            <ul class="divide-y divide-white/10">
                @foreach ($links as $i => [$label, $id])
                    <li>
                        <a href="#{{ $id }}" data-nav-link="{{ $id }}" class="flex items-baseline gap-4 py-5 text-2xl font-medium after:hidden">
                            <span class="text-xs text-white/40">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>{{ $label }}
                        </a>
                    </li>
                @endforeach
            </ul>
            <a href="#contact" class="btn-coral mt-8 w-full">Let's Talk</a>
        </nav>
    </div>
</header>
