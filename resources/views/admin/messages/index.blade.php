@extends('admin.layout')
@section('title', 'Pesan Masuk')

@section('content')
    {{-- Header --}}
    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-white">Pesan Masuk</h1>
            <p class="mt-1 text-xs text-white/40">Semua pesan yang dikirim melalui formulir kontak.</p>
        </div>
        <div class="flex items-center gap-2">
            @php $unread = $messages->getCollection()->filter(fn($m) => is_null($m->read_at))->count(); @endphp
            @if($unread > 0)
                <span class="inline-flex items-center gap-1.5 rounded-full border border-coral/30 bg-coral/10 px-3 py-1 text-xs font-semibold text-coral">
                    <span class="size-1.5 rounded-full bg-coral"></span>
                    {{ $unread }} belum dibaca
                </span>
            @endif
            <span class="rounded-full border border-white/10 bg-white/[0.04] px-3 py-1 text-xs text-white/50">
                {{ $messages->total() }} total pesan
            </span>
        </div>
    </div>

    {{-- Empty State --}}
    @if ($messages->isEmpty())
        <div class="mt-8 flex flex-col items-center justify-center rounded-2xl border border-dashed border-white/10 bg-white/[0.02] py-20 text-center">
            <div class="flex size-16 items-center justify-center rounded-2xl border border-white/10 bg-white/[0.04]">
                <x-icon name="mail" class="size-7 text-white/30" />
            </div>
            <h3 class="mt-4 text-sm font-semibold text-white/70">Belum ada pesan</h3>
            <p class="mt-1 max-w-xs text-xs leading-relaxed text-white/35">
                Pesan dari pengunjung yang mengisi formulir kontak akan muncul di sini.
            </p>
        </div>

    @else
        {{-- Message List --}}
        <div class="mt-6 overflow-hidden rounded-2xl border border-white/[0.08] bg-[#16161c]">
            <div class="divide-y divide-white/[0.06]">
                @foreach ($messages as $m)
                    <a href="{{ route('admin.messages.show', $m) }}"
                        class="group flex items-start gap-4 px-5 py-4 transition hover:bg-white/[0.03] sm:items-center">

                        {{-- Avatar / Initials --}}
                        <div class="flex size-10 shrink-0 items-center justify-center rounded-xl border border-white/10 bg-gradient-to-tr from-coral/20 to-white/5 text-sm font-bold text-white/80">
                            {{ strtoupper(substr($m->name, 0, 1)) }}
                        </div>

                        {{-- Content --}}
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-sm {{ $m->read_at ? 'font-medium text-white/70' : 'font-semibold text-white' }} truncate">
                                    {{ $m->subject }}
                                </span>
                                @unless ($m->read_at)
                                    <span class="shrink-0 rounded-full bg-coral px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-white">Baru</span>
                                @endunless
                            </div>
                            <p class="mt-0.5 truncate text-xs text-white/40">
                                <span class="font-medium text-white/60">{{ $m->name }}</span>
                                · {{ $m->email }}
                                · {{ $m->created_at->diffForHumans() }}
                            </p>
                        </div>

                        {{-- Arrow --}}
                        <x-icon name="arrow-up-right" class="size-4 shrink-0 text-white/20 transition group-hover:translate-x-0.5 group-hover:-translate-y-0.5 group-hover:text-white/50" />
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Pagination --}}
        @if ($messages->hasPages())
            <div class="mt-6">{{ $messages->links() }}</div>
        @endif
    @endif
@endsection
