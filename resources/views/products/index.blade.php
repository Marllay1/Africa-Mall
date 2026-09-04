<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-6 space-y-8">

        @if ($featured->isNotEmpty())
            <div x-data="{ active: 0, count: {{ $featured->count() }} }"
                x-init="setInterval(() => active = (active + 1) % count, 3000)"
                class="relative rounded-2xl sm:rounded-3xl overflow-hidden shadow-sm">
                @foreach ($featured as $i => $product)
                    <a href="{{ route('products.show', $product) }}"
                        x-show="active === {{ $i }}" x-cloak
                        x-transition:enter="transition ease-out duration-500"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        class="block relative h-56 sm:h-80 bg-choco-dark bg-cover bg-center"
                        style="background-image: linear-gradient(0deg, rgba(62,44,31,.75), rgba(62,44,31,.15)), url('{{ $product->image_url }}')">
                        <div class="absolute bottom-6 left-6 right-6 text-white">
                            <span class="inline-block bg-gold text-choco-dark text-xs font-bold px-3 py-1 rounded-full mb-2">{{ __('Nouveauté') }}</span>
                            <h2 class="text-xl sm:text-3xl font-extrabold drop-shadow">{{ $product->name }}</h2>
                            <p class="text-sm sm:text-base opacity-90">{{ $product->shop->name }} &middot; {{ number_format($product->price, 0, ',', ' ') }} {{ $product->devise }}</p>
                        </div>
                    </a>
                @endforeach

                <div class="absolute bottom-3 inset-x-0 flex justify-center gap-2">
                    @foreach ($featured as $i => $product)
                        <button @click="active = {{ $i }}" class="w-2 h-2 rounded-full transition-all" :class="active === {{ $i }} ? 'bg-gold w-6' : 'bg-white/50'"></button>
                    @endforeach
                </div>
            </div>
        @endif

        <form method="GET" action="{{ route('products.index') }}" class="flex items-center gap-3 bg-white rounded-full px-5 border border-beige">
            @if (request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
            <i class="fas fa-search text-choco-soft"></i>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ __('Rechercher...') }}"
                class="flex-1 border-none focus:ring-0 py-3.5 px-2 bg-transparent text-sm">
        </form>

        <div class="flex gap-3 overflow-x-auto pb-1 -mx-1 px-1">
            <a href="{{ route('products.index', ['q' => request('q')]) }}"
                class="flex-shrink-0 px-5 py-2 rounded-full border whitespace-nowrap text-sm {{ request()->missing('category') ? 'bg-choco text-white border-choco' : 'bg-white border-beige text-choco-dark' }}">
                {{ __('Tous') }}
            </a>
            @foreach ($categories as $category)
                <a href="{{ route('products.index', ['q' => request('q'), 'category' => $category->id]) }}"
                    class="flex-shrink-0 px-5 py-2 rounded-full border whitespace-nowrap text-sm {{ (string) request('category') === (string) $category->id ? 'bg-choco text-white border-choco' : 'bg-white border-beige text-choco-dark' }}">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>

        @if (request('q') || request('category'))
            <a href="{{ route('products.index') }}" class="inline-block text-sm text-choco-soft -mt-4">{{ __('Réinitialiser les filtres') }}</a>
        @endif

        <h2 class="font-bold text-choco-dark text-lg">{{ __('Recommandations') }}</h2>

        @if ($products->isEmpty())
            <div class="bg-white shadow-sm rounded-2xl border border-beige p-10 text-center text-choco-soft">
                {{ __('Aucun produit ne correspond à votre recherche.') }}
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach ($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>

            <div>{{ $products->links() }}</div>
        @endif

    </div>
</x-app-layout>
