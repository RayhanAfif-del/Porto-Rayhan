@php
    $c = [
        'email' => setting('contact.email', config('portfolio.contact.email')),
        'github' => setting('contact.github', config('portfolio.contact.github')),
        'linkedin' => setting('contact.linkedin', config('portfolio.contact.linkedin')),
        'instagram' => setting('contact.instagram', config('portfolio.contact.instagram')),
        'tiktok' => setting('contact.tiktok', config('portfolio.contact.tiktok')),
        'whatsapp' => setting('contact.whatsapp', config('portfolio.contact.whatsapp')),
    ];
    $waClean = !empty($c['whatsapp']) ? preg_replace('/\D/', '', $c['whatsapp']) : null;
    $wa = $waClean ? 'https://wa.me/'.$waClean : null;

    $channels = [
        ['Email', $c['email'] ? 'mailto:'.$c['email'] : null, $c['email'], 'mail'],
        ['GitHub', $c['github'], $c['github'] ? preg_replace('#^https?://(www\.)?#', '', $c['github']) : null, 'github'],
        ['LinkedIn', $c['linkedin'], $c['linkedin'] ? preg_replace('#^https?://(www\.)?#', '', $c['linkedin']) : null, 'linkedin'],
        ['Instagram', $c['instagram'], $c['instagram'] ? preg_replace('#^https?://(www\.)?#', '', $c['instagram']) : null, 'instagram'],
        ['TikTok', $c['tiktok'], $c['tiktok'] ? preg_replace('#^https?://(www\.)?#', '', $c['tiktok']) : null, 'tiktok'],
    ];

    if ($wa && $waClean) {
        $channels[] = ['WhatsApp', $wa, '+'.$waClean, 'whatsapp'];
    }
@endphp

