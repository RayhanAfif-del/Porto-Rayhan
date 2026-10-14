@extends('admin.layout')

@section('title', 'Kelola Proyek')

@section('content')
    <div class="space-y-6">
        {{-- Breadcrumb & Title Section --}}
        <div class="flex flex-col justify-between gap-4 lg:flex-row lg:items-center">
            <div>
                <p class="font-mono text-[11px] tracking-[0.2em] text-white/40 uppercase">
                    Portofolio / Kelola Proyek
                </p>
                <h1 class="mt-2 text-2xl font-bold tracking-tight text-white sm:text-3xl">Kelola Proyek</h1>
                <p class="mt-1 text-xs text-white/50 sm:text-sm">
                    Buat showcase karya digital baru untuk portofolio publik Rayhan.
                </p>
                <div class="mt-3 flex items-center gap-2 font-mono text-xs text-white/50">
                    <span class="size-1.5 rounded-full bg-coral"></span>
                    <span>{{ $totalCount }} Proyek</span>
                    <span class="text-white/20">•</span>
                    <span>{{ $publishedCount }} Terbit</span>
                    <span class="text-white/20">•</span>
                    <span>{{ $categoryCount }} Kategori</span>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('home') }}#proyek" target="_blank" rel="noopener"
                    class="flex items-center gap-2 rounded-xl border border-white/10 bg-white/[0.04] px-4 py-2.5 text-xs font-medium text-white transition hover:bg-white/[0.08]">
                    <x-icon name="external-link" class="size-3.5 text-white/60" />
                    <span>Lihat di Web</span>
                </a>
                <a href="{{ route('admin.projects.create') }}"
                    class="flex items-center gap-2 rounded-xl bg-coral px-4 py-2.5 text-xs font-semibold text-white shadow-lg shadow-coral/25 transition hover:bg-coral-dark">
                    <x-icon name="plus" class="size-3.5" />
                    <span>Tambah Proyek</span>
                </a>
            </div>
        </div>

        {{-- Filter Tabs & Search Bar --}}
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div class="flex flex-wrap items-center gap-2" id="filter-tabs">
                <button type="button" data-filter="all"
                    class="filter-btn active rounded-xl bg-coral px-4 py-2 text-xs font-semibold text-white shadow-md shadow-coral/20 transition">
                    Semua Proyek <span class="ml-1 opacity-80">{{ $totalCount }}</span>
                </button>
                <button type="button" data-filter="web"
                    class="filter-btn rounded-xl px-4 py-2 text-xs font-medium text-white/60 transition hover:bg-white/5 hover:text-white">
                    Web Development <span class="ml-1 text-white/40">{{ $categoryCounts['web'] ?? 0 }}</span>
                </button>
                <button type="button" data-filter="uiux"
                    class="filter-btn rounded-xl px-4 py-2 text-xs font-medium text-white/60 transition hover:bg-white/5 hover:text-white">
                    UI/UX Design <span class="ml-1 text-white/40">{{ $categoryCounts['uiux'] ?? 0 }}</span>
                </button>
            </div>

            <div class="relative w-full sm:w-64">
                <x-icon name="search" class="pointer-events-none absolute left-3.5 top-1/2 size-3.5 -translate-y-1/2 text-white/35" />
                <input type="text" id="project-search" placeholder="Cari proyek..."
                    class="w-full rounded-xl border border-white/10 bg-white/[0.03] py-2 pl-9 pr-3 text-xs text-white placeholder:text-white/35 focus:border-white/20 focus:outline-none">
            </div>
        </div>

        {{-- Projects List --}}
        <div id="projects-container" class="space-y-3">
            @forelse ($projects as $project)
                <div class="project-row group flex flex-col justify-between gap-4 rounded-2xl border border-white/5 bg-[#17171d] p-4 transition hover:border-white/10 sm:flex-row sm:items-center"
                    data-category="{{ $project->category }}"
                    data-title="{{ strtolower($project->title) }}"
                    data-description="{{ strtolower($project->description) }}">
                    {{-- Left side info --}}
                    <div class="flex items-start gap-4 min-w-0">
                        <div class="flex size-16 shrink-0 items-center justify-center overflow-hidden rounded-xl border border-white/10 bg-white/5">
                            @if ($project->image)
                                <img src="{{ $project->image_url }}" alt="{{ $project->title }}" class="size-full object-cover">
                            @else
                                <x-icon name="folder" class="size-6 text-white/30" />
                            @endif
                        </div>

                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="truncate text-sm font-bold text-white">{{ $project->title }}</h3>
                                @if ($loop->first)
                                    <span class="rounded bg-coral/15 px-1.5 py-0.5 font-mono text-[9px] font-semibold text-coral uppercase tracking-wide">
                                        Featured
                                    </span>
                                @endif
                                <span class="font-mono text-xs text-white/40">/proyek/{{ $project->slug }}</span>
                            </div>

                            <p class="mt-1 truncate text-xs text-white/60">{{ $project->description }}</p>

                            <div class="mt-2.5 flex flex-wrap items-center gap-1.5">
                                <span class="rounded border border-white/10 bg-white/5 px-2 py-0.5 font-mono text-[10px] text-white/70">
                                    {{ $project->category === 'web' ? 'Web Dev' : ($project->category === 'uiux' ? 'UI/UX' : $project->category_label) }}
                                </span>
                                @foreach (array_slice($project->technologies ?? [], 0, 5) as $tech)
                                    <span class="rounded border border-white/5 bg-white/[0.02] px-2 py-0.5 font-mono text-[10px] text-white/50">
                                        {{ $tech }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Right side actions --}}
                    <div class="flex shrink-0 items-center gap-3 self-end sm:self-center">
                        <div class="flex items-center gap-1.5 font-mono text-xs">
                            @if ($project->published)
                                <span class="size-1.5 rounded-full bg-coral"></span>
                                <span class="font-medium text-white/80">Publik</span>
                            @else
                                <span class="size-1.5 rounded-full bg-white/30"></span>
                                <span class="text-white/40">Draft</span>
                            @endif
                        </div>

                        <div class="flex items-center gap-1.5">
                            {{-- Edit --}}
                            <a href="{{ route('admin.projects.edit', $project) }}" title="Edit Proyek"
                                class="flex size-8 items-center justify-center rounded-lg border border-white/10 text-white/50 transition hover:bg-white/5 hover:text-white">
                                <x-icon name="pencil" class="size-3.5" />
                            </a>

                            {{-- Toggle Publish --}}
                            <form method="POST" action="{{ route('admin.projects.toggle-publish', $project) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    title="{{ $project->published ? 'Jadikan Draft' : 'Publikasikan' }}"
                                    class="flex size-8 items-center justify-center rounded-lg border border-white/10 text-white/50 transition hover:bg-white/5 hover:text-white">
                                    <x-icon name="{{ $project->published ? 'toggle-right' : 'toggle-left' }}"
                                        class="size-4 {{ $project->published ? 'text-coral' : 'text-white/30' }}" />
                                </button>
                            </form>

                            {{-- External View --}}
                            <a href="{{ $project->demo_url ?: route('home') . '#projects' }}" target="_blank" rel="noopener" title="Lihat di Web"
                                class="flex size-8 items-center justify-center rounded-lg border border-white/10 text-white/50 transition hover:bg-white/5 hover:text-white">
                                <x-icon name="external-link" class="size-3.5" />
                            </a>

                            {{-- Delete --}}
                            <form method="POST" action="{{ route('admin.projects.destroy', $project) }}"
                                onsubmit="return confirm('Yakin ingin menghapus proyek &quot;{{ $project->title }}&quot;?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Hapus Proyek"
                                    class="flex size-8 items-center justify-center rounded-lg border border-white/10 text-white/50 transition hover:bg-red-500/10 hover:text-red-400">
                                    <x-icon name="trash" class="size-3.5" />
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-dashed border-white/10 bg-[#17171d] p-12 text-center">
                    <x-icon name="folder" class="mx-auto mb-3 size-10 text-white/20" />
                    <p class="text-sm text-white/60">Belum ada proyek portofolio terdaftar.</p>
                    <a href="{{ route('admin.projects.create') }}" class="mt-3 inline-block text-xs font-semibold text-coral hover:underline">
                        + Tambah Proyek Pertama &rarr;
                    </a>
                </div>
            @endforelse

            <div id="no-filter-match" class="hidden rounded-2xl border border-dashed border-white/10 bg-[#17171d] p-8 text-center">
                <p class="text-xs text-white/40">Tidak ada proyek yang sesuai dengan pencarian atau filter.</p>
            </div>
        </div>

        {{-- Footer summary --}}
        <div class="flex flex-wrap items-center justify-between gap-2 border-t border-white/5 pt-4 text-xs text-white/40">
            <span id="footer-count">Menampilkan {{ $totalCount }} proyek portofolio aktif.</span>
            <button type="button" onclick="window.location.reload()" class="transition hover:text-white">
                Sinkronkan Cache Navigasi
            </button>
        </div>
    </div>

    {{-- Client-side Filter & Search Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const filterButtons = document.querySelectorAll('.filter-btn');
            const searchInput = document.getElementById('project-search');
            const rows = document.querySelectorAll('.project-row');
            const noMatch = document.getElementById('no-filter-match');
            const footerCount = document.getElementById('footer-count');

            let currentFilter = 'all';
            let searchQuery = '';

            function updateList() {
                let visibleCount = 0;

                rows.forEach(row => {
                    const category = row.dataset.category;
                    const title = row.dataset.title || '';
                    const desc = row.dataset.description || '';

                    const matchesCategory = currentFilter === 'all' || category === currentFilter;
                    const matchesSearch = !searchQuery || title.includes(searchQuery) || desc.includes(searchQuery);

                    if (matchesCategory && matchesSearch) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                if (noMatch) {
                    noMatch.classList.toggle('hidden', visibleCount > 0);
                }

                if (footerCount) {
                    footerCount.textContent = `Menampilkan ${visibleCount} proyek portofolio.`;
                }
            }

            filterButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    filterButtons.forEach(b => {
                        b.classList.remove('active', 'bg-coral', 'text-white', 'shadow-md', 'shadow-coral/20', 'font-semibold');
                        b.classList.add('text-white/60', 'font-medium');
                    });

                    btn.classList.add('active', 'bg-coral', 'text-white', 'shadow-md', 'shadow-coral/20', 'font-semibold');
                    btn.classList.remove('text-white/60', 'font-medium');

                    currentFilter = btn.dataset.filter;
                    updateList();
                });
            });

            if (searchInput) {
                searchInput.addEventListener('input', (e) => {
                    searchQuery = e.target.value.toLowerCase().trim();
                    updateList();
                });
            }
        });
    </script>
@endsection