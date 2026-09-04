<x-app-layout>
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 py-8 space-y-8">
        <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 text-sm text-choco-soft hover:text-choco">
            <i class="fas fa-arrow-left"></i> {{ __('Retour aux produits') }}
        </a>

        @if (session('status'))
            <div class="bg-amber-50 border border-amber-200 text-amber-700 text-sm rounded-md p-4">
                @switch(session('status'))
                    @case('added-to-cart') {{ __('Produit ajouté au panier.') }} @break
                    @case('out-of-stock') {{ __('Ce produit est en rupture de stock.') }} @break
                    @case('favorite-added') {{ __('Ajouté à vos favoris.') }} @break
                    @case('favorite-removed') {{ __('Retiré de vos favoris.') }} @break
                    @case('review-added') {{ __('Merci, votre avis a été publié.') }} @break
                @endswitch
            </div>
        @endif

        <div class="bg-white shadow-sm rounded-2xl sm:rounded-3xl border border-beige overflow-hidden grid sm:grid-cols-2">
            @php
                $galleryImages = collect([$product->image_url])->merge($product->images->pluck('url'))->filter()->values();
            @endphp

            <div x-data="{ selected: 0, images: {{ \Illuminate\Support\Js::from($galleryImages) }} }" class="p-4 space-y-3">
                <div class="aspect-square bg-cream rounded-xl overflow-hidden">
                    <template x-if="images.length">
                        <img :src="images[selected]" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    </template>
                    <template x-if="! images.length">
                        <div class="w-full h-full flex items-center justify-center text-choco-soft">
                            <i class="fas fa-image text-4xl"></i>
                        </div>
                    </template>
                </div>

                <div class="flex gap-2" x-show="images.length > 1">
                    <template x-for="(image, index) in images" :key="index">
                        <button @click="selected = index" class="w-14 h-14 rounded-lg overflow-hidden border-2"
                            :class="selected === index ? 'border-choco' : 'border-beige'">
                            <img :src="image" class="w-full h-full object-cover">
                        </button>
                    </template>
                </div>
            </div>

            <div class="p-6 space-y-4">
                <div>
                    <h1 class="text-xl font-bold text-choco-dark">{{ $product->name }}</h1>
                    <p class="text-sm text-choco-soft">{{ $product->shop->name }} @if ($product->category) &middot; {{ $product->category->name }} @endif</p>
                </div>

                <div class="flex items-center gap-2 text-sm">
                    @if ($averageRating)
                        <span class="flex items-center gap-0.5 text-gold">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="fa-star {{ $i <= round($averageRating) ? 'fas' : 'far' }}"></i>
                            @endfor
                        </span>
                        <span class="text-choco-dark font-medium">{{ $averageRating }}</span>
                        <span class="text-choco-soft">({{ __(':n avis', ['n' => $reviewsCount]) }})</span>
                    @else
                        <span class="text-choco-soft">{{ __('Aucun avis pour le moment') }}</span>
                    @endif
                    <span class="text-choco-soft">&middot; {{ __(':n ventes', ['n' => $salesCount]) }}</span>
                </div>

                @if ($product->discount_price)
                    <div class="flex items-baseline gap-2">
                        <p class="text-2xl font-extrabold text-choco">{{ number_format($product->discount_price, 0, ',', ' ') }} {{ $product->devise }}</p>
                        <p class="text-base text-choco-soft line-through">{{ number_format($product->price, 0, ',', ' ') }} {{ $product->devise }}</p>
                    </div>
                @else
                    <p class="text-2xl font-extrabold text-choco">{{ number_format($product->price, 0, ',', ' ') }} {{ $product->devise }}</p>
                @endif

                @if ($product->description)
                    <p class="text-sm text-choco-dark/80">{{ $product->description }}</p>
                @endif

                <p class="text-sm {{ $product->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ $product->stock > 0 ? __('En stock (:n)', ['n' => $product->stock]) : __('Rupture de stock') }}
                </p>

                @auth
                    @if ($product->stock > 0)
                        <div class="space-y-2">
                            <div class="flex items-center gap-3">
                                <form method="POST" action="{{ route('cart.add', $product) }}" class="flex items-center gap-3">
                                    @csrf
                                    <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}"
                                        class="w-20 border-beige focus:border-choco focus:ring-choco rounded-md shadow-sm">
                                    <x-primary-button>{{ __('Ajouter au panier') }}</x-primary-button>
                                </form>
                                <form method="POST" action="{{ route('cart.buy-now', $product) }}">
                                    @csrf
                                    <input type="hidden" name="quantity" value="1">
                                    <x-secondary-button type="submit">{{ __('Acheter maintenant') }}</x-secondary-button>
                                </form>
                            </div>
                        </div>
                    @endif

                    <div class="flex items-center gap-4 pt-1 flex-wrap">
                        @unless ($isOwnShop)
                            <form method="POST" action="{{ route('conversations.start', $product) }}">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-2 text-sm text-choco-soft hover:text-choco">
                                    <i class="far fa-comment"></i>
                                    {{ __('Contacter le vendeur') }}
                                </button>
                            </form>
                        @endunless

                        <form method="POST" action="{{ route('favorites.toggle', $product) }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-2 text-sm {{ $isFavorited ? 'text-red-500' : 'text-choco-soft hover:text-choco' }}">
                                <i class="{{ $isFavorited ? 'fas' : 'far' }} fa-heart"></i>
                                {{ $isFavorited ? __('Dans vos favoris') : __('Ajouter aux favoris') }}
                            </button>
                        </form>

                        <div x-data="{ copied: false }">
                            <button type="button"
                                @click="navigator.share ? navigator.share({ title: '{{ $product->name }}', url: window.location.href }) : (navigator.clipboard.writeText(window.location.href), copied = true, setTimeout(() => copied = false, 2000))"
                                class="inline-flex items-center gap-2 text-sm text-choco-soft hover:text-choco">
                                <i class="fas fa-share-nodes"></i>
                                <span x-text="copied ? '{{ __('Lien copié !') }}' : '{{ __('Partager') }}'"></span>
                            </button>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-choco underline text-sm">{{ __('Connectez-vous pour acheter') }}</a>
                @endauth
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-2xl sm:rounded-3xl border border-beige p-6 space-y-4">
            <h2 class="font-bold text-choco-dark">{{ __('Avis clients') }}</h2>

            @if ($canReview)
                <form method="POST" action="{{ route('reviews.store', $product) }}" x-data="{ rating: 5 }" class="space-y-3 border-b border-beige pb-4">
                    @csrf
                    <div class="flex items-center gap-1">
                        <template x-for="value in [1, 2, 3, 4, 5]" :key="value">
                            <button type="button" @click="rating = value" class="text-lg" :class="value <= rating ? 'text-gold' : 'text-beige'">
                                <i class="fas fa-star"></i>
                            </button>
                        </template>
                        <input type="hidden" name="rating" :value="rating">
                    </div>
                    <textarea name="comment" rows="2" placeholder="{{ __('Votre avis (optionnel)') }}"
                        class="block w-full border-beige focus:border-choco focus:ring-choco rounded-md shadow-sm text-sm"></textarea>
                    <x-input-error :messages="$errors->get('rating')" class="mt-1" />
                    <x-primary-button>{{ __('Publier mon avis') }}</x-primary-button>
                </form>
            @endif

            @forelse ($product->reviews as $review)
                <div class="border-b border-beige last:border-0 pb-4 last:pb-0">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-choco-dark">{{ $review->user->name }}</p>
                        <span class="flex items-center gap-0.5 text-gold text-xs">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="fa-star {{ $i <= $review->rating ? 'fas' : 'far' }}"></i>
                            @endfor
                        </span>
                    </div>
                    @if ($review->comment)
                        <p class="text-sm text-choco-dark/80 mt-1">{{ $review->comment }}</p>
                    @endif
                </div>
            @empty
                <p class="text-sm text-choco-soft">{{ __('Aucun avis pour ce produit.') }}</p>
            @endforelse
        </div>

        @if ($similarProducts->isNotEmpty())
            <div class="space-y-3">
                <h2 class="font-bold text-choco-dark">{{ __('Produits similaires') }}</h2>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    @foreach ($similarProducts as $similar)
                        <x-product-card :product="$similar" />
                    @endforeach
                </div>
            </div>
        @endif

        @if ($recommendedProducts->isNotEmpty())
            <div class="space-y-3">
                <h2 class="font-bold text-choco-dark">{{ __('Produits recommandés') }}</h2>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    @foreach ($recommendedProducts as $recommended)
                        <x-product-card :product="$recommended" />
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
