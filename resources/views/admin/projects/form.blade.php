@extends('admin.layout')

@section('title', $project->exists ? 'Edit Proyek' : 'Tambah Proyek')

@section('content')
    <form id="project-form" method="POST"
        action="{{ $project->exists ? route('admin.projects.update', $project) : route('admin.projects.store') }}"
        enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if ($project->exists)
            @method('PUT')
        @endif

        {{-- Hidden fields for reactive controls --}}
        <input type="hidden" name="category" id="hidden-category" value="{{ old('category', $project->category ?? 'web') }}">
        <input type="hidden" name="technologies" id="hidden-technologies" value="{{ old('technologies', is_array($project->technologies) ? implode(',', $project->technologies) : $project->technologies) }}">
        <input type="hidden" name="published" id="hidden-published" value="{{ old('published', $project->published ?? 1) ? 1 : 0 }}">

        {{-- Top Bar / Breadcrumb & Action Buttons --}}
        <div class="flex flex-col justify-between gap-4 lg:flex-row lg:items-center">
            <div>
                <div class="flex items-center gap-2 font-mono text-[11px] tracking-wider text-white/50 uppercase">
                    <a href="{{ route('admin.projects.index') }}" class="hover:text-white transition">Proyek</a>
                    <span class="text-white/20">/</span>
                    <span class="text-white/80">{{ $project->exists ? 'Edit Proyek' : 'Tambah Baru' }}</span>
                    <span class="text-white/20">•</span>
                    <span class="text-white/40 font-mono">DRAF OTOMATIS</span>
                </div>
                <p class="mt-1 text-xs text-white/50 sm:text-sm">
                    Buat showcase karya digital baru untuk portofolio publik Rayhan.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <button type="button" id="btn-save-draft"
                    class="flex items-center gap-2 rounded-xl border border-white/10 bg-white/[0.04] px-4 py-2.5 text-xs font-medium text-white transition hover:bg-white/[0.08]">
                    <x-icon name="folder" class="size-3.5 text-white/60" />
                    <span>Simpan Draf</span>
                </button>
                <button type="submit" id="btn-publish"
                    class="flex items-center gap-2 rounded-xl bg-coral px-5 py-2.5 text-xs font-semibold text-white shadow-lg shadow-coral/25 transition hover:bg-coral-dark">
                    <span>{{ $project->exists ? 'Perbarui Proyek' : 'Publikasikan' }}</span>
                    <x-icon name="arrow-right" class="size-3.5" />
                </button>
            </div>
        </div>

        {{-- 2-Column Main Form Grid --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
            {{-- Left Column (8 cols): Input Cards --}}
            <div class="space-y-6 lg:col-span-8">
                {{-- Card 1: Kategori & Judul Proyek --}}
                <div class="rounded-2xl border border-white/5 bg-[#17171d] p-6 shadow-sm space-y-5">
                    <div>
                        <label class="block font-mono text-[10px] tracking-[0.2em] font-semibold text-white/50 uppercase mb-3">
                            Kategori Proyek
                        </label>
                        <div class="flex flex-wrap gap-2" id="category-pills">
                            @php
                                $categoriesList = [
                                    'web' => 'Web Development',
                                    'uiux' => 'UI/UX Design',
                                    'responsive' => 'Responsive Web',
                                    'mobile' => 'Mobile App',
                                    'opensource' => 'Open Source',
                                ];
                                $selectedCategory = old('category', $project->category ?? 'web');
                            @endphp
                            @foreach ($categoriesList as $catKey => $catLabel)
                                <button type="button" data-category="{{ $catKey }}"
                                    class="category-btn rounded-xl px-4 py-2 text-xs font-medium transition {{ $selectedCategory === $catKey ? 'bg-coral text-white font-semibold shadow-md shadow-coral/20' : 'border border-white/5 bg-white/[0.03] text-white/60 hover:bg-white/[0.08] hover:text-white' }}">
                                    {{ $catLabel }}
                                </button>
                            @endforeach
                        </div>
                        @error('category')
                            <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <input type="text" id="project-title" name="title" value="{{ old('title', $project->title) }}" required
                            placeholder="Headline"
                            class="w-full bg-transparent border-0 border-b border-white/10 pb-3 text-xl sm:text-2xl font-bold tracking-tight text-white placeholder:text-white/20 focus:border-coral focus:outline-none transition">
                        @error('title')
                            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Card 2: Cover & Media Proyek --}}
                <div class="rounded-2xl border border-white/5 bg-[#17171d] p-6 shadow-sm space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <x-icon name="image" class="size-4 text-coral" />
                            <h2 class="text-xs font-bold uppercase tracking-wider text-white">Cover &amp; Media Proyek</h2>
                        </div>
                        <span class="font-mono text-[10px] text-white/40">Rekomendasi: 16:9 (Min. 1920x1080)</span>
                    </div>

                    {{-- Upload Dropzone --}}
                    <div id="dropzone"
                        class="relative flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-white/10 bg-white/[0.02] p-8 text-center transition hover:border-coral/50 hover:bg-white/[0.04] cursor-pointer">
                        <input type="file" id="cover-file-input" name="image" accept="image/png,image/jpeg,image/webp" class="sr-only">
                        
                        <div id="dropzone-default" class="{{ $project->image ? 'hidden' : 'flex' }} flex-col items-center">
                            <div class="flex size-12 items-center justify-center rounded-2xl border border-white/10 bg-white/5 text-coral mb-3 shadow-inner">
                                <x-icon name="upload-cloud" class="size-6" />
                            </div>
                            <p class="text-xs font-medium text-white/80">
                                Tarik &amp; letakkan cover visual di sini, atau <span class="text-coral underline font-semibold">Pilih Berkas</span>
                            </p>
                            <p class="mt-1 text-[11px] text-white/40">
                                Mendukung format PNG, WebP, atau JPG kualitas tinggi (Maks. 4MB)
                            </p>
                        </div>

                        {{-- Preview when selected --}}
                        <div id="dropzone-preview" class="{{ $project->image ? 'flex' : 'hidden' }} flex-col items-center gap-3">
                            <div class="relative max-h-48 overflow-hidden rounded-xl border border-white/10 shadow-lg">
                                <img id="cover-preview-img" src="{{ $project->image ? $project->image_url : '' }}" alt="Preview" class="h-44 w-auto object-cover">
                            </div>
                            <div class="flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-1 font-mono text-[11px] text-white/70">
                                <span id="cover-file-name" class="truncate max-w-xs">{{ $project->image ? basename($project->image) : 'cover-image.webp' }}</span>
                                <button type="button" id="remove-cover-btn" class="ml-1 text-white/40 hover:text-red-400 transition" title="Ganti Berkas">✕</button>
                            </div>
                        </div>
                    </div>
                    @error('image')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror

                    {{-- Additional Mock Shots Strip --}}
                    <div class="flex items-center gap-3 pt-2 overflow-x-auto">
                        <div class="flex size-14 shrink-0 items-center justify-center rounded-xl border border-white/10 bg-white/5">
                            <x-icon name="image" class="size-5 text-white/30" />
                        </div>
                        <div class="flex size-14 shrink-0 items-center justify-center rounded-xl border border-white/10 bg-white/5">
                            <x-icon name="image" class="size-5 text-white/30" />
                        </div>
                        <div class="flex size-14 shrink-0 items-center justify-center rounded-xl border border-white/10 bg-white/5">
                            <x-icon name="image" class="size-5 text-white/30" />
                        </div>
                        <button type="button" onclick="document.getElementById('cover-file-input').click()"
                            class="flex h-14 shrink-0 items-center gap-1.5 rounded-xl border border-dashed border-white/15 px-3 font-mono text-[10px] text-white/40 transition hover:border-white/30 hover:text-white">
                            <x-icon name="plus" class="size-3.5" />
                            <span>Tambah Shot</span>
                        </button>
                    </div>
                </div>

                {{-- Card 3: Deskripsi & Cerita Proyek --}}
                <div class="rounded-2xl border border-white/5 bg-[#17171d] p-6 shadow-sm space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <x-icon name="pen" class="size-4 text-coral" />
                            <h2 class="text-xs font-bold uppercase tracking-wider text-white">Deskripsi</h2>
                        </div>
                        <div class="flex items-center gap-1 text-white/40 font-mono text-[11px]">
                            <span class="px-1.5 py-0.5 rounded border border-white/5 hover:text-white cursor-pointer font-bold">B</span>
                            <span class="px-1.5 py-0.5 rounded border border-white/5 hover:text-white cursor-pointer italic">I</span>
                            <span class="px-1.5 py-0.5 rounded border border-white/5 hover:text-white cursor-pointer">🔗</span>
                            <span class="px-1.5 py-0.5 rounded border border-white/5 hover:text-white cursor-pointer">&lt;/&gt;</span>
                        </div>
                    </div>

                    {{-- Field 1: Ringkasan --}}
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="project-description" class="font-mono text-[11px] text-white/60">
                                Ringkasan Proyek (Ditampilkan di Kartu Utama)
                            </label>
                            <span id="char-counter" class="font-mono text-[10px] text-white/40">0 / 160</span>
                        </div>
                        <textarea id="project-description" name="description" rows="3" required maxlength="1000"
                            placeholder="Tambahkan deskripsi singkat mengenai proyek yang akan ditampilkan di halaman utama."
                            class="w-full rounded-xl border border-white/10 bg-white/[0.03] p-4 text-xs leading-relaxed text-white placeholder:text-white/30 focus:border-coral/50 focus:outline-none transition">{{ old('description', $project->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Card 4: Teknologi & Alat yang Digunakan --}}
                <div class="rounded-2xl border border-white/5 bg-[#17171d] p-6 shadow-sm space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <x-icon name="code" class="size-4 text-coral" />
                            <h2 class="text-xs font-bold uppercase tracking-wider text-white">Teknologi &amp; Alat yang Digunakan</h2>
                        </div>
                        <span class="font-mono text-[10px] text-white/40">Pilih atau ketik untuk menambah</span>
                    </div>

                    {{-- Active Tech Tags --}}
                    <div class="flex flex-wrap items-center gap-2 rounded-xl border border-white/10 bg-white/[0.02] p-3" id="tags-wrapper">
                        <div id="tech-tags-container" class="flex flex-wrap items-center gap-2"></div>
                        
                        <div class="flex items-center gap-1.5">
                            <input type="text" id="new-tech-input" placeholder="+ Tambah Tech"
                                class="w-28 rounded-lg border border-white/10 bg-white/5 px-2.5 py-1 text-xs text-white placeholder:text-white/40 focus:w-36 focus:border-coral focus:outline-none transition-all">
                        </div>
                    </div>
                    @error('technologies')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror

                    {{-- Popular Suggestions --}}
                    <div class="flex flex-wrap items-center gap-2 font-mono text-[11px] text-white/40">
                        <span>Saran populer:</span>
                        <button type="button" class="quick-tech text-white/60 hover:text-coral transition">+ React</button>
                        <span>•</span>
                        <button type="button" class="quick-tech text-white/60 hover:text-coral transition">+ Figma</button>
                        <span>•</span>
                        <button type="button" class="quick-tech text-white/60 hover:text-coral transition">+ Docker</button>
                        <span>•</span>
                        <button type="button" class="quick-tech text-white/60 hover:text-coral transition">+ Vite</button>
                        <span>•</span>
                        <button type="button" class="quick-tech text-white/60 hover:text-coral transition">+ Alpine.js</button>
                    </div>
                </div>

                {{-- Card 5: Tautan Proyek & Repositori --}}
                <div class="rounded-2xl border border-white/5 bg-[#17171d] p-6 shadow-sm space-y-4">
                    <div class="flex items-center gap-2">
                        <x-icon name="external-link" class="size-4 text-coral" />
                        <h2 class="text-xs font-bold uppercase tracking-wider text-white">Tautan Proyek &amp; Repositori</h2>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        {{-- Demo URL --}}
                        <div>
                            <label for="demo_url" class="block font-mono text-[10px] text-white/60 uppercase mb-2">
                                URL Demo / Website Publik
                            </label>
                            <div class="relative">
                                <span class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 font-mono text-xs text-white/40">https://</span>
                                <input type="text" id="demo_url" name="demo_url"
                                    value="{{ old('demo_url', preg_replace('#^https?://#', '', (string)$project->demo_url)) }}"
                                    placeholder="Isi Link Demo"
                                    class="w-full rounded-xl border border-white/10 bg-white/[0.03] py-2.5 pl-20 pr-4 text-xs text-white placeholder:text-white/30 focus:border-coral focus:outline-none transition">
                            </div>
                            @error('demo_url')
                                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- GitHub Repo --}}
                        <div>
                            <label for="repository_url" class="block font-mono text-[10px] text-white/60 uppercase mb-2">
                                Repositori GitHub / Source Code
                            </label>
                            <div class="relative">
                                <span class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 font-mono text-xs text-white/40">github.com/</span>
                                <input type="text" id="repository_url" name="repository_url"
                                    value="{{ old('repository_url', preg_replace('#^https?://github\.com/#', '', (string)$project->repository_url)) }}"
                                    placeholder="rayhaneuh/sijaka-app"
                                    class="w-full rounded-xl border border-white/10 bg-white/[0.03] py-2.5 pl-28 pr-4 text-xs text-white placeholder:text-white/30 focus:border-coral focus:outline-none transition">
                            </div>
                            @error('repository_url')
                                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column (4 cols): Inspector & Settings --}}
            <div class="space-y-6 lg:col-span-4">
                {{-- Card A: LIVE CARD INSPECTOR --}}
                <div id="inspector-card" class="rounded-2xl border border-white/5 bg-[#17171d] p-5 shadow-sm space-y-4">
                    <div class="flex items-center justify-between border-b border-white/5 pb-3">
                        <div class="flex items-center gap-2 font-mono text-[11px] text-white/70">
                            <span class="size-1.5 rounded-full bg-coral animate-pulse"></span>
                            <span class="font-bold tracking-wider uppercase">Live Card Inspector</span>
                        </div>
                        <span class="font-mono text-[10px] text-white/40">Beranda Preview</span>
                    </div>

                    {{-- Miniature Home Card Preview --}}
                    <div class="overflow-hidden rounded-2xl border border-white/10 bg-[#121217] shadow-xl">
                        {{-- Card Image / Thumbnail with Category Badge --}}
                        <div class="relative aspect-video w-full overflow-hidden bg-white/5 flex items-center justify-center">
                            <img id="inspector-img" src="{{ $project->image ? $project->image_url : asset('images/project-placeholder.svg') }}"
                                alt="Preview" class="size-full object-cover">
                            <span id="inspector-category-badge"
                                class="absolute top-3 left-3 rounded-lg border border-white/15 bg-black/60 px-2.5 py-1 font-mono text-[10px] font-semibold text-white backdrop-blur-md">
                                {{ $project->category_label ?? 'Web Development' }}
                            </span>
                        </div>

                        {{-- Card Content --}}
                        <div class="p-4 space-y-2">
                            <h3 id="inspector-title" class="text-sm font-bold text-white line-clamp-1">
                                {{ $project->title ?: 'SIJAKA' }}
                            </h3>
                            <p id="inspector-desc" class="text-xs text-white/60 line-clamp-2 leading-relaxed">
                                {{ $project->description ?: 'Sistem Informasi Bursa Kerja Khusus yang membantu menghubungkan alumni dengan peluang kerja...' }}
                            </p>
                            <div id="inspector-tech-tags" class="flex flex-wrap gap-1 pt-2">
                                {{-- Filled dynamically by JS --}}
                            </div>
                        </div>
                    </div>

                    <p class="text-[11px] text-white/40 leading-relaxed text-center">
                        Pratinjau otomatis menyesuaikan perubahan input Anda secara realtime.
                    </p>
                </div>

                {{-- Card B: VISIBILITAS & PUBLIKASI --}}
                <div class="rounded-2xl border border-white/5 bg-[#17171d] p-5 shadow-sm space-y-5">
                    <h3 class="font-mono text-[10px] font-bold uppercase tracking-[0.2em] text-white/50">
                        Visibilitas &amp; Publikasi
                    </h3>

                    {{-- Toggle: Tampilkan ke Publik --}}
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-xs font-semibold text-white">Tampilkan ke Publik</p>
                            <p class="text-[10px] text-white/40">Langsung terlihat di beranda utama</p>
                        </div>
                        <button type="button" id="toggle-published-btn"
                            class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ old('published', $project->published ?? 1) ? 'bg-coral' : 'bg-white/10' }}"
                            role="switch" aria-checked="{{ old('published', $project->published ?? 1) ? 'true' : 'false' }}">
                            <span id="toggle-published-thumb"
                                class="pointer-events-none inline-block size-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ old('published', $project->published ?? 1) ? 'translate-x-5' : 'translate-x-0' }}"></span>
                        </button>
                    </div>

                    {{-- Toggle: Sematkan di Hero (Featured) --}}
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-xs font-semibold text-white">Sematkan di Hero (Featured)</p>
                            <p class="text-[10px] text-white/40">Menjadi prioritas urutan 1 di portofolio</p>
                        </div>
                        <button type="button" id="toggle-featured-btn"
                            class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ (old('sort_order', $project->sort_order ?? 0) === 0) ? 'bg-coral' : 'bg-white/10' }}"
                            role="switch" aria-checked="{{ (old('sort_order', $project->sort_order ?? 0) === 0) ? 'true' : 'false' }}">
                            <span id="toggle-featured-thumb"
                                class="pointer-events-none inline-block size-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ (old('sort_order', $project->sort_order ?? 0) === 0) ? 'translate-x-5' : 'translate-x-0' }}"></span>
                        </button>
                    </div>

                    {{-- Periode & Urutan Tampilan --}}
                    <div class="space-y-3 border-t border-white/5 pt-4">
                        <label class="block font-mono text-[10px] text-white/50 uppercase">
                            Periode Pengerjaan
                        </label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="text" placeholder="2025" value="2025"
                                class="w-full rounded-xl border border-white/10 bg-white/[0.03] py-2 px-3 text-xs text-white placeholder:text-white/30 focus:border-coral focus:outline-none font-mono">
                            <input type="text" placeholder="Februari" value="Februari"
                                class="w-full rounded-xl border border-white/10 bg-white/[0.03] py-2 px-3 text-xs text-white placeholder:text-white/30 focus:border-coral focus:outline-none font-mono">
                        </div>

                        <div class="pt-2 flex items-center justify-between">
                            <label for="sort_order" class="font-mono text-[11px] text-white/60">Urutan Tampilan</label>
                            <div class="flex items-center gap-1.5 font-mono text-xs">
                                <span class="text-white/40">Posisi #</span>
                                <input type="number" id="sort_order" name="sort_order"
                                    value="{{ old('sort_order', $project->sort_order ?? 0) }}" min="0" max="999"
                                    class="w-16 rounded-lg border border-white/10 bg-white/5 px-2 py-1 text-center text-xs text-white focus:border-coral focus:outline-none">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card C: Tips Portofolio Berkualitas --}}
                <div class="rounded-2xl border border-white/5 bg-[#17171d] p-5 shadow-sm space-y-3">
                    <div class="flex items-center gap-2 text-amber-400">
                        <x-icon name="lightbulb" class="size-4" />
                        <h4 class="text-xs font-bold text-white">Tips Portofolio Berkualitas</h4>
                    </div>
                    <p class="text-xs leading-relaxed text-white/50">
                        Sertakan screenshot resolusi tinggi dan tautan demo aktif untuk meningkatkan conversion peluang magang atau tawaran freelance hingga 40%.
                    </p>
                </div>
            </div>
        </div>
    </form>

    {{-- Interactive Form JavaScript --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const titleInput = document.getElementById('project-title');
            const descInput = document.getElementById('project-description');
            const slugPreview = document.getElementById('slug-preview');
            const charCounter = document.getElementById('char-counter');

            // Live Card Inspector Elements
            const inspectorTitle = document.getElementById('inspector-title');
            const inspectorDesc = document.getElementById('inspector-desc');
            const inspectorCategory = document.getElementById('inspector-category-badge');
            const inspectorTechTags = document.getElementById('inspector-tech-tags');
            const inspectorImg = document.getElementById('inspector-img');

            // Hidden Inputs
            const hiddenCategory = document.getElementById('hidden-category');
            const hiddenTech = document.getElementById('hidden-technologies');
            const hiddenPublished = document.getElementById('hidden-published');
            const sortOrderInput = document.getElementById('sort_order');

            // Category Labels Dictionary
            const categoryLabels = {
                'web': 'Web Development',
                'uiux': 'UI/UX Design',
                'responsive': 'Responsive Web',
                'mobile': 'Mobile App',
                'opensource': 'Open Source'
            };

            // 1. Live Title & Slug Generator
            function slugify(text) {
                return text.toString().toLowerCase().trim()
                    .replace(/\s+/g, '-')
                    .replace(/[^\w\-]+/g, '')
                    .replace(/\-\-+/g, '-');
            }

            if (titleInput) {
                titleInput.addEventListener('input', (e) => {
                    const val = e.target.value.trim();
                    inspectorTitle.textContent = val || 'Judul Proyek';
                    slugPreview.textContent = slugify(val) || 'sijaka';
                });
            }

            // 2. Live Description & Char Counter
            if (descInput) {
                function updateDesc() {
                    const len = descInput.value.length;
                    charCounter.textContent = `${len} / 160`;
                    inspectorDesc.textContent = descInput.value.trim() || 'Deskripsi singkat proyek...';
                }
                descInput.addEventListener('input', updateDesc);
                updateDesc();
            }

            // 3. Category Buttons
            const categoryButtons = document.querySelectorAll('.category-btn');
            categoryButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    categoryButtons.forEach(b => {
                        b.classList.remove('bg-coral', 'text-white', 'font-semibold', 'shadow-md', 'shadow-coral/20');
                        b.classList.add('border', 'border-white/5', 'bg-white/[0.03]', 'text-white/60');
                    });
                    btn.classList.add('bg-coral', 'text-white', 'font-semibold', 'shadow-md', 'shadow-coral/20');
                    btn.classList.remove('border', 'border-white/5', 'bg-white/[0.03]', 'text-white/60');

                    const selected = btn.dataset.category;
                    hiddenCategory.value = selected;
                    inspectorCategory.textContent = categoryLabels[selected] || selected;
                });
            });

            // 4. Interactive Technology Tag Manager
            const initialTechString = hiddenTech.value;
            let techList = initialTechString ? initialTechString.split(',').map(t => t.trim()).filter(Boolean) : [
                'Laravel 10', 'Tailwind CSS', 'MySQL', 'PHP 8.2', 'JavaScript ES6'
            ];

            const tagsContainer = document.getElementById('tech-tags-container');
            const newTechInput = document.getElementById('new-tech-input');

            function renderTechTags() {
                tagsContainer.innerHTML = '';
                inspectorTechTags.innerHTML = '';

                techList.forEach((tech, idx) => {
                    // Tag in editor
                    const tagPill = document.createElement('span');
                    tagPill.className = 'inline-flex items-center gap-1.5 rounded-lg border border-white/10 bg-white/5 px-2.5 py-1 font-mono text-[11px] text-white/80';
                    tagPill.innerHTML = `
                        <span class="size-1.5 rounded-full bg-coral"></span>
                        <span>${tech}</span>
                        <button type="button" class="ml-1 text-white/40 hover:text-red-400 transition" data-index="${idx}">✕</button>
                    `;
                    tagsContainer.appendChild(tagPill);

                    // Tag in inspector
                    const inspectorPill = document.createElement('span');
                    inspectorPill.className = 'rounded border border-white/10 bg-white/5 px-1.5 py-0.5 font-mono text-[9px] text-white/60';
                    inspectorPill.textContent = tech;
                    inspectorTechTags.appendChild(inspectorPill);
                });

                hiddenTech.value = techList.join(',');
            }

            tagsContainer.addEventListener('click', (e) => {
                if (e.target.tagName === 'BUTTON') {
                    const idx = parseInt(e.target.dataset.index, 10);
                    techList.splice(idx, 1);
                    renderTechTags();
                }
            });

            function addTech(name) {
                const cleaned = name.trim();
                if (cleaned && !techList.includes(cleaned)) {
                    techList.push(cleaned);
                    renderTechTags();
                }
            }

            if (newTechInput) {
                newTechInput.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' || e.key === ',') {
                        e.preventDefault();
                        addTech(newTechInput.value);
                        newTechInput.value = '';
                    }
                });
            }

            document.querySelectorAll('.quick-tech').forEach(btn => {
                btn.addEventListener('click', () => {
                    const tech = btn.textContent.replace('+', '').trim();
                    addTech(tech);
                });
            });

            renderTechTags();

            // 5. Image Dropzone & Realtime Preview
            const dropzone = document.getElementById('dropzone');
            const fileInput = document.getElementById('cover-file-input');
            const dropzoneDefault = document.getElementById('dropzone-default');
            const dropzonePreview = document.getElementById('dropzone-preview');
            const coverPreviewImg = document.getElementById('cover-preview-img');
            const coverFileName = document.getElementById('cover-file-name');
            const removeCoverBtn = document.getElementById('remove-cover-btn');

            dropzone.addEventListener('click', (e) => {
                if (!e.target.closest('#remove-cover-btn')) {
                    fileInput.click();
                }
            });

            ['dragenter', 'dragover'].forEach(eventName => {
                dropzone.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    dropzone.classList.add('border-coral', 'bg-white/[0.06]');
                });
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    dropzone.classList.remove('border-coral', 'bg-white/[0.06]');
                });
            });

            dropzone.addEventListener('drop', (e) => {
                if (e.dataTransfer.files.length) {
                    fileInput.files = e.dataTransfer.files;
                    handleFileSelected(e.dataTransfer.files[0]);
                }
            });

            fileInput.addEventListener('change', () => {
                if (fileInput.files.length) {
                    handleFileSelected(fileInput.files[0]);
                }
            });

            function handleFileSelected(file) {
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        coverPreviewImg.src = e.target.result;
                        inspectorImg.src = e.target.result;
                        coverFileName.textContent = `${file.name} (${(file.size / (1024 * 1024)).toFixed(1)} MB)`;
                        dropzoneDefault.classList.add('hidden');
                        dropzonePreview.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                }
            }

            if (removeCoverBtn) {
                removeCoverBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    fileInput.value = '';
                    dropzonePreview.classList.add('hidden');
                    dropzoneDefault.classList.remove('hidden');
                });
            }

            // 6. Publication & Featured Toggles
            const togglePubBtn = document.getElementById('toggle-published-btn');
            const togglePubThumb = document.getElementById('toggle-published-thumb');

            togglePubBtn.addEventListener('click', () => {
                const isCurrentlyOn = hiddenPublished.value === '1';
                const nextState = !isCurrentlyOn;
                hiddenPublished.value = nextState ? '1' : '0';

                togglePubBtn.classList.toggle('bg-coral', nextState);
                togglePubBtn.classList.toggle('bg-white/10', !nextState);
                togglePubThumb.classList.toggle('translate-x-5', nextState);
                togglePubThumb.classList.toggle('translate-x-0', !nextState);
                togglePubBtn.setAttribute('aria-checked', nextState ? 'true' : 'false');
            });

            const toggleFeaturedBtn = document.getElementById('toggle-featured-btn');
            const toggleFeaturedThumb = document.getElementById('toggle-featured-thumb');

            toggleFeaturedBtn.addEventListener('click', () => {
                const isFeatured = sortOrderInput.value === '0';
                const nextFeatured = !isFeatured;
                sortOrderInput.value = nextFeatured ? '0' : '1';

                toggleFeaturedBtn.classList.toggle('bg-coral', nextFeatured);
                toggleFeaturedBtn.classList.toggle('bg-white/10', !nextFeatured);
                toggleFeaturedThumb.classList.toggle('translate-x-5', nextFeatured);
                toggleFeaturedThumb.classList.toggle('translate-x-0', !nextFeatured);
                toggleFeaturedBtn.setAttribute('aria-checked', nextFeatured ? 'true' : 'false');
            });

            // 7. Top Bar Actions
            const saveDraftBtn = document.getElementById('btn-save-draft');
            const publishBtn = document.getElementById('btn-publish');
            const form = document.getElementById('project-form');

            if (saveDraftBtn) {
                saveDraftBtn.addEventListener('click', () => {
                    hiddenPublished.value = '0';
                    form.submit();
                });
            }

            if (publishBtn) {
                publishBtn.addEventListener('click', () => {
                    hiddenPublished.value = '1';
                });
            }

            const scrollPreviewBtn = document.getElementById('scroll-preview-btn');
            if (scrollPreviewBtn) {
                scrollPreviewBtn.addEventListener('click', () => {
                    document.getElementById('inspector-card').scrollIntoView({ behavior: 'smooth' });
                });
            }
        });
    </script>
@endsection