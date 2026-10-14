@props(['project'])

<article data-category="{{ $project->category }}" class="project-item group flex flex-col">
    <div class="relative overflow-hidden rounded-3xl bg-offwhite">
        <img src="{{ $project->image_url }}" data-fallback="{{ asset('images/project-placeholder.svg') }}"
            alt="Tampilan proyek {{ $project->title }}" width="1200" height="800" loading="lazy" decoding="async"
            class="aspect-[3/2] w-full object-cover transition duration-700 ease-out group-hover:scale-[1.04]">
        <span class="absolute left-4 top-4 rounded-full bg-white/95 px-3 py-1 text-xs font-medium text-ink">{{ $project->category_label }}</span>
    </div>

    <div class="flex flex-1 flex-col pt-6">
        <h3 class="text-2xl font-semibold tracking-tight">{{ $project->title }}</h3>
        <p class="mt-3 text-[0.95rem] leading-relaxed text-muted">{{ $project->description }}</p>

        @if ($project->technologies)
            <ul class="mt-5 flex flex-wrap gap-2" aria-label="Teknologi yang digunakan">
                @foreach ($project->technologies as $tech)
                    <li class="rounded-full border border-line px-3 py-1 text-xs text-muted">{{ $tech }}</li>
                @endforeach
            </ul>
        @endif

        @if ($project->demo_url || $project->repository_url)
            <div class="mt-6 flex flex-wrap gap-5 text-sm font-medium">
                @if ($project->demo_url)
                    <a href="{{ $project->demo_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 hover:text-coral">Live Demo <x-icon name="arrow-up-right" class="size-4" /></a>
                @endif
                @if ($project->repository_url)
                    <a href="{{ $project->repository_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 hover:text-coral">Source Code <x-icon name="arrow-up-right" class="size-4" /></a>
                @endif
            </div>
        @endif
    </div>
</article>
