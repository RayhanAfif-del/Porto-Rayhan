@extends('admin.layout')
@section('title', 'Pesan')

@section('content')
    <a href="{{ route('admin.messages.index') }}" class="text-sm text-neutral-500 hover:underline">← Kembali</a>

    <article class="mt-4 max-w-2xl rounded-xl bg-white p-6 shadow-sm">
        <h1 class="text-xl font-semibold">{{ $message->subject }}</h1>
        <p class="mt-1 text-sm text-neutral-500">
            {{ $message->name }} &lt;<a href="mailto:{{ $message->email }}" class="text-coral-dark hover:underline">{{ $message->email }}</a>&gt; · {{ $message->created_at->format('d M Y H:i') }}
        </p>
        <div class="mt-6 whitespace-pre-line text-sm leading-relaxed">{{ $message->message }}</div>

        <form method="POST" action="{{ route('admin.messages.destroy', $message) }}" class="mt-8" onsubmit="return confirm('Hapus pesan ini?')">
            @csrf @method('DELETE')
            <button class="text-sm text-red-600 hover:underline">Hapus pesan</button>
        </form>
    </article>
@endsection
