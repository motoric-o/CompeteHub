@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['style' => 'width: 100%; padding: 0.75rem 1rem; background: var(--background, #f7f9f3); border: 1px solid var(--border, #000000); border-radius: calc(var(--radius, 1rem) - 0.25rem); font-family: inherit; font-size: 0.9rem; color: var(--foreground, #000000); box-sizing: border-box; outline: none; transition: border-color 0.2s, box-shadow 0.2s;']) }}>
