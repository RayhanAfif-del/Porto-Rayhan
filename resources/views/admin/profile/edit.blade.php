@extends('admin.layout')
@section('title', 'Profil & Pengaturan Akun')

@section('content')
    <div class="space-y-6">
        {{-- Header Section --}}
        <div class="flex flex-col justify-between gap-4 lg:flex-row lg:items-center">
            <div>
                <div class="flex items-center gap-2 font-mono text-[11px] tracking-wider text-white/50 uppercase">
                    <a href="{{ route('admin.dashboard') }}" class="transition hover:text-white">Admin</a>
                    <span class="text-white/20">/</span>
                    <span class="text-white/80">Profil & Akun</span>
                </div>
                <h1 class="mt-2 text-2xl font-bold tracking-tight text-white sm:text-3xl">
                    Profil & Pengaturan Akun
                </h1>
                <p class="mt-1 text-xs text-white/50 sm:text-sm">
                    Kelola nama pengguna, alamat email untuk login, dan pembaruan kata sandi akun administrator Anda.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.dashboard') }}"
                    class="rounded-xl border border-white/10 bg-white/[0.04] px-4 py-2.5 text-xs font-medium text-white/70 transition hover:bg-white/[0.08] hover:text-white">
                    Kembali ke Dashboard
                </a>
            </div>
        </div>

        {{-- Main Layout Grid --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
            {{-- Left Column (8 cols): Profile & Password Forms --}}
            <div class="space-y-6 lg:col-span-8">
                {{-- Form 1: Informasi Profil Akun --}}
                <div class="rounded-2xl border border-white/5 bg-[#17171d] p-6 shadow-sm">
                    <div class="flex items-center justify-between border-b border-white/5 pb-4 mb-6">
                        <div class="flex items-center gap-2 text-white">
                            <div class="flex size-8 items-center justify-center rounded-lg bg-coral/10 text-coral">
                                <x-icon name="user" class="size-4" />
                            </div>
                            <div>
                                <h2 class="text-sm font-bold tracking-tight">Informasi Profil Admin</h2>
                                <p class="text-[11px] text-white/40">Perbarui nama pengguna dan alamat email akun Anda.</p>
                            </div>
                        </div>
                        <span class="font-mono text-[10px] tracking-widest text-white/40 uppercase">Identitas</span>
                    </div>

                    <form method="POST" action="{{ route('admin.profile.update') }}" class="space-y-5">
                        @csrf
                        @method('PATCH')

                        {{-- Nama Lengkap --}}
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label for="name" class="font-mono text-[11px] font-semibold tracking-wider text-white/70 uppercase">
                                    Nama Lengkap <span class="text-coral">*</span>
                                </label>
                                <span class="font-mono text-[10px] text-white/40">Ditampilkan di header</span>
                            </div>
                            <div class="relative">
                                <x-icon name="user" class="pointer-events-none absolute left-3.5 top-1/2 size-4 -translate-y-1/2 text-white/35" />
                                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                                    placeholder="Contoh: Muhammad Rayhan"
                                    class="w-full rounded-xl border border-white/10 bg-white/[0.03] py-2.5 pl-10 pr-4 text-xs text-white placeholder:text-white/30 focus:border-coral focus:outline-none transition @error('name') border-red-500/50 @enderror">
                            </div>
                            @error('name')
                                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email Login --}}
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label for="email" class="font-mono text-[11px] font-semibold tracking-wider text-white/70 uppercase">
                                    Alamat Email Login <span class="text-coral">*</span>
                                </label>
                                <span class="font-mono text-[10px] text-white/40">Email untuk login</span>
                            </div>
                            <div class="relative">
                                <x-icon name="mail" class="pointer-events-none absolute left-3.5 top-1/2 size-4 -translate-y-1/2 text-white/35" />
                                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                                    placeholder="nama@gmail.com"
                                    class="w-full rounded-xl border border-white/10 bg-white/[0.03] py-2.5 pl-10 pr-4 text-xs text-white placeholder:text-white/30 focus:border-coral focus:outline-none transition @error('email') border-red-500/50 @enderror">
                            </div>
                            @error('email')
                                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-1.5 text-[11px] text-white/40">
                                💡 Pastikan Anda mengingat email ini, karena digunakan untuk login ke <span class="font-mono text-white/60">/admin/login</span>.
                            </p>
                        </div>

                        {{-- Submit Button --}}
                        <div class="pt-3 border-t border-white/5 flex items-center justify-end">
                            <button type="submit"
                                class="flex items-center gap-2 rounded-xl bg-coral px-5 py-2.5 text-xs font-semibold text-white shadow-lg shadow-coral/25 transition hover:bg-coral-dark">
                                <x-icon name="check" class="size-3.5" />
                                <span>Simpan Profil</span>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Form 2: Perbarui Kata Sandi --}}
                <div class="rounded-2xl border border-white/5 bg-[#17171d] p-6 shadow-sm">
                    <div class="flex items-center justify-between border-b border-white/5 pb-4 mb-6">
                        <div class="flex items-center gap-2 text-white">
                            <div class="flex size-8 items-center justify-center rounded-lg bg-coral/10 text-coral">
                                <x-icon name="lock" class="size-4" />
                            </div>
                            <div>
                                <h2 class="text-sm font-bold tracking-tight">Ganti Kata Sandi (Password)</h2>
                                <p class="text-[11px] text-white/40">Pastikan akun menggunakan kata sandi yang aman dan tidak mudah ditebak.</p>
                            </div>
                        </div>
                        <span class="font-mono text-[10px] tracking-widest text-white/40 uppercase">Keamanan</span>
                    </div>

                    <form method="POST" action="{{ route('admin.profile.password.update') }}" class="space-y-5">
                        @csrf
                        @method('PUT')

                        {{-- Password Saat Ini --}}
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label for="current_password" class="font-mono text-[11px] font-semibold tracking-wider text-white/70 uppercase">
                                    Kata Sandi Saat Ini <span class="text-coral">*</span>
                                </label>
                                <span class="font-mono text-[10px] text-white/40">Verifikasi identitas</span>
                            </div>
                            <div class="relative">
                                <x-icon name="lock" class="pointer-events-none absolute left-3.5 top-1/2 size-4 -translate-y-1/2 text-white/35" />
                                <input type="password" id="current_password" name="current_password" required
                                    placeholder="Masukkan kata sandi saat ini"
                                    class="w-full rounded-xl border border-white/10 bg-white/[0.03] py-2.5 pl-10 pr-10 text-xs text-white placeholder:text-white/30 focus:border-coral focus:outline-none transition @error('current_password') border-red-500/50 @enderror">
                                <button type="button" onclick="togglePasswordVisibility('current_password', this)"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-white/40 hover:text-white transition p-1"
                                    aria-label="Lihat kata sandi">
                                    <x-icon name="eye" class="size-4" />
                                </button>
                            </div>
                            @error('current_password')
                                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password Baru & Konfirmasi (2 Grid Columns) --}}
                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                            {{-- Password Baru --}}
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <label for="password" class="font-mono text-[11px] font-semibold tracking-wider text-white/70 uppercase">
                                        Kata Sandi Baru <span class="text-coral">*</span>
                                    </label>
                                    <span class="font-mono text-[10px] text-white/40">Min. 8 karakter</span>
                                </div>
                                <div class="relative">
                                    <x-icon name="key" class="pointer-events-none absolute left-3.5 top-1/2 size-4 -translate-y-1/2 text-white/35" />
                                    <input type="password" id="password" name="password" required minlength="8"
                                        placeholder="Kata sandi baru"
                                        class="w-full rounded-xl border border-white/10 bg-white/[0.03] py-2.5 pl-10 pr-10 text-xs text-white placeholder:text-white/30 focus:border-coral focus:outline-none transition @error('password') border-red-500/50 @enderror">
                                    <button type="button" onclick="togglePasswordVisibility('password', this)"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-white/40 hover:text-white transition p-1"
                                        aria-label="Lihat kata sandi">
                                        <x-icon name="eye" class="size-4" />
                                    </button>
                                </div>
                                @error('password')
                                    <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Konfirmasi Password Baru --}}
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <label for="password_confirmation" class="font-mono text-[11px] font-semibold tracking-wider text-white/70 uppercase">
                                        Ulangi Kata Sandi Baru <span class="text-coral">*</span>
                                    </label>
                                    <span class="font-mono text-[10px] text-white/40">Harus sama</span>
                                </div>
                                <div class="relative">
                                    <x-icon name="key" class="pointer-events-none absolute left-3.5 top-1/2 size-4 -translate-y-1/2 text-white/35" />
                                    <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8"
                                        placeholder="Ketik ulang kata sandi baru"
                                        class="w-full rounded-xl border border-white/10 bg-white/[0.03] py-2.5 pl-10 pr-10 text-xs text-white placeholder:text-white/30 focus:border-coral focus:outline-none transition">
                                    <button type="button" onclick="togglePasswordVisibility('password_confirmation', this)"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-white/40 hover:text-white transition p-1"
                                        aria-label="Lihat kata sandi">
                                        <x-icon name="eye" class="size-4" />
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="pt-3 border-t border-white/5 flex items-center justify-end">
                            <button type="submit"
                                class="flex items-center gap-2 rounded-xl bg-coral px-5 py-2.5 text-xs font-semibold text-white shadow-lg shadow-coral/25 transition hover:bg-coral-dark">
                                <x-icon name="lock" class="size-3.5" />
                                <span>Perbarui Kata Sandi</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Right Column (4 cols): User Overview Card & Security Tips --}}
            <div class="space-y-6 lg:col-span-4">
                {{-- User Info Summary Card --}}
                <div class="rounded-2xl border border-white/5 bg-[#17171d] p-6 shadow-sm text-center">
                    <div class="relative mx-auto flex size-20 items-center justify-center rounded-full border-2 border-coral/30 bg-gradient-to-tr from-coral/40 via-white/10 to-coral/10 font-bold text-2xl text-white shadow-xl shadow-coral/10">
                        {{ strtoupper(substr($user->name ?? 'A', 0, 1)) }}
                        <span class="absolute bottom-0 right-0 size-5 rounded-full border-2 border-[#17171d] bg-emerald-400" title="Akun Aktif"></span>
                    </div>

                    <h3 class="mt-4 text-base font-bold text-white">{{ $user->name }}</h3>
                    <p class="text-xs text-white/50 font-mono mt-0.5 truncate">{{ $user->email }}</p>

                    <div class="mt-3 inline-flex items-center gap-1.5 rounded-full border border-coral/20 bg-coral/10 px-3 py-1 text-[11px] font-semibold text-coral">
                        <x-icon name="shield" class="size-3" />
                        <span>Superadministrator</span>
                    </div>

                    <div class="mt-6 space-y-3 border-t border-white/5 pt-4 text-left text-xs">
                        <div class="flex items-center justify-between">
                            <span class="text-white/40">Status Akun:</span>
                            <span class="inline-flex items-center gap-1 font-medium text-emerald-400">
                                <span class="size-1.5 rounded-full bg-emerald-400"></span>
                                Terverifikasi
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-white/40">Hak Akses:</span>
                            <span class="font-medium text-white/80">Akses Penuh (Full Admin)</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-white/40">Enkripsi Sandi:</span>
                            <span class="font-mono text-[11px] text-white/60">Bcrypt / Hashed</span>
                        </div>
                    </div>
                </div>

                {{-- Security Best Practices Card --}}
                <div class="rounded-2xl border border-white/5 bg-[#17171d] p-5 shadow-sm space-y-3">
                    <div class="flex items-center gap-2 text-white">
                        <x-icon name="lightbulb" class="size-4 text-amber-400" />
                        <h4 class="text-xs font-bold tracking-tight">Tips Keamanan Akun</h4>
                    </div>

                    <ul class="space-y-2.5 text-[11px] leading-relaxed text-white/50">
                        <li class="flex items-start gap-2">
                            <span class="mt-1 size-1 rounded-full bg-coral shrink-0"></span>
                            <span>Gunakan minimal 8 karakter kombinasi huruf besar, kecil, dan angka.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="mt-1 size-1 rounded-full bg-coral shrink-0"></span>
                            <span>Jangan berikan kata sandi login kepada siapapun.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="mt-1 size-1 rounded-full bg-coral shrink-0"></span>
                            <span>Jika Anda mengubah email, gunakan email yang baru saat login berikutnya.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Script toggle password visibility --}}
    <script>
        function togglePasswordVisibility(inputId, btn) {
            const input = document.getElementById(inputId);
            if (!input) return;

            if (input.type === 'password') {
                input.type = 'text';
                btn.classList.add('text-coral');
                btn.classList.remove('text-white/40');
            } else {
                input.type = 'password';
                btn.classList.remove('text-coral');
                btn.classList.add('text-white/40');
            }
        }
    </script>
@endsection
