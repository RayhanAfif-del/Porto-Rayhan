@php
    $itemsExp = $experiences ?? null;
    if (empty($itemsExp) || (is_countable($itemsExp) && count($itemsExp) === 0)) {
        $defaultExperiences = config('portfolio.experiences', [
            [
                'role' => 'Frontend & Web Developer Intern',
                'company' => 'Digital Agency / Prakerin',
                'period' => '2024 — 2025',
                'description' => 'Mengembangkan antarmuka web responsif, slicing komponen UI/UX dari Figma ke Tailwind CSS & Blade, dan integrasi API RESTful.',
                'skills' => ['Laravel', 'Tailwind CSS', 'Figma', 'JavaScript'],
            ],
            [
                'role' => 'Freelance Web Developer & UI Designer',
                'company' => 'Proyek Independen',
                'period' => '2023 — Sekarang',
                'description' => 'Merancang UI/UX modern dan membangun website sistem informasi serta landing page fungsional untuk berbagai kebutuhan klien.',
                'skills' => ['UI/UX Design', 'PHP', 'MySQL', 'Figma'],
            ],
        ]);

        $rawExp = setting('experience.items', $defaultExperiences);
        $itemsExp = is_string($rawExp) ? (json_decode($rawExp, true) ?? $defaultExperiences) : $rawExp;
        if (! is_array($itemsExp)) {
            $itemsExp = $defaultExperiences;
        }
    }
@endphp

<section id="education" class="bg-offwhite py-24 sm:py-32 lg:py-40">
    <div class="container-x">
        <x-section-label data-reveal>EDUCATION &amp; EXPERIENCE</x-section-label>
        <h2 data-reveal data-delay="100" class="headline max-w-[20ch]">Learning &amp; journey that shapes how I build.</h2>

        <div class="mt-16 grid gap-14 lg:grid-cols-12 lg:gap-16">
            {{-- Column 1: Education (lg:col-span-6) -- Left col first (100ms) --}}
            <div data-reveal data-delay="100" class="lg:col-span-6">
                <div class="mb-8 flex items-center gap-3">
                    <div class="flex size-9 items-center justify-center rounded-2xl bg-ink text-white shadow-sm">
                        <x-icon name="graduation-cap" class="size-4" />
                    </div>
                    <div>
                        <h3 class="text-xs font-semibold uppercase tracking-[0.2em] text-muted">Education</h3>
                        <p class="font-mono text-[10px] text-muted/70">Riwayat Akademik Formal</p>
                    </div>
                </div>

                <div class="space-y-2">
                    @forelse ($education as $edu)
                        <div data-reveal data-delay="{{ min(150 + ($loop->index * 100), 500) }}" class="relative border-l border-ink/20 pb-8 pl-7 last:border-transparent last:pb-0">
                            <span class="absolute -left-[5px] top-1.5 size-2.5 rounded-full bg-coral ring-4 ring-offwhite" aria-hidden="true"></span>
                            @if ($edu->period)<p class="font-mono text-xs font-medium text-coral">{{ $edu->period }}</p>@endif
                            <h4 class="mt-1 text-lg font-semibold tracking-tight text-ink">{{ $edu->school }}</h4>
                            <p class="mt-0.5 font-medium text-ink/80">{{ $edu->major }}</p>
                            @if ($edu->description)<p class="mt-2.5 text-[0.95rem] leading-relaxed text-muted">{{ $edu->description }}</p>@endif
                        </div>
                    @empty
                        <p class="text-muted">Data pendidikan belum ditambahkan.</p>
                    @endforelse
                </div>
            </div>

            {{-- Column 2: Experience (lg:col-span-6) -- Right col second (300ms) --}}
            <div data-reveal data-delay="300" class="lg:col-span-6">
                <div class="mb-8 flex items-center gap-3">
                    <div class="flex size-9 items-center justify-center rounded-2xl bg-ink text-white shadow-sm shadow-coral/20">
                        <x-icon name="briefcase" class="size-4" />
                    </div>
                    <div>
                        <h3 class="text-xs font-semibold uppercase tracking-[0.2em] text-muted">Experience</h3>
                        <p class="font-mono text-[10px] text-muted/70">Praktik Kerja &amp; Proyek Lapangan</p>
                    </div>
                </div>

                <div class="space-y-2">
                    @forelse ($itemsExp as $exp)
                        @php
                            $ePeriod = is_object($exp) ? $exp->period : ($exp['period'] ?? null);
                            $eRole = is_object($exp) ? $exp->role : ($exp['role'] ?? 'Peran');
                            $eCompany = is_object($exp) ? $exp->company : ($exp['company'] ?? 'Instansi / Klien');
                            $eDesc = is_object($exp) ? $exp->description : ($exp['description'] ?? null);
                            $eSkills = is_object($exp) ? $exp->skills : ($exp['skills'] ?? []);
                        @endphp
                        <div data-reveal data-delay="{{ min(350 + ($loop->index * 80), 500) }}" class="relative border-l border-ink/20 pb-8 pl-7 last:border-transparent last:pb-0">
                            <span class="absolute -left-[5px] top-1.5 size-2.5 rounded-full bg-coral ring-4 ring-offwhite" aria-hidden="true"></span>
                            @if (!empty($ePeriod))
                                <p class="font-mono text-xs font-medium text-coral">{{ $ePeriod }}</p>
                            @endif
                            <h4 class="mt-1 text-lg font-semibold tracking-tight text-ink">{{ $eRole }}</h4>
                            <p class="mt-0.5 font-medium text-ink/80">{{ $eCompany }}</p>
                            @if (!empty($eDesc))
                                <p class="mt-2.5 text-[0.95rem] leading-relaxed text-muted">{{ $eDesc }}</p>
                            @endif
                            @if (!empty($eSkills) && is_array($eSkills))
                                <div class="mt-3 flex flex-wrap gap-1.5">
                                    @foreach ($eSkills as $skill)
                                        <span class="rounded-full border border-line bg-white/70 px-2.5 py-0.5 font-mono text-[10px] text-muted">{{ $skill }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="text-muted">Data pengalaman belum ditambahkan.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>
