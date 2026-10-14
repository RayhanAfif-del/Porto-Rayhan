@extends('admin.layout')
@section('title', $item->exists ? 'Edit sertifikat' : 'Tambah sertifikat')

@section('content')
    <h1 class="text-2xl font-semibold">{{ $item->exists ? 'Edit sertifikat' : 'Tambah sertifikat' }}</h1>

    <form method="POST" enctype="multipart/form-data" class="mt-6 max-w-2xl space-y-5 rounded-xl bg-white p-6 shadow-sm"
        action="{{ $item->exists ? route('admin.certificates.update', $item) : route('admin.certificates.store') }}">
        @csrf
        @if ($item->exists) @method('PUT') @endif

        <x-admin.field name="title" label="Nama sertifikat" :value="$item->title" required />
        <x-admin.field name="issuer" label="Penyelenggara" :value="$item->issuer" required />
        <x-admin.field name="issued_at" label="Tanggal terbit" type="date" :value="$item->issued_at?->format('Y-m-d')" />
        <x-admin.field name="verification_url" label="Tautan verifikasi" type="url" :value="$item->verification_url" hint="Kosongkan jika tidak ada." />

        <div>
            @if ($item->image)<img src="{{ $item->image_url }}" alt="" class="mb-3 h-28 rounded-lg object-cover">@endif
            <x-admin.field name="image" label="Gambar sertifikat" type="file" accept="image/png,image/jpeg,image/webp" hint="JPG, PNG, atau WebP, maks 4 MB." />
        </div>

        <x-admin.field name="sort_order" label="Urutan" type="number" :value="$item->sort_order ?? 0" min="0" />

        <div class="flex gap-3">
            <button type="submit" class="btn-coral">Simpan</button>
            <a href="{{ route('admin.certificates.index') }}" class="btn-ghost-dark">Batal</a>
        </div>
    </form>
@endsection
