<div class="lg:col-span-7">
    <h3 class="mb-8 text-xs font-semibold uppercase tracking-[0.2em] text-muted">Certificates</h3>

    @if ($certificates->isEmpty())
        <div data-reveal class="flex flex-col items-start gap-4 rounded-3xl border border-dashed border-ink/20 p-8 sm:p-10">
            <x-icon name="award" class="size-8 text-coral" />
            <p class="max-w-md leading-relaxed text-muted">
                Belum ada sertifikat yang ditambahkan. Tambahkan data asli ke tabel <code>certificates</code> (judul, penyelenggara, tanggal, gambar, dan tautan verifikasi) agar tampil di sini.
            </p>
        </div>
    @else
        <div class="grid gap-6 sm:grid-cols-2">
            @foreach ($certificates as $cert)
                <article data-reveal class="group overflow-hidden rounded-3xl bg-paper">
                    <img src="{{ $cert->image_url }}" data-fallback="{{ asset('images/certificate-placeholder.svg') }}"
                        alt="Sertifikat {{ $cert->title }}" width="1200" height="850" loading="lazy" decoding="async"
                        class="aspect-[7/5] w-full object-cover transition duration-700 group-hover:scale-[1.03]">
                    <div class="p-6">
                        <h4 class="text-lg font-semibold tracking-tight">{{ $cert->title }}</h4>
                        <p class="mt-1 text-sm text-muted">{{ $cert->issuer }}@if ($cert->issued_at) &middot; {{ $cert->issued_at->translatedFormat('F Y') }}@endif</p>
                        @if ($cert->verification_url)
                            <a href="{{ $cert->verification_url }}" target="_blank" rel="noopener noreferrer" class="mt-4 inline-flex items-center gap-1.5 text-sm font-medium hover:text-coral">Verifikasi <x-icon name="arrow-up-right" class="size-4" /></a>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</div>
