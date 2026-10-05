<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-6 space-y-8">

        @if ($featured->isNotEmpty())
            <div x-data="{
                    active: 0,
                    count: {{ $featured->count() }},
                    timer: null,
                    touchX: null,
                    start() { this.stop(); this.timer = setInterval(() => this.next(), 4500); },
                    stop() { clearInterval(this.timer); },
                    next() { this.active = (this.active + 1) % this.count; },
                    prev() { this.active = (this.active - 1 + this.count) % this.count; },
                }"
                x-init="start()"
                @mouseenter="stop()" @mouseleave="start()"
                @touchstart="touchX = $event.changedTouches[0].clientX"
                @touchend="
                    let delta = $event.changedTouches[0].clientX - touchX;
                    if (delta > 40) { prev(); start(); }
                    else if (delta < -40) { next(); start(); }
                "
                class="relative rounded-[28px] overflow-hidden shadow-[0_15px_40px_rgba(62,44,31,.18)] h-60 sm:h-[22rem]">
                @foreach ($featured as $i => $product)
                    <a href="{{ route('products.show', $product) }}"
                        class="absolute inset-0 transition-opacity duration-700 ease-in-out"
                        :class="active === {{ $i }} ? 'opacity-100 z-10' : 'opacity-0 z-0 pointer-events-none'"
                        style="background-image: linear-gradient(100deg, rgba(42,28,18,.88) 0%, rgba(42,28,18,.45) 50%, rgba(42,28,18,.05) 100%), url('{{ $product->image_url }}'); background-size: cover; background-position: center;">
                        <div class="absolute inset-y-0 left-0 flex flex-col justify-center gap-2.5 px-6 sm:px-10 max-w-[80%] sm:max-w-[55%] text-white">
                            @if ($product->shop->isPremium())
                                <span class="inline-flex items-center gap-1.5 w-fit text-white text-[0.7rem] font-bold px-3 py-1.5 rounded-full" style="background: linear-gradient(135deg,#c29a6a,#a7754b);">
                                    <i class="fas fa-star"></i> {{ __('Boutique Premium') }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 w-fit bg-gold text-choco-dark text-[0.7rem] font-bold px-3 py-1.5 rounded-full">{{ __('Nouveauté') }}</span>
                            @endif
                            <h2 class="text-xl sm:text-3xl font-extrabold drop-shadow leading-tight">{{ $product->name }}</h2>
                            <p class="text-sm sm:text-base opacity-90">{{ $product->shop->name }}</p>
                            <p class="text-lg sm:text-2xl font-bold">{{ number_format($product->effectivePrice(), 0, ',', ' ') }} {{ $product->devise }}</p>
                        </div>
                    </a>
                @endforeach

                @if ($featured->count() > 1)
                    <button @click.prevent.stop="prev(); start()" aria-label="{{ __('Précédent') }}"
                        class="hidden sm:flex absolute left-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-white/85 hover:bg-white items-center justify-center text-choco-dark shadow-md transition">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button @click.prevent.stop="next(); start()" aria-label="{{ __('Suivant') }}"
                        class="hidden sm:flex absolute right-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-white/85 hover:bg-white items-center justify-center text-choco-dark shadow-md transition">
                        <i class="fas fa-chevron-right"></i>
                    </button>

                    <div class="absolute bottom-4 inset-x-0 flex justify-center gap-2 z-20">
                        @foreach ($featured as $i => $product)
                            <button @click="active = {{ $i }}; start()" aria-label="{{ __('Aller à la diapositive :n', ['n' => $i + 1]) }}"
                                class="h-1.5 rounded-full transition-all" :class="active === {{ $i }} ? 'bg-white w-7' : 'bg-white/40 w-1.5'"></button>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif

        <form method="GET" action="{{ route('products.index') }}" class="space-y-3">
            @if (request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
            @if (request('subcategory'))
                <input type="hidden" name="subcategory" value="{{ request('subcategory') }}">
            @endif
            <div class="flex items-center gap-3 bg-white rounded-full px-5 border border-beige">
                <i class="fas fa-search text-choco-soft"></i>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ __('Rechercher...') }}"
                    class="flex-1 border-none focus:ring-0 py-3.5 px-2 bg-transparent text-sm">
            </div>

            <div class="flex items-center gap-2 flex-wrap text-sm">
                <input type="number" name="price_min" value="{{ request('price_min') }}" placeholder="{{ __('Prix min') }}"
                    class="w-28 border-beige focus:border-choco focus:ring-choco rounded-full text-sm py-1.5 px-3">
                <input type="number" name="price_max" value="{{ request('price_max') }}" placeholder="{{ __('Prix max') }}"
                    class="w-28 border-beige focus:border-choco focus:ring-choco rounded-full text-sm py-1.5 px-3">
                <select name="rating" class="border-beige focus:border-choco focus:ring-choco rounded-full text-sm py-1.5 px-3">
                    <option value="">{{ __('Toutes les notes') }}</option>
                    @foreach ([4, 3, 2, 1] as $stars)
                        <option value="{{ $stars }}" @selected((string) request('rating') === (string) $stars)>{{ $stars }}+ <i class="fas fa-star"></i></option>
                    @endforeach
                </select>
                <select name="sort" class="border-beige focus:border-choco focus:ring-choco rounded-full text-sm py-1.5 px-3">
                    <option value="latest" @selected(request('sort', 'latest') === 'latest')>{{ __('Nouveautés') }}</option>
                    <option value="price_asc" @selected(request('sort') === 'price_asc')>{{ __('Prix croissant') }}</option>
                    <option value="price_desc" @selected(request('sort') === 'price_desc')>{{ __('Prix décroissant') }}</option>
                    <option value="popularity" @selected(request('sort') === 'popularity')>{{ __('Popularité') }}</option>
                    <option value="rating" @selected(request('sort') === 'rating')>{{ __('Meilleures notes') }}</option>
                </select>
                <button type="submit" class="px-4 py-1.5 bg-choco text-white rounded-full text-sm">{{ __('Filtrer') }}</button>
            </div>
        </form>

        <div class="flex gap-3 overflow-x-auto pb-1 -mx-1 px-1">
            <a href="{{ route('products.index', request()->except('category', 'subcategory', 'page')) }}"
                class="flex-shrink-0 px-5 py-2 rounded-full border whitespace-nowrap text-sm {{ request()->missing('category') ? 'bg-choco text-white border-choco' : 'bg-white border-beige text-choco-dark' }}">
                {{ __('Tous') }}
            </a>
            @foreach ($categories as $category)
                <a href="{{ route('products.index', array_merge(request()->except('page', 'subcategory'), ['category' => $category->id])) }}"
                    class="flex-shrink-0 px-5 py-2 rounded-full border whitespace-nowrap text-sm {{ (string) request('category') === (string) $category->id ? 'bg-choco text-white border-choco' : 'bg-white border-beige text-choco-dark' }}">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>

        @php
            $currentCategory = $categories->first(fn ($c) => (string) $c->id === (string) request('category'));
        @endphp
        @if ($currentCategory && $currentCategory->children->isNotEmpty())
            <div class="flex gap-2 overflow-x-auto pb-1 -mx-1 px-1 -mt-4">
                <a href="{{ route('products.index', array_merge(request()->except('page', 'subcategory'), ['category' => $currentCategory->id])) }}"
                    class="flex-shrink-0 px-4 py-1.5 rounded-full border whitespace-nowrap text-xs {{ request()->missing('subcategory') ? 'bg-choco-soft text-white border-choco-soft' : 'bg-cream border-beige text-choco-dark' }}">
                    {{ __('Toutes les sous-catégories') }}
                </a>
                @foreach ($currentCategory->children as $subcategory)
                    <a href="{{ route('products.index', array_merge(request()->except('page'), ['category' => $currentCategory->id, 'subcategory' => $subcategory->id])) }}"
                        class="flex-shrink-0 px-4 py-1.5 rounded-full border whitespace-nowrap text-xs {{ (string) request('subcategory') === (string) $subcategory->id ? 'bg-choco-soft text-white border-choco-soft' : 'bg-cream border-beige text-choco-dark' }}">
                        {{ $subcategory->name }}
                    </a>
                @endforeach
            </div>
        @endif

        @if (request('q') || request('category') || request('subcategory') || request('price_min') || request('price_max') || request('rating') || request('sort'))
            <a href="{{ route('products.index') }}" class="inline-block text-sm text-choco-soft -mt-4">{{ __('Réinitialiser les filtres') }}</a>
        @endif

        @if ($sponsored->isNotEmpty())
            <div class="space-y-3">
                <h2 class="font-bold text-choco-dark text-lg">{{ __('Produits sponsorisés') }}</h2>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    @foreach ($sponsored as $ad)
                        <a href="{{ route('advertisements.click', $ad) }}" class="bg-white shadow-sm rounded-2xl overflow-hidden border border-beige hover:shadow-md hover:-translate-y-0.5 transition block">
                            <div class="aspect-square bg-cream relative">
                                @if ($ad->product->image_url)
                                    <img src="{{ $ad->product->image_url }}" alt="{{ $ad->product->name }}" class="w-full h-full object-cover">
                                @endif
                                <span class="absolute top-2 left-2 inline-flex items-center gap-1 bg-choco text-white text-[0.65rem] font-bold px-2 py-1 rounded-full">
                                    {{ __('Sponsorisé') }}
                                </span>
                            </div>
                            <div class="p-3 pb-2">
                                <p class="text-sm font-medium text-choco-dark truncate">{{ $ad->product->name }}</p>
                                <p class="text-xs text-choco-soft truncate">{{ $ad->product->shop->name }}</p>
                                <p class="mt-1 font-bold text-choco">{{ number_format($ad->product->effectivePrice(), 0, ',', ' ') }} {{ $ad->product->devise }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        @if ($recommended->isNotEmpty())
            <div class="space-y-3">
                <h2 class="font-bold text-choco-dark text-lg">{{ __('Recommandé pour vous') }}</h2>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    @foreach ($recommended as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
            </div>
        @endif

        <h2 class="font-bold text-choco-dark text-lg">{{ __('Tous les produits') }}</h2>

        @if ($products->isEmpty())
            <div class="flex flex-col items-center text-center py-14 px-6">
                <div class="w-16 h-16 rounded-full bg-cream flex items-center justify-center mb-4">
                    <i class="fas {{ $hasFilters ? 'fa-magnifying-glass' : 'fa-shop' }} text-2xl text-choco-soft"></i>
                </div>
                @if ($hasFilters)
                    <h3 class="text-base font-bold text-choco-dark mb-1.5">{{ __('Aucun produit ne correspond à votre recherche') }}</h3>
                    <p class="text-sm text-choco-soft mb-5 max-w-xs">{{ __('Essayez de modifier vos mots-clés ou vos filtres.') }}</p>
                    <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 bg-choco hover:bg-choco-light text-white font-semibold text-sm px-5 py-2.5 rounded-full transition">
                        <i class="fas fa-rotate-left"></i> {{ __('Réinitialiser les filtres') }}
                    </a>
                @else
                    <h3 class="text-base font-bold text-choco-dark mb-1.5">{{ __('Aucun produit disponible pour le moment') }}</h3>
                    <p class="text-sm text-choco-soft max-w-xs">{{ __('Revenez bientôt, de nouveaux produits arrivent régulièrement.') }}</p>
                @endif
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
