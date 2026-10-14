@php
    $c = config('portfolio.contact');
    $wa = $c['whatsapp'] ? 'https://wa.me/'.preg_replace('/\D/', '', $c['whatsapp']) : null;
    $channels = [
        ['Email', $c['email'] ? 'mailto:'.$c['email'] : null, $c['email'], 'mail'],
        ['GitHub', $c['github'], $c['github'] ? preg_replace('#^https?://(www\.)?#', '', $c['github']) : null, 'github'],
        ['LinkedIn', $c['linkedin'], $c['linkedin'] ? preg_replace('#^https?://(www\.)?#', '', $c['linkedin']) : null, 'linkedin'],
        ['WhatsApp', $wa, $c['whatsapp'] ? '+'.preg_replace('/\D/', '', $c['whatsapp']) : null, 'whatsapp'],
    ];
@endphp

<section id="contact" class="bg-ink py-24 text-white sm:py-32 lg:py-40">
    <div class="container-x">
        <x-section-label :dark="true" data-reveal>CONTACT</x-section-label>
        <h2 data-reveal class="display max-w-[14ch]">Have a Project in Mind? Let's Talk.</h2>
        <p data-reveal class="mt-8 max-w-xl text-base leading-relaxed text-white/65 sm:text-lg">
            Terbuka untuk kesempatan belajar, kolaborasi, magang, dan proyek pengembangan website.
        </p>

        <div class="mt-16 grid gap-14 border-t border-white/15 pt-14 lg:grid-cols-12 lg:gap-20">
            <ul class="space-y-6 lg:col-span-5">
                @foreach ($channels as [$label, $url, $text, $icon])
                    <li data-reveal class="flex items-start gap-4">
                        <span class="flex size-11 shrink-0 items-center justify-center rounded-full border border-white/20 text-white/80"><x-icon :name="$icon" class="size-5" /></span>
                        <div class="min-w-0">
                            <p class="text-xs uppercase tracking-[0.18em] text-white/40">{{ $label }}</p>
                            @if ($url)
                                <a href="{{ $url }}" @if (! str_starts_with($url, 'mailto:')) target="_blank" rel="noopener noreferrer" @endif class="mt-1 block break-words text-lg transition hover:text-coral">{{ $text }}</a>
                            @else
                                <p class="mt-1 text-white/35">Belum diatur</p>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>

            <div class="lg:col-span-7">
                @if (session('contact_success'))
                    <div role="status" class="mb-6 flex items-start gap-3 rounded-2xl border border-emerald-400/30 bg-emerald-400/10 p-4 text-sm text-emerald-200">
                        <x-icon name="check" class="mt-0.5 size-5 shrink-0" />{{ session('contact_success') }}
                    </div>
                @endif
                @if (session('contact_error'))
                    <div role="alert" class="mb-6 flex items-start gap-3 rounded-2xl border border-coral/40 bg-coral/10 p-4 text-sm text-red-200">
                        <x-icon name="alert" class="mt-0.5 size-5 shrink-0" />{{ session('contact_error') }}
                    </div>
                @endif

                <form action="{{ route('contact.store') }}" method="POST" data-contact-form novalidate class="grid gap-5 sm:grid-cols-2">
                    @csrf
                    {{-- Honeypot anti-spam: disembunyikan dari pengguna --}}
                    <div class="absolute -left-[9999px]" aria-hidden="true">
                        <label for="website">Website</label>
                        <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                    </div>

                    @foreach ([['name', 'Nama', 'text', 'name'], ['email', 'Email', 'email', 'email']] as [$f, $label, $type, $ac])
                        <div>
                            <label for="{{ $f }}" class="mb-2 block text-sm text-white/70">{{ $label }}</label>
                            <input id="{{ $f }}" name="{{ $f }}" type="{{ $type }}" autocomplete="{{ $ac }}" required maxlength="150" value="{{ old($f) }}"
                                @error($f) aria-invalid="true" aria-describedby="{{ $f }}-error" @enderror class="field">
                            @error($f)<p id="{{ $f }}-error" class="mt-2 text-sm text-red-300">{{ $message }}</p>@enderror
                        </div>
                    @endforeach

                    <div class="sm:col-span-2">
                        <label for="subject" class="mb-2 block text-sm text-white/70">Subjek</label>
                        <input id="subject" name="subject" type="text" required maxlength="150" value="{{ old('subject') }}"
                            @error('subject') aria-invalid="true" aria-describedby="subject-error" @enderror class="field">
                        @error('subject')<p id="subject-error" class="mt-2 text-sm text-red-300">{{ $message }}</p>@enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="message" class="mb-2 block text-sm text-white/70">Pesan</label>
                        <textarea id="message" name="message" rows="6" required minlength="10" maxlength="3000"
                            @error('message') aria-invalid="true" aria-describedby="message-error" @enderror class="field resize-y">{{ old('message') }}</textarea>
                        @error('message')<p id="message-error" class="mt-2 text-sm text-red-300">{{ $message }}</p>@enderror
                    </div>

                    <div class="sm:col-span-2">
                        <button type="submit" class="btn-coral w-full sm:w-auto disabled:opacity-60"><span data-label>Kirim Pesan</span> <x-icon name="arrow-up-right" class="size-4" /></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
