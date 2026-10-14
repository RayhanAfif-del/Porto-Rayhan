@php
    $filters = ['all' => 'All Projects', 'web' => 'Web Development', 'uiux' => 'UI/UX Design'];
@endphp

<section id="projects" class="bg-paper py-24 sm:py-32 lg:py-40">
    <div class="container-x">
        <div class="space-y-6">
            <div>
                <x-section-label data-reveal>PROJECTS</x-section-label>
                <h2 data-reveal class="display" style="font-size:clamp(2.5rem,6.5vw,5.5rem)">Selected Works</h2>
            </div>
            <p data-reveal class="max-w-2xl text-base leading-relaxed text-muted">
                A selection of projects where design, functionality, and problem-solving come together.
            </p>
        </div>

        <div role="group" aria-label="Filter proyek" class="mt-12 flex flex-wrap gap-2 sm:mt-16">
            @foreach ($filters as $value => $label)
                <button type="button" data-filter="{{ $value }}" aria-pressed="{{ $loop->first ? 'true' : 'false' }}"
                    class="btn-pill border hover:border-ink {{ $loop->first ? 'border-ink bg-ink text-white' : 'border-line text-muted hover:text-ink' }}">{{ $label }}</button>
            @endforeach
        </div>

        @if ($projects->isEmpty())
            <p class="mt-14 rounded-3xl border border-dashed border-line p-10 text-center text-muted">Belum ada proyek yang dipublikasikan. Jalankan <code>php artisan db:seed</code> atau tambahkan data ke tabel <code>projects</code>.</p>
        @else
            <div class="mt-12 grid gap-x-8 gap-y-16 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($projects as $project)
                    <x-project-card :project="$project" />
                @endforeach
            </div>
            <p data-filter-empty class="mt-14 hidden rounded-3xl border border-dashed border-line p-10 text-center text-muted">Belum ada proyek pada kategori ini.</p>
        @endif
    </div>
</section>
