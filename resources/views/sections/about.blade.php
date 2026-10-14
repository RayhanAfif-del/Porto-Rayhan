<section id="about" class="bg-offwhite py-24 sm:py-32 lg:py-40">
    <div class="container-x grid items-center gap-14 lg:grid-cols-12 lg:gap-20">
        <div class="lg:col-span-7">
            <x-section-label data-reveal>WHO I AM</x-section-label>
            <h2 data-reveal class="headline max-w-[20ch]">I build websites and digital experiences that connect design with functionality.</h2>

            <div data-reveal class="mt-10 grid gap-6 text-[1.02rem] leading-relaxed text-muted sm:grid-cols-2 sm:gap-10">
                <p>
                    Saya <strong class="font-semibold text-ink">Muhammad Rayhan</strong>, siswa jurusan PPLG (Pengembangan Perangkat Lunak dan Gim) yang tertarik pada Web Development dan UI/UX Design.
                </p>
                <p>
                    Saya terus belajar dengan membangun proyek, mulai dari sistem informasi hingga rancangan antarmuka. Tujuan saya adalah berkembang menjadi developer yang mampu menjembatani desain dan fungsionalitas.
                </p>
            </div>

            <div data-reveal class="mt-10 flex flex-wrap gap-3">
                @if (config('portfolio.cv_url'))
                    <a href="{{ config('portfolio.cv_url') }}" class="btn-coral" download>Unduh CV <x-icon name="arrow-up-right" class="size-4" /></a>
                @endif
                <a href="#education" class="btn-ghost-dark">Lihat Pendidikan &amp; Sertifikat</a>
            </div>
        </div>

        <figure data-reveal class="lg:col-span-5">
            <img src="{{ asset(config('portfolio.images.about')) }}" data-fallback="{{ asset('images/about-placeholder.svg') }}"
                alt="Foto profil Muhammad Rayhan (placeholder, ganti dengan foto asli)" width="900" height="1100" loading="lazy" decoding="async"
                class="aspect-[4/5] w-full rounded-[2rem] object-cover">
        </figure>
    </div>
</section>
