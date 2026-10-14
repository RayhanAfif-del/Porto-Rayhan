<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Masuk Admin — Portofolio Rayhan</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    @vite(['resources/css/app.css'])
</head>
<body class="flex min-h-[100dvh] flex-col bg-[#131317] text-white"
    style="background-image: radial-gradient(circle at 18% 48%, rgb(255 90 77 / .08), transparent 38%), radial-gradient(circle at 86% 72%, rgb(255 90 77 / .06), transparent 36%);">

    {{-- Bilah atas --}}
    <div class="flex items-center justify-between px-5 py-5 sm:px-6">
        <p class="flex items-center gap-2.5 font-mono text-xs tracking-[0.14em] text-white/70">
            <span class="size-2 rounded-full bg-coral" aria-hidden="true"></span>SECURE CORE // ADMIN
        </p>
        @if (request()->isSecure())
            <p class="flex items-center gap-1.5 rounded-full bg-white/5 px-3 py-1.5 text-xs font-medium text-white/80">
                <x-icon name="lock" class="size-3.5 text-coral" /> Encrypted Session
            </p>
        @endif
    </div>

    <main class="flex flex-1 flex-col items-center justify-center px-5 py-10">
        <p class="mb-6 flex items-center gap-2 rounded-full border border-white/10 bg-white/[0.04] px-4 py-1.5 font-mono text-[0.7rem] tracking-[0.2em] text-white/70">
            <span class="size-1.5 rounded-full bg-coral" aria-hidden="true"></span>PORTAL ADMIN
        </p>

        <form method="POST" action="{{ url('/admin/login') }}" class="w-full max-w-[27.5rem] rounded-3xl border border-white/10 bg-[#1c1c21] p-6 shadow-2xl shadow-black/40 sm:p-9">
            @csrf

            <div class="text-center">
                <p class="text-3xl font-semibold tracking-tight">Masuk ke Dashboard<span class="text-coral">.</span></p>
                <p class="mt-2 text-sm text-white/60">Masukan Email & Password Anda Untuk Masuk Ke Dashboard</p>
            </div>

            <div class="mt-9 space-y-5">
                <div>
                    <label for="email" class="mb-2 block text-xs font-medium text-white/70">Email</label>
                    <div class="relative">
                        <x-icon name="at" class="pointer-events-none absolute left-4 top-1/2 size-[1.15rem] -translate-y-1/2 text-white/45" />
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                            placeholder="Masukan Email Anda" @error('email') aria-invalid="true" aria-describedby="login-error" @enderror
                            class="field pl-11">
                    </div>
                </div>

                <div>
                    <label for="password" class="mb-2 block text-xs font-medium text-white/70">Password</label>
                    <div class="relative">
                        <x-icon name="key" class="pointer-events-none absolute left-4 top-1/2 size-[1.15rem] -translate-y-1/2 text-white/45" />
                        <input id="password" name="password" type="password" required autocomplete="current-password"
                            placeholder="Masukan Password Anda" class="field px-11">
                        <button type="button" id="toggle-password" aria-label="Tampilkan kata sandi" aria-pressed="false"
                            class="absolute right-3 top-1/2 flex size-8 -translate-y-1/2 items-center justify-center rounded-lg text-white/45 transition hover:text-white">
                            <x-icon name="eye" class="size-[1.15rem]" data-eye="show" />
                            <x-icon name="eye-off" class="hidden size-[1.15rem]" data-eye="hide" />
                        </button>
                    </div>
                </div>

                @error('email')
                    <p id="login-error" role="alert" class="rounded-xl border border-coral/30 bg-coral/10 px-4 py-3 text-sm text-red-200">{{ $message }}</p>
                @enderror

                <div class="flex items-center justify-between gap-4 text-xs text-white/70">
                    <label class="flex cursor-pointer items-center gap-2.5">
                        <input type="checkbox" name="remember" value="1" class="size-4 rounded accent-coral"> Ingat saya
                    </label>
                    <span class="font-mono text-[0.7rem] text-white/40">IP: {{ $maskedIp }}</span>
                </div>

                <button type="submit"
                    class="flex w-full items-center justify-center gap-2 rounded-xl bg-coral px-5 py-3.5 text-sm font-semibold text-ink shadow-[0_8px_28px_rgb(255_90_77/0.28)] transition hover:bg-coral-dark">
                    Masuk ke Konsol <x-icon name="arrow-right" class="size-4" />
                </button>
            </div>
        </form>

        <a href="{{ route('home') }}" class="mt-12 flex items-center gap-2 text-sm text-white/70 transition hover:text-white">
            <x-icon name="arrow-left" class="size-4" /> Kembali ke Portofolio Utama
        </a>
    </main>

    <footer class="bg-black/20 px-5 py-5 text-center text-xs text-white/45">
        &copy; {{ date('Y') }} Muhammad Rayhan &bull; System Portal
    </footer>

    <script>
        (function () {
            var btn = document.getElementById('toggle-password');
            var input = document.getElementById('password');
            if (!btn || !input) return;
            btn.addEventListener('click', function () {
                var show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                btn.setAttribute('aria-pressed', String(show));
                btn.setAttribute('aria-label', show ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
                btn.querySelector('[data-eye="show"]').classList.toggle('hidden', show);
                btn.querySelector('[data-eye="hide"]').classList.toggle('hidden', !show);
            });
        })();
    </script>
</body>
</html>
