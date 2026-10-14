<section id="education" class="bg-offwhite py-24 sm:py-32 lg:py-40">
    <div class="container-x">
        <x-section-label data-reveal>EDUCATION &amp; CERTIFICATES</x-section-label>
        <h2 data-reveal class="headline max-w-[18ch]">Learning that shapes how I build.</h2>

        <div class="mt-16 grid gap-16 lg:grid-cols-12 lg:gap-20">
            {{-- Timeline pendidikan --}}
            <div class="lg:col-span-5">
                <h3 class="mb-8 text-xs font-semibold uppercase tracking-[0.2em] text-muted">Education</h3>
                @forelse ($education as $edu)
                    <div data-reveal class="relative border-l border-ink/20 pb-10 pl-8 last:pb-0">
                        <span class="absolute -left-[5px] top-1.5 size-2.5 rounded-full bg-coral" aria-hidden="true"></span>
                        @if ($edu->period)<p class="text-sm text-muted">{{ $edu->period }}</p>@endif
                        <h4 class="mt-1 text-xl font-semibold tracking-tight">{{ $edu->school }}</h4>
                        <p class="mt-1 font-medium">{{ $edu->major }}</p>
                        @if ($edu->description)<p class="mt-3 text-[0.95rem] leading-relaxed text-muted">{{ $edu->description }}</p>@endif
                    </div>
                @empty
                    <p class="text-muted">Data pendidikan belum ditambahkan.</p>
                @endforelse
            </div>

            @include('sections.certificates')
        </div>
    </div>
</section>
