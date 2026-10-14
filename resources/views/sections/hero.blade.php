<section id="home" class="bg-ink p-2 pt-2 sm:p-3">
    <div class="relative isolate flex min-h-[100svh] flex-col justify-end overflow-hidden rounded-[1.75rem] bg-charcoal text-white sm:rounded-[2.25rem]">
        <img src="{{ asset(config('portfolio.images.hero')) }}" alt="" width="1920" height="1080" fetchpriority="high"
            class="absolute inset-0 -z-20 h-full w-full object-cover">
        <div class="absolute inset-0 -z-10 bg-gradient-to-t from-ink/90 via-ink/45 to-ink/60" aria-hidden="true"></div>

        <div class="container-x pb-10 pt-36 sm:pb-14 lg:pb-16">
            <p data-reveal class="mb-6 text-xs font-semibold uppercase tracking-[0.22em] text-coral">PERSONAL PORTFOLIO / WEB DEVELOPER</p>

            <h1 data-reveal style="--reveal-delay:80ms" class="display max-w-[16ch]">Building Digital Experiences That Matter.</h1>

            <div class="mt-8 grid items-end gap-10 lg:grid-cols-12">
                <p data-reveal style="--reveal-delay:160ms" class="max-w-xl text-base leading-relaxed text-white/75 sm:text-lg lg:col-span-6">
                    Saya Muhammad Rayhan, Junior Web Developer &amp; UI/UX Designer yang berfokus pada pengembangan website modern dan perancangan pengalaman digital yang intuitif.
                </p>

                <div data-reveal style="--reveal-delay:240ms" class="flex flex-wrap gap-3 lg:col-span-6 lg:justify-end">
                    <a href="#projects" class="btn-coral">View My Projects <x-icon name="arrow-up-right" class="size-4" /></a>
                    <a href="#contact" class="btn-ghost-light">Let's Talk</a>
                </div>
            </div>

            <ul class="mt-12 flex flex-wrap gap-x-8 gap-y-3 border-t border-white/15 pt-6 text-xs font-medium uppercase tracking-[0.18em] text-white/60">
                <li>Web Development</li>
                <li>UI/UX Design</li>
                <li>Creative Solutions</li>
            </ul>
        </div>
    </div>
</section>
