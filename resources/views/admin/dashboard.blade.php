@extends('admin.layout')
@section('title', 'Dashboard')

@section('content')
    <div class="space-y-6">
        {{-- Header Section --}}
        <div class="flex flex-col justify-between gap-4 lg:flex-row lg:items-center">
            <div>
                <div class="flex items-center gap-2 font-mono text-[11px] tracking-wider text-white/50 uppercase">
                    <span class="size-2 rounded-full bg-coral"></span>
                    <span>Console Admin</span>
                    <span class="text-white/20">•</span>
                    <span>Portfolio Hub</span>
                </div>
                <h1 class="mt-2 text-2xl font-bold tracking-tight text-white sm:text-3xl">Dashboard</h1>
                <p class="mt-1 text-xs text-white/50 sm:text-sm">
                    Selamat datang kembali, {{ Auth::user()->name ?? 'Rayhan' }}. Berikut ikhtisar status data dan performa portofolio Anda.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('home') }}" target="_blank" rel="noopener"
                    class="flex items-center gap-2 rounded-xl border border-white/10 bg-white/[0.03] px-4 py-2.5 text-xs font-medium text-white transition hover:bg-white/[0.08]">
                    <x-icon name="external-link" class="size-3.5 text-white/60" />
                    <span>Lihat Situs</span>
                </a>
                <a href="{{ route('admin.projects.create') }}"
                    class="flex items-center gap-2 rounded-xl bg-gradient-to-r from-coral to-[#ff6b5d] px-4 py-2.5 text-xs font-semibold text-white shadow-lg shadow-coral/25 transition hover:brightness-110">
                    <x-icon name="plus" class="size-3.5" />
                    <span>Tambah Proyek Baru</span>
                </a>
            </div>
        </div>

        {{-- Status Info Banner --}}
        <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-white/5 bg-white/[0.02] px-5 py-3 text-xs text-white/70">
            <div class="flex flex-wrap items-center gap-3">
                <span class="inline-flex items-center gap-2 rounded-full border border-emerald-500/20 bg-emerald-500/10 px-3 py-1 text-[11px] font-medium text-emerald-400">
                    <span class="size-1.5 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.8)]"></span>
                    Production Live
                </span>
                <span class="text-white/60">rayhan-portfolio.dev terhubung ke GitHub Main</span>
            </div>
            <div class="flex flex-wrap items-center gap-4 text-white/40">
                <span class="flex items-center gap-1.5">
                    <x-icon name="clock" class="size-3.5" />
                    Sinkronisasi data terkini
                </span>
                <span class="flex items-center gap-1.5 font-mono text-[11px]">
                    <x-icon name="code" class="size-3.5" />
                    v1.4.2-stable
                </span>
            </div>
        </div>

        {{-- 4 Stat Metric Cards --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            {{-- Proyek Card --}}
            <div class="rounded-2xl border border-white/5 bg-[#17171d] p-5 shadow-sm transition hover:border-white/10">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-3xl font-bold tracking-tight text-white">{{ $projectsCount }}</p>
                        <p class="mt-1 text-sm font-medium text-white/80 whitespace-nowrap">Proyek</p>
                    </div>
                    <div class="flex size-10 items-center justify-center rounded-xl border border-white/5 bg-white/[0.03] text-white/60">
                        <x-icon name="folder" class="size-4" />
                    </div>
                </div>
                <div class="mt-4 flex items-center justify-between border-t border-white/5 pt-3 text-[11px]">
                    <span class="inline-flex items-center gap-1.5 font-mono text-white/60">
                        <span class="size-1.5 rounded-full bg-coral"></span>
                        {{ $publishedProjectsCount }} Ditampilkan
                    </span>
                    <span class="text-white/40">Laravel &amp; UI</span>
                </div>
            </div>

            {{-- Pendidikan Card --}}
            <div class="rounded-2xl border border-white/5 bg-[#17171d] p-5 shadow-sm transition hover:border-white/10">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-3xl font-bold tracking-tight text-white">{{ $educationCount }}</p>
                        <p class="mt-1 text-sm font-medium text-white/80 whitespace-nowrap">Pendidikan</p>
                    </div>
                    <div class="flex size-10 items-center justify-center rounded-xl border border-white/5 bg-white/[0.03] text-white/60">
                        <x-icon name="graduation-cap" class="size-4" />
                    </div>
                </div>
                <div class="mt-4 flex items-center justify-between border-t border-white/5 pt-3 text-[11px]">
                    <span class="truncate font-mono text-white/60 max-w-[120px]">{{ $primaryEducation->major ?? 'SMK (PPLG)' }}</span>
                    <span class="font-medium text-emerald-400">Aktif</span>
                </div>
            </div>

            {{-- Sertifikat Card --}}
            <div class="rounded-2xl border border-white/5 bg-[#17171d] p-5 shadow-sm transition hover:border-white/10">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-3xl font-bold tracking-tight text-white">{{ $certificatesCount }}</p>
                        <p class="mt-1 text-sm font-medium text-white/80 whitespace-nowrap">Sertifikat</p>
                    </div>
                    <div class="flex size-10 items-center justify-center rounded-xl border border-white/5 bg-white/[0.03] text-white/60">
                        <x-icon name="badge-check" class="size-4" />
                    </div>
                </div>
                <div class="mt-4 flex items-center justify-between border-t border-white/5 pt-3 text-[11px]">
                    <a href="{{ route('admin.certificates.create') }}" class="text-white/60 transition hover:text-white">
                        + Unggah Bukti
                    </a>
                    <span class="text-white/40">{{ $certificatesCount }} tersimpan</span>
                </div>
            </div>

            {{-- Pesan Masuk Baru Card --}}
            <div class="rounded-2xl border border-white/5 bg-[#17171d] p-5 shadow-sm transition hover:border-white/10">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-3xl font-bold tracking-tight text-white">{{ $unreadMessagesCount }}</p>
                        <p class="mt-1 text-sm font-medium text-white/80 whitespace-nowrap">Pesan Masuk Baru</p>
                    </div>
                    <div class="flex size-10 items-center justify-center rounded-xl border border-white/5 bg-white/[0.03] text-white/60">
                        <x-icon name="mail" class="size-4" />
                    </div>
                </div>
                <div class="mt-4 flex items-center justify-between border-t border-white/5 pt-3 text-[11px]">
                    @if ($unreadMessagesCount > 0)
                        <span class="rounded-md bg-coral/15 px-2 py-0.5 text-[10px] font-medium text-coral">Perlu Respons</span>
                    @else
                        <span class="text-[10px] text-white/40">Semua Terbaca</span>
                    @endif
                    <span class="text-white/40">Formulir Kontak</span>
                </div>
            </div>
        </div>

        {{-- 2-Column Main Section --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
            {{-- Left Column (Projects & Education) --}}
            <div class="space-y-6 lg:col-span-7 xl:col-span-8">
                {{-- Proyek Portofolio Terdaftar --}}
                <div class="rounded-2xl border border-white/5 bg-[#17171d] p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <span class="h-4 w-1 rounded-full bg-coral"></span>
                            <h2 class="text-sm font-bold tracking-tight text-white">Proyek Portofolio Terdaftar</h2>
                            <span class="rounded-full border border-white/10 bg-white/5 px-2 py-0.5 font-mono text-[10px] text-white/60">{{ $projectsCount }} Item</span>
                        </div>
                        <a href="{{ route('admin.projects.index') }}" class="text-xs font-medium text-white/50 transition hover:text-white">
                            Kelola Semua &gt;
                        </a>
                    </div>

                    <div class="mt-4 space-y-3">
                        @forelse ($latestProjects as $project)
                            <div class="flex flex-col gap-3 rounded-xl border border-white/5 bg-white/[0.02] p-3.5 transition hover:border-white/10 hover:bg-white/[0.04] sm:flex-row sm:items-center sm:justify-between">
                                <div class="flex items-start gap-3.5 min-w-0">
                                    <div class="flex size-12 shrink-0 items-center justify-center overflow-hidden rounded-xl border border-white/10 bg-white/5">
                                        @if ($project->image)
                                            <img src="{{ $project->image_url }}" alt="{{ $project->title }}" class="size-full object-cover">
                                        @else
                                            <x-icon name="folder" class="size-5 text-white/40" />
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <h3 class="truncate text-xs font-semibold text-white">{{ $project->title }}</h3>
                                            <span class="rounded border border-white/10 bg-white/5 px-1.5 py-0.5 text-[9px] text-white/60">
                                                {{ $project->category_label }}
                                            </span>
                                        </div>
                                        <p class="mt-0.5 truncate text-[11px] text-white/40">{{ $project->description }}</p>
                                        <div class="mt-2 flex flex-wrap gap-1">
                                            @foreach (array_slice($project->technologies ?? [], 0, 4) as $tech)
                                                <span class="rounded border border-white/5 bg-white/[0.03] px-1.5 py-0.5 font-mono text-[9px] text-white/60">
                                                    {{ $tech }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div class="flex shrink-0 items-center gap-2 self-end sm:self-center">
                                    @if ($project->published)
                                        <span class="rounded-md border border-white/10 bg-white/5 px-2 py-1 text-[10px] text-white/60">Publik</span>
                                    @endif
                                    <a href="{{ route('admin.projects.edit', $project) }}" title="Edit Proyek"
                                        class="flex size-7 items-center justify-center rounded-lg border border-white/10 bg-white/5 text-white/60 transition hover:border-white/20 hover:text-white">
                                        <x-icon name="pencil" class="size-3" />
                                    </a>
                                    <a href="{{ route('home') }}#proyek" target="_blank" rel="noopener" title="Lihat di Beranda"
                                        class="flex size-7 items-center justify-center rounded-lg border border-white/10 bg-white/5 text-white/60 transition hover:border-white/20 hover:text-white">
                                        <x-icon name="eye" class="size-3" />
                                    </a>
                                </div>
                            </div>
                        @empty
                            <p class="py-8 text-center text-xs text-white/40">Belum ada proyek terdaftar.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Data Pendidikan & Kompetensi --}}
                <div class="rounded-2xl border border-white/5 bg-[#17171d] p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <x-icon name="graduation-cap" class="size-4 text-coral" />
                            <h2 class="text-sm font-bold tracking-tight text-white">Data Pendidikan &amp; Kompetensi</h2>
                        </div>
                        <a href="{{ route('admin.education.index') }}" class="text-xs font-medium text-white/50 transition hover:text-white">
                            Edit Data &rarr;
                        </a>
                    </div>

                    <p class="mt-3 text-xs leading-relaxed text-white/60">
                        Jurusan {{ $primaryEducation->major ?? 'Pengembangan Perangkat Lunak dan Gim (PPLG)' }}. {{ $primaryEducation->description ?? 'Fokus pembelajaran pada pemrograman web, basis data MySQL, arsitektur MVC Laravel, dan perancangan antarmuka Figma.' }}
                    </p>

                    <div class="mt-4 flex flex-wrap gap-2">
                        @php
                            $skills = ['HTML / CSS / JS', 'PHP & Laravel', 'Tailwind CSS', 'MySQL Database', 'Figma UI/UX'];
                        @endphp
                        @foreach ($skills as $skill)
                            <span class="inline-flex items-center gap-1.5 rounded-full border border-white/10 bg-white/5 px-3 py-1 text-[11px] text-white/80">
                                <span class="size-1.5 rounded-full bg-coral"></span>
                                {{ $skill }}
                            </span>
                        @endforeach
                    </div>

                    <div class="mt-5 flex flex-wrap items-center justify-between gap-2 border-t border-white/5 pt-3 font-mono text-[11px] text-white/40">
                        <span>Status Kurikulum: Aktif {{ $primaryEducation->period ?? '2024/2025' }}</span>
                        <span>{{ $educationCount }} Institusi Terdaftar</span>
                    </div>
                </div>
            </div>

            {{-- Right Column (Messages & Certificate CTA) --}}
            <div class="space-y-6 lg:col-span-5 xl:col-span-4">
                {{-- Pesan Terbaru --}}
                <div class="rounded-2xl border border-white/5 bg-[#17171d] p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <span class="h-4 w-1 rounded-full bg-coral"></span>
                            <h2 class="text-sm font-bold tracking-tight text-white">Pesan Terbaru</h2>
                            @if ($unreadMessagesCount > 0)
                                <span class="rounded-full bg-coral px-2 py-0.5 text-[10px] font-bold text-white">{{ $unreadMessagesCount }} Baru</span>
                            @endif
                        </div>
                        <a href="{{ route('admin.messages.index') }}" class="text-xs font-medium text-white/50 transition hover:text-white">
                            Semua Pesan &gt;
                        </a>
                    </div>

                    <div class="mt-4 space-y-3">
                        @forelse ($latestMessages as $m)
                            <div class="space-y-2 rounded-xl border border-white/5 bg-white/[0.02] p-3.5 transition hover:border-white/10 hover:bg-white/[0.04]">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <div class="flex size-7 shrink-0 items-center justify-center rounded-full border border-white/10 bg-white/5 font-mono text-[10px] font-semibold text-white/80">
                                            {{ strtoupper(substr($m->name, 0, 2)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="truncate text-xs font-semibold text-white">{{ $m->name }}</p>
                                            <p class="truncate text-[10px] text-white/40">{{ $m->email }}</p>
                                        </div>
                                    </div>
                                    <span class="shrink-0 text-[10px] text-white/40">
                                        {{ $m->created_at ? $m->created_at->diffForHumans() : 'Baru saja' }}
                                    </span>
                                </div>

                                <p class="text-xs font-semibold text-white/90">{{ $m->subject }}</p>
                                <p class="line-clamp-2 text-[11px] leading-relaxed text-white/50">{{ $m->message }}</p>

                                <div class="flex items-center justify-between border-t border-white/5 pt-2 text-[10px]">
                                    <span class="text-white/40">Via Form Kontak</span>
                                    <div class="flex items-center gap-1.5">
                                        <a href="{{ route('admin.messages.show', $m) }}"
                                            class="flex items-center gap-1 rounded-md border border-white/10 bg-white/5 px-2 py-1 text-white/70 transition hover:bg-white/10 hover:text-white">
                                            <x-icon name="check" class="size-2.5" />
                                            <span>Tandai</span>
                                        </a>
                                        <a href="mailto:{{ $m->email }}?subject=Re: {{ rawurlencode($m->subject) }}"
                                            class="flex items-center gap-1 rounded-md bg-coral/20 px-2 py-1 font-medium text-coral transition hover:bg-coral hover:text-white">
                                            <x-icon name="send" class="size-2.5" />
                                            <span>Balas</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="py-8 text-center text-xs text-white/40">Belum ada pesan masuk.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Lengkapi Sertifikat CTA Card --}}
                <div class="space-y-3 rounded-2xl border border-white/5 bg-[#17171d] p-5 shadow-sm">
                    <div class="flex size-9 items-center justify-center rounded-xl border border-coral/20 bg-coral/10 text-coral">
                        <x-icon name="badge-check" class="size-4" />
                    </div>
                    <h3 class="text-sm font-bold text-white">Lengkapi Sertifikat</h3>
                    <p class="text-xs leading-relaxed text-white/50">
                        Tambahkan sertifikat pelatihan atau kompetensi ke tabel publik portofolio untuk meningkatkan kredibilitas di mata rekruter.
                    </p>
                    <a href="{{ route('admin.certificates.index') }}"
                        class="flex items-center justify-center gap-2 rounded-xl border border-white/10 bg-white/[0.04] px-4 py-2.5 text-xs font-medium text-white transition hover:bg-white/[0.08]">
                        <x-icon name="plus" class="size-3.5 text-white/60" />
                        <span>Buka Modul Sertifikat</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
