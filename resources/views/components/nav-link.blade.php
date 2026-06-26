@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 focus:outline-none transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 focus:outline-none transition duration-150 ease-in-out';
$styles = ($active ?? false)
    ? 'border-color: var(--primary, #4f46e5); color: var(--foreground, #000000);'
    : 'color: var(--muted-foreground, #333333);&:hover{color: var(--foreground, #000000);}'; 
@endphp

<a {{ $attributes->merge(['class' => $classes, 'style' => $styles]) }}>
    {{ $slot }}
</a>
