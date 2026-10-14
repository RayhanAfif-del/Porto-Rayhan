@props(['name', 'label', 'type' => 'text', 'value' => null, 'hint' => null, 'rows' => 4])

<div>
    <label for="{{ $name }}" class="mb-1.5 block font-mono text-[11px] font-semibold tracking-wider text-white/60 uppercase">{{ $label }}</label>
    @if ($type === 'textarea')
        <textarea id="{{ $name }}" name="{{ $name }}" rows="{{ $rows }}" {{ $attributes->merge(['class' => 'w-full rounded-xl border border-white/10 bg-white/[0.03] p-3 text-xs text-white placeholder:text-white/30 focus:border-coral focus:outline-none transition']) }}>{{ old($name, $value) }}</textarea>
    @else
        <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" @if ($type !== 'file') value="{{ old($name, $value) }}" @endif
            {{ $attributes->merge(['class' => 'w-full rounded-xl border border-white/10 bg-white/[0.03] px-3.5 py-2.5 text-xs text-white placeholder:text-white/30 focus:border-coral focus:outline-none transition']) }}>
    @endif
    @if ($hint)<p class="mt-1 font-mono text-[10px] text-white/40">{{ $hint }}</p>@endif
    @error($name)<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
</div>
