@php
    $defaultGallery = config('portfolio.images.gallery', []);
    $galleryRaw = setting('images.gallery', $defaultGallery);
    $gallery = is_string($galleryRaw) ? (json_decode($galleryRaw, true) ?? $defaultGallery) : $galleryRaw;
    if (! is_array($gallery)) {
        $gallery = $defaultGallery;
    }
    $gallery = array_values(array_filter($gallery, fn ($img) => ! isset($img['visible']) || $img['visible']));
@endphp

<section id="gallery" aria-label="Galeri visual" class="bg-paper pb-8 pt-8 sm:pb-12">
    <div class="container-x grid gap-2.5 sm:gap-3 md:grid-cols-12">
        @foreach ($gallery as $i => $img)
            <figure data-reveal="zoom" data-delay="{{ min(($i % 4 + 1) * 100, 400) }}" class="group overflow-hidden rounded-3xl bg-offwhite {{ $img['class'] ?? 'md:col-span-6 aspect-video' }}">
                <img src="{{ asset($img['src']) }}" data-fallback="{{ asset('images/project-placeholder.svg') }}" alt="{{ $img['alt'] ?? 'Dokumentasi Galeri' }}"
                    width="1400" height="900" loading="lazy" decoding="async"
                    class="h-full w-full object-cover transition duration-700 ease-out group-hover:scale-[1.04]">
            </figure>
        @endforeach
    </div>
</section>
