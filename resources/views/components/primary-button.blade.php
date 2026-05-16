<button {{ $attributes->merge(['type' => 'submit', 'style' => 'display: inline-flex; align-items: center; padding: 0.6rem 1.25rem; background: var(--primary, #4f46e5); color: var(--primary-foreground, #ffffff); font-family: inherit; font-size: 0.875rem; font-weight: 700; border: none; border-radius: calc(var(--radius, 1rem) - 0.25rem); cursor: pointer; transition: opacity 0.2s; letter-spacing: 0.01em;']) }}>
    {{ $slot }}
</button>
