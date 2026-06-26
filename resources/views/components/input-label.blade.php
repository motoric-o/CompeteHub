@props(['value'])

<label {{ $attributes->merge(['style' => 'display: block; font-size: 0.875rem; font-weight: 600; color: var(--foreground, #000000); margin-bottom: 0.4rem;']) }}>
    {{ $value ?? $slot }}
</label>
