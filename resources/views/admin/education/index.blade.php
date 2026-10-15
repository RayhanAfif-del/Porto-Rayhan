@extends('admin.layout')

@section('title', 'Kelola Pendidikan')

@section('content')
    <div class="space-y-8">
        {{-- Header Section --}}
        <div class="flex flex-col justify-between gap-4 lg:flex-row lg:items-center">
            <div>
                <div class="flex items-center gap-2 font-mono text-[11px] tracking-wider text-white/50 uppercase">
                <p class="font-mono text-[11px] tracking-[0.2em] text-white/40 uppercase">
                    Portofolio / Pendidikan
                </p>
                </div>
                <h1 class="mt-2 text-2xl font-bold tracking-tight text-white sm:text-3xl">Kelola Pendidikan</h1>
                <p class="mt-1 text-xs text-white/50 sm:text-sm">
                    Atur riwayat pendidikan formal dan sertifikasi untuk ditampilkan di beranda.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.education.create') }}"
                    class="flex items-center gap-2 rounded-xl bg-coral px-4 py-2.5 text-xs font-semibold text-white shadow-lg shadow-coral/25 transition hover:bg-coral-dark">
                    <x-icon name="plus" class="size-3.5" />
                    <span>Tambah Pendidikan</span>
                </a>
            </div>
        </div>

        {{-- Filter Tabs & Search Bar --}}
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div class="relative w-full sm:w-64">
                <x-icon name="search" class="pointer-events-none absolute left-3.5 top-1/2 size-3.5 -translate-y-1/2 text-white/35" />
                <input type="text" id="education-search" placeholder="Cari pendidikan..."
                    class="w-full rounded-xl border border-white/10 bg-white/[0.03] py-2 pl-9 pr-3 text-xs text-white placeholder:text-white/35 focus:border-white/20 focus:outline-none">
            </div>
        </div>

        {{-- Education Items List --}}
        <div id="education-container" class="space-y-3">
            @forelse ($items as $e)
                <div class="edu-row flex flex-col justify-between gap-4 rounded-2xl border border-white/5 bg-[#17171d] p-5 transition hover:border-white/10 sm:flex-row sm:items-center"
                    data-title="{{ strtolower($e->school) }}"
                    data-major="{{ strtolower($e->major) }}"
                    data-desc="{{ strtolower($e->description ?? '') }}">
                    {{-- Left info --}}
                    <div class="min-w-0">
                        <div class="flex items-center gap-2 font-mono text-xs">
                            <span class="size-1.5 rounded-full bg-coral"></span>
                            <span class="text-white/60">{{ $e->period ?: '2022 — Sekarang' }}</span>
                            <span class="text-white/20">•</span>
                            <span class="font-medium text-coral">Aktif</span>
                        </div>

                        <h3 class="mt-1.5 text-base sm:text-lg font-bold text-white tracking-tight">
                            {{ $e->school }}
                        </h3>

                        <p class="mt-0.5 text-xs font-semibold text-coral">
                            {{ $e->major }}
                        </p>

                        @if ($e->description)
                            <p class="mt-2 text-xs leading-relaxed text-white/60">
                                {{ $e->description }}
                            </p>
                        @endif
                    </div>

                    {{-- Right controls --}}
                    <div class="flex shrink-0 items-center gap-4 self-end sm:self-center">
                        <div class="flex items-center gap-2.5">
                            <button type="button" class="relative inline-flex h-5 w-9 shrink-0 cursor-pointer rounded-full border-2 border-transparent bg-coral transition-colors" role="switch" aria-checked="true">
                                <span class="translate-x-4 pointer-events-none inline-block size-4 transform rounded-full bg-white shadow transition"></span>
                            </button>
                            <span class="font-mono text-xs text-white/70">Tampil di Beranda</span>
                        </div>

                        <div class="flex items-center gap-1.5">
                            <a href="{{ route('admin.education.edit', $e) }}" title="Edit Pendidikan"
                                class="flex size-8 items-center justify-center rounded-lg border border-white/10 text-white/50 transition hover:bg-white/5 hover:text-white">
                                <x-icon name="pencil" class="size-3.5" />
                            </a>

                            <form method="POST" action="{{ route('admin.education.destroy', $e) }}"
                                onsubmit="return confirm('Hapus data pendidikan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Hapus Pendidikan"
                                    class="flex size-8 items-center justify-center rounded-lg border border-white/10 text-white/50 transition hover:bg-red-500/10 hover:text-red-400">
                                    <x-icon name="trash" class="size-3.5" />
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-dashed border-white/10 bg-[#17171d] p-12 text-center">
                    <x-icon name="graduation-cap" class="mx-auto mb-3 size-10 text-white/20" />
                    <p class="text-sm text-white/60">Belum ada riwayat pendidikan terdaftar.</p>
                    <a href="{{ route('admin.education.create') }}" class="mt-3 inline-block text-xs font-semibold text-coral hover:underline">
                        + Tambah Pendidikan Pertama &rarr;
                    </a>
                </div>
            @endforelse

            <div id="no-edu-match" class="hidden rounded-2xl border border-dashed border-white/10 bg-[#17171d] p-8 text-center">
                <p class="text-xs text-white/40">Tidak ada data pendidikan yang sesuai dengan pencarian.</p>
            </div>
        </div>

        {{-- Live Public Home Preview Section --}}
        <div class="space-y-4 pt-4 border-t border-white/5">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 font-mono text-xs tracking-wider text-white/60 uppercase">
                    <x-icon name="eye" class="size-3.5 text-white/50" />
                    <span>Pratinjau Beranda Publik</span>
                </div>
                <span class="font-mono text-xs text-white/40">Tersinkronisasi</span>
            </div>

            @php
                $defaultExperiences = config('portfolio.experiences', []);
                $rawExp = setting('experience.items', $defaultExperiences);
                $previewExperiences = is_string($rawExp) ? (json_decode($rawExp, true) ?? $defaultExperiences) : $rawExp;
                if (! is_array($previewExperiences)) {
                    $previewExperiences = $defaultExperiences;
                }
            @endphp

            <div class="overflow-hidden rounded-2xl border border-white/5 bg-[#121217] p-6 sm:p-8 shadow-xl">
                <div class="space-y-6">
                    <div>
                        <div class="flex items-center gap-2 font-mono text-xs text-white/50 uppercase tracking-widest">
                            <span class="size-1.5 rounded-full bg-coral"></span>
                            <span>Education &amp; Experience</span>
                        </div>
                        <h2 class="mt-2 text-2xl sm:text-3xl font-bold tracking-tight text-white">
                            Learning &amp; journey that shapes how I build.
                        </h2>
                    </div>

                    <div class="grid gap-8 pt-4 lg:grid-cols-2 lg:gap-10">
                        {{-- Education Column --}}
                        <div>
                            <div class="mb-4 flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-white/70">
                                <x-icon name="graduation-cap" class="size-4 text-coral" />
                                <span>Pendidikan Formal</span>
                            </div>
                            <div class="space-y-6">
                                @forelse ($items as $edu)
                                    <div class="relative border-l border-white/10 pb-6 pl-6 last:pb-0">
                                        <span class="absolute -left-[5px] top-1.5 size-2 rounded-full bg-coral"></span>
                                        <p class="font-mono text-xs text-white/50">{{ $edu->period ?: '2022 — Sekarang' }}</p>
                                        <h4 class="mt-1 text-base font-bold text-white">{{ $edu->school }}</h4>
                                        <p class="mt-0.5 text-xs font-semibold text-coral">{{ $edu->major }}</p>
                                        @if ($edu->description)
                                            <p class="mt-2 text-xs leading-relaxed text-white/60">{{ $edu->description }}</p>
                                        @endif
                                    </div>
                                @empty
                                    <p class="text-xs text-white/40">Data pendidikan belum ditambahkan.</p>
                                @endforelse
                            </div>
                        </div>

                        {{-- Experience Column --}}
                        <div>
                            <div class="mb-4 flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-white/70">
                                <x-icon name="briefcase" class="size-4 text-coral" />
                                <span>Pengalaman &amp; Proyek Lapangan</span>
                            </div>
                            <div class="space-y-6">
                                @forelse ($previewExperiences as $exp)
                                    <div class="relative border-l border-white/10 pb-6 pl-6 last:pb-0">
                                        <span class="absolute -left-[5px] top-1.5 size-2 rounded-full bg-coral"></span>
                                        @if (!empty($exp['period']))
                                            <p class="font-mono text-xs text-white/50">{{ $exp['period'] }}</p>
                                        @endif
                                        <h4 class="mt-1 text-base font-bold text-white">{{ $exp['role'] ?? 'Peran' }}</h4>
                                        <p class="mt-0.5 text-xs font-semibold text-coral">{{ $exp['company'] ?? 'Instansi / Klien' }}</p>
                                        @if (!empty($exp['description']))
                                            <p class="mt-2 text-xs leading-relaxed text-white/60">{{ $exp['description'] }}</p>
                                        @endif
                                        @if (!empty($exp['skills']) && is_array($exp['skills']))
                                            <div class="mt-2.5 flex flex-wrap gap-1.5">
                                                @foreach ($exp['skills'] as $skill)
                                                    <span class="rounded-full border border-white/10 bg-white/5 px-2 py-0.5 font-mono text-[10px] text-white/60">{{ $skill }}</span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @empty
                                    <p class="text-xs text-white/40">Data pengalaman belum ditambahkan.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Client-side Search Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('education-search');
            const rows = document.querySelectorAll('.edu-row');
            const noMatch = document.getElementById('no-edu-match');

            if (searchInput) {
                searchInput.addEventListener('input', (e) => {
                    const q = e.target.value.toLowerCase().trim();
                    let visibleCount = 0;

                    rows.forEach(row => {
                        const title = row.dataset.title || '';
                        const major = row.dataset.major || '';
                        const desc = row.dataset.desc || '';

                        const match = !q || title.includes(q) || major.includes(q) || desc.includes(q);
                        row.style.display = match ? '' : 'none';
                        if (match) visibleCount++;
                    });

                    if (noMatch) {
                        noMatch.classList.toggle('hidden', visibleCount > 0);
                    }
                });
            }
        });
    </script>
@endsection
