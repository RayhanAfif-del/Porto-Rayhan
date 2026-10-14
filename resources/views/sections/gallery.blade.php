<section id="gallery" aria-label="Galeri visual" class="bg-paper pb-8 pt-8 sm:pb-12">
    <div class="container-x grid gap-4 sm:gap-5 md:grid-cols-12">
        @foreach (config('portfolio.images.gallery') as $i => $img)
            <figure data-reveal style="--reveal-delay:{{ ($i % 2) * 100 }}ms" class="group overflow-hidden rounded-3xl bg-offwhite {{ $img['class'] }}">
                <img src="{{ asset($img['src']) }}" data-fallback="{{ asset('images/project-placeholder.svg') }}" alt="{{ $img['alt'] }}"
                    width="1400" height="900" loading="lazy" decoding="async"
                    class="h-full w-full object-cover transition duration-700 ease-out group-hover:scale-[1.04]">
            </figure>
        @endforeach
    </div>
</section>
