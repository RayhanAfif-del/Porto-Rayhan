@extends('admin.layout')
@section('title', 'Pesan')

@section('content')
    <h1 class="text-2xl font-semibold">Pesan masuk</h1>

    <div class="mt-6 divide-y rounded-xl bg-white shadow-sm">
        @forelse ($messages as $m)
            <a href="{{ route('admin.messages.show', $m) }}" class="flex items-center justify-between gap-4 px-5 py-4 hover:bg-neutral-50">
                <span class="min-w-0">
                    <span class="block truncate {{ $m->read_at ? '' : 'font-semibold' }}">{{ $m->subject }}</span>
                    <span class="block truncate text-sm text-neutral-500">{{ $m->name }} · {{ $m->email }} · {{ $m->created_at->diffForHumans() }}</span>
                </span>
                @unless ($m->read_at)<span class="shrink-0 rounded-full bg-coral px-2 py-0.5 text-xs text-white">Baru</span>@endunless
            </a>
        @empty
            <p class="px-5 py-8 text-center text-sm text-neutral-500">Belum ada pesan.</p>
        @endforelse
    </div>

    <div class="mt-6">{{ $messages->links() }}</div>
@endsection
