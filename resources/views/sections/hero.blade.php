@php
    $heroBadges = array_filter(array_map('trim', explode(',', setting('hero.badges', 'Web Development, UI/UX Design, Creative Solutions'))));
@endphp

<section id="home" class="bg-ink p-2 pt-2 sm:p-3">
    <div class="relative isolate flex min-h-[100svh] flex-col justify-end overflow-hidden rounded-[1.75rem] bg-charcoal text-white sm:rounded-[2.25rem]">
        <img src="{{ asset(setting('images.hero', config('portfolio.images.hero'))) }}" alt="" width="1920" height="1080" fetchpriority="high"
            data-parallax="0.15"
            class="absolute inset-0 -z-20 h-full w-full object-cover scale-105 will-change-transform">
        <div class="absolute inset-0 -z-10 bg-gradient-to-t from-ink/90 via-ink/45 to-ink/60" aria-hidden="true"></div>

        <div class="container-x pb-10 pt-36 sm:pb-14 lg:pb-16">
            <p data-reveal class="mb-6 text-xs font-semibold uppercase tracking-[0.22em] text-coral">
                {{ setting('hero.tagline', 'PERSONAL PORTFOLIO / WEB DEVELOPER') }}
            </p>

            <h1 data-reveal data-delay="100" class="display max-w-[16ch]">
                {{ setting('hero.headline', 'Building Digital Experiences That Matter.') }}
            </h1>

            <div class="mt-8 grid items-end gap-10 lg:grid-cols-12">
                <p data-reveal data-delay="200" class="max-w-xl text-base leading-relaxed text-white/75 sm:text-lg lg:col-span-6">
                    {{ setting('hero.description', 'Saya Muhammad Rayhan, Junior Web Developer & UI/UX Designer yang berfokus pada pengembangan website modern dan perancangan pengalaman digital yang intuitif.') }}
                </p>

                <div data-reveal data-delay="300" class="flex flex-wrap gap-3 lg:col-span-6 lg:justify-end">
                    <a href="#projects" class="btn-coral">View My Projects <x-icon name="arrow-up-right" class="size-4" /></a>
                    <a href="#contact" class="btn-ghost-light">Let's Talk</a>
                </div>
            </div>

            @if (! empty($heroBadges))
                <ul data-reveal data-delay="400" class="mt-12 flex flex-wrap gap-x-8 gap-y-3 border-t border-white/15 pt-6 text-xs font-medium uppercase tracking-[0.18em] text-white/60">
                    @foreach ($heroBadges as $badge)
                        <li>{{ $badge }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</section>
