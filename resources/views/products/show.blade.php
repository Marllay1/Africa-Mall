<x-app-layout>
    <x-slot name="header">
        <a href="{{ route('products.index') }}" class="text-sm text-gray-600 hover:text-gray-900">&larr; {{ __('Retour aux produits') }}</a>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden grid sm:grid-cols-2">
                <div class="aspect-square bg-gray-100">
                    @if ($product->image_url)
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    @endif
                </div>

                <div class="p-6 space-y-4">
                    <div>
                        <h1 class="text-xl font-semibold text-gray-900">{{ $product->name }}</h1>
                        <p class="text-sm text-gray-500">{{ $product->shop->name }} @if ($product->category) &middot; {{ $product->category->name }} @endif</p>
                    </div>

                    <p class="text-2xl font-bold text-gray-900">{{ number_format($product->price, 0, ',', ' ') }} {{ $product->devise }}</p>

                    @if ($product->description)
                        <p class="text-sm text-gray-600">{{ $product->description }}</p>
                    @endif

                    <p class="text-sm {{ $product->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $product->stock > 0 ? __('En stock (:n)', ['n' => $product->stock]) : __('Rupture de stock') }}
                    </p>

                    @auth
                        @if ($product->stock > 0)
                            <form method="POST" action="{{ route('cart.add', $product) }}" class="flex items-center gap-3">
                                @csrf
                                <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}"
                                    class="w-20 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <x-primary-button>{{ __('Ajouter au panier') }}</x-primary-button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="text-indigo-600 underline text-sm">{{ __('Connectez-vous pour acheter') }}</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
