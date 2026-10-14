@props(['dark' => false])

<p {{ $attributes->class(['mb-6 flex items-center gap-3 text-xs font-semibold uppercase tracking-[0.22em]', 'text-white/60' => $dark, 'text-muted' => ! $dark]) }}>
    <span class="inline-block h-2 w-2 rounded-full bg-coral" aria-hidden="true"></span>
    {{ $slot }}
</p>
