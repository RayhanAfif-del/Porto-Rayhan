@extends('admin.layout')

@section('title', $item->exists ? 'Edit Riwayat Pendidikan' : 'Tambah Riwayat Pendidikan')

@section('content')
    <div class="space-y-6">
        {{-- Top Bar / Breadcrumb & Action Buttons --}}
        <div class="flex flex-col justify-between gap-4 lg:flex-row lg:items-center">
            <div>
                <div class="flex items-center gap-2 font-mono text-[11px] tracking-wider text-white/50 uppercase">
                    <a href="{{ route('admin.education.index') }}" class="hover:text-white transition">Pendidikan</a>
                    <span class="text-white/20">/</span>
                    <span class="text-white/80">{{ $item->exists ? 'Edit Riwayat' : 'Tambah Riwayat' }}</span>
                    <span class="text-white/20">•</span>
                    <span class="rounded bg-white/5 border border-white/10 px-1.5 py-0.5 text-[9px] font-mono text-white/60">
                        ID #EDU-{{ str_pad($item->id ?? 1, 2, '0', STR_PAD_LEFT) }}
                    </span>
                </div>
                <h1 class="mt-2 text-2xl font-bold tracking-tight text-white sm:text-3xl">
                    {{ $item->exists ? 'Edit Riwayat Pendidikan' : 'Tambah Riwayat Pendidikan' }}
                </h1>
                <p class="mt-1 text-xs text-white/50 sm:text-sm">
                    Kelola riwayat institusi pendidikan, sekolah, dan program studi yang ditampilkan pada portofolio publik.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.education.index') }}"
                    class="rounded-xl border border-white/10 bg-white/[0.04] px-4 py-2.5 text-xs font-medium text-white/70 transition hover:bg-white/[0.08] hover:text-white">
                    Batal
                </a>
                <button type="button" onclick="document.getElementById('education-form').submit()"
                    class="flex items-center gap-1.5 rounded-xl bg-coral px-5 py-2.5 text-xs font-semibold text-white shadow-lg shadow-coral/25 transition hover:bg-coral-dark">
                    <span>{{ $item->exists ? 'Simpan Perubahan' : 'Simpan Pendidikan' }}</span>
                    <x-icon name="arrow-up-right" class="size-3.5" />
                </button>
            </div>
        </div>

        {{-- Main Form Grid --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
            {{-- Left Column (8 cols): Input Fields --}}
            <div class="space-y-6 lg:col-span-8">
                <form id="education-form" method="POST"
                    action="{{ $item->exists ? route('admin.education.update', $item) : route('admin.education.store') }}"
                    class="rounded-2xl border border-white/5 bg-[#17171d] p-6 shadow-sm space-y-6">
                    @csrf
                    @if ($item->exists)
                        @method('PUT')
                    @endif

                    {{-- Card Header --}}
                    <div class="flex items-center justify-between border-b border-white/5 pb-4">
                        <div class="flex items-center gap-2 text-white">
                            <x-icon name="pen" class="size-4 text-coral" />
                            <h2 class="text-sm font-bold tracking-tight">Informasi Pendidikan</h2>
                        </div>
                        <span class="font-mono text-[10px] tracking-widest text-white/40 uppercase">Formulir Ramping</span>
                    </div>

                    {{-- Nama Sekolah --}}
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="school" class="font-mono text-[11px] font-semibold tracking-wider text-white/70 uppercase">
                                Nama Sekolah / Institusi <span class="text-coral">*</span>
                            </label>
                            <span class="font-mono text-[10px] text-white/40">Wajib diisi</span>
                        </div>
                        <div class="relative">
                            <x-icon name="graduation-cap" class="pointer-events-none absolute left-3.5 top-1/2 size-4 -translate-y-1/2 text-white/35" />
                            <input type="text" id="school" name="school" value="{{ old('school', $item->school) }}" required
                                placeholder="SMK Negeri 1 Cimahi"
                                class="w-full rounded-xl border border-white/10 bg-white/[0.03] py-2.5 pl-10 pr-4 text-xs text-white placeholder:text-white/30 focus:border-coral focus:outline-none transition">
                        </div>
                        @error('school')
                            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Program Studi --}}
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="major" class="font-mono text-[11px] font-semibold tracking-wider text-white/70 uppercase">
                                Program Studi (Opsional)
                            </label>
                            <span class="font-mono text-[10px] text-white/40">Bisa dikosongkan</span>
                        </div>
                        <div class="relative">
                            <x-icon name="pen" class="pointer-events-none absolute left-3.5 top-1/2 size-4 -translate-y-1/2 text-white/35" />
                            <input type="text" id="major" name="major" value="{{ old('major', $item->major) }}" required
                                placeholder="Pengembangan Perangkat Lunak dan Gim (PPLG)"
                                class="w-full rounded-xl border border-white/10 bg-white/[0.03] py-2.5 pl-10 pr-4 text-xs text-white placeholder:text-white/30 focus:border-coral focus:outline-none transition">
                        </div>
                        @error('major')
                            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Periode & Urutan Tampil (2 Columns) --}}
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="period" class="block font-mono text-[11px] font-semibold tracking-wider text-white/70 uppercase mb-2">
                                Periode
                            </label>
                            <div class="relative">
                                <x-icon name="clock" class="pointer-events-none absolute left-3.5 top-1/2 size-4 -translate-y-1/2 text-white/35" />
                                <input type="text" id="period" name="period" value="{{ old('period', $item->period ?? '2022 — Sekarang') }}"
                                    placeholder="2022 — Sekarang"
                                    class="w-full rounded-xl border border-white/10 bg-white/[0.03] py-2.5 pl-10 pr-4 text-xs text-white placeholder:text-white/30 focus:border-coral focus:outline-none transition">
                            </div>
                            @error('period')
                                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="sort_order" class="block font-mono text-[11px] font-semibold tracking-wider text-white/70 uppercase mb-2">
                                Urutan Tampil
                            </label>
                            <div class="relative">
                                <x-icon name="dashboard" class="pointer-events-none absolute left-3.5 top-1/2 size-3.5 -translate-y-1/2 text-white/35" />
                                <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $item->sort_order ?? 0) }}" min="0" max="999"
                                    class="w-full rounded-xl border border-white/10 bg-white/[0.03] py-2.5 pl-10 pr-4 text-xs text-white placeholder:text-white/30 focus:border-coral focus:outline-none transition">
                            </div>
                            <p class="mt-1.5 font-mono text-[10px] text-white/40">Prioritas di portofolio (0 = paling atas).</p>
                            @error('sort_order')
                                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Deskripsi Pembelajaran --}}
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="description" class="font-mono text-[11px] font-semibold tracking-wider text-white/70 uppercase">
                                Deskripsi Pembelajaran
                            </label>
                            <span class="font-mono text-[10px] text-white/40">Ringkasan portofolio</span>
                        </div>
                        <textarea id="description" name="description" rows="4"
                            placeholder="Mempelajari pemrograman web, basis data, dan perancangan antarmuka pengguna."
                            class="w-full rounded-xl border border-white/10 bg-white/[0.03] p-4 text-xs leading-relaxed text-white placeholder:text-white/30 focus:border-coral focus:outline-none transition">{{ old('description', $item->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </form>
            </div>

            {{-- Right Column (4 cols): Live Preview & Danger Zone --}}
            <div class="space-y-6 lg:col-span-4">
                {{-- Live Preview Card --}}
                <div class="rounded-2xl border border-white/5 bg-[#17171d] p-5 shadow-sm space-y-4">
                    <div class="flex items-center justify-between border-b border-white/5 pb-3">
                        <div class="flex items-center gap-2 font-mono text-[11px] text-white/70">
                            <span class="size-1.5 rounded-full bg-coral animate-pulse"></span>
                            <span class="font-bold tracking-wider uppercase">Pratinjau Tampilan</span>
                        </div>
                        <span class="font-mono text-[10px] text-white/40">Live Preview</span>
                    </div>

                    <div class="rounded-2xl border border-white/5 bg-[#121217] p-5 shadow-inner space-y-3">
                        <div class="flex items-center justify-between">
                            <span id="preview-period-badge" class="inline-flex items-center gap-1.5 rounded-full border border-white/10 bg-white/5 px-2.5 py-1 font-mono text-[10px] text-white/80">
                                <span class="size-1.5 rounded-full bg-coral"></span>
                                <span id="preview-period">{{ $item->period ?: '2022 — Sekarang' }}</span>
                            </span>
                            <span id="preview-order" class="font-mono text-[10px] text-white/40">Urutan #{{ $item->sort_order ?? 0 }}</span>
                        </div>

                        <div class="flex items-start gap-3 pt-1">
                            <div class="flex size-9 shrink-0 items-center justify-center rounded-xl border border-white/10 bg-white/5 text-coral">
                                <x-icon name="graduation-cap" class="size-4" />
                            </div>
                            <div class="min-w-0">
                                <h3 id="preview-school" class="text-sm font-bold text-white truncate">
                                    {{ $item->school ?: 'SMK Negeri 1 Cimahi' }}
                                </h3>
                                <p id="preview-major" class="text-[11px] font-medium text-coral truncate mt-0.5">
                                    {{ $item->major ?: 'Pengembangan Perangkat Lunak dan Gim (PPLG)' }}
                                </p>
                            </div>
                        </div>

                        <p id="preview-desc" class="text-xs text-white/60 leading-relaxed pt-1">
                            {{ $item->description ?: 'Mempelajari pemrograman web, basis data, dan perancangan antarmuka pengguna.' }}
                        </p>

                        <div class="flex items-center justify-between border-t border-white/5 pt-3 font-mono text-[10px]">
                            <span class="text-white/40">Section: "Education &amp; Growth"</span>
                            <span class="text-coral font-medium flex items-center gap-1">
                                <span>Kartu Aktif</span>
                                <x-icon name="arrow-up-right" class="size-2.5" />
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Hapus Riwayat Card --}}
                @if ($item->exists)
                    <div class="rounded-2xl border border-white/5 bg-[#17171d] p-5 flex items-center justify-between gap-3 shadow-sm">
                        <div>
                            <p class="text-xs font-semibold text-white">Hapus Riwayat?</p>
                            <p class="text-[10px] text-white/40">Pindahkan ke arsip sampah</p>
                        </div>
                        <form method="POST" action="{{ route('admin.education.destroy', $item) }}"
                            onsubmit="return confirm('Yakin ingin menghapus riwayat pendidikan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="rounded-xl border border-red-500/20 bg-red-500/15 px-3.5 py-2 text-xs font-semibold text-red-400 transition hover:bg-red-500 hover:text-white">
                                Hapus Entri
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        {{-- Bottom Action Bar --}}
        <div class="rounded-2xl border border-white/5 bg-[#17171d] p-4 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-sm">
            <div class="flex items-center gap-2 font-mono text-xs text-white/70">
                <span class="size-1.5 rounded-full bg-coral"></span>
                <span>Data siap diperbarui ke portofolio</span>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.education.index') }}"
                    class="rounded-xl border border-white/10 bg-white/[0.04] px-4 py-2 text-xs font-medium text-white/70 transition hover:bg-white/[0.08] hover:text-white">
                    Batal
                </a>
                <button type="button" onclick="document.getElementById('education-form').submit()"
                    class="flex items-center gap-1.5 rounded-xl bg-coral px-5 py-2 text-xs font-semibold text-white shadow-lg shadow-coral/25 transition hover:bg-coral-dark">
                    <span>{{ $item->exists ? 'Simpan Perubahan' : 'Simpan Pendidikan' }}</span>
                    <x-icon name="arrow-up-right" class="size-3.5" />
                </button>
            </div>
        </div>
    </div>

    {{-- Interactive Live Preview Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const schoolInput = document.getElementById('school');
            const majorInput = document.getElementById('major');
            const periodInput = document.getElementById('period');
            const descInput = document.getElementById('description');
            const sortOrderInput = document.getElementById('sort_order');

            const previewSchool = document.getElementById('preview-school');
            const previewMajor = document.getElementById('preview-major');
            const previewPeriod = document.getElementById('preview-period');
            const previewDesc = document.getElementById('preview-desc');
            const previewOrder = document.getElementById('preview-order');

            if (schoolInput) {
                schoolInput.addEventListener('input', (e) => {
                    previewSchool.textContent = e.target.value.trim() || 'Nama Sekolah / Institusi';
                });
            }

            if (majorInput) {
                majorInput.addEventListener('input', (e) => {
                    previewMajor.textContent = e.target.value.trim() || 'Program Studi';
                });
            }

            if (periodInput) {
                periodInput.addEventListener('input', (e) => {
                    previewPeriod.textContent = e.target.value.trim() || '2022 — Sekarang';
                });
            }

            if (descInput) {
                descInput.addEventListener('input', (e) => {
                    previewDesc.textContent = e.target.value.trim() || 'Mempelajari pemrograman web, basis data, dan perancangan antarmuka pengguna.';
                });
            }

            if (sortOrderInput) {
                sortOrderInput.addEventListener('input', (e) => {
                    previewOrder.textContent = `Urutan #${e.target.value || 0}`;
                });
            }
        });
    </script>
@endsection
