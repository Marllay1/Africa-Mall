@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-choco text-start text-base font-medium text-choco-dark bg-cream focus:outline-none focus:text-choco-dark focus:bg-cream focus:border-choco transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-choco-soft hover:text-choco-dark hover:bg-cream hover:border-beige focus:outline-none focus:text-choco-dark focus:bg-cream focus:border-beige transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
