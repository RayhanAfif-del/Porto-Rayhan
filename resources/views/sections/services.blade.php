@php
    $defaultServices = [
        ['icon' => 'code', 'title' => 'Web Development', 'items' => [
            'Pengembangan website dengan Laravel dan PHP.',
            'Implementasi antarmuka dengan HTML, CSS, dan JavaScript.',
            'Pengelolaan database MySQL.',
        ]],
        ['icon' => 'pen', 'title' => 'UI/UX Design', 'items' => [
            'Wireframing dan prototyping.',
            'Perancangan layout website.',
            'Desain antarmuka menggunakan Figma.',
        ]],
        ['icon' => 'devices', 'title' => 'Responsive Web Design', 'items' => [
            'Layout adaptif untuk desktop, tablet, dan mobile.',
            'Optimalisasi navigasi dan pengalaman pengguna.',
            'Pengujian tampilan lintas perangkat.',
        ]],
    ];

    $servicesRaw = setting('services.items', $defaultServices);
    $services = is_string($servicesRaw) ? (json_decode($servicesRaw, true) ?? $defaultServices) : $servicesRaw;
    if (! is_array($services)) {
        $services = $defaultServices;
    }
@endphp

<section id="skills" class="bg-ink py-24 text-white sm:py-32 lg:py-40">
    <div class="container-x">
        <div class="space-y-6">
            <div>
                <x-section-label :dark="true" data-reveal>{{ setting('services.label', 'SKILLS & SERVICES') }}</x-section-label>
                <h2 data-reveal class="display" style="font-size:clamp(2.5rem,6.5vw,5.5rem)">
                    {{ setting('services.headline', 'What I Can Do') }}
                </h2>
            </div>
            <p data-reveal class="max-w-2xl text-base leading-relaxed text-white/60">
                {{ setting('services.description', 'Menggabungkan pengembangan web dan desain antarmuka untuk membangun pengalaman digital yang fungsional, responsif, dan menarik.') }}
            </p>
        </div>

        <ol class="mt-16 border-t border-white/15 sm:mt-20">
            @foreach ($services as $i => $s)
                <li data-reveal class="group grid gap-6 border-b border-white/15 py-10 transition-colors hover:bg-white/[0.03] lg:grid-cols-12 lg:gap-10 lg:py-14">
                    <div class="flex items-start gap-5 lg:col-span-5">
                        <span class="pt-2 text-xs font-medium text-white/40">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <h3 class="text-3xl font-semibold tracking-tight sm:text-4xl lg:text-[2.6rem]">{{ $s['title'] ?? 'Layanan' }}</h3>
                    </div>
                    <ul class="space-y-3 text-[0.98rem] leading-relaxed text-white/65 lg:col-span-7">
                        @if (isset($s['items']) && is_array($s['items']))
                            @foreach ($s['items'] as $item)
                                <li class="flex gap-3"><span class="mt-2.5 size-1.5 shrink-0 rounded-full bg-coral" aria-hidden="true"></span>{{ $item }}</li>
                            @endforeach
                        @endif
                    </ul>
                </li>
            @endforeach
        </ol>
    </div>
</section>
