@props(['product'])

<a href="{{ route('products.show', $product) }}" class="bg-white shadow-sm rounded-2xl overflow-hidden border border-beige hover:shadow-md hover:-translate-y-0.5 transition">
    <div class="aspect-square bg-cream">
        @if ($product->image_url)
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
        @else
            <div class="w-full h-full flex items-center justify-center text-choco-soft">
                <i class="fas fa-image text-2xl"></i>
            </div>
        @endif
    </div>
    <div class="p-3">
        <p class="text-sm font-medium text-choco-dark truncate">{{ $product->name }}</p>
        <p class="text-xs text-choco-soft truncate">{{ $product->shop->name }}</p>
        @if ($product->discount_price)
            <p class="mt-1">
                <span class="font-bold text-choco">{{ number_format($product->discount_price, 0, ',', ' ') }} {{ $product->devise }}</span>
                <span class="text-xs text-choco-soft line-through ml-1">{{ number_format($product->price, 0, ',', ' ') }}</span>
            </p>
        @else
            <p class="mt-1 font-bold text-choco">{{ number_format($product->price, 0, ',', ' ') }} {{ $product->devise }}</p>
        @endif
    </div>
</a>
