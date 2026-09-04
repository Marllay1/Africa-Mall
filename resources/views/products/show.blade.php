<x-app-layout>
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 py-8 space-y-4">
        <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 text-sm text-choco-soft hover:text-choco">
            <i class="fas fa-arrow-left"></i> {{ __('Retour aux produits') }}
        </a>

        <div class="bg-white shadow-sm rounded-2xl sm:rounded-3xl border border-beige overflow-hidden grid sm:grid-cols-2">
            <div class="aspect-square bg-cream">
                @if ($product->image_url)
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-choco-soft">
                        <i class="fas fa-image text-4xl"></i>
                    </div>
                @endif
            </div>

            <div class="p-6 space-y-4">
                <div>
                    <h1 class="text-xl font-bold text-choco-dark">{{ $product->name }}</h1>
                    <p class="text-sm text-choco-soft">{{ $product->shop->name }} @if ($product->category) &middot; {{ $product->category->name }} @endif</p>
                </div>

                <p class="text-2xl font-extrabold text-choco">{{ number_format($product->price, 0, ',', ' ') }} {{ $product->devise }}</p>

                @if ($product->description)
                    <p class="text-sm text-choco-dark/80">{{ $product->description }}</p>
                @endif

                <p class="text-sm {{ $product->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ $product->stock > 0 ? __('En stock (:n)', ['n' => $product->stock]) : __('Rupture de stock') }}
                </p>

                @auth
                    @if ($product->stock > 0)
                        <form method="POST" action="{{ route('cart.add', $product) }}" class="flex items-center gap-3">
                            @csrf
                            <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}"
                                class="w-20 border-beige focus:border-choco focus:ring-choco rounded-md shadow-sm">
                            <x-primary-button>{{ __('Ajouter au panier') }}</x-primary-button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="text-choco underline text-sm">{{ __('Connectez-vous pour acheter') }}</a>
                @endauth
            </div>
        </div>
    </div>
</x-app-layout>
