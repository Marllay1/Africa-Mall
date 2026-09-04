<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-6 space-y-8">

        @if ($featured->isNotEmpty())
            <div x-data="{ active: 0, count: {{ $featured->count() }} }"
                x-init="setInterval(() => active = (active + 1) % count, 4500)"
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

        <form method="GET" action="{{ route('products.index') }}" class="flex flex-wrap gap-3 bg-white shadow-sm rounded-2xl border border-beige p-4">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ __('Rechercher un produit...') }}"
                class="flex-1 min-w-[200px] border-beige focus:border-choco focus:ring-choco rounded-md shadow-sm">

            <select name="category" class="border-beige focus:border-choco focus:ring-choco rounded-md shadow-sm">
                <option value="">{{ __('Toutes les catégories') }}</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected((string) request('category') === (string) $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            <button class="px-4 py-2 bg-choco hover:bg-choco-light text-white text-sm rounded-md">{{ __('Filtrer') }}</button>
            @if (request('q') || request('category'))
                <a href="{{ route('products.index') }}" class="px-4 py-2 text-sm text-choco-soft self-center">{{ __('Réinitialiser') }}</a>
            @endif
        </form>

        @if ($products->isEmpty())
            <div class="bg-white shadow-sm rounded-2xl border border-beige p-10 text-center text-choco-soft">
                {{ __('Aucun produit ne correspond à votre recherche.') }}
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach ($products as $product)
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
                            <p class="mt-1 font-bold text-choco">{{ number_format($product->price, 0, ',', ' ') }} {{ $product->devise }}</p>
                        </div>
                    </a>
                @endforeach
            </div>

            <div>{{ $products->links() }}</div>
        @endif

    </div>
</x-app-layout>
