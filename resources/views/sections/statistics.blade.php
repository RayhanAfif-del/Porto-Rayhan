@php
    $stats = [
        ['value' => str_pad($projects->count(), 2, '0', STR_PAD_LEFT), 'title' => 'Featured Projects', 'desc' => 'Proyek yang ditampilkan di portofolio ini.'],
        ['value' => str_pad(count($technologies), 2, '0', STR_PAD_LEFT), 'title' => 'Technologies Used', 'desc' => 'Bahasa, framework, dan tools yang saya gunakan.'],
        ['value' => '2', 'title' => 'Design & Development', 'desc' => 'Dua bidang utama: pengembangan web dan UI/UX.'],
        ['value' => 'PPLG', 'title' => 'Learning Journey', 'desc' => 'Terus belajar lewat pendidikan dan proyek nyata.'],
    ];
@endphp

<section id="statistics" aria-label="Statistik" class="bg-paper py-20 sm:py-28">
    <div class="container-x">
        <div class="grid grid-cols-2 border-t border-line lg:grid-cols-4">
            @foreach ($stats as $i => $s)
                <div data-reveal data-delay="{{ min(($i + 1) * 100, 400) }}"
                    class="border-b border-line py-8 pr-4 sm:py-10 lg:border-b-0 lg:py-12 {{ $i > 0 ? 'lg:border-l lg:pl-8' : '' }} {{ $i % 2 === 1 ? 'border-l pl-5 sm:pl-8' : '' }}">
                    <p data-counter="true" class="text-[clamp(2.75rem,6vw,5rem)] font-semibold leading-none tracking-tighter">{{ $s['value'] }}</p>
                    <p class="mt-6 text-sm font-semibold">{{ $s['title'] }}</p>
                    <p class="mt-2 max-w-[24ch] text-sm leading-relaxed text-muted">{{ $s['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
