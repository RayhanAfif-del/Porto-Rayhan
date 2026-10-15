@extends('admin.layout')

@section('title', 'Pengaturan Konten')

@section('content')
    <div class="space-y-6">
        {{-- Top Bar / Breadcrumb & Actions Header --}}
        <div class="flex flex-col justify-between gap-4 lg:flex-row lg:items-center">
            <div>
                <div class="flex items-center gap-2 font-mono text-[11px] tracking-wider text-white/50 uppercase">
                    <span class="size-1.5 rounded-full bg-coral"></span>
                    <span class="text-white/80">PUSAT KONFIGURASI TAMPILAN</span>
                </div>
                <h1 class="mt-2 text-2xl font-bold tracking-tight text-white sm:text-3xl">Pengaturan Konten</h1>
                <p class="mt-1 text-xs text-white/50 sm:text-sm max-w-2xl">
                    Kelola teks utama, narasi identitas, dan elemen visual yang tampil pada halaman web portofolio secara terpusat tanpa menyentuh kode mentah.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" target="_blank" rel="noopener"
                    class="flex items-center gap-2 rounded-xl border border-white/10 bg-[#17171d] px-4 py-2.5 text-xs font-medium text-white/80 transition hover:bg-white/[0.08] hover:text-white">
                    <span>Pratinjau Situs</span>
                    <x-icon name="external-link" class="size-3.5 text-white/60" />
                </a>
                <button type="button" onclick="submitSettingsForm()"
                    class="flex items-center gap-2 rounded-xl bg-coral px-5 py-2.5 text-xs font-semibold text-white shadow-lg shadow-coral/25 transition hover:bg-coral-dark">
                    <x-icon name="check" class="size-3.5" />
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </div>

        {{-- Errors summary --}}
        @if ($errors->any())
            <div class="rounded-2xl border border-red-500/20 bg-red-500/10 p-4 text-xs text-red-400 space-y-1">
                <div class="flex items-center gap-2 font-semibold">
                    <x-icon name="alert" class="size-4" />
                    <span>Ada beberapa kendala validasi pada formulir:</span>
                </div>
                <ul class="list-disc pl-5 space-y-0.5 text-red-300">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="settings-form" method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="active_tab" id="active_tab_input" value="{{ $activeTab }}">

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
                {{-- Left Column (4 cols): Navigation Tabs & Live Card Preview --}}
                <div class="space-y-6 lg:col-span-4 xl:col-span-3">
                    {{-- Navigasi Modul Card --}}
                    <div class="rounded-2xl border border-white/5 bg-[#17171d] p-4 shadow-sm">
                        <div class="flex items-center justify-between pb-3 px-1 border-b border-white/5 mb-3">
                            <span class="font-mono text-[11px] font-semibold tracking-wider text-white/60 uppercase">
                                NAVIGASI MODUL
                            </span>
                        </div>

                        @php
                            $galRaw = setting('images.gallery', []);
                            $galArr = is_string($galRaw) ? json_decode($galRaw, true) : $galRaw;
                            $galleryCount = is_array($galArr) ? count($galArr) : 4;

                            $moduleTabs = [
                                'identity' => ['label' => 'Identitas Utama', 'icon' => 'id-card'],
                                'hero' => ['label' => 'Hero Section', 'icon' => 'star'],
                                'about' => ['label' => 'Tentang Saya', 'icon' => 'user'],
                                'services' => ['label' => 'Layanan & Keahlian', 'icon' => 'code'],
                                'technologies' => ['label' => 'Teknologi & Stack', 'icon' => 'layers'],
                                'social' => ['label' => 'Kontak & Tautan', 'icon' => 'at'],
                                'gallery' => ['label' => 'Galeri Visual', 'icon' => 'image'],
                            ];
                        @endphp

                        <nav class="space-y-1.5">
                            @foreach ($moduleTabs as $key => $tab)
                                @php
                                    $isCurrent = $activeTab === $key;
                                @endphp
                                <button type="button" onclick="switchTab('{{ $key }}')"
                                    id="tab-btn-{{ $key }}"
                                    class="tab-nav-btn flex w-full items-center justify-between rounded-xl px-3.5 py-2.5 text-left text-xs font-medium transition {{ $isCurrent ? 'bg-coral text-white shadow-md shadow-coral/25' : 'text-white/60 hover:bg-white/5 hover:text-white' }}">
                                    <div class="flex items-center gap-3">
                                        <x-icon :name="$tab['icon']" class="size-4 shrink-0 {{ $isCurrent ? 'text-white' : 'text-white/40' }}" />
                                        <span>{{ $tab['label'] }}</span>
                                    </div>
                                </button>
                            @endforeach
                        </nav>
                    </div>

                    {{-- Pratinjau Kartu Instan --}}
                    <div class="rounded-2xl border border-white/5 bg-[#17171d] p-5 shadow-sm space-y-4">
                        <div class="flex items-center justify-between border-b border-white/5 pb-3">
                            <div class="flex items-center gap-2">
                                <x-icon name="eye" class="size-3.5 text-white/40" />
                                <span class="font-mono text-[11px] font-semibold tracking-wider text-white/60 uppercase">
                                    PRATINJAU KARTU INSTAN
                                </span>
                            </div>
                            <span class="flex items-center gap-1 font-mono text-[10px] text-coral font-semibold uppercase tracking-wider">
                                <span class="size-1.5 rounded-full bg-coral shadow-[0_0_8px_rgba(239,68,68,0.8)]"></span>
                                LIVE
                            </span>
                        </div>

                        {{-- Card Mockup Preview --}}
                        <div class="rounded-xl border border-white/10 bg-[#121217] p-4 text-left space-y-3 relative overflow-hidden">
                            <div class="flex items-center justify-between">
                                <span class="rounded bg-white/5 border border-white/10 px-2 py-0.5 font-mono text-[9px] uppercase tracking-wider text-white/60">
                                    PERSONAL PORTFOLIO
                                </span>
                                <x-icon name="badge-check" class="size-3.5 text-coral" />
                            </div>

                            <div class="flex items-center gap-3">
                                <div class="relative size-11 shrink-0 overflow-hidden rounded-full border border-white/10 bg-white/5">
                                    <img id="preview-avatar"
                                        src="{{ asset(setting('images.about', 'images/about-placeholder.svg')) }}"
                                        alt="Preview"
                                        class="h-full w-full object-cover">
                                    <span class="absolute bottom-0 right-0 size-2.5 rounded-full border border-[#121217] bg-emerald-400"></span>
                                </div>
                                <div class="min-w-0">
                                    <h4 id="preview-name" class="text-xs font-bold text-white truncate">
                                        {{ setting('identity.name', 'Muhammad Rayhan') }}
                                    </h4>
                                    <p id="preview-role" class="font-mono text-[10px] text-coral truncate">
                                        {{ setting('identity.role', 'Junior Web Developer & UI/UX') }}
                                    </p>
                                </div>
                            </div>

                            <p id="preview-bio" class="text-[11px] text-white/60 leading-relaxed line-clamp-3">
                                {{ setting('identity.bio_summary', 'Membangun pengalaman web yang presisi, performan tinggi, dan estetik modern dengan fokus pada ekosistem web responsif.') }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Right Column (8 cols): Tab Content Panels --}}
                <div class="space-y-6 lg:col-span-8 xl:col-span-9">

                    {{-- TAB 1: IDENTITAS UTAMA --}}
                    <div id="tab-panel-identity" class="tab-content-panel {{ $activeTab === 'identity' ? '' : 'hidden' }} space-y-6">
                        {{-- Group Header Banner --}}
                        <div class="rounded-2xl border border-white/5 bg-[#17171d] p-6 shadow-sm">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="flex items-center gap-3.5">
                                    <div class="flex size-10 shrink-0 items-center justify-center rounded-xl border border-white/5 bg-white/[0.03] text-coral">
                                        <x-icon name="id-card" class="size-5" />
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h2 class="text-base font-bold text-white tracking-tight">Identitas &amp; Profil Utama</h2>
                                            <span class="rounded bg-white/5 border border-white/10 px-1.5 py-0.5 font-mono text-[9px] text-white/40 uppercase">
                                                GROUP: IDENTITY
                                            </span>
                                        </div>
                                        <p class="mt-0.5 text-xs text-white/50">
                                            Nama publik, sapaan, peran, dan ringkasan perkenalan untuk hero dan kartu profil.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Photo Banner Box --}}
                        <div class="rounded-2xl border border-white/5 bg-[#17171d] p-5 shadow-sm">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="flex items-center gap-4">
                                    <div class="size-16 shrink-0 overflow-hidden rounded-full border border-white/10 bg-[#121217]">
                                        <img id="identity-photo-preview"
                                            src="{{ asset(setting('images.about', 'images/about-placeholder.svg')) }}"
                                            alt="Foto Profil"
                                            class="h-full w-full object-cover">
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h3 class="text-sm font-bold text-white">Foto Profil Utama</h3>
                                            <span class="rounded bg-white/5 border border-white/10 px-1.5 py-0.5 font-mono text-[9px] text-white/50 uppercase">
                                                PNG, WEBP, JPG
                                            </span>
                                        </div>
                                        <p class="mt-1 text-xs text-white/40">
                                            Disarankan rasio 1:1, resolusi minimal 400x400px. Maksimal 2MB.
                                        </p>
                                        <div class="mt-3 flex items-center gap-3">
                                            <label for="identity-file-input"
                                                class="inline-flex items-center gap-1.5 rounded-xl border border-white/10 bg-white/[0.04] px-3 py-1.5 text-xs font-medium text-white transition hover:bg-white/[0.08] cursor-pointer">
                                                <x-icon name="upload-cloud" class="size-3.5 text-coral" />
                                                <span>Unggah Gambar Baru</span>
                                            </label>
                                            <input type="file" id="identity-file-input" name="settings_files[images.about]"
                                                accept="image/*"
                                                onchange="previewUploadedImage(this, 'identity-photo-preview')"
                                                class="hidden">
                                            <button type="button"
                                                onclick="document.getElementById('input-val-images-about').value = 'images/about-placeholder.svg'; document.getElementById('identity-photo-preview').src = '{{ asset('images/about-placeholder.svg') }}';"
                                                class="text-xs text-white/40 hover:text-red-400 transition">
                                                Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-right font-mono text-[10px] text-white/35">
                                    <input type="text"
                                        id="input-val-images-about"
                                        name="settings[images.about]"
                                        value="{{ old('settings.images.about', setting('images.about', 'images/about-placeholder.svg')) }}"
                                        class="mt-1 bg-transparent text-right font-mono text-[10px] text-white/50 focus:outline-none max-w-[220px]">
                                </div>
                            </div>
                        </div>

                        {{-- Form Fields --}}
                        <div class="rounded-2xl border border-white/5 bg-[#17171d] p-6 shadow-sm space-y-6">
                            {{-- Row 1: Nama Lengkap & Nama Panggilan --}}
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                {{-- Nama Lengkap --}}
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between">
                                        <label for="identity-full-name" class="font-mono text-[11px] font-semibold tracking-wider text-white/80 uppercase">
                                            Nama Lengkap
                                        </label>
                                    </div>
                                    <div class="relative">
                                        <x-icon name="user" class="pointer-events-none absolute left-3.5 top-1/2 size-4 -translate-y-1/2 text-white/35" />
                                        <input type="text" id="identity-full-name"
                                            name="settings[identity.name]"
                                            data-key="identity.name"
                                            maxlength="50"
                                            value="{{ old('settings.identity.name', setting('identity.name', 'Muhammad Rayhan')) }}"
                                            placeholder="Nama lengkap..."
                                            oninput="updateCharCount(this, 'count-name')"
                                            class="w-full rounded-xl border border-white/10 bg-[#121217] py-2.5 pl-10 pr-4 text-xs text-white placeholder:text-white/30 focus:border-coral focus:outline-none transition">
                                    </div>
                                    <div class="flex items-center justify-between text-[10px] text-white/40">
                                        <span>Nama ini muncul di tajuk utama dan meta OpenGraph.</span>
                                        <span id="count-name" class="font-mono text-white/50">15/50</span>
                                    </div>
                                </div>

                                {{-- Nama Panggilan --}}
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between">
                                        <label for="identity-short-name" class="font-mono text-[11px] font-semibold tracking-wider text-white/80 uppercase">
                                            Nama Panggilan / Display
                                        </label>
                                    </div>
                                    <div class="relative">
                                        <x-icon name="hash" class="pointer-events-none absolute left-3.5 top-1/2 size-4 -translate-y-1/2 text-white/35" />
                                        <input type="text" id="identity-short-name"
                                            name="settings[identity.short_name]"
                                            data-key="identity.short_name"
                                            maxlength="20"
                                            value="{{ old('settings.identity.short_name', setting('identity.short_name', 'Rayhan')) }}"
                                            placeholder="Nama display..."
                                            oninput="updateCharCount(this, 'count-short')"
                                            class="w-full rounded-xl border border-white/10 bg-[#121217] py-2.5 pl-10 pr-4 text-xs text-white placeholder:text-white/30 focus:border-coral focus:outline-none transition">
                                    </div>
                                    <div class="flex items-center justify-between text-[10px] text-white/40">
                                        <span>Digunakan pada navbar dan salam ramah.</span>
                                        <span id="count-short" class="font-mono text-white/50">6/20</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Row 2: Peran / Spesialisasi Profesional --}}
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <label for="identity-role" class="font-mono text-[11px] font-semibold tracking-wider text-white/80 uppercase">
                                        Peran / Spesialisasi Profesional
                                    </label>
                                </div>
                                <div class="relative">
                                    <x-icon name="layout" class="pointer-events-none absolute left-3.5 top-1/2 size-4 -translate-y-1/2 text-white/35" />
                                    <input type="text" id="identity-role"
                                        name="settings[identity.role]"
                                        data-key="identity.role"
                                        maxlength="70"
                                        value="{{ old('settings.identity.role', setting('identity.role', 'Junior Web Developer & UI/UX Designer')) }}"
                                        placeholder="Peran profesional..."
                                        oninput="updateCharCount(this, 'count-role')"
                                        class="w-full rounded-xl border border-white/10 bg-[#121217] py-2.5 pl-10 pr-4 text-xs text-white placeholder:text-white/30 focus:border-coral focus:outline-none transition">
                                </div>
                                <div class="flex items-center justify-between text-[10px] text-white/40">
                                    <span>Sub-judul utama di bawah nama di landing page hero section.</span>
                                    <span id="count-role" class="font-mono text-white/50">39/70</span>
                                </div>
                            </div>

                            {{-- Row 3: Ringkasan Singkat (Bio Headline) --}}
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <label for="identity-bio" class="font-mono text-[11px] font-semibold tracking-wider text-white/80 uppercase">
                                        Footer Section Text
                                    </label>
                                </div>
                                <textarea id="identity-bio"
                                    name="settings[identity.bio_summary]"
                                    data-key="identity.bio_summary"
                                    rows="3"
                                    maxlength="200"
                                    placeholder="Tulis ringkasan singkat profil..."
                                    oninput="updateCharCount(this, 'count-bio')"
                                    class="w-full rounded-xl border border-white/10 bg-[#121217] p-3 text-xs text-white placeholder:text-white/30 focus:border-coral focus:outline-none transition leading-relaxed">{{ old('settings.identity.bio_summary', setting('identity.bio_summary', 'Membangun pengalaman web yang presisi, performan tinggi, dan estetik modern dengan fokus pada ekosistem web responsif.')) }}</textarea>
                                <div class="flex items-center justify-between text-[10px] text-white/40">
                                    <span>Maksimum 160 karakter agar tajuk tetap ringkas di layar seluler.</span>
                                    <span id="count-bio" class="font-mono text-white/50">120/160</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- TAB 2: HERO SECTION --}}
                    <div id="tab-panel-hero" class="tab-content-panel {{ $activeTab === 'hero' ? '' : 'hidden' }} space-y-6">
                        <div class="rounded-2xl border border-white/5 bg-[#17171d] p-6 shadow-sm">
                            <div class="flex items-center gap-3.5 border-b border-white/5 pb-4">
                                <div class="flex size-10 shrink-0 items-center justify-center rounded-xl border border-white/5 bg-white/[0.03] text-coral">
                                    <x-icon name="star" class="size-5" />
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h2 class="text-base font-bold text-white tracking-tight">Hero Section</h2>
                                        <span class="rounded bg-white/5 border border-white/10 px-1.5 py-0.5 font-mono text-[9px] text-white/40 uppercase">
                                            GROUP: HERO
                                        </span>
                                    </div>
                                    <p class="mt-0.5 text-xs text-white/50">Banner beranda, tajuk utama, teks intro, dan foto latar hero.</p>
                                </div>
                            </div>

                            <div class="mt-6 space-y-5">
                                <div class="space-y-2">
                                    <label class="font-mono text-[11px] font-semibold tracking-wider text-white/80 uppercase">Tagline Atas</label>
                                    <input type="text" name="settings[hero.tagline]" value="{{ old('settings.hero.tagline', setting('hero.tagline')) }}"
                                        class="w-full rounded-xl border border-white/10 bg-[#121217] py-2.5 px-3 text-xs text-white placeholder:text-white/30 focus:border-coral focus:outline-none transition">
                                </div>

                                <div class="space-y-2">
                                    <label class="font-mono text-[11px] font-semibold tracking-wider text-white/80 uppercase">Judul Utama (Headline)</label>
                                    <input type="text" name="settings[hero.headline]" value="{{ old('settings.hero.headline', setting('hero.headline')) }}"
                                        class="w-full rounded-xl border border-white/10 bg-[#121217] py-2.5 px-3 text-xs text-white placeholder:text-white/30 focus:border-coral focus:outline-none transition">
                                </div>

                                <div class="space-y-2">
                                    <label class="font-mono text-[11px] font-semibold tracking-wider text-white/80 uppercase">Deskripsi Paragraf Hero</label>
                                    <textarea name="settings[hero.description]" rows="3"
                                        class="w-full rounded-xl border border-white/10 bg-[#121217] p-3 text-xs text-white placeholder:text-white/30 focus:border-coral focus:outline-none transition">{{ old('settings.hero.description', setting('hero.description')) }}</textarea>
                                </div>

                                <div class="space-y-2">
                                    <label class="font-mono text-[11px] font-semibold tracking-wider text-white/80 uppercase">Keahlian di Bawah Hero (Pisahkan Koma)</label>
                                    <input type="text" name="settings[hero.badges]" value="{{ old('settings.hero.badges', setting('hero.badges')) }}"
                                        class="w-full rounded-xl border border-white/10 bg-[#121217] py-2.5 px-3 text-xs text-white placeholder:text-white/30 focus:border-coral focus:outline-none transition">
                                </div>

                                <div class="space-y-2">
                                    <label class="font-mono text-[11px] font-semibold tracking-wider text-white/80 uppercase">Gambar Latar Hero</label>
                                    <div class="flex items-center gap-4">
                                        <div class="h-16 w-28 shrink-0 overflow-hidden rounded-xl border border-white/10 bg-[#121217]">
                                            <img id="hero-img-preview" src="{{ asset(setting('images.hero', 'images/hero-placeholder.svg')) }}" class="h-full w-full object-cover">
                                        </div>
                                        <div class="flex-1 space-y-1.5">
                                            <input type="text" name="settings[images.hero]" value="{{ old('settings.images.hero', setting('images.hero')) }}"
                                                class="w-full rounded-xl border border-white/10 bg-[#121217] py-2 px-3 text-xs text-white focus:border-coral focus:outline-none transition">
                                            <label class="inline-flex items-center gap-1.5 rounded-lg border border-white/10 bg-white/5 px-2.5 py-1 text-[11px] font-medium text-white/70 hover:bg-white/10 hover:text-white cursor-pointer transition">
                                                <x-icon name="upload-cloud" class="size-3.5 text-coral" />
                                                <span>Pilih File Hero</span>
                                                <input type="file" name="settings_files[images.hero]" accept="image/*" onchange="previewUploadedImage(this, 'hero-img-preview')" class="hidden">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- TAB 3: TENTANG SAYA --}}
                    <div id="tab-panel-about" class="tab-content-panel {{ $activeTab === 'about' ? '' : 'hidden' }} space-y-6">
                        <div class="rounded-2xl border border-white/5 bg-[#17171d] p-6 shadow-sm">
                            <div class="flex items-center gap-3.5 border-b border-white/5 pb-4">
                                <div class="flex size-10 shrink-0 items-center justify-center rounded-xl border border-white/5 bg-white/[0.03] text-coral">
                                    <x-icon name="user" class="size-5" />
                                </div>
                                <div>
                                    <h2 class="text-base font-bold text-white tracking-tight">Tentang Saya (About)</h2>
                                    <p class="mt-0.5 text-xs text-white/50">Teks narasi profil, latar belakang, dan cerita karier.</p>
                                </div>
                            </div>

                            <div class="mt-6 space-y-5">
                                <div class="space-y-2">
                                    <label class="font-mono text-[11px] font-semibold tracking-wider text-white/80 uppercase">Label Section</label>
                                    <input type="text" name="settings[about.label]" value="{{ old('settings.about.label', setting('about.label', 'WHO I AM')) }}"
                                        class="w-full rounded-xl border border-white/10 bg-[#121217] py-2.5 px-3 text-xs text-white focus:border-coral focus:outline-none transition">
                                </div>

                                <div class="space-y-2">
                                    <label class="font-mono text-[11px] font-semibold tracking-wider text-white/80 uppercase">Judul Headline About</label>
                                    <textarea name="settings[about.headline]" rows="2"
                                        class="w-full rounded-xl border border-white/10 bg-[#121217] p-3 text-xs text-white focus:border-coral focus:outline-none transition">{{ old('settings.about.headline', setting('about.headline')) }}</textarea>
                                </div>

                                <div class="space-y-2">
                                    <label class="font-mono text-[11px] font-semibold tracking-wider text-white/80 uppercase">Paragraf Pertama</label>
                                    <textarea name="settings[about.paragraph_1]" rows="3"
                                        class="w-full rounded-xl border border-white/10 bg-[#121217] p-3 text-xs text-white focus:border-coral focus:outline-none transition">{{ old('settings.about.paragraph_1', setting('about.paragraph_1')) }}</textarea>
                                </div>

                                <div class="space-y-2">
                                    <label class="font-mono text-[11px] font-semibold tracking-wider text-white/80 uppercase">Paragraf Kedua</label>
                                    <textarea name="settings[about.paragraph_2]" rows="3"
                                        class="w-full rounded-xl border border-white/10 bg-[#121217] p-3 text-xs text-white focus:border-coral focus:outline-none transition">{{ old('settings.about.paragraph_2', setting('about.paragraph_2')) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- TAB 4: LAYANAN & KEAHLIAN --}}
                    <div id="tab-panel-services" class="tab-content-panel {{ $activeTab === 'services' ? '' : 'hidden' }} space-y-6">
                        {{-- Group Header Banner --}}
                        <div class="rounded-2xl border border-white/5 bg-[#17171d] p-6 shadow-sm">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-white/5 pb-4">
                                <div class="flex items-center gap-3.5">
                                    <div class="flex size-10 shrink-0 items-center justify-center rounded-xl border border-white/5 bg-white/[0.03] text-coral">
                                        <x-icon name="code" class="size-5" />
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h2 class="text-base font-bold text-white tracking-tight">Layanan &amp; Keahlian</h2>
                                            <span class="rounded bg-white/5 border border-white/10 px-1.5 py-0.5 font-mono text-[9px] text-white/40 uppercase">
                                                GROUP: SERVICES
                                            </span>
                                        </div>
                                        <p class="mt-0.5 text-xs text-white/50">Bidang keahlian yang ditawarkan dalam format JSON terstruktur.</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1.5 self-start sm:self-auto font-mono text-[10px] text-emerald-400 font-semibold uppercase tracking-wider">
                                    <span class="size-1.5 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.8)]"></span>
                                    <span>Tersimpan Otomatis</span>
                                </div>
                            </div>

                            <div class="mt-6 space-y-5">
                                {{-- LABEL SECTION --}}
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between">
                                        <label for="services-section-label" class="font-mono text-[11px] font-semibold tracking-wider text-white/80 uppercase">
                                            LABEL SECTION
                                        </label>
                                        <span class="font-mono text-[10px] text-white/35">section.label</span>
                                    </div>
                                    <div class="relative">
                                        <input type="text" id="services-section-label" name="settings[services.label]"
                                            value="{{ old('settings.services.label', setting('services.label', 'SKILLS & SERVICES')) }}"
                                            placeholder="SKILLS & SERVICES"
                                            class="w-full rounded-xl border border-white/10 bg-[#121217] py-2.5 pl-3.5 pr-10 text-xs text-white placeholder:text-white/30 focus:border-coral focus:outline-none transition">
                                        <span class="pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2 font-mono text-xs text-white/35">#</span>
                                    </div>
                                </div>

                                {{-- JUDUL LAYANAN --}}
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between">
                                        <label for="services-section-headline" class="font-mono text-[11px] font-semibold tracking-wider text-white/80 uppercase">
                                            JUDUL LAYANAN
                                        </label>
                                        <span class="font-mono text-[10px] text-white/35">section.title</span>
                                    </div>
                                    <div class="relative">
                                        <input type="text" id="services-section-headline" name="settings[services.headline]"
                                            value="{{ old('settings.services.headline', setting('services.headline', 'What I Can Do')) }}"
                                            placeholder="What I Can Do"
                                            class="w-full rounded-xl border border-white/10 bg-[#121217] py-2.5 pl-3.5 pr-10 text-xs text-white placeholder:text-white/30 focus:border-coral focus:outline-none transition">
                                        <span class="pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2 font-mono text-xs text-white/35">T</span>
                                    </div>
                                </div>

                                {{-- DESKRIPSI LAYANAN --}}
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between">
                                        <label for="services-section-desc" class="font-mono text-[11px] font-semibold tracking-wider text-white/80 uppercase">
                                            DESKRIPSI LAYANAN
                                        </label>
                                        <span class="font-mono text-[10px] text-white/35">section.description</span>
                                    </div>
                                    <textarea id="services-section-desc" name="settings[services.description]" rows="3"
                                        class="w-full rounded-xl border border-white/10 bg-[#121217] p-3 text-xs text-white placeholder:text-white/30 focus:border-coral focus:outline-none transition leading-relaxed">{{ old('settings.services.description', setting('services.description', 'Menggabungkan pengembangan web dan desain antarmuka untuk membangun pengalaman digital yang fungsional, responsif, dan menarik.')) }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- DAFTAR ITEM LAYANAN --}}
                        <div class="rounded-2xl border border-white/5 bg-[#17171d] p-6 shadow-sm space-y-6">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-white/5 pb-4">
                                <div class="flex items-center gap-3">
                                    <h3 class="font-mono text-[11px] font-semibold tracking-wider text-white/80 uppercase">
                                        DAFTAR ITEM LAYANAN
                                    </h3>
                                    <span id="services-count-badge" class="rounded bg-white/5 border border-white/10 px-2 py-0.5 font-mono text-[10px] text-white/60">
                                        0 Layanan Aktif
                                    </span>
                                </div>
                                <button type="button" onclick="addNewServiceItem()"
                                    class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-coral px-4 py-2 text-xs font-semibold text-white shadow-md shadow-coral/25 hover:bg-coral-dark transition">
                                    <x-icon name="plus" class="size-3.5" />
                                    <span>Tambah Layanan Baru</span>
                                </button>
                            </div>

                            {{-- Interactive Item Cards Container --}}
                            <div id="services-items-container" class="space-y-4">
                                {{-- Rendered dynamically by JavaScript --}}
                            </div>

                            {{-- Hidden JSON storage input submitted with the form --}}
                            <input type="hidden" id="services-json-input" name="settings[services.items]"
                                value="{{ old('settings.services.items', is_array(setting('services.items')) ? json_encode(setting('services.items'), JSON_UNESCAPED_UNICODE) : (setting('services.items') ?? '[]')) }}">

                            {{-- Advanced Raw JSON Drawer --}}
                            <div class="pt-3 border-t border-white/5">
                                <button type="button" onclick="toggleRawJsonMode()"
                                    class="inline-flex items-center gap-2 font-mono text-[11px] text-white/40 hover:text-coral transition">
                                    <x-icon name="code" class="size-3.5" />
                                    <span id="raw-json-toggle-text">Mode Raw JSON (Lanjutan)</span>
                                </button>
                                <div id="raw-json-container" class="hidden mt-3 space-y-2">
                                    <textarea id="raw-json-textarea" rows="6" oninput="syncFromRawJson()"
                                        class="w-full rounded-xl border border-white/10 bg-black/60 p-3 font-mono text-[11px] text-emerald-400 focus:border-coral focus:outline-none transition leading-relaxed"></textarea>
                                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 text-[10px] text-white/40">
                                        <span>Perubahan pada raw JSON langsung tersinkronisasi ke formulir visual.</span>
                                        <button type="button" onclick="formatServicesJson()" class="text-coral hover:underline font-mono">Ratakan Format JSON</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- HASIL RENDERING VISUAL KARTU --}}
                        <div class="rounded-2xl border border-white/5 bg-[#17171d] p-6 shadow-sm space-y-4">
                            <div class="flex items-center justify-between border-b border-white/5 pb-3">
                                <div class="flex items-center gap-2">
                                    <x-icon name="eye" class="size-4 text-white/40" />
                                    <span class="font-mono text-[11px] font-semibold tracking-wider text-white/80 uppercase">
                                        HASIL RENDERING VISUAL KARTU
                                    </span>
                                </div>
                                <span id="services-rendering-count" class="font-mono text-[10px] text-white/50">
                                    0 Item terdaftar
                                </span>
                            </div>

                            <div id="services-visual-grid" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Rendered dynamically by JavaScript --}}
                            </div>
                        </div>
                    </div>

                    {{-- TAB 5: TEKNOLOGI & STACK --}}
                    <div id="tab-panel-technologies" class="tab-content-panel {{ $activeTab === 'technologies' ? '' : 'hidden' }} space-y-6">
                        {{-- Header Banner --}}
                        <div class="rounded-2xl border border-white/5 bg-[#17171d] p-6 shadow-sm">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="flex items-center gap-3.5">
                                    <div class="flex size-10 shrink-0 items-center justify-center rounded-xl border border-white/5 bg-white/[0.03] text-coral">
                                        <x-icon name="layers" class="size-5" />
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h2 class="text-base font-bold text-white tracking-tight">Teknologi &amp; Stack</h2>
                                            <span class="rounded bg-coral/10 border border-coral/20 px-2 py-0.5 font-mono text-[9px] text-coral font-bold uppercase tracking-wider">
                                                MARQUEE SYNC
                                            </span>
                                        </div>
                                        <p class="mt-0.5 text-xs text-white/50">Daftar bahasa pemrograman, framework, dan tools yang berjalan di marquee halaman publik.</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1.5 self-start sm:self-auto font-mono text-[10px] text-emerald-400 font-semibold uppercase tracking-wider">
                                    <span class="size-1.5 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.8)]"></span>
                                    <span>Tersimpan Otomatis</span>
                                </div>
                            </div>
                        </div>

                        {{-- Card 1: Koleksi Chip Tag Interaktif --}}
                        <div class="rounded-2xl border border-white/5 bg-[#17171d] p-6 shadow-sm space-y-5">
                            <div class="flex items-center justify-between border-b border-white/5 pb-3">
                                <div class="flex items-center gap-2">
                                    <x-icon name="hash" class="size-4 text-white/40" />
                                    <h3 class="font-mono text-[11px] font-semibold tracking-wider text-white/80 uppercase">
                                        Koleksi Chip Tag Interaktif
                                    </h3>
                                </div>
                                <span id="tech-active-count-badge" class="font-mono text-[10px] text-white/50">
                                    0 Tag Aktif
                                </span>
                            </div>

                            {{-- Active Chips Container --}}
                            <div id="tech-chips-container" class="flex flex-wrap items-center gap-2.5 min-h-[44px]">
                                {{-- Rendered dynamically by JavaScript --}}
                            </div>

                            {{-- Input Bar to Add New Tech --}}
                            <div class="rounded-xl border border-white/10 bg-[#121217] p-1.5 flex flex-col sm:flex-row items-center gap-2 transition focus-within:border-coral">
                                <div class="flex flex-1 items-center gap-2 w-full pl-2">
                                    <x-icon name="plus" class="size-4 text-white/30 shrink-0" />
                                    <input type="text" id="new-tech-input"
                                        placeholder="Tambah Teknologi / Stack Baru (misal: Svelte, Python, Docker...)"
                                        onkeydown="handleNewTechKeydown(event)"
                                        class="w-full bg-transparent text-xs text-white placeholder:text-white/30 focus:outline-none">
                                </div>
                                <div class="flex items-center gap-2 w-full sm:w-auto shrink-0 justify-end">
                                    <select id="new-tech-category"
                                        class="rounded-lg border border-white/10 bg-[#17171d] px-3 py-1.5 text-xs text-white/70 focus:border-coral focus:outline-none">
                                        <option value="frontend">Kategori: Frontend</option>
                                        <option value="backend">Kategori: Backend &amp; DB</option>
                                        <option value="design">Kategori: Design &amp; Tools</option>
                                    </select>
                                    <button type="button" onclick="addNewTechTag()"
                                        class="flex items-center gap-1.5 rounded-lg bg-coral px-4 py-1.5 text-xs font-semibold text-white shadow-md shadow-coral/20 hover:bg-coral-dark transition shrink-0">
                                        <x-icon name="plus" class="size-3.5" />
                                        <span>Tambah</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Card 2: Filter Kategori Stack --}}
                        <div class="rounded-2xl border border-white/5 bg-[#17171d] p-5 shadow-sm space-y-4">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <div class="flex size-9 shrink-0 items-center justify-center rounded-xl bg-white/[0.03] border border-white/5 text-coral">
                                        <x-icon name="sliders" class="size-4" />
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-bold text-white tracking-tight">Filter Kategori Stack</h4>
                                        <p class="text-[11px] text-white/40">Tampilkan tag berdasarkan klasifikasi keahlian</p>
                                    </div>
                                </div>

                                {{-- Filter Buttons --}}
                                <div id="tech-category-filters" class="flex flex-wrap items-center gap-1.5">
                                    <button type="button" onclick="filterTechCategory('all')" id="filter-tech-all"
                                        class="tech-filter-btn rounded-xl px-3 py-1.5 text-xs font-medium transition bg-coral text-white shadow-md shadow-coral/20">
                                        Semua (<span id="filter-count-all">0</span>)
                                    </button>
                                    <button type="button" onclick="filterTechCategory('frontend')" id="filter-tech-frontend"
                                        class="tech-filter-btn rounded-xl px-3 py-1.5 text-xs font-medium transition bg-white/5 text-white/70 hover:bg-white/10 hover:text-white">
                                        Frontend (<span id="filter-count-frontend">0</span>)
                                    </button>
                                    <button type="button" onclick="filterTechCategory('backend')" id="filter-tech-backend"
                                        class="tech-filter-btn rounded-xl px-3 py-1.5 text-xs font-medium transition bg-white/5 text-white/70 hover:bg-white/10 hover:text-white">
                                        Backend &amp; DB (<span id="filter-count-backend">0</span>)
                                    </button>
                                    <button type="button" onclick="filterTechCategory('design')" id="filter-tech-design"
                                        class="tech-filter-btn rounded-xl px-3 py-1.5 text-xs font-medium transition bg-white/5 text-white/70 hover:bg-white/10 hover:text-white">
                                        Design &amp; Tools (<span id="filter-count-design">0</span>)
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Card 3: Rekomendasi Cepat Ditambahkan --}}
                        <div class="rounded-2xl border border-white/5 bg-[#17171d] p-5 shadow-sm space-y-3">
                            <div class="flex items-center justify-between border-b border-white/5 pb-2.5">
                                <div class="flex items-center gap-2">
                                    <x-icon name="sparkles" class="size-4 text-coral" />
                                    <h4 class="text-xs font-bold text-white">Rekomendasi Cepat Ditambahkan</h4>
                                </div>
                                <span class="font-mono text-[10px] text-white/40">Klik untuk menambah</span>
                            </div>
                            <div id="tech-quick-recommendations" class="flex flex-wrap items-center gap-2">
                                {{-- Rendered dynamically by JavaScript --}}
                            </div>
                        </div>

                        {{-- Card 4: Pratinjau Marquee Publik (Live Visual) --}}
                        <div class="rounded-2xl border border-white/5 bg-[#17171d] p-5 shadow-sm space-y-4">
                            <div class="flex items-center justify-between border-b border-white/5 pb-3">
                                <div class="flex items-center gap-2">
                                    <x-icon name="eye" class="size-4 text-white/40" />
                                    <h4 class="font-mono text-[11px] font-semibold tracking-wider text-white/80 uppercase">
                                        Pratinjau Marquee Publik (Live Visual)
                                    </h4>
                                </div>
                                <span class="font-mono text-[10px] text-emerald-400 font-semibold tracking-wider">
                                    Berjalan Halus (CSS Keyframes)
                                </span>
                            </div>

                            <div class="overflow-hidden rounded-xl border border-white/10 bg-[#121217] py-4"
                                style="mask-image:linear-gradient(to right,transparent,#000 8%,#000 92%,transparent); -webkit-mask-image:linear-gradient(to right,transparent,#000 8%,#000 92%,transparent);">
                                <div class="tech-marquee-wrapper overflow-hidden flex whitespace-nowrap">
                                    <div id="tech-marquee-content-1" class="tech-marquee-track flex shrink-0 items-center gap-12 pr-12 text-sm font-semibold tracking-wide text-white/90">
                                        {{-- Rendered dynamically by JS --}}
                                    </div>
                                    <div id="tech-marquee-content-2" class="tech-marquee-track flex shrink-0 items-center gap-12 pr-12 text-sm font-semibold tracking-wide text-white/90" aria-hidden="true">
                                        {{-- Duplicate for seamless loop --}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Hidden JSON storage input submitted with the form --}}
                        <input type="hidden" id="tech-json-input" name="settings[technologies.list]"
                            value="{{ old('settings.technologies.list', is_array(setting('technologies.list')) ? json_encode(setting('technologies.list'), JSON_UNESCAPED_UNICODE) : (setting('technologies.list') ?? '[]')) }}">

                        {{-- Advanced Raw JSON Drawer for Technologies --}}
                        <div class="pt-2 border-t border-white/5">
                            <button type="button" onclick="toggleTechRawJsonMode()"
                                class="inline-flex items-center gap-2 font-mono text-[11px] text-white/40 hover:text-coral transition">
                                <x-icon name="code" class="size-3.5" />
                                <span id="tech-raw-json-toggle-text">Mode Raw JSON (Lanjutan)</span>
                            </button>
                            <div id="tech-raw-json-container" class="hidden mt-3 space-y-2">
                                <textarea id="tech-raw-json-textarea" rows="5" oninput="syncFromTechRawJson()"
                                    class="w-full rounded-xl border border-white/10 bg-black/60 p-3 font-mono text-[11px] text-emerald-400 focus:border-coral focus:outline-none transition leading-relaxed"></textarea>
                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 text-[10px] text-white/40">
                                    <span>Perubahan langsung memperbarui chip dan pratinjau marquee.</span>
                                    <button type="button" onclick="formatTechJson()" class="text-coral hover:underline font-mono">Ratakan Format JSON</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- TAB 6: KONTAK & TAUTAN --}}
                    <div id="tab-panel-social" class="tab-content-panel {{ $activeTab === 'social' ? '' : 'hidden' }} space-y-6">
                        {{-- Header Banner --}}
                        <div class="rounded-2xl border border-white/5 bg-[#17171d] p-6 shadow-sm">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="flex items-center gap-3.5">
                                    <div class="flex size-10 shrink-0 items-center justify-center rounded-xl border border-white/5 bg-white/[0.03] text-coral">
                                        <x-icon name="at" class="size-5" />
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h2 class="text-base font-bold text-white tracking-tight">Kontak &amp; Tautan Sosial</h2>
                                        </div>
                                        <p class="mt-0.5 text-xs text-white/50">Kanal komunikasi publik, email, dan tautan jejaring sosial yang tampil di footer portofolio.</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1.5 self-start sm:self-auto font-mono text-[10px] text-coral font-semibold uppercase tracking-wider">
                                    <span class="size-1.5 rounded-full bg-coral shadow-[0_0_8px_rgba(239,68,68,0.8)]"></span>
                                    <span>Tersimpan Otomatis</span>
                                </div>
                            </div>
                        </div>

                        {{-- Form Inputs Card --}}
                        <div class="rounded-2xl border border-white/5 bg-[#17171d] p-6 shadow-sm space-y-5">
                            {{-- Field 1: Email Resmi / Kontak --}}
                            <div class="space-y-1.5">
                                <div class="flex items-center justify-between">
                                    <label for="contact-email-input" class="font-mono text-[11px] font-semibold tracking-wider text-white/80 uppercase">
                                        EMAIL RESMI / KONTAK
                                    </label>
                                    <span class="font-mono text-[10px] text-white/35">Wajib diisi</span>
                                </div>
                                <div class="relative">
                                    <x-icon name="mail" class="pointer-events-none absolute left-3.5 top-1/2 size-4 -translate-y-1/2 text-white/35" />
                                    <input type="email" id="contact-email-input" name="settings[contact.email]"
                                        value="{{ old('settings.contact.email', setting('contact.email', 'rayhan.kh@example.com')) }}"
                                        placeholder="nama@domain.com"
                                        oninput="updateFooterPreview()"
                                        class="w-full rounded-xl border border-white/10 bg-[#121217] py-2.5 pl-10 pr-4 text-xs text-white placeholder:text-white/30 focus:border-coral focus:outline-none transition">
                                </div>
                                <p class="text-[11px] text-white/40">Email utama yang menerima pesan kontak dari pengunjung web.</p>
                            </div>

                            {{-- Field 2: Link GitHub --}}
                            <div class="space-y-1.5">
                                <div class="flex items-center justify-between">
                                    <label for="contact-github-input" class="font-mono text-[11px] font-semibold tracking-wider text-white/80 uppercase">
                                        LINK GITHUB
                                    </label>
                                    <span class="font-mono text-[10px] text-white/35">Koneksi Aktif</span>
                                </div>
                                <div class="relative">
                                    <x-icon name="code" class="pointer-events-none absolute left-3.5 top-1/2 size-4 -translate-y-1/2 text-white/35" />
                                    <input type="url" id="contact-github-input" name="settings[contact.github]"
                                        value="{{ old('settings.contact.github', setting('contact.github', 'https://github.com/rayhan-dev')) }}"
                                        placeholder="https://github.com/..."
                                        oninput="updateFooterPreview()"
                                        class="w-full rounded-xl border border-white/10 bg-[#121217] py-2.5 pl-10 pr-4 text-xs text-white placeholder:text-white/30 focus:border-coral focus:outline-none transition">
                                </div>
                                <p class="text-[11px] text-white/40">Menampilkan repositori kode terbuka dan kontribusi publik.</p>
                            </div>

                            {{-- Field 3: Link LinkedIn --}}
                            <div class="space-y-1.5">
                                <div class="flex items-center justify-between">
                                    <label for="contact-linkedin-input" class="font-mono text-[11px] font-semibold tracking-wider text-white/80 uppercase">
                                        LINK LINKEDIN
                                    </label>
                                    <span class="font-mono text-[10px] text-white/35">Profil Karir</span>
                                </div>
                                <div class="relative">
                                    <x-icon name="linkedin" class="pointer-events-none absolute left-3.5 top-1/2 size-4 -translate-y-1/2 text-white/35" />
                                    <input type="url" id="contact-linkedin-input" name="settings[contact.linkedin]"
                                        value="{{ old('settings.contact.linkedin', setting('contact.linkedin', 'https://linkedin.com/in/muhammad-rayhan')) }}"
                                        placeholder="https://linkedin.com/in/..."
                                        oninput="updateFooterPreview()"
                                        class="w-full rounded-xl border border-white/10 bg-[#121217] py-2.5 pl-10 pr-4 text-xs text-white placeholder:text-white/30 focus:border-coral focus:outline-none transition">
                                </div>
                                <p class="text-[11px] text-white/40">Jaringan profesional dan riwayat pengalaman formal.</p>
                            </div>

                            {{-- Field 4: Link Instagram --}}
                            <div class="space-y-1.5">
                                <div class="flex items-center justify-between">
                                    <label for="contact-instagram-input" class="font-mono text-[11px] font-semibold tracking-wider text-white/80 uppercase">
                                        LINK INSTAGRAM
                                    </label>
                                    <span class="rounded bg-coral/10 border border-coral/20 px-2 py-0.5 font-mono text-[9px] text-coral font-bold uppercase tracking-wider">Baru</span>
                                </div>
                                <div class="relative">
                                    <x-icon name="instagram" class="pointer-events-none absolute left-3.5 top-1/2 size-4 -translate-y-1/2 text-white/35" />
                                    <input type="url" id="contact-instagram-input" name="settings[contact.instagram]"
                                        value="{{ old('settings.contact.instagram', setting('contact.instagram', 'https://instagram.com/rayhan.visual')) }}"
                                        placeholder="https://instagram.com/..."
                                        oninput="updateFooterPreview()"
                                        class="w-full rounded-xl border border-white/10 bg-[#121217] py-2.5 pl-10 pr-4 text-xs text-white placeholder:text-white/30 focus:border-coral focus:outline-none transition">
                                </div>
                                <p class="text-[11px] text-white/40">Menampilkan tautan akun Instagram di footer publik untuk galeri visual dan kegiatan.</p>
                            </div>

                            {{-- Field 5: Link TikTok --}}
                            <div class="space-y-1.5">
                                <div class="flex items-center justify-between">
                                    <label for="contact-tiktok-input" class="font-mono text-[11px] font-semibold tracking-wider text-white/80 uppercase">
                                        LINK TIKTOK
                                    </label>
                                    <span class="rounded bg-coral/10 border border-coral/20 px-2 py-0.5 font-mono text-[9px] text-coral font-bold uppercase tracking-wider">Baru</span>
                                </div>
                                <div class="relative">
                                    <x-icon name="tiktok" class="pointer-events-none absolute left-3.5 top-1/2 size-4 -translate-y-1/2 text-white/35" />
                                    <input type="url" id="contact-tiktok-input" name="settings[contact.tiktok]"
                                        value="{{ old('settings.contact.tiktok', setting('contact.tiktok', 'https://tiktok.com/@rayhan.codes')) }}"
                                        placeholder="https://tiktok.com/@..."
                                        oninput="updateFooterPreview()"
                                        class="w-full rounded-xl border border-white/10 bg-[#121217] py-2.5 pl-10 pr-4 text-xs text-white placeholder:text-white/30 focus:border-coral focus:outline-none transition">
                                </div>
                                <p class="text-[11px] text-white/40">Menampilkan tautan profil TikTok untuk portofolio kreatif dan video proses rekayasa.</p>
                            </div>
                        </div>

                        {{-- Card: Hasil Rendering Footer Publik --}}
                        <div class="rounded-2xl border border-white/5 bg-[#17171d] p-6 shadow-sm space-y-4">
                            <div class="flex items-center justify-between border-b border-white/5 pb-3">
                                <div class="flex items-center gap-2">
                                    <x-icon name="layout" class="size-4 text-white/40" />
                                    <h4 class="font-mono text-[11px] font-semibold tracking-wider text-white/80 uppercase">
                                        Hasil Rendering Footer Publik
                                    </h4>
                                </div>
                                <span id="footer-links-count" class="font-mono text-[10px] text-white/50">
                                    5 Ikon Terhubung
                                </span>
                            </div>
                            <p class="text-xs text-white/50">
                                Representasi visual tombol tautan interaktif yang langsung muncul di bagian footer pengunjung situs.
                            </p>

                            <div class="rounded-xl border border-white/10 bg-[#121217] p-5">
                                <div class="flex flex-wrap items-center gap-3">
                                    {{-- Email Mock Button --}}
                                    <div id="footer-btn-email" class="inline-flex items-center gap-2 rounded-xl border border-white/10 bg-white/[0.04] px-4 py-2 text-xs font-medium text-white transition">
                                        <x-icon name="mail" class="size-3.5 text-white/60" />
                                        <span>Email</span>
                                        <span class="footer-btn-dot size-1.5 rounded-full bg-coral"></span>
                                    </div>

                                    {{-- GitHub Mock Button --}}
                                    <div id="footer-btn-github" class="inline-flex items-center gap-2 rounded-xl border border-white/10 bg-white/[0.04] px-4 py-2 text-xs font-medium text-white transition">
                                        <x-icon name="code" class="size-3.5 text-white/60" />
                                        <span>GitHub</span>
                                        <span class="footer-btn-dot size-1.5 rounded-full bg-coral"></span>
                                    </div>

                                    {{-- LinkedIn Mock Button --}}
                                    <div id="footer-btn-linkedin" class="inline-flex items-center gap-2 rounded-xl border border-white/10 bg-white/[0.04] px-4 py-2 text-xs font-medium text-white transition">
                                        <x-icon name="linkedin" class="size-3.5 text-white/60" />
                                        <span>LinkedIn</span>
                                        <span class="footer-btn-dot size-1.5 rounded-full bg-coral"></span>
                                    </div>

                                    {{-- Instagram Mock Button --}}
                                    <div id="footer-btn-instagram" class="inline-flex items-center gap-2 rounded-xl border border-white/10 bg-white/[0.04] px-4 py-2 text-xs font-medium text-white transition">
                                        <x-icon name="instagram" class="size-3.5 text-white/60" />
                                        <span>Instagram</span>
                                        <span class="footer-btn-dot size-1.5 rounded-full bg-coral"></span>
                                    </div>

                                    {{-- TikTok Mock Button --}}
                                    <div id="footer-btn-tiktok" class="inline-flex items-center gap-2 rounded-xl border border-white/10 bg-white/[0.04] px-4 py-2 text-xs font-medium text-white transition">
                                        <x-icon name="tiktok" class="size-3.5 text-white/60" />
                                        <span>TikTok</span>
                                        <span class="footer-btn-dot size-1.5 rounded-full bg-coral"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- TAB 7: GALERI VISUAL --}}
                    @php
                        $defaultGalleryList = config('portfolio.images.gallery', [
                            ['src' => 'images/gallery-1.svg', 'title' => 'Dokumentasi Proyek Utama', 'alt' => 'Placeholder dokumentasi proyek 1', 'class' => 'md:col-span-7 aspect-[16/10]', 'visible' => true],
                            ['src' => 'images/gallery-2.svg', 'title' => 'Perancangan UI/UX', 'alt' => 'Placeholder dokumentasi proyek 2 (Design System)', 'class' => 'md:col-span-5 aspect-[4/5] md:aspect-auto', 'visible' => true],
                            ['src' => 'images/gallery-3.svg', 'title' => 'Arsitektur Kode', 'alt' => 'Pengembangan kode backend & arsitektur database', 'class' => 'md:col-span-4 aspect-[4/3]', 'visible' => true],
                            ['src' => 'images/gallery-4.svg', 'title' => 'Review Multi-Device', 'alt' => 'Review tampilan responsif multi-device', 'class' => 'md:col-span-8 aspect-[16/9] md:aspect-[16/8]', 'visible' => true],
                        ]);
                        $rawGal = setting('images.gallery', $defaultGalleryList);
                        $currentGalleryList = is_string($rawGal) ? (json_decode($rawGal, true) ?? $defaultGalleryList) : $rawGal;
                        if (!is_array($currentGalleryList) || empty($currentGalleryList)) {
                            $currentGalleryList = $defaultGalleryList;
                        }
                        foreach ($currentGalleryList as $gIdx => &$gItem) {
                            if (!isset($gItem['title'])) {
                                $defTitles = ['Dokumentasi Proyek Utama', 'Perancangan UI/UX', 'Arsitektur Kode', 'Review Multi-Device'];
                                $gItem['title'] = $defTitles[$gIdx % 4] ?? 'Dokumentasi Galeri';
                            }
                            if (!isset($gItem['visible'])) {
                                $gItem['visible'] = true;
                            }
                        }
                        unset($gItem);
                    @endphp

                    <div id="tab-panel-gallery" class="tab-content-panel {{ $activeTab === 'gallery' ? '' : 'hidden' }} space-y-6">
                        {{-- Header Banner --}}
                        <div class="rounded-2xl border border-white/5 bg-[#17171d] p-6 shadow-sm">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="flex items-center gap-3.5">
                                    <div class="flex size-10 shrink-0 items-center justify-center rounded-xl border border-white/5 bg-white/[0.03] text-coral">
                                        <x-icon name="image" class="size-5" />
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h2 class="text-base font-bold text-white tracking-tight">Galeri Visual</h2>
                                            <span class="rounded bg-coral/10 border border-coral/20 px-2 py-0.5 font-mono text-[9px] text-coral font-bold uppercase tracking-wider">
                                                GUI Mode
                                            </span>
                                        </div>
                                        <p class="mt-0.5 text-xs text-white/50">
                                            Kelola daftar foto showcase dokumentasi secara visual tanpa input JSON. Ubah thumbnail, teks deskripsi, dan tata letak secara real-time.
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 self-start sm:self-auto">
                                    <div class="inline-flex items-center gap-2 rounded-xl border border-white/10 bg-white/[0.04] px-3.5 py-2 text-xs font-medium text-white">
                                        <x-icon name="image" class="size-3.5 text-white/50" />
                                        <span id="gallery-installed-count">4 Foto Terpasang</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Card: Unggah Foto Baru & Variasi Layout --}}
                        <div class="rounded-2xl border border-white/5 bg-[#17171d] p-5 sm:p-6 shadow-sm space-y-4">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div>
                                    <h3 class="text-sm font-bold text-white tracking-tight">Variasi Layout &amp; Unggah Foto</h3>
                                    <p class="mt-0.5 text-xs text-white/50">Dukungan WebP, PNG, JPG hingga 5MB. Pilih preset tata letak atau atur rasio per slot.</p>
                                </div>
                                <button type="button" onclick="addNewGallerySlot()"
                                    class="flex items-center gap-2 rounded-xl bg-coral px-4 py-2 text-xs font-semibold text-white shadow-lg shadow-coral/25 transition hover:bg-coral-dark self-start sm:self-auto shrink-0">
                                    <x-icon name="plus" class="size-3.5" />
                                    <span>+ Tambah Foto</span>
                                </button>
                            </div>

                            {{-- Layout Preset Quick Buttons --}}
                            <div class="flex flex-wrap items-center gap-2 pt-1 border-t border-white/5">
                                <span class="font-mono text-[10px] text-white/40 uppercase tracking-wider mr-1">Preset Tata Letak:</span>
                                <button type="button" onclick="applyGalleryPreset('bento-2x2')" id="preset-btn-bento-2x2"
                                    class="gallery-preset-btn flex items-center gap-1.5 rounded-lg border border-coral/30 bg-coral/10 px-2.5 py-1 text-[11px] font-medium text-coral hover:bg-coral/20 transition font-mono">
                                    <span>🍱 Bento Asimetris (2x2)</span>
                                </button>
                                <button type="button" onclick="applyGalleryPreset('spotlight')" id="preset-btn-spotlight"
                                    class="gallery-preset-btn flex items-center gap-1.5 rounded-lg border border-white/10 bg-white/[0.04] px-2.5 py-1 text-[11px] font-medium text-white/70 hover:bg-white/[0.08] hover:text-white transition font-mono">
                                    <span>🌟 Spotlight Hero</span>
                                </button>
                                <button type="button" onclick="applyGalleryPreset('masonry')" id="preset-btn-masonry"
                                    class="gallery-preset-btn flex items-center gap-1.5 rounded-lg border border-white/10 bg-white/[0.04] px-2.5 py-1 text-[11px] font-medium text-white/70 hover:bg-white/[0.08] hover:text-white transition font-mono">
                                    <span>🧱 Masonry 3-Kolom</span>
                                </button>
                                <button type="button" onclick="applyGalleryPreset('panorama')" id="preset-btn-panorama"
                                    class="gallery-preset-btn flex items-center gap-1.5 rounded-lg border border-white/10 bg-white/[0.04] px-2.5 py-1 text-[11px] font-medium text-white/70 hover:bg-white/[0.08] hover:text-white transition font-mono">
                                    <span>🎞️ Panorama Sinematik</span>
                                </button>
                                <button type="button" onclick="applyGalleryPreset('quad')" id="preset-btn-quad"
                                    class="gallery-preset-btn flex items-center gap-1.5 rounded-lg border border-white/10 bg-white/[0.04] px-2.5 py-1 text-[11px] font-medium text-white/70 hover:bg-white/[0.08] hover:text-white transition font-mono">
                                    <span>🪟 Quad Seimbang (6:6)</span>
                                </button>
                            </div>

                            {{-- Dropzone Area --}}
                            <div id="gallery-dropzone" onclick="document.getElementById('gallery-dropzone-file').click()"
                                class="group relative flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-white/10 bg-[#121217] p-7 text-center cursor-pointer transition hover:border-coral/50 hover:bg-[#15151c]">
                                <input type="file" id="gallery-dropzone-file" accept="image/*" class="hidden" onchange="handleDropzoneUpload(this)">
                                <div class="flex size-11 items-center justify-center rounded-full border border-white/10 bg-white/[0.03] text-white/60 group-hover:scale-110 group-hover:text-coral group-hover:border-coral/40 transition">
                                    <x-icon name="upload-cloud" class="size-5" />
                                </div>
                                <p class="mt-2.5 text-xs font-medium text-white/80 group-hover:text-white">
                                    Klik untuk telusuri berkas atau seret foto ke area ini
                                </p>
                                <p class="mt-1 font-mono text-[10px] text-white/40">
                                    Disarankan resolusi 1920 × 1080 piksel • Kompresi WebP otomatis diaktifkan
                                </p>
                            </div>
                        </div>

                        {{-- Section: DAFTAR SLOT GALERI PORTOFOLIO --}}
                        <div class="space-y-3.5">
                            <div class="flex items-center justify-between px-1">
                                <div class="flex items-center gap-2 font-mono text-[11px] font-semibold tracking-wider text-white/80 uppercase">
                                    <span class="size-1.5 rounded-full bg-coral"></span>
                                    <span>DAFTAR SLOT GALERI PORTOFOLIO (<span id="slots-count-header">4 ITEM</span>)</span>
                                </div>
                                <span class="font-mono text-[10px] text-white/40">
                                    Seret gagang untuk menyusun urutan
                                </span>
                            </div>

                            {{-- Slots Container --}}
                            <div id="gallery-slots-list" class="space-y-3">
                                {{-- Dynamically populated via JS --}}
                            </div>
                        </div>

                        {{-- Card: Hasil Rendering Galeri Publik (Pratinjau Nyata) --}}
                        <div class="rounded-2xl border border-white/5 bg-[#17171d] p-5 sm:p-6 shadow-sm space-y-3.5">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-white/5 pb-3">
                                <div class="flex items-center gap-2">
                                    <x-icon name="layout" class="size-4 text-coral" />
                                    <h4 class="font-mono text-[11px] font-semibold tracking-wider text-white/80 uppercase">
                                        Hasil Rendering Galeri Publik (Pratinjau Nyata)
                                    </h4>
                                </div>
                                <div class="flex items-center gap-3 font-mono text-[10px] text-white/40">
                                    <span class="inline-flex items-center gap-1.5 text-coral">
                                        <span class="size-1.5 rounded-full bg-coral shadow-[0_0_8px_rgba(239,68,68,0.8)]"></span>
                                        <span>Jarak Rapat (gap-2.5)</span>
                                    </span>
                                    <span>Breakpoint: Desktop &gt; 1024px</span>
                                </div>
                            </div>
                            <p class="text-xs text-white/50">
                                Klik langsung pada kartu untuk mengganti gambar. Susunan responsif dengan jarak rapat presisi sesuai diagram portofolio Anda.
                            </p>

                            {{-- Bento Grid Live Mockup --}}
                            <div class="rounded-2xl border border-white/10 bg-[#0d0d12] p-2.5 sm:p-3">
                                <div id="gallery-preview-grid" class="grid grid-cols-1 md:grid-cols-12 gap-2.5 sm:gap-3">
                                    {{-- Rendered dynamically --}}
                                </div>
                            </div>
                        </div>

                        {{-- Mode Teknis: Hidden JSON sync & expandable view --}}
                        <div class="rounded-2xl border border-white/5 bg-[#17171d] p-5 shadow-sm space-y-3">
                            <div class="flex items-center justify-between">
                                <button type="button" onclick="document.getElementById('gallery-raw-json-container').classList.toggle('hidden')"
                                    class="flex items-center gap-2 font-mono text-[11px] font-semibold tracking-wider text-white/60 hover:text-white uppercase transition">
                                    <x-icon name="code" class="size-3.5 text-white/40" />
                                    <span>Tampilkan Format Mentah JSON (Mode Teknis)</span>
                                </button>
                                <button type="button" onclick="formatGalleryRawJson()" class="font-mono text-[10px] text-coral hover:underline">
                                    Ratakan Format JSON
                                </button>
                            </div>
                            <div id="gallery-raw-json-container" class="hidden space-y-2 pt-2">
                                <textarea id="gallery-json-input" name="settings[images.gallery]" rows="8" oninput="syncFromGalleryRawJson()"
                                    class="w-full rounded-xl border border-white/10 bg-black/60 p-3 font-mono text-[11px] text-emerald-400 focus:border-coral focus:outline-none transition leading-relaxed">{{ old('settings.images.gallery', json_encode($currentGalleryList, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) }}</textarea>
                                <p class="font-mono text-[10px] text-white/40">
                                    Perubahan pada input visual otomatis tersimpan ke field JSON di atas untuk dikirim ke server.
                                </p>
                            </div>
                        </div>

                        {{-- Hidden File Input for Slot Image Replacement --}}
                        <input type="file" id="slot-file-picker" accept="image/*" class="hidden" onchange="handleSlotImageUpload(this)">
                    </div>

                    {{-- Bottom Action Bar (Sticky / Footer) --}}
                    <div class="rounded-2xl border border-white/5 bg-[#17171d] p-4 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-sm">
                        <button type="reset" onclick="window.location.reload()"
                            class="flex items-center gap-2 text-xs font-medium text-white/50 transition hover:text-white">
                            <x-icon name="refresh" class="size-3.5" />
                            <span>Reload / Batal</span>
                        </button>

                        <div class="flex items-center gap-3">
                            <button type="button" onclick="submitSettingsForm()"
                                class="rounded-xl border border-white/10 bg-white/[0.04] px-4 py-2.5 text-xs font-medium text-white/70 transition hover:bg-white/[0.08] hover:text-white">
                                Simpan Draf
                            </button>
                            <button type="button" onclick="submitSettingsForm()"
                                class="flex items-center gap-2 rounded-xl bg-coral px-5 py-2.5 text-xs font-semibold text-white shadow-lg shadow-coral/25 transition hover:bg-coral-dark">
                                <x-icon name="check" class="size-3.5" />
                                <span>Simpan Perubahan</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Interactive Live Preview & Tab Navigation Scripts --}}
    <script>
        function switchTab(groupKey) {
            document.getElementById('active_tab_input').value = groupKey;

            // Hide all panels
            document.querySelectorAll('.tab-content-panel').forEach(function(panel) {
                panel.classList.add('hidden');
            });

            // Show selected panel
            const activePanel = document.getElementById('tab-panel-' + groupKey);
            if (activePanel) {
                activePanel.classList.remove('hidden');
            }

            // Update tab button styles
            document.querySelectorAll('.tab-nav-btn').forEach(function(btn) {
                btn.classList.remove('bg-coral', 'text-white', 'shadow-md', 'shadow-coral/25');
                btn.classList.add('text-white/60', 'hover:bg-white/5', 'hover:text-white');
            });

            const activeBtn = document.getElementById('tab-btn-' + groupKey);
            if (activeBtn) {
                activeBtn.classList.remove('text-white/60', 'hover:bg-white/5', 'hover:text-white');
                activeBtn.classList.add('bg-coral', 'text-white', 'shadow-md', 'shadow-coral/25');
            }

            history.replaceState(null, null, '?tab=' + groupKey);
        }

        function updateCharCount(input, counterId) {
            const counter = document.getElementById(counterId);
            if (counter && input.maxLength > 0) {
                counter.textContent = input.value.length + '/' + input.maxLength;
            }

            // Live synchronization to preview card
            if (input.dataset.key === 'identity.name') {
                const p = document.getElementById('preview-name');
                if (p) p.textContent = input.value || 'Nama Anda';
            } else if (input.dataset.key === 'identity.role') {
                const p = document.getElementById('preview-role');
                if (p) p.textContent = input.value || 'Peran Profesional';
            } else if (input.dataset.key === 'identity.bio_summary') {
                const p = document.getElementById('preview-bio');
                if (p) p.textContent = input.value || 'Ringkasan bio...';
            }
        }

        function previewUploadedImage(input, targetImgId) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById(targetImgId);
                    if (img) img.src = e.target.result;

                    const previewAvatar = document.getElementById('preview-avatar');
                    if (previewAvatar) previewAvatar.src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function formatJsonText(elementId) {
            const el = document.getElementById(elementId);
            if (!el) return;
            try {
                const parsed = JSON.parse(el.value);
                el.value = JSON.stringify(parsed, null, 2);
            } catch (err) {
                alert('Format JSON tidak valid: ' + err.message);
            }
        }

        // ==========================================
        // LAYANAN & KEAHLIAN (SERVICES) MANAGER
        // ==========================================
        const serviceIcons = {
            'code': '<svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="m16 18 6-6-6-6"/><path d="m8 6-6 6 6 6"/></svg>',
            'pen': '<svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.4 3.6a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>',
            'devices': '<svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect width="14" height="10" x="2" y="5" rx="2"/><rect width="6" height="11" x="16" y="9" rx="1.5"/></svg>',
            'layout': '<svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>',
            'layers': '<svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>',
            'globe': '<svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg>',
            'sparkles': '<svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3-1.9 5.8a2 2 0 0 1-1.3 1.3L3 12l5.8 1.9a2 2 0 0 1 1.3 1.3L12 21l1.9-5.8a2 2 0 0 1 1.3-1.3L21 12l-5.8-1.9a2 2 0 0 1-1.3-1.3Z"/></svg>'
        };

        let servicesData = [];
        let draggedServiceIndex = null;

        function escapeHtml(str) {
            if (typeof str !== 'string') return '';
            return str
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function initServicesManager() {
            const jsonInput = document.getElementById('services-json-input');
            if (!jsonInput) return;

            try {
                const rawVal = jsonInput.value.trim();
                servicesData = rawVal ? JSON.parse(rawVal) : [];
            } catch (err) {
                console.warn('Gagal mem-parsing data awal layanan JSON:', err);
                servicesData = [];
            }

            if (!Array.isArray(servicesData) || servicesData.length === 0) {
                servicesData = [
                    {
                        icon: 'code',
                        title: 'Web Development',
                        items: [
                            'Pengembangan website dengan Laravel dan PHP.',
                            'Implementasi antarmuka dengan HTML, CSS, dan JavaScript.',
                            'Pengelolaan database MySQL.'
                        ]
                    },
                    {
                        icon: 'layout',
                        title: 'UI/UX Design',
                        items: [
                            'Wireframing dan prototyping Figma.',
                            'Perancangan layout responsif dan interaktif.'
                        ]
                    }
                ];
            }

            renderServicesEditor();
            renderServicesPreview();
            syncServicesToJson(false);
        }

        function renderServicesEditor() {
            const container = document.getElementById('services-items-container');
            if (!container) return;

            if (servicesData.length === 0) {
                container.innerHTML = `
                    <div class="rounded-xl border border-dashed border-white/10 p-8 text-center space-y-3">
                        <div class="mx-auto flex size-12 items-center justify-center rounded-xl bg-white/5 text-white/40">
                            ${serviceIcons['code'] || ''}
                        </div>
                        <p class="text-xs text-white/50">Belum ada item layanan yang ditambahkan.</p>
                        <button type="button" onclick="addNewServiceItem()" class="inline-flex items-center gap-1.5 rounded-xl bg-coral px-4 py-2 text-xs font-semibold text-white transition hover:bg-coral-dark">
                            + Tambah Layanan Pertama
                        </button>
                    </div>
                `;
                updateServicesCounts();
                return;
            }

            let html = '';
            servicesData.forEach((service, sIndex) => {
                const numBadge = '#' + String(sIndex + 1).padStart(2, '0');
                const titleVal = escapeHtml(service.title || '');
                const iconKey = service.icon || 'code';
                const items = Array.isArray(service.items) ? service.items : [];

                let pointsHtml = '';
                items.forEach((point, pIndex) => {
                    pointsHtml += `
                        <div class="flex items-center gap-2 group/pt">
                            <span class="size-1.5 rounded-full bg-coral shrink-0"></span>
                            <input type="text"
                                value="${escapeHtml(point)}"
                                oninput="updateServicePoint(${sIndex}, ${pIndex}, this.value)"
                                placeholder="Poin keahlian atau lingkup kerja..."
                                class="flex-1 rounded-xl border border-white/5 bg-[#17171d] px-3 py-2 text-xs text-white/90 placeholder:text-white/30 focus:border-coral focus:outline-none transition">
                            <button type="button"
                                onclick="deleteServicePoint(${sIndex}, ${pIndex})"
                                class="p-1.5 text-white/30 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition"
                                title="Hapus Poin">
                                <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                            </button>
                        </div>
                    `;
                });

                html += `
                    <div id="service-card-${sIndex}"
                        draggable="true"
                        ondragstart="onServiceDragStart(event, ${sIndex})"
                        ondragover="onServiceDragOver(event, ${sIndex})"
                        ondragleave="onServiceDragLeave(event, ${sIndex})"
                        ondrop="onServiceDrop(event, ${sIndex})"
                        ondragend="onServiceDragEnd(event)"
                        class="rounded-xl border border-white/10 bg-[#121217] p-4 space-y-4 transition duration-150 hover:border-white/20">

                        {{-- Card Header & Reordering Controls --}}
                        <div class="flex items-center justify-between pb-3 border-b border-white/5">
                            <div class="flex items-center gap-3 cursor-grab select-none" title="Tahan dan geser untuk mengatur urutan">
                                <span class="text-white/30 hover:text-white/60 transition">
                                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="9" cy="12" r="1"/><circle cx="9" cy="5" r="1"/><circle cx="9" cy="19" r="1"/>
                                        <circle cx="15" cy="12" r="1"/><circle cx="15" cy="5" r="1"/><circle cx="15" cy="19" r="1"/>
                                    </svg>
                                </span>
                                <span class="rounded bg-white/5 border border-white/10 px-2 py-0.5 font-mono text-[10px] text-white/60 font-bold">${numBadge}</span>
                                <span id="service-preview-title-${sIndex}" class="text-xs font-bold text-white truncate max-w-[200px] sm:max-w-md">${titleVal || 'Layanan Baru'}</span>
                            </div>

                            <div class="flex items-center gap-1">
                                <button type="button"
                                    onclick="moveServiceItem(${sIndex}, -1)"
                                    class="p-1 rounded-lg text-white/40 hover:text-white hover:bg-white/5 transition disabled:opacity-30 disabled:pointer-events-none"
                                    ${sIndex === 0 ? 'disabled' : ''}
                                    title="Pindahkan ke Atas">
                                    <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>
                                </button>
                                <button type="button"
                                    onclick="moveServiceItem(${sIndex}, 1)"
                                    class="p-1 rounded-lg text-white/40 hover:text-white hover:bg-white/5 transition disabled:opacity-30 disabled:pointer-events-none"
                                    ${sIndex === servicesData.length - 1 ? 'disabled' : ''}
                                    title="Pindahkan ke Bawah">
                                    <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                                </button>
                                <button type="button"
                                    onclick="deleteServiceItem(${sIndex})"
                                    class="p-1 rounded-lg text-white/40 hover:text-red-400 hover:bg-red-500/10 transition ml-1"
                                    title="Hapus Layanan Ini">
                                    <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                </button>
                            </div>
                        </div>

                        {{-- Judul Layanan --}}
                        <div class="space-y-1.5">
                            <label class="font-mono text-[10px] uppercase tracking-wider text-white/60">Judul Layanan</label>
                            <input type="text"
                                id="service-input-title-${sIndex}"
                                value="${titleVal}"
                                oninput="updateServiceTitle(${sIndex}, this.value)"
                                placeholder="Contoh: Web Development"
                                class="w-full rounded-xl border border-white/10 bg-[#17171d] px-3.5 py-2 text-xs text-white placeholder:text-white/30 focus:border-coral focus:outline-none transition">
                        </div>

                        {{-- Poin Keahlian / Lingkup Kerja --}}
                        <div class="space-y-2.5 pt-1">
                            <div class="flex items-center justify-between">
                                <span class="font-mono text-[10px] font-semibold text-white/60 tracking-wider uppercase">POIN KEAHLIAN / LINGKUP KERJA</span>
                                <button type="button"
                                    onclick="addServicePoint(${sIndex})"
                                    class="inline-flex items-center gap-1 text-[11px] font-semibold text-coral hover:text-coral-dark transition">
                                    <span>+ Tambah Poin</span>
                                </button>
                            </div>

                            <div class="space-y-2">
                                ${pointsHtml}
                            </div>
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;
            updateServicesCounts();
        }

        function renderServicesPreview() {
            const previewGrid = document.getElementById('services-visual-grid');
            if (!previewGrid) return;

            if (servicesData.length === 0) {
                previewGrid.innerHTML = `
                    <div class="col-span-full rounded-xl border border-white/5 bg-[#121217] p-6 text-center text-xs text-white/40">
                        Tidak ada layanan untuk ditampilkan.
                    </div>
                `;
                return;
            }

            let html = '';
            servicesData.forEach((service, index) => {
                const num = '#' + String(index + 1).padStart(2, '0');
                const title = escapeHtml(service.title || 'Layanan Tanpa Judul');
                const items = Array.isArray(service.items) ? service.items : [];

                let pointsList = '';
                items.forEach(pt => {
                    if (pt && pt.trim()) {
                        pointsList += `
                            <li class="flex items-start gap-2">
                                <span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-coral"></span>
                                <span class="text-white/70">${escapeHtml(pt)}</span>
                            </li>
                        `;
                    }
                });

                html += `
                    <div class="rounded-2xl border border-white/10 bg-[#121217] p-5 space-y-3.5 relative overflow-hidden transition hover:border-white/20">
                        <div class="flex items-center justify-end">
                            <span class="font-mono text-[10px] text-white/40">${num}</span>
                        </div>
                        <h4 class="text-sm font-bold tracking-tight text-white">${title}</h4>
                        <ul class="space-y-2 text-[11px] leading-relaxed">
                            ${pointsList || '<li class="text-white/30 italic">Belum ada poin keahlian.</li>'}
                        </ul>
                    </div>
                `;
            });

            previewGrid.innerHTML = html;
        }

        function updateServicesCounts() {
            const count = servicesData.length;
            const badge = document.getElementById('services-count-badge');
            if (badge) badge.textContent = `${count} Layanan Aktif`;

            const renderingCount = document.getElementById('services-rendering-count');
            if (renderingCount) renderingCount.textContent = `${count} Item terdaftar`;
        }

        function syncServicesToJson(updateTextarea = true) {
            const jsonInput = document.getElementById('services-json-input');
            const jsonStr = JSON.stringify(servicesData, null, 2);
            if (jsonInput) jsonInput.value = jsonStr;

            const rawTextarea = document.getElementById('raw-json-textarea');
            if (rawTextarea && updateTextarea) {
                rawTextarea.value = jsonStr;
            }
        }

        function updateServiceTitle(index, val) {
            if (servicesData[index]) {
                servicesData[index].title = val;
                const previewTitle = document.getElementById('service-preview-title-' + index);
                if (previewTitle) previewTitle.textContent = val || 'Layanan Baru';

                renderServicesPreview();
                syncServicesToJson(false);
            }
        }

        function updateServiceIcon(index, iconKey) {
            if (servicesData[index]) {
                servicesData[index].icon = iconKey;
                renderServicesPreview();
                syncServicesToJson(false);
            }
        }

        function updateServicePoint(serviceIndex, pointIndex, val) {
            if (servicesData[serviceIndex] && servicesData[serviceIndex].items) {
                servicesData[serviceIndex].items[pointIndex] = val;
                renderServicesPreview();
                syncServicesToJson(false);
            }
        }

        function addNewServiceItem() {
            servicesData.push({
                icon: 'code',
                title: 'Layanan Baru',
                items: [
                    'Poin keahlian pertama...'
                ]
            });
            renderServicesEditor();
            renderServicesPreview();
            syncServicesToJson();

            // Focus on newly added service title input
            const newIndex = servicesData.length - 1;
            const titleInput = document.getElementById('service-input-title-' + newIndex);
            if (titleInput) {
                titleInput.focus();
                titleInput.select();
            }
        }

        function deleteServiceItem(index) {
            if (confirm('Hapus layanan "' + (servicesData[index].title || 'ini') + '"?')) {
                servicesData.splice(index, 1);
                renderServicesEditor();
                renderServicesPreview();
                syncServicesToJson();
            }
        }

        function addServicePoint(serviceIndex) {
            if (servicesData[serviceIndex]) {
                if (!Array.isArray(servicesData[serviceIndex].items)) {
                    servicesData[serviceIndex].items = [];
                }
                servicesData[serviceIndex].items.push('');
                renderServicesEditor();
                renderServicesPreview();
                syncServicesToJson();
            }
        }

        function deleteServicePoint(serviceIndex, pointIndex) {
            if (servicesData[serviceIndex] && Array.isArray(servicesData[serviceIndex].items)) {
                servicesData[serviceIndex].items.splice(pointIndex, 1);
                renderServicesEditor();
                renderServicesPreview();
                syncServicesToJson();
            }
        }

        function moveServiceItem(index, direction) {
            const targetIndex = index + direction;
            if (targetIndex < 0 || targetIndex >= servicesData.length) return;

            const item = servicesData.splice(index, 1)[0];
            servicesData.splice(targetIndex, 0, item);

            renderServicesEditor();
            renderServicesPreview();
            syncServicesToJson();
        }

        // Drag and drop event handlers
        function onServiceDragStart(e, index) {
            draggedServiceIndex = index;
            e.dataTransfer.effectAllowed = 'move';
            e.target.classList.add('opacity-50');
        }

        function onServiceDragOver(e, index) {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
            const card = document.getElementById('service-card-' + index);
            if (card && draggedServiceIndex !== index) {
                card.classList.add('border-coral');
            }
        }

        function onServiceDragLeave(e, index) {
            const card = document.getElementById('service-card-' + index);
            if (card) {
                card.classList.remove('border-coral');
            }
        }

        function onServiceDrop(e, targetIndex) {
            e.preventDefault();
            const card = document.getElementById('service-card-' + targetIndex);
            if (card) card.classList.remove('border-coral');

            if (draggedServiceIndex !== null && draggedServiceIndex !== targetIndex) {
                const item = servicesData.splice(draggedServiceIndex, 1)[0];
                servicesData.splice(targetIndex, 0, item);
                renderServicesEditor();
                renderServicesPreview();
                syncServicesToJson();
            }
            draggedServiceIndex = null;
        }

        function onServiceDragEnd(e) {
            e.target.classList.remove('opacity-50');
            document.querySelectorAll('#services-items-container > div').forEach(c => c.classList.remove('border-coral'));
            draggedServiceIndex = null;
        }

        // Raw JSON drawer controls
        function toggleRawJsonMode() {
            const drawer = document.getElementById('raw-json-container');
            const toggleText = document.getElementById('raw-json-toggle-text');
            if (!drawer) return;

            if (drawer.classList.contains('hidden')) {
                drawer.classList.remove('hidden');
                document.getElementById('raw-json-textarea').value = JSON.stringify(servicesData, null, 2);
                if (toggleText) toggleText.textContent = 'Sembunyikan Mode Kode JSON';
            } else {
                drawer.classList.add('hidden');
                if (toggleText) toggleText.textContent = 'Mode Raw JSON (Lanjutan)';
            }
        }

        function syncFromRawJson() {
            const rawTextarea = document.getElementById('raw-json-textarea');
            if (!rawTextarea) return;

            try {
                const parsed = JSON.parse(rawTextarea.value);
                if (Array.isArray(parsed)) {
                    servicesData = parsed;
                    renderServicesEditor();
                    renderServicesPreview();
                    const jsonInput = document.getElementById('services-json-input');
                    if (jsonInput) jsonInput.value = rawTextarea.value;
                }
            } catch (e) {
                // Ignore while typing invalid JSON
            }
        }

        function formatServicesJson() {
            const rawTextarea = document.getElementById('raw-json-textarea');
            if (!rawTextarea) return;
            try {
                const parsed = JSON.parse(rawTextarea.value);
                rawTextarea.value = JSON.stringify(parsed, null, 2);
            } catch (err) {
                alert('Format JSON tidak valid: ' + err.message);
            }
        }

        // ==========================================
        // TEKNOLOGI & STACK MANAGER
        // ==========================================
        let techList = [];
        let currentTechFilter = 'all';
        let draggedTechIndex = null;

        const techRecommendations = ['Vue.js', 'TypeScript', 'Docker', 'PostgreSQL', 'Next.js', 'Python', 'Redis', 'Svelte'];

        function getTechCategory(name) {
            const lower = (name || '').toLowerCase().trim();
            if (['html', 'css', 'javascript', 'js', 'typescript', 'ts', 'react', 'vue', 'vue.js', 'next.js', 'next', 'tailwind css', 'tailwind', 'bootstrap', 'svelte', 'alpine.js', 'sass'].includes(lower)) {
                return 'frontend';
            }
            if (['php', 'laravel', 'mysql', 'postgresql', 'postgres', 'docker', 'python', 'node.js', 'nodejs', 'express', 'redis', 'mongodb', 'go', 'golang', 'java', 'spring', 'django'].includes(lower)) {
                return 'backend';
            }
            return 'design'; // figma, git, github, adobe, tools, etc.
        }

        function getTechDotColor(name) {
            const lower = (name || '').toLowerCase().trim();
            if (lower === 'html') return '#f59e0b'; // amber
            if (lower === 'css') return '#3b82f6'; // blue
            if (lower === 'javascript' || lower === 'js') return '#eab308'; // yellow
            if (lower === 'typescript' || lower === 'ts') return '#38bdf8'; // sky
            if (lower === 'php') return '#818cf8'; // indigo
            if (lower === 'laravel') return '#ef4444'; // red
            if (lower === 'tailwind css' || lower === 'tailwind') return '#06b6d4'; // teal
            if (lower === 'mysql') return '#60a5fa'; // blue
            if (lower === 'figma') return '#f43f5e'; // rose
            if (lower === 'git' || lower === 'github') return '#f97316'; // orange
            if (lower === 'react') return '#38bdf8'; // cyan
            if (lower === 'vue' || lower === 'vue.js') return '#10b981'; // emerald
            if (lower === 'docker') return '#0284c7'; // blue
            if (lower === 'next.js' || lower === 'next') return '#ffffff'; // white
            if (lower === 'postgresql' || lower === 'postgres') return '#3b82f6'; // blue
            if (lower === 'python') return '#facc15'; // yellow
            if (lower === 'redis') return '#dc2626'; // red
            if (lower === 'svelte') return '#ff3e00'; // orange
            return '#cbd5e1';
        }

        function initTechManager() {
            const jsonInput = document.getElementById('tech-json-input');
            if (!jsonInput) return;

            try {
                const rawVal = jsonInput.value.trim();
                techList = rawVal ? JSON.parse(rawVal) : [];
            } catch (err) {
                console.warn('Gagal mem-parsing data awal teknologi:', err);
                techList = [];
            }

            if (!Array.isArray(techList) || techList.length === 0) {
                techList = ['HTML', 'CSS', 'JavaScript', 'PHP', 'Laravel', 'Tailwind CSS', 'MySQL', 'Figma', 'Git', 'React'];
            }

            renderTechChips();
            renderTechMarquee();
            renderTechRecommendations();
            updateTechCounts();
            syncTechToJson(false);
        }

        function renderTechChips() {
            const container = document.getElementById('tech-chips-container');
            if (!container) return;

            let html = '';
            techList.forEach((tech, index) => {
                const category = getTechCategory(tech);
                // Filter check
                if (currentTechFilter !== 'all' && category !== currentTechFilter) {
                    return;
                }

                const dotColor = getTechDotColor(tech);
                html += `
                    <div id="tech-chip-${index}"
                        draggable="true"
                        ondragstart="onTechDragStart(event, ${index})"
                        ondragover="onTechDragOver(event, ${index})"
                        ondragleave="onTechDragLeave(event, ${index})"
                        ondrop="onTechDrop(event, ${index})"
                        ondragend="onTechDragEnd(event)"
                        class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-[#121217] px-3.5 py-1.5 text-xs text-white/90 transition hover:border-white/20 select-none cursor-grab active:cursor-grabbing">
                        <span class="size-2 rounded-full shrink-0" style="background-color: ${dotColor}; box-shadow: 0 0 6px ${dotColor}80;"></span>
                        <span class="font-medium">${escapeHtml(tech)}</span>
                        <button type="button" onclick="removeTechTag(${index})" class="text-white/30 hover:text-red-400 transition ml-0.5" title="Hapus ${escapeHtml(tech)}">
                            ✕
                        </button>
                    </div>
                `;
            });

            if (html === '') {
                container.innerHTML = `<span class="text-xs text-white/40 italic py-2">Tidak ada teknologi di kategori ini.</span>`;
            } else {
                container.innerHTML = html;
            }
        }

        function renderTechMarquee() {
            const track1 = document.getElementById('tech-marquee-content-1');
            const track2 = document.getElementById('tech-marquee-content-2');
            if (!track1 || !track2) return;

            if (techList.length === 0) {
                track1.innerHTML = `<span class="text-xs text-white/40 italic">Belum ada teknologi untuk ditampilkan di marquee.</span>`;
                track2.innerHTML = '';
                return;
            }

            let itemsHtml = '';
            techList.forEach(tech => {
                const dotColor = getTechDotColor(tech);
                itemsHtml += `
                    <div class="flex items-center gap-4">
                        <span class="size-2 rounded-full" style="background-color: ${dotColor}; box-shadow: 0 0 8px ${dotColor}90;"></span>
                        <span class="text-sm font-bold text-white tracking-wide">${escapeHtml(tech)}</span>
                    </div>
                `;
            });

            track1.innerHTML = itemsHtml;
            track2.innerHTML = itemsHtml;
        }

        function renderTechRecommendations() {
            const container = document.getElementById('tech-quick-recommendations');
            if (!container) return;

            let html = '';
            techRecommendations.forEach(rec => {
                const exists = techList.some(t => t.toLowerCase() === rec.toLowerCase());
                if (!exists) {
                    html += `
                        <button type="button" onclick="addQuickTech('${rec}')"
                            class="inline-flex items-center gap-1.5 rounded-full border border-white/10 bg-white/[0.03] px-3 py-1 text-xs font-medium text-white/70 hover:border-coral/50 hover:bg-coral/10 hover:text-coral transition">
                            <span class="text-coral font-bold">+</span>
                            <span>${escapeHtml(rec)}</span>
                        </button>
                    `;
                }
            });

            if (html === '') {
                container.innerHTML = `<span class="text-xs text-white/40 italic">Semua rekomendasi telah ditambahkan.</span>`;
            } else {
                container.innerHTML = html;
            }
        }

        function updateTechCounts() {
            const total = techList.length;
            const badge = document.getElementById('tech-active-count-badge');
            if (badge) badge.textContent = `${total} Tag Aktif`;

            let feCount = 0;
            let beCount = 0;
            let designCount = 0;

            techList.forEach(t => {
                const cat = getTechCategory(t);
                if (cat === 'frontend') feCount++;
                else if (cat === 'backend') beCount++;
                else designCount++;
            });

            const cntAll = document.getElementById('filter-count-all');
            if (cntAll) cntAll.textContent = total;
            const cntFe = document.getElementById('filter-count-frontend');
            if (cntFe) cntFe.textContent = feCount;
            const cntBe = document.getElementById('filter-count-backend');
            if (cntBe) cntBe.textContent = beCount;
            const cntDe = document.getElementById('filter-count-design');
            if (cntDe) cntDe.textContent = designCount;
        }

        function filterTechCategory(cat) {
            currentTechFilter = cat;

            // Update button styles
            document.querySelectorAll('.tech-filter-btn').forEach(btn => {
                btn.classList.remove('bg-coral', 'text-white', 'shadow-md', 'shadow-coral/20');
                btn.classList.add('bg-white/5', 'text-white/70', 'hover:bg-white/10', 'hover:text-white');
            });

            const activeBtn = document.getElementById('filter-tech-' + cat);
            if (activeBtn) {
                activeBtn.classList.remove('bg-white/5', 'text-white/70', 'hover:bg-white/10', 'hover:text-white');
                activeBtn.classList.add('bg-coral', 'text-white', 'shadow-md', 'shadow-coral/20');
            }

            renderTechChips();
        }

        function addNewTechTag() {
            const input = document.getElementById('new-tech-input');
            if (!input) return;

            const val = input.value.trim();
            if (!val) return;

            // Check if already exists
            const exists = techList.some(t => t.toLowerCase() === val.toLowerCase());
            if (exists) {
                alert(`Teknologi "${val}" sudah ada dalam daftar.`);
                return;
            }

            techList.push(val);
            input.value = '';
            input.focus();

            renderTechChips();
            renderTechMarquee();
            renderTechRecommendations();
            updateTechCounts();
            syncTechToJson();
        }

        function handleNewTechKeydown(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addNewTechTag();
            }
        }

        function addQuickTech(name) {
            if (!techList.some(t => t.toLowerCase() === name.toLowerCase())) {
                techList.push(name);
                renderTechChips();
                renderTechMarquee();
                renderTechRecommendations();
                updateTechCounts();
                syncTechToJson();
            }
        }

        function removeTechTag(index) {
            techList.splice(index, 1);
            renderTechChips();
            renderTechMarquee();
            renderTechRecommendations();
            updateTechCounts();
            syncTechToJson();
        }

        // Drag and Drop for Tech Chips
        function onTechDragStart(e, index) {
            draggedTechIndex = index;
            e.dataTransfer.effectAllowed = 'move';
            e.target.classList.add('opacity-40');
        }

        function onTechDragOver(e, index) {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
            const chip = document.getElementById('tech-chip-' + index);
            if (chip && draggedTechIndex !== index) {
                chip.classList.add('border-coral');
            }
        }

        function onTechDragLeave(e, index) {
            const chip = document.getElementById('tech-chip-' + index);
            if (chip) chip.classList.remove('border-coral');
        }

        function onTechDrop(e, targetIndex) {
            e.preventDefault();
            const chip = document.getElementById('tech-chip-' + targetIndex);
            if (chip) chip.classList.remove('border-coral');

            if (draggedTechIndex !== null && draggedTechIndex !== targetIndex) {
                const item = techList.splice(draggedTechIndex, 1)[0];
                techList.splice(targetIndex, 0, item);
                renderTechChips();
                renderTechMarquee();
                syncTechToJson();
            }
            draggedTechIndex = null;
        }

        function onTechDragEnd(e) {
            e.target.classList.remove('opacity-40');
            document.querySelectorAll('#tech-chips-container > div').forEach(c => c.classList.remove('border-coral'));
            draggedTechIndex = null;
        }

        function syncTechToJson(updateTextarea = true) {
            const jsonInput = document.getElementById('tech-json-input');
            const jsonStr = JSON.stringify(techList, null, 2);
            if (jsonInput) jsonInput.value = jsonStr;

            const rawTextarea = document.getElementById('tech-raw-json-textarea');
            if (rawTextarea && updateTextarea) {
                rawTextarea.value = jsonStr;
            }
        }

        function toggleTechRawJsonMode() {
            const drawer = document.getElementById('tech-raw-json-container');
            const toggleText = document.getElementById('tech-raw-json-toggle-text');
            if (!drawer) return;

            if (drawer.classList.contains('hidden')) {
                drawer.classList.remove('hidden');
                document.getElementById('tech-raw-json-textarea').value = JSON.stringify(techList, null, 2);
                if (toggleText) toggleText.textContent = 'Sembunyikan Mode Kode JSON';
            } else {
                drawer.classList.add('hidden');
                if (toggleText) toggleText.textContent = 'Mode Raw JSON (Lanjutan)';
            }
        }

        function syncFromTechRawJson() {
            const rawTextarea = document.getElementById('tech-raw-json-textarea');
            if (!rawTextarea) return;

            try {
                const parsed = JSON.parse(rawTextarea.value);
                if (Array.isArray(parsed)) {
                    techList = parsed.filter(t => typeof t === 'string' && t.trim() !== '');
                    renderTechChips();
                    renderTechMarquee();
                    renderTechRecommendations();
                    updateTechCounts();
                    const jsonInput = document.getElementById('tech-json-input');
                    if (jsonInput) jsonInput.value = rawTextarea.value;
                }
            } catch (e) {
                // Ignore while typing
            }
        }

        function formatTechJson() {
            const rawTextarea = document.getElementById('tech-raw-json-textarea');
            if (!rawTextarea) return;
            try {
                const parsed = JSON.parse(rawTextarea.value);
                rawTextarea.value = JSON.stringify(parsed, null, 2);
            } catch (err) {
                alert('Format JSON tidak valid: ' + err.message);
            }
        }

        // ==========================================
        // FOOTER LINKS LIVE PREVIEW
        // ==========================================
        function updateFooterPreview() {
            const email = (document.getElementById('contact-email-input')?.value || '').trim();
            const github = (document.getElementById('contact-github-input')?.value || '').trim();
            const linkedin = (document.getElementById('contact-linkedin-input')?.value || '').trim();
            const instagram = (document.getElementById('contact-instagram-input')?.value || '').trim();
            const tiktok = (document.getElementById('contact-tiktok-input')?.value || '').trim();

            const links = [
                { id: 'footer-btn-email', val: email },
                { id: 'footer-btn-github', val: github },
                { id: 'footer-btn-linkedin', val: linkedin },
                { id: 'footer-btn-instagram', val: instagram },
                { id: 'footer-btn-tiktok', val: tiktok },
            ];

            let activeCount = 0;
            links.forEach(item => {
                const el = document.getElementById(item.id);
                if (!el) return;
                const dot = el.querySelector('.footer-btn-dot');
                if (item.val) {
                    activeCount++;
                    el.classList.remove('opacity-40');
                    if (dot) {
                        dot.classList.remove('bg-white/20');
                        dot.classList.add('bg-coral');
                    }
                } else {
                    el.classList.add('opacity-40');
                    if (dot) {
                        dot.classList.remove('bg-coral');
                        dot.classList.add('bg-white/20');
                    }
                }
            });

            const countEl = document.getElementById('footer-links-count');
            if (countEl) {
                countEl.textContent = `${activeCount} Ikon Terhubung`;
            }
        }

        // ==========================================
        // GALLERY VISUAL MANAGER (BENTO GRID)
        // ==========================================
        let galleryData = @json($currentGalleryList);
        let activeSlotForUpload = null;
        let draggedGalleryIndex = null;
        let isUploadingGallery = false;

        function escapeHtml(str) {
            if (!str) return '';
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;');
        }

        const layoutPresets = {
            'md:col-span-7 aspect-[16/10]': { name: 'Slot Utama (Bento Lebar)', meta: '7 Kolom / Rasio 16:10', label: 'Lebar Asimetris (7 Kolom - 16:10)', previewClass: 'col-span-12 md:col-span-7 aspect-[16/10]' },
            'md:col-span-5 aspect-[4/5] md:aspect-auto': { name: 'Slot Vertikal Kanan (Bento Tinggi)', meta: '5 Kolom / Rasio 4:5', label: 'Vertikal Tinggi (5 Kolom - 4:5)', previewClass: 'col-span-12 md:col-span-5 aspect-[4/5]' },
            'md:col-span-5 aspect-[4/5]': { name: 'Slot Vertikal Kanan (Bento Tinggi)', meta: '5 Kolom / Rasio 4:5', label: 'Vertikal Tinggi (5 Kolom - 4:5)', previewClass: 'col-span-12 md:col-span-5 aspect-[4/5]' },
            'md:col-span-4 aspect-[4/3]': { name: 'Slot Bawah Kiri (Kompak)', meta: '4 Kolom / Rasio 4:3', label: 'Kecil Kompak (4 Kolom - 4:3)', previewClass: 'col-span-12 md:col-span-4 aspect-[4/3]' },
            'md:col-span-4 aspect-square': { name: 'Slot Kotak Presisi', meta: '4 Kolom / Rasio 1:1', label: 'Kotak Presisi (4 Kolom - 1:1)', previewClass: 'col-span-12 md:col-span-4 aspect-square' },
            'md:col-span-4 aspect-[1/1]': { name: 'Slot Kotak Presisi', meta: '4 Kolom / Rasio 1:1', label: 'Kotak Presisi (4 Kolom - 1:1)', previewClass: 'col-span-12 md:col-span-4 aspect-square' },
            'md:col-span-8 aspect-[16/9] md:aspect-[16/8]': { name: 'Slot Bawah Kanan (Bento Sedang)', meta: '8 Kolom / Rasio 16:9', label: 'Panjang Sedang (8 Kolom - 16:9)', previewClass: 'col-span-12 md:col-span-8 aspect-[16/9]' },
            'md:col-span-8 aspect-[16/9]': { name: 'Slot Bawah Kanan (Bento Sedang)', meta: '8 Kolom / Rasio 16:9', label: 'Panjang Sedang (8 Kolom - 16:9)', previewClass: 'col-span-12 md:col-span-8 aspect-[16/9]' },
            'md:col-span-8 aspect-[21/9]': { name: 'Slot Ultrawide Cinema', meta: '8 Kolom / Rasio 21:9', label: 'Ultrawide Cinema (8 Kolom - 21:9)', previewClass: 'col-span-12 md:col-span-8 aspect-[21/9]' },
            'md:col-span-6 aspect-video': { name: 'Slot Standar Seimbang', meta: '6 Kolom / Rasio 16:9', label: 'Standar Seimbang (6 Kolom - 16:9)', previewClass: 'col-span-12 md:col-span-6 aspect-video' },
            'md:col-span-6 aspect-[4/3]': { name: 'Slot Kompak Seimbang', meta: '6 Kolom / Rasio 4:3', label: 'Kompak Seimbang (6 Kolom - 4:3)', previewClass: 'col-span-12 md:col-span-6 aspect-[4/3]' },
            'md:col-span-3 aspect-square': { name: 'Slot Mini Widget', meta: '3 Kolom / Rasio 1:1', label: 'Mini Widget (3 Kolom - 1:1)', previewClass: 'col-span-12 md:col-span-3 aspect-square' },
            'md:col-span-9 aspect-[16/9]': { name: 'Slot Major Spotlight', meta: '9 Kolom / Rasio 16:9', label: 'Major Spotlight (9 Kolom - 16:9)', previewClass: 'col-span-12 md:col-span-9 aspect-[16/9]' },
            'md:col-span-12 aspect-[21/9]': { name: 'Slot Fullwidth Panorama', meta: '12 Kolom / Rasio 21:9', label: 'Fullwidth Panorama (12 Kolom - 21:9)', previewClass: 'col-span-12 aspect-[21/9]' },
            'md:col-span-12 aspect-video': { name: 'Slot Fullwidth Banner', meta: '12 Kolom / Rasio 16:9', label: 'Fullwidth Banner (12 Kolom - 16:9)', previewClass: 'col-span-12 aspect-video' },
        };

        function getLayoutInfo(cls) {
            return layoutPresets[cls] || {
                name: 'Slot Kustom',
                meta: 'Layout Kustom',
                label: 'Kustom (' + cls + ')',
                previewClass: cls || 'col-span-12 md:col-span-6 aspect-video'
            };
        }

        function initGalleryManager() {
            renderGallerySlots();
            renderGalleryPreview();
            setupGalleryDropzone();
            syncGalleryToJson();

            // Safety listener on global mouseup to ensure draggable is reset
            window.addEventListener('mouseup', () => {
                document.querySelectorAll('.slot-card').forEach(c => {
                    c.draggable = false;
                });
            });

            // Auto sync before submit on form
            const form = document.getElementById('settings-form');
            if (form) {
                form.addEventListener('submit', () => {
                    syncGalleryToJson();
                });
            }
        }

        function renderGallerySlots() {
            const listEl = document.getElementById('gallery-slots-list');
            if (!listEl) return;
            listEl.innerHTML = '';

            const installedCountEl = document.getElementById('gallery-installed-count');
            if (installedCountEl) installedCountEl.textContent = `${galleryData.length} Foto Terpasang`;

            const slotsHeaderEl = document.getElementById('slots-count-header');
            if (slotsHeaderEl) slotsHeaderEl.textContent = `${galleryData.length} ITEM`;

            const tabCounter = document.getElementById('tab-counter-gallery');
            if (tabCounter) tabCounter.textContent = galleryData.length;

            galleryData.forEach((item, index) => {
                const info = getLayoutInfo(item.class);
                const numStr = String(index + 1).padStart(2, '0');
                const isVisible = item.visible !== false;
                const isLight = item.theme === 'light' || (!item.theme && index === 1);
                const rawSrc = item.src || '';
                const fileName = rawSrc ? rawSrc.split('/').pop() : 'Belum ada berkas';
                const imgSrc = rawSrc ? (rawSrc.startsWith('http') || rawSrc.startsWith('/') ? rawSrc : '/' + rawSrc) : '/images/project-placeholder.svg';

                const card = document.createElement('div');
                card.className = 'slot-card rounded-2xl border border-white/10 bg-[#17171d] p-3.5 sm:p-4 space-y-3.5 hover:border-white/20 transition';
                card.draggable = false;
                card.dataset.index = index;

                // Drag event listeners
                card.addEventListener('dragstart', (e) => {
                    draggedGalleryIndex = index;
                    card.classList.add('opacity-40');
                });
                card.addEventListener('dragend', () => {
                    card.draggable = false;
                    card.classList.remove('opacity-40');
                });
                card.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    card.classList.add('border-coral');
                });
                card.addEventListener('dragleave', () => {
                    card.classList.remove('border-coral');
                });
                card.addEventListener('drop', (e) => {
                    e.preventDefault();
                    card.classList.remove('border-coral');
                    if (draggedGalleryIndex !== null && draggedGalleryIndex !== index) {
                        const movedItem = galleryData.splice(draggedGalleryIndex, 1)[0];
                        galleryData.splice(index, 0, movedItem);
                        renderGallerySlots();
                        renderGalleryPreview();
                        syncGalleryToJson();
                    }
                });

                card.innerHTML = `
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 border-b border-white/5 pb-2.5">
                        <div class="flex items-center gap-2.5">
                            <span class="slot-drag-handle cursor-grab active:cursor-grabbing text-white/30 hover:text-white p-1 rounded hover:bg-white/5 transition" title="Tekan &amp; seret untuk memindahkan urutan slot">
                                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="9" cy="12" r="1"/><circle cx="9" cy="5" r="1"/><circle cx="9" cy="19" r="1"/><circle cx="15" cy="12" r="1"/><circle cx="15" cy="5" r="1"/><circle cx="15" cy="19" r="1"/></svg>
                            </span>
                            <span class="rounded bg-white/5 border border-white/10 px-2 py-0.5 font-mono text-[10px] font-bold text-coral">
                                ${numStr}
                            </span>
                            <span class="font-semibold text-xs text-white">
                                ${info.name}
                            </span>
                            <span class="rounded px-2 py-0.5 font-mono text-[9px] ${isLight ? 'bg-amber-400/10 text-amber-300 border border-amber-400/20' : 'bg-white/5 text-white/50 border border-white/5'}">
                                ${isLight ? 'Aksen Terang' : 'Dark Matrix'}
                            </span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="font-mono text-[10px] text-white/40 hidden sm:inline-block">
                                ${info.meta}
                            </span>
                            <label class="flex items-center gap-2 cursor-pointer font-mono text-[11px] text-white/70 hover:text-white">
                                <input type="checkbox" ${isVisible ? 'checked' : ''} onchange="toggleGalleryVisibility(${index}, this.checked)"
                                    class="rounded border-white/20 bg-white/5 text-coral focus:ring-0 focus:ring-offset-0 size-3.5">
                                <span>Tampil di Web</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row gap-4 items-start">
                        {{-- Thumbnail Preview Box --}}
                        <div class="relative w-full md:w-48 h-32 shrink-0 rounded-xl overflow-hidden border border-white/10 ${isLight ? 'bg-[#d6d3cb]' : 'bg-[#121217]'} group cursor-pointer" onclick="triggerSlotImageUpload(${index})" title="Klik untuk mengganti / upload foto">
                            <img src="${imgSrc}" alt="${escapeHtml(item.alt || '')}" onerror="this.src='/images/project-placeholder.svg'"
                                class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition flex flex-col items-center justify-center gap-1.5 p-2">
                                <span class="rounded-lg bg-coral text-white font-mono text-[10px] font-bold px-2.5 py-1 shadow-md">
                                    Ganti Foto
                                </span>
                                <span class="font-mono text-[9px] text-white/70 text-center">Klik untuk telusuri</span>
                            </div>
                            <div class="slot-filename-badge absolute bottom-1.5 left-1.5 right-1.5 rounded bg-black/80 backdrop-blur-sm border border-white/10 px-2 py-0.5 font-mono text-[9px] text-white/70 truncate">
                                ${fileName}
                            </div>
                        </div>

                        {{-- Editable Fields --}}
                        <div class="flex-1 w-full space-y-3">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                {{-- Field 1: Image Path / URL --}}
                                <div class="space-y-1">
                                    <div class="flex items-center justify-between">
                                        <label class="font-mono text-[10px] font-semibold tracking-wider text-white/60 uppercase">
                                            File / URL Gambar
                                        </label>
                                        <span class="font-mono text-[9px] text-white/40">bisa ketik path / URL</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <input type="text" value="${escapeHtml(rawSrc)}" oninput="updateGallerySrc(${index}, this.value)"
                                            placeholder="Contoh: images/gallery-1.svg"
                                            class="flex-1 rounded-xl border border-white/10 bg-[#121217] py-2 px-3 font-mono text-xs text-white placeholder:text-white/30 focus:border-coral focus:outline-none transition">
                                        <button type="button" onclick="triggerSlotImageUpload(${index})"
                                            class="shrink-0 flex items-center gap-1 rounded-xl border border-white/10 bg-white/[0.04] px-2.5 py-2 text-xs font-medium text-white/80 hover:bg-white/[0.08] hover:text-white transition" title="Upload berkas gambar dari komputer">
                                            <svg class="size-3.5 text-coral" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                            <span class="hidden sm:inline">Upload</span>
                                        </button>
                                    </div>
                                </div>

                                {{-- Field 2: Slot Title / Caption --}}
                                <div class="space-y-1">
                                    <label class="block font-mono text-[10px] font-semibold tracking-wider text-white/60 uppercase">
                                        Judul Slot / Caption
                                    </label>
                                    <input type="text" value="${escapeHtml(item.title || '')}" oninput="updateGalleryTitle(${index}, this.value)"
                                        placeholder="Contoh: Dokumentasi Proyek Utama"
                                        class="w-full rounded-xl border border-white/10 bg-[#121217] py-2 px-3 text-xs text-white placeholder:text-white/30 focus:border-coral focus:outline-none transition">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                {{-- Field 3: Alt Text --}}
                                <div class="space-y-1">
                                    <label class="block font-mono text-[10px] font-semibold tracking-wider text-white/60 uppercase">
                                        Teks Aksesibilitas (Alt Text)
                                    </label>
                                    <input type="text" value="${escapeHtml(item.alt || '')}" oninput="updateGalleryAlt(${index}, this.value)"
                                        placeholder="Deskripsi foto untuk pembaca layar..."
                                        class="w-full rounded-xl border border-white/10 bg-[#121217] py-2 px-3 text-xs text-white placeholder:text-white/30 focus:border-coral focus:outline-none transition">
                                </div>

                                {{-- Field 4: Card Theme --}}
                                <div class="space-y-1">
                                    <label class="block font-mono text-[10px] font-semibold tracking-wider text-white/60 uppercase">
                                        Tema Visual Kartu
                                    </label>
                                    <select onchange="updateGalleryTheme(${index}, this.value)"
                                        class="w-full rounded-xl border border-white/10 bg-[#121217] py-2 px-3 text-xs text-white focus:border-coral focus:outline-none transition">
                                        <option value="dark" ${!isLight ? 'selected' : ''}>Dark Matrix (Gelap Bawaan)</option>
                                        <option value="light" ${isLight ? 'selected' : ''}>Light Accent (Terang / Aksen Gambar 2)</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Field 5: Layout Grid --}}
                            <div class="space-y-1">
                                <label class="block font-mono text-[10px] font-semibold tracking-wider text-white/60 uppercase">
                                    Posisi Layout Grid (Bervariasi)
                                </label>
                                <select onchange="updateGalleryLayout(${index}, this.value)"
                                    class="w-full rounded-xl border border-white/10 bg-[#121217] py-2 px-3 text-xs text-white focus:border-coral focus:outline-none transition">
                                    <option value="md:col-span-7 aspect-[16/10]" ${item.class && item.class.includes('col-span-7') ? 'selected' : ''}>Lebar Asimetris (7 Kolom - 16:10)</option>
                                    <option value="md:col-span-5 aspect-[4/5] md:aspect-auto" ${item.class && item.class.includes('col-span-5') ? 'selected' : ''}>Vertikal Tinggi (5 Kolom - 4:5)</option>
                                    <option value="md:col-span-4 aspect-[4/3]" ${item.class && item.class.includes('col-span-4') && !item.class.includes('square') && !item.class.includes('1/1') ? 'selected' : ''}>Kecil Kompak (4 Kolom - 4:3)</option>
                                    <option value="md:col-span-4 aspect-square" ${item.class && item.class.includes('col-span-4') && (item.class.includes('square') || item.class.includes('1/1')) ? 'selected' : ''}>Kotak Presisi (4 Kolom - 1:1)</option>
                                    <option value="md:col-span-8 aspect-[16/9] md:aspect-[16/8]" ${item.class && item.class.includes('col-span-8') && !item.class.includes('21/9') ? 'selected' : ''}>Panjang Sedang (8 Kolom - 16:9)</option>
                                    <option value="md:col-span-8 aspect-[21/9]" ${item.class && item.class.includes('col-span-8') && item.class.includes('21/9') ? 'selected' : ''}>Ultrawide Cinema (8 Kolom - 21:9)</option>
                                    <option value="md:col-span-6 aspect-video" ${item.class && item.class.includes('col-span-6') && item.class.includes('video') ? 'selected' : ''}>Standar Seimbang (6 Kolom - 16:9)</option>
                                    <option value="md:col-span-6 aspect-[4/3]" ${item.class && item.class.includes('col-span-6') && item.class.includes('4/3') ? 'selected' : ''}>Kompak Seimbang (6 Kolom - 4:3)</option>
                                    <option value="md:col-span-3 aspect-square" ${item.class && item.class.includes('col-span-3') ? 'selected' : ''}>Mini Widget (3 Kolom - 1:1)</option>
                                    <option value="md:col-span-9 aspect-[16/9]" ${item.class && item.class.includes('col-span-9') ? 'selected' : ''}>Major Spotlight (9 Kolom - 16:9)</option>
                                    <option value="md:col-span-12 aspect-[21/9]" ${item.class && item.class.includes('col-span-12') && item.class.includes('21/9') ? 'selected' : ''}>Fullwidth Panorama (12 Kolom - 21:9)</option>
                                    <option value="md:col-span-12 aspect-video" ${item.class && item.class.includes('col-span-12') && item.class.includes('video') ? 'selected' : ''}>Fullwidth Banner (12 Kolom - 16:9)</option>
                                </select>
                            </div>

                            {{-- Actions Footer --}}
                            <div class="flex items-center justify-between pt-1 border-t border-white/5">
                                <div class="flex items-center gap-2">
                                    <button type="button" onclick="triggerSlotImageUpload(${index})"
                                        class="flex items-center gap-1.5 rounded-lg border border-white/10 bg-white/[0.04] px-3 py-1.5 text-xs font-medium text-white/80 hover:bg-white/[0.08] hover:text-white transition">
                                        <svg class="size-3.5 text-coral" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect width="18" height="18" x="3" y="3" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                                        <span>Ganti / Unggah Berkas</span>
                                    </button>
                                    <button type="button" onclick="resetSlotToPlaceholder(${index})"
                                        class="flex items-center gap-1 rounded-lg border border-white/10 bg-white/[0.02] px-2.5 py-1.5 text-xs font-medium text-white/50 hover:bg-white/[0.05] hover:text-white transition" title="Kembalikan ke ilustrasi bawaan">
                                        <span>Reset ke Default</span>
                                    </button>
                                </div>
                                <button type="button" onclick="deleteGallerySlot(${index})"
                                    class="flex items-center gap-1 rounded-lg border border-white/10 bg-white/[0.04] px-2.5 py-1.5 text-xs font-medium text-white/40 hover:text-coral hover:border-coral/30 hover:bg-coral/10 transition" title="Hapus Slot">
                                    <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                    <span>Hapus</span>
                                </button>
                            </div>
                        </div>
                    </div>
                `;

                // Handle drag enablement via grab handle only
                const dragHandle = card.querySelector('.slot-drag-handle');
                if (dragHandle) {
                    dragHandle.addEventListener('mousedown', () => {
                        card.draggable = true;
                    });
                }

                listEl.appendChild(card);
            });
        }

        function renderGalleryPreview() {
            const previewEl = document.getElementById('gallery-preview-grid');
            if (!previewEl) return;
            previewEl.innerHTML = '';

            const defaultTitles = [
                'Dokumentasi Proyek Utama',
                'Perancangan UI/UX',
                'Arsitektur Kode',
                'Review Multi-Device'
            ];

            galleryData.forEach((item, index) => {
                if (item.visible === false) return;

                const info = getLayoutInfo(item.class);
                const titleText = item.title || defaultTitles[index % 4] || item.alt || 'Dokumentasi Proyek';
                const imgSrc = item.src ? (item.src.startsWith('http') || item.src.startsWith('/') ? item.src : '/' + item.src) : '';
                const isLight = item.theme === 'light' || (!item.theme && index === 1);

                const card = document.createElement('div');
                card.className = `group relative overflow-hidden rounded-2xl border transition-all duration-300 hover:scale-[1.01] hover:shadow-xl cursor-pointer ${info.previewClass} ${
                    isLight 
                        ? 'bg-[#d6d3cb] text-[#1c1c22] border-black/15 shadow-sm' 
                        : 'bg-[#181820] text-white border-white/10'
                }`;

                // Architectural blueprint grid styling
                const gridColor = isLight ? 'rgba(0,0,0,0.06)' : 'rgba(255,255,255,0.04)';
                card.style.backgroundImage = `linear-gradient(to right, ${gridColor} 1px, transparent 1px), linear-gradient(to bottom, ${gridColor} 1px, transparent 1px)`;
                card.style.backgroundSize = '22px 22px';

                card.innerHTML = `
                    <!-- Background image layer with smooth reveal on hover -->
                    <div class="absolute inset-0 z-0 overflow-hidden">
                        <img src="${imgSrc}" alt="${escapeHtml(item.alt || '')}" onerror="this.style.display='none'"
                            class="h-full w-full object-cover opacity-40 group-hover:opacity-75 transition duration-500 group-hover:scale-105">
                    </div>

                    <!-- Overlay tint -->
                    <div class="absolute inset-0 z-10 ${
                        isLight ? 'bg-[#d6d3cb]/70 group-hover:bg-[#d6d3cb]/40' : 'bg-[#15151e]/65 group-hover:bg-[#15151e]/30'
                    } transition duration-300"></div>

                    <!-- Red circle action button top-right (matches user diagram) -->
                    <div onclick="event.stopPropagation(); triggerSlotImageUpload(${index})"
                        class="absolute top-3.5 right-3.5 z-20 size-8 sm:size-9 rounded-full bg-coral shadow-lg shadow-coral/30 flex items-center justify-center text-white transition duration-300 group-hover:scale-110 hover:brightness-110"
                        title="Klik untuk ganti foto">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M7 17 17 7"/><path d="M8 7h9v9"/></svg>
                    </div>

                    <!-- Centered interactive action: GALERI N — GANTI GAMBAR -->
                    <div class="relative z-20 h-full w-full flex flex-col items-center justify-center p-4 text-center select-none" onclick="triggerSlotImageUpload(${index})">
                        <span class="inline-flex items-center gap-2 rounded-xl font-mono text-[10px] sm:text-[11px] font-bold tracking-wider uppercase px-3.5 py-1.5 transition ${
                            isLight 
                                ? 'bg-black/10 text-neutral-800 border border-black/10 group-hover:bg-coral group-hover:text-white group-hover:border-coral' 
                                : 'bg-white/10 text-white/90 border border-white/10 group-hover:bg-coral group-hover:text-white group-hover:border-coral'
                        }">
                            <span>GALERI ${index + 1} — GANTI GAMBAR</span>
                        </span>
                        <p class="mt-2 text-[11px] font-medium tracking-wide truncate max-w-[85%] ${isLight ? 'text-neutral-700' : 'text-white/60'}">
                            ${escapeHtml(titleText)}
                        </p>
                    </div>
                `;

                previewEl.appendChild(card);
            });
        }

        function updateGallerySrc(index, val) {
            if (galleryData[index]) {
                galleryData[index].src = val;
                const slotCard = document.querySelector(`.slot-card[data-index="${index}"]`);
                if (slotCard) {
                    const img = slotCard.querySelector('img');
                    const badge = slotCard.querySelector('.slot-filename-badge');
                    const resolved = val ? (val.startsWith('http') || val.startsWith('/') ? val : '/' + val) : '/images/project-placeholder.svg';
                    if (img) img.src = resolved;
                    if (badge) badge.textContent = val ? val.split('/').pop() : 'Belum ada berkas';
                }
                renderGalleryPreview();
                syncGalleryToJson();
            }
        }

        function updateGalleryTitle(index, val) {
            if (galleryData[index]) {
                galleryData[index].title = val;
                renderGalleryPreview();
                syncGalleryToJson();
            }
        }

        function updateGalleryAlt(index, val) {
            if (galleryData[index]) {
                galleryData[index].alt = val;
                if (!galleryData[index].title || galleryData[index].title.startsWith('Dokumentasi Proyek')) {
                    galleryData[index].title = val;
                }
                renderGalleryPreview();
                syncGalleryToJson();
            }
        }

        function updateGalleryTheme(index, theme) {
            if (galleryData[index]) {
                galleryData[index].theme = theme;
                renderGallerySlots();
                renderGalleryPreview();
                syncGalleryToJson();
            }
        }

        function updateGalleryLayout(index, val) {
            if (galleryData[index]) {
                galleryData[index].class = val;
                renderGallerySlots();
                renderGalleryPreview();
                syncGalleryToJson();
            }
        }

        function toggleGalleryVisibility(index, isVisible) {
            if (galleryData[index]) {
                galleryData[index].visible = isVisible;
                renderGalleryPreview();
                syncGalleryToJson();
            }
        }

        function resetSlotToPlaceholder(index) {
            if (galleryData[index]) {
                const placeholderNum = ((index) % 4) + 1;
                galleryData[index].src = `images/gallery-${placeholderNum}.svg`;
                renderGallerySlots();
                renderGalleryPreview();
                syncGalleryToJson();
                showGalleryToast('Slot dikembalikan ke placeholder bawaan.');
            }
        }

        function addNewGallerySlot() {
            const newIdx = galleryData.length + 1;
            const defaultClasses = [
                'md:col-span-6 aspect-video',
                'md:col-span-4 aspect-[4/3]',
                'md:col-span-8 aspect-[16/9]',
                'md:col-span-12 aspect-[21/9]'
            ];
            galleryData.push({
                src: `images/gallery-${((newIdx - 1) % 4) + 1}.svg`,
                title: `Dokumentasi Proyek ${newIdx}`,
                alt: `Dokumentasi galeri visual ${newIdx}`,
                class: defaultClasses[(newIdx - 1) % defaultClasses.length],
                theme: (newIdx === 2) ? 'light' : 'dark',
                visible: true
            });
            renderGallerySlots();
            renderGalleryPreview();
            syncGalleryToJson();
            showGalleryToast('Slot galeri baru ditambahkan.');
        }

        function deleteGallerySlot(index) {
            if (galleryData.length <= 1) {
                alert('Setidaknya harus menyisakan 1 foto slot galeri.');
                return;
            }
            if (confirm('Hapus slot galeri ini?')) {
                galleryData.splice(index, 1);
                renderGallerySlots();
                renderGalleryPreview();
                syncGalleryToJson();
                showGalleryToast('Slot galeri telah dihapus.');
            }
        }

        function triggerSlotImageUpload(index) {
            activeSlotForUpload = index;
            const picker = document.getElementById('slot-file-picker');
            if (picker) {
                picker.value = '';
                picker.click();
            }
        }

        function handleSlotImageUpload(input) {
            if (!input.files || !input.files[0] || activeSlotForUpload === null) return;
            uploadGalleryFile(input.files[0], activeSlotForUpload);
            input.value = '';
        }

        function handleDropzoneUpload(input) {
            if (!input.files || !input.files[0]) return;
            uploadGalleryFile(input.files[0], null);
            input.value = '';
        }

        function setupGalleryDropzone() {
            const dropzone = document.getElementById('gallery-dropzone');
            if (!dropzone) return;

            ['dragenter', 'dragover'].forEach(eventName => {
                dropzone.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzone.classList.add('border-coral', 'bg-[#181822]');
                }, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzone.classList.remove('border-coral', 'bg-[#181822]');
                }, false);
            });

            dropzone.addEventListener('drop', (e) => {
                const dt = e.dataTransfer;
                const files = dt.files;
                if (files && files.length > 0) {
                    uploadGalleryFile(files[0], null);
                }
            }, false);
        }

        function uploadGalleryFile(file, targetIndex) {
            if (isUploadingGallery) return;

            if (!file.type.match(/^image\//) && !file.name.match(/\.(svg|webp|png|jpe?g|gif)$/i)) {
                alert('File harus berupa berkas gambar (JPG, PNG, WebP, SVG, GIF).');
                return;
            }

            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran file maksimal 5MB.');
                return;
            }

            isUploadingGallery = true;
            const countHeader = document.getElementById('slots-count-header');
            const prevHeader = countHeader ? countHeader.textContent : '';
            if (countHeader) countHeader.textContent = 'MENGUNGGAH FOTO...';

            const formData = new FormData();
            formData.append('image', file);
            const tokenInput = document.querySelector('input[name="_token"]');
            if (tokenInput) formData.append('_token', tokenInput.value);

            fetch('{{ route('admin.settings.upload-gallery-image') }}', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(res => {
                if (!res.ok) {
                    return res.json().then(data => { throw new Error(data.message || 'Gagal mengunggah foto.'); });
                }
                return res.json();
            })
            .then(data => {
                isUploadingGallery = false;
                if (data.success && data.path) {
                    const cleanName = file.name.replace(/\.[^/.]+$/, "").replace(/[-_]/g, ' ');
                    if (targetIndex !== null && galleryData[targetIndex]) {
                        galleryData[targetIndex].src = data.path;
                        if (!galleryData[targetIndex].title || galleryData[targetIndex].title.startsWith('Dokumentasi Proyek')) {
                            galleryData[targetIndex].title = cleanName;
                        }
                    } else {
                        galleryData.push({
                            src: data.path,
                            title: cleanName,
                            alt: file.name,
                            class: 'md:col-span-6 aspect-video',
                            theme: 'dark',
                            visible: true
                        });
                    }
                    renderGallerySlots();
                    renderGalleryPreview();
                    syncGalleryToJson();
                    showGalleryToast('Foto berhasil diunggah!');
                } else {
                    alert(data.message || 'Gagal mengunggah foto.');
                }
            })
            .catch(err => {
                isUploadingGallery = false;
                if (countHeader) countHeader.textContent = prevHeader;
                alert('Terjadi kesalahan saat mengunggah foto: ' + err.message);
            });
        }

        function showGalleryToast(msg) {
            let toast = document.getElementById('gallery-toast');
            if (!toast) {
                toast = document.createElement('div');
                toast.id = 'gallery-toast';
                toast.className = 'fixed bottom-6 right-6 z-50 flex items-center gap-2 rounded-xl border border-coral/30 bg-[#17171d] px-4 py-3 text-xs font-medium text-white shadow-2xl transition duration-300 pointer-events-none opacity-0 translate-y-2';
                document.body.appendChild(toast);
            }
            toast.innerHTML = `<span class="size-2 rounded-full bg-emerald-400"></span><span>${msg}</span>`;
            toast.classList.remove('opacity-0', 'translate-y-2');
            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-2');
            }, 2800);
        }

        function applyGalleryPreset(presetKey) {
            document.querySelectorAll('.gallery-preset-btn').forEach(btn => {
                btn.classList.remove('border-coral/30', 'bg-coral/10', 'text-coral');
                btn.classList.add('border-white/10', 'bg-white/[0.04]', 'text-white/70');
            });
            const activeBtn = document.getElementById('preset-btn-' + presetKey);
            if (activeBtn) {
                activeBtn.classList.remove('border-white/10', 'bg-white/[0.04]', 'text-white/70');
                activeBtn.classList.add('border-coral/30', 'bg-coral/10', 'text-coral');
            }

            if (presetKey === 'bento-2x2') {
                galleryData = [
                    { src: 'images/gallery-1.svg', title: 'Dokumentasi Proyek Utama', alt: 'Placeholder dokumentasi proyek 1', class: 'md:col-span-7 aspect-[16/10]', theme: 'dark', visible: true },
                    { src: 'images/gallery-2.svg', title: 'Perancangan UI/UX', alt: 'Placeholder dokumentasi proyek 2 (Design System)', class: 'md:col-span-5 aspect-[4/5] md:aspect-auto', theme: 'light', visible: true },
                    { src: 'images/gallery-3.svg', title: 'Arsitektur Kode', alt: 'Pengembangan kode backend & arsitektur database', class: 'md:col-span-4 aspect-[4/3]', theme: 'dark', visible: true },
                    { src: 'images/gallery-4.svg', title: 'Review Multi-Device', alt: 'Review tampilan responsif multi-device', class: 'md:col-span-8 aspect-[16/9] md:aspect-[16/8]', theme: 'dark', visible: true }
                ];
            } else if (presetKey === 'spotlight') {
                galleryData = [
                    { src: 'images/gallery-1.svg', title: 'Hero Showcase Proyek', alt: 'Hero spotlight proyek utama', class: 'md:col-span-8 aspect-[16/10]', theme: 'dark', visible: true },
                    { src: 'images/gallery-2.svg', title: 'Detail Arsitektur', alt: 'Detail sistem & interaksi', class: 'md:col-span-4 aspect-[4/3]', theme: 'light', visible: true },
                    { src: 'images/gallery-3.svg', title: 'Mobile Experience', alt: 'Pratinjau antarmuka mobile', class: 'md:col-span-4 aspect-[4/3]', theme: 'dark', visible: true },
                    { src: 'images/gallery-4.svg', title: 'Fullwidth Panorama Showcase', alt: 'Dokumentasi komprehensif', class: 'md:col-span-12 aspect-[21/9]', theme: 'dark', visible: true }
                ];
            } else if (presetKey === 'masonry') {
                galleryData = [
                    { src: 'images/gallery-1.svg', title: 'Frontend Architecture', alt: 'Desain antarmuka frontend', class: 'md:col-span-4 aspect-[4/3]', theme: 'dark', visible: true },
                    { src: 'images/gallery-2.svg', title: 'Design System & UI', alt: 'Komponen visual desain', class: 'md:col-span-4 aspect-[4/3]', theme: 'light', visible: true },
                    { src: 'images/gallery-3.svg', title: 'Database & API Engine', alt: 'Arsitektur server', class: 'md:col-span-4 aspect-[4/3]', theme: 'dark', visible: true },
                    { src: 'images/gallery-4.svg', title: 'Multi-Device Testing', alt: 'Pengujian performa multi-device', class: 'md:col-span-6 aspect-video', theme: 'dark', visible: true },
                    { src: 'images/gallery-1.svg', title: 'DevOps & Deployment', alt: 'Pipeline rilis aplikasi', class: 'md:col-span-6 aspect-video', theme: 'dark', visible: true }
                ];
            } else if (presetKey === 'panorama') {
                galleryData = [
                    { src: 'images/gallery-1.svg', title: 'Master Panorama Banner', alt: 'Banner panorama utama', class: 'md:col-span-12 aspect-[21/9]', theme: 'dark', visible: true },
                    { src: 'images/gallery-2.svg', title: 'UI/UX Visual Showcase', alt: 'Showcase visual desain', class: 'md:col-span-6 aspect-[16/10]', theme: 'light', visible: true },
                    { src: 'images/gallery-3.svg', title: 'Fullstack Implementation', alt: 'Implementasi kode', class: 'md:col-span-6 aspect-[16/10]', theme: 'dark', visible: true },
                    { src: 'images/gallery-4.svg', title: 'Final System Deliverable', alt: 'Hasil akhir sistem', class: 'md:col-span-12 aspect-[21/9]', theme: 'dark', visible: true }
                ];
            } else if (presetKey === 'quad') {
                galleryData = [
                    { src: 'images/gallery-1.svg', title: 'Koleksi Dokumentasi 1', alt: 'Dokumentasi proyek 1', class: 'md:col-span-6 aspect-video', theme: 'dark', visible: true },
                    { src: 'images/gallery-2.svg', title: 'Koleksi Dokumentasi 2', alt: 'Dokumentasi proyek 2', class: 'md:col-span-6 aspect-video', theme: 'light', visible: true },
                    { src: 'images/gallery-3.svg', title: 'Koleksi Dokumentasi 3', alt: 'Dokumentasi proyek 3', class: 'md:col-span-6 aspect-video', theme: 'dark', visible: true },
                    { src: 'images/gallery-4.svg', title: 'Koleksi Dokumentasi 4', alt: 'Dokumentasi proyek 4', class: 'md:col-span-6 aspect-video', theme: 'dark', visible: true }
                ];
            }

            renderGallerySlots();
            renderGalleryPreview();
            syncGalleryToJson();
        }

        function resetToBentoPreset() {
            applyGalleryPreset('bento-2x2');
        }

        function syncGalleryToJson() {
            const jsonStr = JSON.stringify(galleryData, null, 2);
            const inputEl = document.getElementById('gallery-json-input');
            if (inputEl) {
                inputEl.value = jsonStr;
            }
        }

        function syncFromGalleryRawJson() {
            const inputEl = document.getElementById('gallery-json-input');
            if (!inputEl) return;
            try {
                const parsed = JSON.parse(inputEl.value);
                if (Array.isArray(parsed)) {
                    galleryData = parsed;
                    renderGallerySlots();
                    renderGalleryPreview();
                }
            } catch (e) {
                // Ignore while user is typing
            }
        }

        function formatGalleryRawJson() {
            const inputEl = document.getElementById('gallery-json-input');
            if (!inputEl) return;
            try {
                const parsed = JSON.parse(inputEl.value);
                inputEl.value = JSON.stringify(parsed, null, 2);
            } catch (err) {
                alert('Format JSON tidak valid: ' + err.message);
            }
        }

        function submitSettingsForm() {
            if (typeof syncGalleryToJson === 'function') syncGalleryToJson();
            if (typeof syncServicesToJson === 'function') syncServicesToJson();
            if (typeof syncTechToJson === 'function') syncTechToJson();
            const form = document.getElementById('settings-form');
            if (form) form.submit();
        }

        // ==========================================
        // INITIALIZATION ON DOM READY
        // ==========================================
        document.addEventListener('DOMContentLoaded', function() {
            // Character counters
            const nameEl = document.getElementById('identity-full-name');
            if (nameEl) updateCharCount(nameEl, 'count-name');

            const shortEl = document.getElementById('identity-short-name');
            if (shortEl) updateCharCount(shortEl, 'count-short');

            const roleEl = document.getElementById('identity-role');
            if (roleEl) updateCharCount(roleEl, 'count-role');

            const bioEl = document.getElementById('identity-bio');
            if (bioEl) updateCharCount(bioEl, 'count-bio');

            // Initialize Services Manager
            initServicesManager();

            // Initialize Technology & Stack Manager
            initTechManager();

            // Initialize Footer Links Preview
            updateFooterPreview();

            // Initialize Gallery Visual Manager
            initGalleryManager();
        });
    </script>

    <style>
        @keyframes techMarqueeScroll {
            0% { transform: translateX(0); }
            100% { transform: translateX(-100%); }
        }
        .tech-marquee-track {
            animation: techMarqueeScroll 28s linear infinite;
        }
        .tech-marquee-wrapper:hover .tech-marquee-track {
            animation-play-state: paused;
        }
    </style>
@endsection
