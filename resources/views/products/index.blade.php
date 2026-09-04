<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Produits') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <form method="GET" action="{{ route('products.index') }}" class="flex flex-wrap gap-3 bg-white shadow-sm sm:rounded-lg p-4">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ __('Rechercher un produit...') }}"
                    class="flex-1 min-w-[200px] border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">

                <select name="category" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="">{{ __('Toutes les catégories') }}</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected((string) request('category') === (string) $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                <button class="px-4 py-2 bg-gray-800 text-white text-sm rounded-md">{{ __('Filtrer') }}</button>
                @if (request('q') || request('category'))
                    <a href="{{ route('products.index') }}" class="px-4 py-2 text-sm text-gray-600 self-center">{{ __('Réinitialiser') }}</a>
                @endif
            </form>

            @if ($products->isEmpty())
                <div class="bg-white shadow-sm sm:rounded-lg p-10 text-center text-gray-500">
                    {{ __('Aucun produit ne correspond à votre recherche.') }}
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach ($products as $product)
                        <a href="{{ route('products.show', $product) }}" class="bg-white shadow-sm rounded-lg overflow-hidden hover:shadow-md transition">
                            <div class="aspect-square bg-gray-100">
                                @if ($product->image_url)
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div class="p-3">
                                <p class="text-sm font-medium text-gray-800 truncate">{{ $product->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $product->shop->name }}</p>
                                <p class="mt-1 font-semibold text-gray-900">{{ number_format($product->price, 0, ',', ' ') }} {{ $product->devise }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div>{{ $products->links() }}</div>
            @endif

        </div>
    </div>
</x-app-layout>
