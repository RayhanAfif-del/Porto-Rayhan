@extends('admin.layout')

@section('title', 'Kelola Pengalaman')

@section('content')
    <div class="space-y-8">
        {{-- Header Section --}}
        <div class="flex flex-col justify-between gap-4 lg:flex-row lg:items-center">
            <div>
                <div class="flex items-center gap-2 font-mono text-[11px] tracking-wider text-white/50 uppercase">
                    <p class="font-mono text-[11px] tracking-[0.2em] text-white/40 uppercase">
                        Portofolio / Pengalaman
                    </p>
                </div>
                <h1 class="mt-2 text-2xl font-bold tracking-tight text-white sm:text-3xl">Kelola Pengalaman</h1>
                <p class="mt-1 text-xs text-white/50 sm:text-sm">
                    Atur riwayat praktik kerja (PKL/Prakerin), magang, dan proyek freelance yang tampil di beranda.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.experiences.create') }}"
                    class="flex items-center gap-2 rounded-xl bg-coral px-4 py-2.5 text-xs font-semibold text-white shadow-lg shadow-coral/25 transition hover:bg-coral-dark">
                    <x-icon name="plus" class="size-3.5" />
                    <span>Tambah Pengalaman</span>
                </a>
            </div>
        </div>

        {{-- Flash Status Message --}}
        @if (session('status'))
            <div class="rounded-2xl border border-coral/20 bg-coral/10 p-4 text-xs font-medium text-coral flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <x-icon name="check" class="size-4" />
                    <span>{{ session('status') }}</span>
                </div>
            </div>
        @endif

        {{-- Search Bar --}}
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div class="relative w-full sm:w-72">
                <x-icon name="search" class="pointer-events-none absolute left-3.5 top-1/2 size-3.5 -translate-y-1/2 text-white/35" />
                <input type="text" id="experience-search" placeholder="Cari posisi, instansi, atau keahlian..."
                    class="w-full rounded-xl border border-white/10 bg-white/[0.03] py-2 pl-9 pr-3 text-xs text-white placeholder:text-white/35 focus:border-white/20 focus:outline-none">
            </div>
        </div>

        {{-- Experience Items List --}}
        <div id="experience-container" class="space-y-3">
            @forelse ($items as $e)
                <div class="exp-row flex flex-col justify-between gap-4 rounded-2xl border border-white/5 bg-[#17171d] p-5 transition hover:border-white/10 sm:flex-row sm:items-center"
                    data-role="{{ strtolower($e->role) }}"
                    data-company="{{ strtolower($e->company) }}"
                    data-skills="{{ strtolower($e->skills_string) }}"
                    data-desc="{{ strtolower($e->description ?? '') }}">
                    {{-- Left info --}}
                    <div class="min-w-0">
                        <div class="flex items-center gap-2 font-mono text-xs">
                            <span class="size-1.5 rounded-full bg-coral"></span>
                            <span class="text-white/60">{{ $e->period ?: '2024 — Sekarang' }}</span>
                            <span class="text-white/20">•</span>
                            <span class="font-medium text-coral">Aktif</span>
                        </div>

                        <h3 class="mt-1.5 text-base sm:text-lg font-bold text-white tracking-tight">
                            {{ $e->role }}
                        </h3>

                        <p class="mt-0.5 text-xs font-semibold text-coral">
                            {{ $e->company }}
                        </p>

                        @if ($e->description)
                            <p class="mt-2 text-xs leading-relaxed text-white/60">
                                {{ $e->description }}
                            </p>
                        @endif

                        @if (!empty($e->skills) && is_array($e->skills))
                            <div class="mt-3 flex flex-wrap gap-1.5">
                                @foreach ($e->skills as $skill)
                                    <span class="rounded-full border border-white/10 bg-white/5 px-2.5 py-0.5 font-mono text-[10px] text-white/70">{{ $skill }}</span>
                                @endforeach
                            </div>
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
                            <a href="{{ route('admin.experiences.edit', $e) }}" title="Edit Pengalaman"
                                class="flex size-8 items-center justify-center rounded-lg border border-white/10 text-white/50 transition hover:bg-white/5 hover:text-white">
                                <x-icon name="pencil" class="size-3.5" />
                            </a>

                            <form method="POST" action="{{ route('admin.experiences.destroy', $e) }}"
                                onsubmit="return confirm('Hapus data pengalaman ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Hapus Pengalaman"
                                    class="flex size-8 items-center justify-center rounded-lg border border-white/10 text-white/50 transition hover:bg-red-500/10 hover:text-red-400">
                                    <x-icon name="trash" class="size-3.5" />
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-dashed border-white/10 bg-[#17171d] p-12 text-center">
                    <x-icon name="briefcase" class="mx-auto mb-3 size-10 text-white/20" />
                    <p class="text-sm text-white/60">Belum ada riwayat pengalaman terdaftar.</p>
                    <a href="{{ route('admin.experiences.create') }}" class="mt-3 inline-block text-xs font-semibold text-coral hover:underline">
                        + Tambah Pengalaman Pertama &rarr;
                    </a>
                </div>
            @endforelse

            <div id="no-exp-match" class="hidden rounded-2xl border border-dashed border-white/10 bg-[#17171d] p-8 text-center">
                <p class="text-xs text-white/40">Tidak ada data pengalaman yang sesuai dengan pencarian.</p>
            </div>
        </div>

        {{-- Live Public Home Preview Section --}}
        <div class="space-y-4 pt-4 border-t border-white/5">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 font-mono text-xs tracking-wider text-white/60 uppercase">
                    <x-icon name="eye" class="size-3.5 text-white/50" />
                    <span>Pratinjau Beranda Publik (Education &amp; Experience)</span>
                </div>
                <span class="font-mono text-xs text-white/40">Tersinkronisasi</span>
            </div>

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
                        {{-- Experience Column (Highlighted) --}}
                        <div class="rounded-xl border border-coral/20 bg-coral/[0.02] p-4 sm:p-5">
                            <div class="mb-4 flex items-center justify-between">
                                <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-coral">
                                    <x-icon name="briefcase" class="size-4 text-coral" />
                                    <span>Experience (Pengalaman Aktif)</span>
                                </div>
                                <span class="rounded bg-coral/20 px-2 py-0.5 font-mono text-[10px] text-coral">Tampil di Kolom Kanan</span>
                            </div>

                            <div class="space-y-6">
                                @forelse ($items as $exp)
                                    <div class="relative border-l border-coral/30 pb-6 pl-6 last:pb-0">
                                        <span class="absolute -left-[5px] top-1.5 size-2 rounded-full bg-coral"></span>
                                        @if (!empty($exp->period))
                                            <p class="font-mono text-xs text-coral">{{ $exp->period }}</p>
                                        @endif
                                        <h4 class="mt-1 text-base font-bold text-white">{{ $exp->role }}</h4>
                                        <p class="mt-0.5 text-xs font-semibold text-white/80">{{ $exp->company }}</p>
                                        @if (!empty($exp->description))
                                            <p class="mt-2 text-xs leading-relaxed text-white/60">{{ $exp->description }}</p>
                                        @endif
                                        @if (!empty($exp->skills) && is_array($exp->skills))
                                            <div class="mt-2.5 flex flex-wrap gap-1.5">
                                                @foreach ($exp->skills as $skill)
                                                    <span class="rounded-full border border-white/10 bg-white/5 px-2 py-0.5 font-mono text-[10px] text-white/70">{{ $skill }}</span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @empty
                                    <p class="text-xs text-white/40">Data pengalaman belum ditambahkan.</p>
                                @endforelse
                            </div>
                        </div>

                        {{-- Quick Info Column --}}
                        <div class="space-y-4">
                            <div class="rounded-xl border border-white/5 bg-[#17171d] p-5">
                                <h4 class="text-xs font-bold uppercase tracking-wider text-white">Tata Letak Beranda</h4>
                                <p class="mt-2 text-xs leading-relaxed text-white/60">
                                    Pada tampilan publik di bagian <strong class="text-white">EDUCATION &amp; EXPERIENCE</strong>, data Pengalaman otomatis tampil di kolom kanan sejajar dengan riwayat Pendidikan di kolom kiri, menghasilkan tampilan 2 kolom yang penuh dan estetis.
                                </p>
                            </div>

                            <div class="rounded-xl border border-white/5 bg-[#17171d] p-5">
                                <h4 class="text-xs font-bold uppercase tracking-wider text-white">Kelola Pendidikan</h4>
                                <p class="mt-2 text-xs leading-relaxed text-white/60">
                                    Ingin mengatur riwayat akademik formal di kolom kiri? Kunjungi menu
                                    <a href="{{ route('admin.education.index') }}" class="text-coral hover:underline font-semibold">Pendidikan &rarr;</a>
                                </p>
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
            const searchInput = document.getElementById('experience-search');
            const rows = document.querySelectorAll('.exp-row');
            const noMatch = document.getElementById('no-exp-match');

            if (searchInput) {
                searchInput.addEventListener('input', (e) => {
                    const q = e.target.value.toLowerCase().trim();
                    let visibleCount = 0;

                    rows.forEach(row => {
                        const role = row.dataset.role || '';
                        const company = row.dataset.company || '';
                        const skills = row.dataset.skills || '';
                        const desc = row.dataset.desc || '';

                        const match = !q || role.includes(q) || company.includes(q) || skills.includes(q) || desc.includes(q);
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
