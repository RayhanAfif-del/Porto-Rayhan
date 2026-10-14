@extends('admin.layout')
@section('title', 'Sertifikat')

@section('content')
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Sertifikat</h1>
        <a href="{{ route('admin.certificates.create') }}" class="btn-coral">Tambah sertifikat</a>
    </div>

    <div class="mt-6 divide-y rounded-xl bg-white shadow-sm">
        @forelse ($items as $c)
            <div class="flex items-center justify-between gap-4 px-5 py-4">
                <div class="min-w-0"><p class="truncate font-medium">{{ $c->title }}</p><p class="truncate text-sm text-neutral-500">{{ $c->issuer }}@if ($c->issued_at) · {{ $c->issued_at->format('d M Y') }}@endif</p></div>
                <div class="shrink-0 text-sm">
                    <a href="{{ route('admin.certificates.edit', $c) }}" class="font-medium text-coral-dark hover:underline">Edit</a>
                    <form method="POST" action="{{ route('admin.certificates.destroy', $c) }}" class="ml-3 inline" onsubmit="return confirm('Hapus sertifikat ini?')">@csrf @method('DELETE')<button class="text-red-600 hover:underline">Hapus</button></form>
                </div>
            </div>
        @empty
            <p class="px-5 py-8 text-center text-sm text-neutral-500">Belum ada sertifikat.</p>
        @endforelse
    </div>
@endsection
