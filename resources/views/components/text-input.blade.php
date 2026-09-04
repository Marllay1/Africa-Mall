@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-beige focus:border-choco focus:ring-choco rounded-md shadow-sm']) }}>
