@extends('admin.layout')

@section('title', $item->exists ? 'Edit Riwayat Pengalaman' : 'Tambah Riwayat Pengalaman')

@section('content')
    <div class="space-y-6">
        {{-- Top Bar / Breadcrumb & Action Buttons --}}
        <div class="flex flex-col justify-between gap-4 lg:flex-row lg:items-center">
            <div>
                <div class="flex items-center gap-2 font-mono text-[11px] tracking-wider text-white/50 uppercase">
                    <a href="{{ route('admin.experiences.index') }}" class="hover:text-white transition">Pengalaman</a>
                    <span class="text-white/20">/</span>
                    <span class="text-white/80">{{ $item->exists ? 'Edit Pengalaman' : 'Tambah Pengalaman' }}</span>
                    <span class="text-white/20">•</span>
                    <span class="rounded bg-white/5 border border-white/10 px-1.5 py-0.5 text-[9px] font-mono text-white/60">
                        ID #EXP-{{ str_pad($item->id ?? 1, 2, '0', STR_PAD_LEFT) }}
                    </span>
                </div>
                <h1 class="mt-2 text-2xl font-bold tracking-tight text-white sm:text-3xl">
                    {{ $item->exists ? 'Edit Riwayat Pengalaman' : 'Tambah Riwayat Pengalaman' }}
                </h1>
                <p class="mt-1 text-xs text-white/50 sm:text-sm">
                    Kelola riwayat praktik kerja (PKL/Prakerin), magang, dan proyek freelance yang ditampilkan pada portofolio publik.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.experiences.index') }}"
                    class="rounded-xl border border-white/10 bg-white/[0.04] px-4 py-2.5 text-xs font-medium text-white/70 transition hover:bg-white/[0.08] hover:text-white">
                    Batal
                </a>
                <button type="button" onclick="document.getElementById('experience-form').submit()"
                    class="flex items-center gap-1.5 rounded-xl bg-coral px-5 py-2.5 text-xs font-semibold text-white shadow-lg shadow-coral/25 transition hover:bg-coral-dark">
                    <span>{{ $item->exists ? 'Simpan Perubahan' : 'Simpan Pengalaman' }}</span>
                    <x-icon name="arrow-up-right" class="size-3.5" />
                </button>
            </div>
        </div>

        {{-- Main Form Grid --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
            {{-- Left Column (8 cols): Input Fields --}}
            <div class="space-y-6 lg:col-span-8">
                <form id="experience-form" method="POST"
                    action="{{ $item->exists ? route('admin.experiences.update', $item) : route('admin.experiences.store') }}"
                    class="rounded-2xl border border-white/5 bg-[#17171d] p-6 shadow-sm space-y-6">
                    @csrf
                    @if ($item->exists)
                        @method('PUT')
                    @endif

                    {{-- Card Header --}}
                    <div class="flex items-center justify-between border-b border-white/5 pb-4">
                        <div class="flex items-center gap-2 text-white">
                            <x-icon name="briefcase" class="size-4 text-coral" />
                            <h2 class="text-sm font-bold tracking-tight">Detail Pengalaman Kerja / Proyek</h2>
                        </div>
                        <span class="font-mono text-[10px] tracking-widest text-white/40 uppercase">Formulir Pengalaman</span>
                    </div>

                    {{-- Posisi / Peran --}}
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="role" class="font-mono text-[11px] font-semibold tracking-wider text-white/70 uppercase">
                                Peran / Posisi <span class="text-coral">*</span>
                            </label>
                            <span class="font-mono text-[10px] text-white/40">Wajib diisi</span>
                        </div>
                        <div class="relative">
                            <x-icon name="briefcase" class="pointer-events-none absolute left-3.5 top-1/2 size-4 -translate-y-1/2 text-white/35" />
                            <input type="text" id="role" name="role" value="{{ old('role', $item->role) }}" required
                                placeholder="Contoh: Frontend & Web Developer Intern"
                                class="w-full rounded-xl border border-white/10 bg-white/[0.03] py-2.5 pl-10 pr-4 text-xs text-white placeholder:text-white/30 focus:border-coral focus:outline-none transition">
                        </div>
                        @error('role')
                            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nama Perusahaan / Instansi / Klien --}}
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="company" class="font-mono text-[11px] font-semibold tracking-wider text-white/70 uppercase">
                                Instansi / Perusahaan / Klien <span class="text-coral">*</span>
                            </label>
                            <span class="font-mono text-[10px] text-white/40">Wajib diisi</span>
                        </div>
                        <div class="relative">
                            <x-icon name="layers" class="pointer-events-none absolute left-3.5 top-1/2 size-4 -translate-y-1/2 text-white/35" />
                            <input type="text" id="company" name="company" value="{{ old('company', $item->company) }}" required
                                placeholder="Contoh: Digital Agency / Prakerin atau Proyek Independen"
                                class="w-full rounded-xl border border-white/10 bg-white/[0.03] py-2.5 pl-10 pr-4 text-xs text-white placeholder:text-white/30 focus:border-coral focus:outline-none transition">
                        </div>
                        @error('company')
                            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Periode & Urutan Tampil (2 Columns) --}}
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label for="period" class="font-mono text-[11px] font-semibold tracking-wider text-white/70 uppercase">
                                    Periode Waktu
                                </label>
                                <span class="font-mono text-[10px] text-white/40">Opsional</span>
                            </div>
                            <div class="relative">
                                <x-icon name="calendar" class="pointer-events-none absolute left-3.5 top-1/2 size-4 -translate-y-1/2 text-white/35" />
                                <input type="text" id="period" name="period" value="{{ old('period', $item->period) }}"
                                    placeholder="Contoh: 2024 — 2025"
                                    class="w-full rounded-xl border border-white/10 bg-white/[0.03] py-2.5 pl-10 pr-4 text-xs text-white placeholder:text-white/30 focus:border-coral focus:outline-none transition">
                            </div>
                            @error('period')
                                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label for="sort_order" class="font-mono text-[11px] font-semibold tracking-wider text-white/70 uppercase">
                                    Urutan Tampil
                                </label>
                                <span class="font-mono text-[10px] text-white/40">Angka kecil di awal</span>
                            </div>
                            <div class="relative">
                                <x-icon name="list-ordered" class="pointer-events-none absolute left-3.5 top-1/2 size-4 -translate-y-1/2 text-white/35" />
                                <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $item->sort_order ?? 0) }}" min="0" max="9999"
                                    placeholder="0"
                                    class="w-full rounded-xl border border-white/10 bg-white/[0.03] py-2.5 pl-10 pr-4 text-xs text-white placeholder:text-white/30 focus:border-coral focus:outline-none transition">
                            </div>
                            @error('sort_order')
                                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Keahlian & Teknologi Digunakan --}}
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="skills" class="font-mono text-[11px] font-semibold tracking-wider text-white/70 uppercase">
                                Keahlian &amp; Teknologi
                            </label>
                            <span class="font-mono text-[10px] text-white/40">Pisahkan dengan koma (,)</span>
                        </div>
                        <div class="relative">
                            <x-icon name="code" class="pointer-events-none absolute left-3.5 top-1/2 size-4 -translate-y-1/2 text-white/35" />
                            <input type="text" id="skills" name="skills" value="{{ old('skills', $item->skills_string) }}"
                                placeholder="Contoh: Laravel, Tailwind CSS, Figma, PHP"
                                class="w-full rounded-xl border border-white/10 bg-white/[0.03] py-2.5 pl-10 pr-4 text-xs text-white placeholder:text-white/30 focus:border-coral focus:outline-none transition">
                        </div>
                        <p class="mt-1.5 font-mono text-[10px] text-white/40">Teknologi akan tampil sebagai pil label kecil di beranda.</p>
                        @error('skills')
                            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Deskripsi Tanggung Jawab & Kontribusi --}}
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="description" class="font-mono text-[11px] font-semibold tracking-wider text-white/70 uppercase">
                                Deskripsi Tanggung Jawab &amp; Kontribusi
                            </label>
                            <span class="font-mono text-[10px] text-white/40">Maks. 1000 karakter</span>
                        </div>
                        <textarea id="description" name="description" rows="4"
                            placeholder="Tuliskan pengalaman, tugas utama, atau hasil kerja Anda selama berada di peran ini..."
                            class="w-full rounded-xl border border-white/10 bg-white/[0.03] p-4 text-xs text-white placeholder:text-white/30 focus:border-coral focus:outline-none transition leading-relaxed">{{ old('description', $item->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </form>
            </div>

            {{-- Right Column (4 cols): Live Preview & Information --}}
            <div class="space-y-6 lg:col-span-4">
                {{-- Live Mockup Card --}}
                <div class="rounded-2xl border border-white/5 bg-[#17171d] p-5 shadow-sm space-y-4">
                    <div class="flex items-center justify-between border-b border-white/5 pb-3">
                        <div class="flex items-center gap-2">
                            <x-icon name="eye" class="size-4 text-coral" />
                            <h3 class="text-xs font-bold uppercase tracking-wider text-white">Pratinjau Linimasa</h3>
                        </div>
                        <span class="rounded bg-coral/20 px-2 py-0.5 font-mono text-[9px] text-coral">Interaktif</span>
                    </div>

                    <div class="rounded-xl border border-white/5 bg-[#121217] p-5">
                        <div class="relative border-l border-coral/30 pb-2 pl-6">
                            <span class="absolute -left-[5px] top-1.5 size-2 rounded-full bg-coral"></span>
                            <p id="preview-period" class="font-mono text-xs text-coral">
                                {{ $item->period ?: '2024 — Sekarang' }}
                            </p>
                            <h4 id="preview-role" class="mt-1 text-base font-bold text-white tracking-tight">
                                {{ $item->role ?: 'Peran / Posisi Anda' }}
                            </h4>
                            <p id="preview-company" class="mt-0.5 text-xs font-semibold text-white/80">
                                {{ $item->company ?: 'Nama Instansi / Perusahaan' }}
                            </p>
                            <p id="preview-desc" class="mt-2 text-xs leading-relaxed text-white/60">
                                {{ $item->description ?: 'Deskripsi tugas dan tanggung jawab akan langsung ditampilkan di sini.' }}
                            </p>
                            <div id="preview-skills-container" class="mt-3 flex flex-wrap gap-1.5">
                                @if (!empty($item->skills) && is_array($item->skills))
                                    @foreach ($item->skills as $s)
                                        <span class="rounded-full border border-white/10 bg-white/5 px-2 py-0.5 font-mono text-[10px] text-white/70">{{ $s }}</span>
                                    @endforeach
                                @else
                                    <span class="rounded-full border border-white/10 bg-white/5 px-2 py-0.5 font-mono text-[10px] text-white/40">Contoh: Laravel</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Panduan Card --}}
                <div class="rounded-2xl border border-white/5 bg-[#17171d] p-5 shadow-sm space-y-3">
                    <div class="flex items-center gap-2">
                        <x-icon name="info" class="size-4 text-white/50" />
                        <h3 class="text-xs font-bold uppercase tracking-wider text-white">Panduan Pengisian</h3>
                    </div>
                    <ul class="space-y-2 text-xs text-white/60 leading-relaxed list-disc pl-4">
                        <li>Gunakan nama peran yang jelas seperti <em>Frontend Intern</em>, <em>Web Developer</em>, atau <em>UI Designer</em>.</li>
                        <li>Sebutkan nama perusahaan, instansi magang, atau label <em>Proyek Lepas / Freelance</em>.</li>
                        <li>Sertakan keahlian utama yang digunakan agar pengunjung situs dapat melihat relevansi keahlian Anda secara instan.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Interactive Live Preview Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const roleInput = document.getElementById('role');
            const companyInput = document.getElementById('company');
            const periodInput = document.getElementById('period');
            const skillsInput = document.getElementById('skills');
            const descInput = document.getElementById('description');

            const previewRole = document.getElementById('preview-role');
            const previewCompany = document.getElementById('preview-company');
            const previewPeriod = document.getElementById('preview-period');
            const previewDesc = document.getElementById('preview-desc');
            const previewSkills = document.getElementById('preview-skills-container');

            if (roleInput) {
                roleInput.addEventListener('input', (e) => {
                    previewRole.textContent = e.target.value.trim() || 'Peran / Posisi Anda';
                });
            }
            if (companyInput) {
                companyInput.addEventListener('input', (e) => {
                    previewCompany.textContent = e.target.value.trim() || 'Nama Instansi / Perusahaan';
                });
            }
            if (periodInput) {
                periodInput.addEventListener('input', (e) => {
                    previewPeriod.textContent = e.target.value.trim() || '2024 — Sekarang';
                });
            }
            if (descInput) {
                descInput.addEventListener('input', (e) => {
                    previewDesc.textContent = e.target.value.trim() || 'Deskripsi tugas dan tanggung jawab akan langsung ditampilkan di sini.';
                });
            }
            if (skillsInput) {
                skillsInput.addEventListener('input', (e) => {
                    const raw = e.target.value;
                    const items = raw.split(',').map(s => s.trim()).filter(Boolean);
                    previewSkills.innerHTML = '';
                    if (items.length > 0) {
                        items.forEach(skill => {
                            const badge = document.createElement('span');
                            badge.className = 'rounded-full border border-white/10 bg-white/5 px-2 py-0.5 font-mono text-[10px] text-white/70';
                            badge.textContent = skill;
                            previewSkills.appendChild(badge);
                        });
                    } else {
                        const empty = document.createElement('span');
                        empty.className = 'rounded-full border border-white/10 bg-white/5 px-2 py-0.5 font-mono text-[10px] text-white/40';
                        empty.textContent = 'Contoh: Laravel';
                        previewSkills.appendChild(empty);
                    }
                });
            }
        });
    </script>
@endsection
