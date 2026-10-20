@extends('admin.layout')
@section('title', 'Detail Pesan')

@section('content')
    {{-- Back --}}
    <a href="{{ route('admin.messages.index') }}"
        class="inline-flex items-center gap-2 text-xs font-medium text-white/40 transition hover:text-white/70">
        <x-icon name="arrow-left" class="size-3.5" />
        Kembali ke Pesan Masuk
    </a>

    {{-- Flash: Reply Sent --}}
    @if (session('reply_sent'))
        <div role="status" class="mt-5 flex items-center gap-3 rounded-2xl border border-emerald-500/20 bg-emerald-500/10 px-5 py-3.5 text-sm text-emerald-300">
            <x-icon name="check" class="size-4 shrink-0 text-emerald-400" />
            <span>{{ session('reply_sent') }}</span>
        </div>
    @endif

    {{-- Validation errors --}}
    @if ($errors->any())
        <div role="alert" class="mt-5 flex items-start gap-3 rounded-2xl border border-coral/30 bg-coral/10 px-5 py-3.5 text-sm text-red-300">
            <x-icon name="alert" class="mt-0.5 size-4 shrink-0" />
            <ul class="space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mt-5 grid gap-5 lg:grid-cols-12">

        {{-- ── Left: Original Message ───────────────────────────────────────── --}}
        <div class="lg:col-span-7">
            <div class="overflow-hidden rounded-2xl border border-white/[0.08] bg-[#16161c]">

                {{-- Card Header --}}
                <div class="border-b border-white/[0.06] px-6 py-5">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                        <div class="flex min-w-0 items-center gap-4">
                            {{-- Avatar --}}
                            <div class="flex size-12 shrink-0 items-center justify-center rounded-xl border border-white/10 bg-gradient-to-tr from-coral/25 to-white/5 text-lg font-bold text-white/90">
                                {{ strtoupper(substr($message->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <h1 class="truncate text-base font-bold tracking-tight text-white sm:text-lg">{{ $message->subject }}</h1>
                                <div class="mt-1 flex flex-wrap items-center gap-x-2 gap-y-1 text-xs text-white/45">
                                    <span class="font-medium text-white/70">{{ $message->name }}</span>
                                    <span>·</span>
                                    <span>{{ $message->email }}</span>
                                    <span>·</span>
                                    <time datetime="{{ $message->created_at->toIso8601String() }}">
                                        {{ $message->created_at->format('d M Y, H:i') }}
                                    </time>
                                </div>
                            </div>
                        </div>

                        {{-- Status badges --}}
                        <div class="flex flex-wrap items-center gap-2 sm:shrink-0">
                            @if ($message->read_at)
                                <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-500/20 bg-emerald-500/10 px-3 py-1 text-[11px] font-medium text-emerald-400">
                                    <x-icon name="check" class="size-3" /> Dibaca
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 rounded-full border border-coral/25 bg-coral/10 px-3 py-1 text-[11px] font-medium text-coral">
                                    <span class="size-1.5 animate-pulse rounded-full bg-coral"></span> Baru
                                </span>
                            @endif
                            @if ($message->ip_address)
                                <span class="hidden rounded-full border border-white/10 bg-white/[0.04] px-3 py-1 font-mono text-[10px] text-white/35 sm:inline-block">
                                    {{ $message->ip_address }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Message Body --}}
                <div class="px-6 py-6">
                    <div class="whitespace-pre-line text-sm leading-relaxed text-white/75">{{ $message->message }}</div>
                </div>

                {{-- Footer Actions --}}
                <div class="flex items-center justify-between border-t border-white/[0.06] px-6 py-4">
                    <p class="text-[11px] text-white/25">
                        @if ($message->read_at)
                            Dibaca {{ $message->read_at->diffForHumans() }}
                        @else
                            Belum dibaca
                        @endif
                    </p>
                    <form method="POST" action="{{ route('admin.messages.destroy', $message) }}"
                        onsubmit="return confirm('Yakin ingin menghapus pesan ini?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="inline-flex items-center gap-1.5 rounded-xl border border-red-500/20 bg-red-500/10 px-3.5 py-2 text-xs font-semibold text-red-400 transition hover:border-red-500/40 hover:bg-red-500/20">
                            <x-icon name="trash" class="size-3.5" />
                            Hapus Pesan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- ── Right: Reply Form ────────────────────────────────────────────── --}}
        <div class="lg:col-span-5">
            <div class="overflow-hidden rounded-2xl border border-white/[0.08] bg-[#16161c]">

                {{-- Form Header --}}
                <div class="border-b border-white/[0.06] px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="flex size-9 items-center justify-center rounded-xl border border-coral/25 bg-coral/10">
                            <x-icon name="send" class="size-4 text-coral" />
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-white">Balas Pesan</h2>
                            <p class="text-[11px] text-white/40">Kirim ke: <span class="text-coral">{{ $message->email }}</span></p>
                        </div>
                    </div>
                </div>

                {{-- Reply Form --}}
                <form action="{{ route('admin.messages.reply', $message) }}" method="POST" id="reply-form" class="flex flex-col">
                    @csrf

                    {{-- Quoted preview --}}
                    <div class="mx-6 mt-5 rounded-xl border border-white/[0.06] bg-white/[0.02] p-3">
                        <p class="mb-1 font-mono text-[10px] font-semibold uppercase tracking-wider text-white/30">Membalas pesan</p>
                        <p class="truncate text-xs text-white/50">{{ $message->subject }}</p>
                    </div>

                    {{-- Textarea --}}
                    <div class="px-6 pt-4">
                        <label for="reply_body" class="mb-2 block font-mono text-[11px] font-semibold uppercase tracking-wider text-white/50">
                            Isi Balasan <span class="text-coral">*</span>
                        </label>
                        <textarea
                            id="reply_body"
                            name="reply_body"
                            rows="10"
                            required
                            minlength="10"
                            maxlength="5000"
                            placeholder="Tulis balasan Anda di sini..."
                            class="w-full resize-y rounded-xl border border-white/10 bg-white/[0.04] p-4 text-sm text-white placeholder:text-white/25 transition focus:border-coral/50 focus:bg-white/[0.06] focus:outline-none leading-relaxed"
                        >{{ old('reply_body') }}</textarea>
                        <div class="mt-1.5 flex justify-end">
                            <span id="char-count" class="font-mono text-[10px] text-white/25">0 / 5000</span>
                        </div>
                    </div>

                    {{-- Send Button --}}
                    <div class="border-t border-white/[0.06] px-6 py-4 mt-2">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <p class="text-[11px] leading-relaxed text-white/30">
                                Email akan dikirim dari<br>
                                <span class="text-white/50">{{ config('mail.from.address') }}</span>
                            </p>
                            <button type="submit" id="send-btn"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-coral px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-coral/25 transition hover:bg-coral-dark active:scale-[0.98] sm:w-auto w-full">
                                <x-icon name="send" class="size-4" />
                                <span id="send-label">Kirim Balasan</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Mail config notice (dev only) --}}
            @if (config('mail.default') === 'log')
                <div class="mt-4 flex items-start gap-3 rounded-2xl border border-amber-500/20 bg-amber-500/10 px-4 py-3.5 text-xs text-amber-300">
                    <x-icon name="alert" class="mt-0.5 size-4 shrink-0 text-amber-400" />
                    <div>
                        <p class="font-semibold">Mode pengembangan</p>
                        <p class="mt-0.5 text-amber-300/70">Email tidak benar-benar terkirim. Atur <code class="text-amber-200">MAIL_MAILER=smtp</code> di <code class="text-amber-200">.env</code> untuk mengirim email nyata.</p>
                    </div>
                </div>
            @endif
        </div>

    </div>

    <script>
        // Character counter
        const textarea = document.getElementById('reply_body');
        const counter  = document.getElementById('char-count');
        const updateCount = () => {
            const len = textarea.value.length;
            counter.textContent = `${len} / 5000`;
            counter.classList.toggle('text-coral', len > 4500);
        };
        textarea?.addEventListener('input', updateCount);
        updateCount();

        // Prevent double submit
        document.getElementById('reply-form')?.addEventListener('submit', () => {
            const btn   = document.getElementById('send-btn');
            const label = document.getElementById('send-label');
            if (btn && label) {
                btn.disabled  = true;
                label.textContent = 'Mengirim…';
            }
        });
    </script>
@endsection
