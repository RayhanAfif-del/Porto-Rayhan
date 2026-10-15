@php
    $c = [
        'email' => setting('contact.email', config('portfolio.contact.email')),
        'github' => setting('contact.github', config('portfolio.contact.github')),
        'linkedin' => setting('contact.linkedin', config('portfolio.contact.linkedin')),
        'instagram' => setting('contact.instagram', config('portfolio.contact.instagram')),
        'tiktok' => setting('contact.tiktok', config('portfolio.contact.tiktok')),
    ];
    $socials = array_filter([
        'GitHub' => $c['github'],
        'LinkedIn' => $c['linkedin'],
        'Instagram' => $c['instagram'],
        'TikTok' => $c['tiktok'],
        'Email' => $c['email'] ? 'mailto:'.$c['email'] : null,
    ]);
    $nav = ['Home' => 'home', 'About' => 'about', 'Skills' => 'skills', 'Projects' => 'projects', 'Education' => 'education', 'Contact' => 'contact'];
    $shortName = setting('identity.short_name', config('portfolio.short_name', 'Rayhan'));
    $fullName = setting('identity.name', config('portfolio.name', 'Muhammad Rayhan'));
    $role = setting('identity.role', config('portfolio.role', 'Junior Web Developer & UI/UX Designer'));
@endphp

<footer class="border-t border-white/10 bg-ink text-white">
    <div class="container-x py-14 lg:py-20">
        <div class="grid gap-12 lg:grid-cols-12">
            <div class="lg:col-span-5">
                <p class="text-3xl font-semibold tracking-tight">{{ $shortName }}<span class="text-coral">.</span></p>
                <p class="mt-4 max-w-sm text-sm leading-relaxed text-white/60">
                    {{ setting('identity.bio_summary', 'Membangun pengalaman web yang presisi, performan tinggi, dan estetik modern dengan fokus pada ekosistem web responsif.') }}
                </p>
            </div>

            <nav aria-label="Navigasi footer" class="lg:col-span-4">
                <p class="mb-4 text-xs font-semibold uppercase tracking-[0.2em] text-white/40">Navigasi</p>
                <ul class="grid grid-cols-2 gap-x-6 gap-y-3 text-sm text-white/70">
                    @foreach ($nav as $label => $id)
                        <li><a href="#{{ $id }}" class="transition hover:text-white">{{ $label }}</a></li>
                    @endforeach
                </ul>
            </nav>

            <div class="lg:col-span-3">
                <p class="mb-4 text-xs font-semibold uppercase tracking-[0.2em] text-white/40">Media Sosial</p>
                @if ($socials)
                    <ul class="space-y-3 text-sm text-white/70">
                        @foreach ($socials as $label => $url)
                            <li><a href="{{ $url }}" @if (! str_starts_with($url, 'mailto:')) target="_blank" rel="noopener noreferrer" @endif class="transition hover:text-white">{{ $label }}</a></li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-sm text-white/40">Tautan belum diatur di Pengaturan Admin.</p>
                @endif
            </div>
        </div>

        <div class="mt-14 flex flex-col items-start justify-between gap-6 border-t border-white/10 pt-8 text-sm text-white/50 sm:flex-row sm:items-center">
            <p>&copy; {{ date('Y') }} {{ $fullName }}. All rights reserved.</p>
            <button type="button" data-scrolltop class="inline-flex items-center gap-2 rounded-full border border-white/20 px-5 py-2.5 text-white/80 transition hover:bg-white hover:text-ink">
                Kembali ke atas <x-icon name="arrow-up" class="size-4" />
            </button>
        </div>
    </div>
</footer>
