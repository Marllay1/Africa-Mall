@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-choco-dark']) }}>
    {{ $value ?? $slot }}
</label>