<section id="contact" class="bg-ink py-20 text-white sm:py-28 lg:py-32">
    <div class="container-x">
        <div class="grid items-stretch gap-10 lg:grid-cols-12 lg:gap-14">
            {{-- Left Column: Header, Narrative & Quick Contact Cards (Left Slide) --}}
            <div data-reveal="left" class="lg:col-span-5 flex flex-col justify-between">
                <div>
                    <x-section-label :dark="true">{{ setting('contact.label', 'CONTACT') }}</x-section-label>
                    
                    <h2 class="headline text-white mt-3 text-3xl sm:text-4xl lg:text-5xl font-bold tracking-tight">
                        Have a Project in Mind?<br class="hidden sm:inline" />
                        <span class="text-coral">Let's Talk.</span>
                    </h2>

                    <p class="mt-4 text-[0.95rem] leading-relaxed text-white/65">
                        Open to learning opportunities, collaborations, internships, and projects involving functional website development or UI/UX design.                
                    </p>
                </div>

                {{-- Direct Contact & Social Media Action Cards --}}
                <div class="mt-6 space-y-2.5">
                    @foreach ($channels as [$label, $url, $text, $icon])
                        @if ($url && $text)
                            <a href="{{ $url }}" @if (! str_starts_with($url, 'mailto:')) target="_blank" rel="noopener noreferrer" @endif
                                class="group flex items-center justify-between rounded-2xl border border-white/10 bg-white/[0.02] p-3 transition-all duration-200 hover:border-coral/40 hover:bg-white/[0.05]">
                                <div class="flex items-center gap-3.5 min-w-0">
                                    <div class="flex size-10 shrink-0 items-center justify-center rounded-xl border border-white/10 bg-white/5 text-white/80 transition group-hover:border-coral/30 group-hover:bg-coral/10 group-hover:text-coral">
                                        <x-icon :name="$icon" class="size-4" />
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-mono text-[10px] font-semibold uppercase tracking-wider text-white/40 group-hover:text-coral/80 transition">{{ $label }}</p>
                                        <p class="truncate text-xs sm:text-sm font-medium text-white/90 group-hover:text-white transition">{{ $text }}</p>
                                    </div>
                                </div>
                                <x-icon name="arrow-up-right" class="size-4 shrink-0 text-white/30 transition-transform duration-200 group-hover:-translate-y-0.5 group-hover:translate-x-0.5 group-hover:text-coral" />
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- Right Column: Compact Form Card (Right Slide) --}}
            <div data-reveal="right" data-delay="150" class="lg:col-span-7 flex flex-col">
                <div class="h-full rounded-3xl border border-white/10 bg-[#16161c]/90 p-6 sm:p-8 shadow-2xl backdrop-blur-sm flex flex-col justify-between">
                    <div class="mb-5 flex items-center justify-between border-b border-white/5 pb-4">
                        <div>
                            <h3 class="text-base sm:text-lg font-bold tracking-tight text-white">Kirim Pesan Langsung</h3>
                            <p class="text-xs text-white/50 mt-0.5">Isi formulir berikut untuk memulai diskusi atau penawaran.</p>
                        </div>
                        <span class="hidden font-mono text-[10px] text-coral sm:inline-block">Respons Cepat</span>
                    </div>

                    @if (session('contact_success'))
                        <div role="status" class="mb-6 flex items-start gap-3 rounded-2xl border border-emerald-400/30 bg-emerald-400/10 p-4 text-xs sm:text-sm text-emerald-200">
                            <x-icon name="check" class="mt-0.5 size-4 shrink-0" />
                            <span>{{ session('contact_success') }}</span>
                        </div>
                    @endif
                    @if (session('contact_error'))
                        <div role="alert" class="mb-6 flex items-start gap-3 rounded-2xl border border-coral/40 bg-coral/10 p-4 text-xs sm:text-sm text-red-200">
                            <x-icon name="alert" class="mt-0.5 size-4 shrink-0" />
                            <span>{{ session('contact_error') }}</span>
                        </div>
                    @endif

                    <form action="{{ route('contact.store') }}" method="POST" data-contact-form novalidate class="flex flex-1 flex-col justify-between space-y-4">
                        @csrf
                        {{-- Honeypot anti-spam: disembunyikan dari pengguna --}}
                        <div class="absolute -left-[9999px]" aria-hidden="true">
                            <label for="website">Website</label>
                            <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                        </div>

                        <div class="flex-1 space-y-4 flex flex-col">
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="name" class="mb-1.5 block font-mono text-[11px] font-semibold uppercase tracking-wider text-white/70">
                                        Nama Lengkap <span class="text-coral">*</span>
                                    </label>
                                    <input id="name" name="name" type="text" autocomplete="name" required maxlength="150" value="{{ old('name') }}"
                                        placeholder="Masukkan nama Anda"
                                        @error('name') aria-invalid="true" aria-describedby="name-error" @enderror
                                        class="w-full rounded-xl border border-white/10 bg-white/[0.04] px-3.5 py-2.5 text-xs sm:text-sm text-white placeholder:text-white/30 transition focus:border-coral focus:bg-white/[0.07] focus:outline-none">
                                    @error('name')<p id="name-error" class="mt-1 text-xs text-red-300">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label for="email" class="mb-1.5 block font-mono text-[11px] font-semibold uppercase tracking-wider text-white/70">
                                        Alamat Email <span class="text-coral">*</span>
                                    </label>
                                    <input id="email" name="email" type="email" autocomplete="email" required maxlength="150" value="{{ old('email') }}"
                                        placeholder="nama@email.com"
                                        @error('email') aria-invalid="true" aria-describedby="email-error" @enderror
                                        class="w-full rounded-xl border border-white/10 bg-white/[0.04] px-3.5 py-2.5 text-xs sm:text-sm text-white placeholder:text-white/30 transition focus:border-coral focus:bg-white/[0.07] focus:outline-none">
                                    @error('email')<p id="email-error" class="mt-1 text-xs text-red-300">{{ $message }}</p>@enderror
                                </div>
                            </div>

                            <div>
                                <label for="subject" class="mb-1.5 block font-mono text-[11px] font-semibold uppercase tracking-wider text-white/70">
                                    Subjek Diskusi <span class="text-coral">*</span>
                                </label>
                                <input id="subject" name="subject" type="text" required maxlength="150" value="{{ old('subject') }}"
                                    placeholder="Contoh: Diskusi Proyek Website / Penawaran Magang"
                                    @error('subject') aria-invalid="true" aria-describedby="subject-error" @enderror
                                    class="w-full rounded-xl border border-white/10 bg-white/[0.04] px-3.5 py-2.5 text-xs sm:text-sm text-white placeholder:text-white/30 transition focus:border-coral focus:bg-white/[0.07] focus:outline-none">
                                @error('subject')<p id="subject-error" class="mt-1 text-xs text-red-300">{{ $message }}</p>@enderror
                            </div>

                            <div class="flex-1 flex flex-col">
                                <label for="message" class="mb-1.5 block font-mono text-[11px] font-semibold uppercase tracking-wider text-white/70">
                                    Isi Pesan <span class="text-coral">*</span>
                                </label>
                                <textarea id="message" name="message" required minlength="10" maxlength="3000"
                                    placeholder="Tuliskan pesan, rincian proyek, atau pertanyaan Anda di sini..."
                                    @error('message') aria-invalid="true" aria-describedby="message-error" @enderror
                                    class="w-full flex-1 min-h-[140px] rounded-xl border border-white/10 bg-white/[0.04] p-3.5 text-xs sm:text-sm text-white placeholder:text-white/30 transition focus:border-coral focus:bg-white/[0.07] focus:outline-none leading-relaxed resize-y">{{ old('message') }}</textarea>
                                @error('message')<p id="message-error" class="mt-1 text-xs text-red-300">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="pt-2 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <button type="submit" class="btn-coral w-full sm:w-auto inline-flex items-center justify-center gap-2 py-3 px-6 text-xs sm:text-sm font-semibold shadow-lg shadow-coral/25">
                                <span data-label>Kirim Pesan</span>
                                <x-icon name="arrow-up-right" class="size-4" />
                            </button>
                            <p class="text-[11px] font-mono text-white/40 text-center sm:text-right">
                                Pesan tersimpan otomatis ke database &amp; admin.
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
