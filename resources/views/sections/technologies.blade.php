<section id="technologies" aria-label="Teknologi yang digunakan" class="border-b border-line bg-paper py-8 sm:py-10">
    {{-- Mobile: daftar rapi tanpa animasi --}}
    <ul class="container-x flex flex-wrap justify-center gap-x-8 gap-y-4 text-sm font-medium text-muted md:hidden">
        @foreach ($technologies as $tech)
            <li class="flex items-center gap-2"><span class="size-1.5 rounded-full bg-coral" aria-hidden="true"></span>{{ $tech }}</li>
        @endforeach
    </ul>

    {{-- Desktop: marquee lambat --}}
    <div class="marquee hidden overflow-hidden md:block" style="mask-image:linear-gradient(to right,transparent,#000 8%,#000 92%,transparent)">
        <div class="marquee-track">
            @foreach ([false, true] as $dup)
                <ul class="flex shrink-0 items-center gap-14 pr-14 text-lg font-medium tracking-tight text-ink/80 {{ $dup ? 'marquee-dup' : '' }}" @if ($dup) aria-hidden="true" @endif>
                    @foreach ($technologies as $tech)
                        <li class="flex items-center gap-14 whitespace-nowrap">{{ $tech }}<span class="size-1.5 rounded-full bg-coral" aria-hidden="true"></span></li>
                    @endforeach
                </ul>
            @endforeach
        </div>
    </div>
</section>
