<x-seller-layout>
    <div x-data="{ modalOpen: {{ $errors->any() ? 'true' : 'false' }} }" class="space-y-5">

        <div class="flex items-center justify-between flex-wrap gap-4">
            <h2 class="text-seller-sidebar font-semibold text-xl">{{ __('Mes produits') }}</h2>
            <button @click="modalOpen = true" class="inline-flex items-center gap-2 text-white font-semibold px-5 py-3 rounded-2xl" style="background: linear-gradient(135deg,#c29a6a,#a7754b);">
                <i class="fas fa-plus-circle"></i> {{ __('Ajouter Produit') }}
            </button>
        </div>

        @if (session('status'))
            <div class="bg-[#e7f0da] border border-[#d3e3c0] text-[#4b6b2c] text-sm rounded-2xl p-4">
                @switch(session('status'))
                    @case('product-created') {{ __('Produit créé.') }} @break
                    @case('product-updated') {{ __('Produit mis à jour.') }} @break
                    @case('product-deleted') {{ __('Produit supprimé.') }} @break
                @endswitch
            </div>
        @endif

        <div class="bg-white rounded-[24px] shadow-[0_10px_25px_rgba(120,70,30,.07)] border border-[#f0e2d0] p-5 overflow-x-auto">
            <table class="w-full border-collapse text-sm">
                <thead>
                    <tr>
                        <th class="bg-[#f9f2e7] p-3.5 text-left text-[#5e3e2b] font-medium rounded-l-lg">{{ __('Produit') }}</th>
                        <th class="bg-[#f9f2e7] p-3.5 text-left text-[#5e3e2b] font-medium">{{ __('Prix') }}</th>
                        <th class="bg-[#f9f2e7] p-3.5 text-left text-[#5e3e2b] font-medium">{{ __('Stock') }}</th>
                        <th class="bg-[#f9f2e7] p-3.5 text-left text-[#5e3e2b] font-medium">{{ __('Statut') }}</th>
                        <th class="bg-[#f9f2e7] p-3.5 text-left text-[#5e3e2b] font-medium rounded-r-lg">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td class="p-3.5 border-b border-[#f0e2d0]">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-[55px] h-[55px] rounded-2xl bg-cream flex-shrink-0 overflow-hidden">
                                        @if ($product->image_url)
                                            <img src="{{ $product->image_url }}" class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                    <div>
                                        <strong class="text-seller-sidebar">{{ $product->name }}</strong><br>
                                        <small class="text-[#7b5e47]">{{ $product->category?->name }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="p-3.5 border-b border-[#f0e2d0] text-seller-sidebar">{{ number_format($product->price, 0, ',', ' ') }} {{ $product->devise }}</td>
                            <td class="p-3.5 border-b border-[#f0e2d0] text-seller-sidebar">{{ $product->stock }}</td>
                            <td class="p-3.5 border-b border-[#f0e2d0]">
                                @if ($product->stock === 0)
                                    <span class="inline-block px-3.5 py-1.5 rounded-full text-[13px] font-semibold bg-[#fce8e6] text-[#b34a3b]">{{ __('Rupture') }}</span>
                                @elseif (! $product->is_active)
                                    <span class="inline-block px-3.5 py-1.5 rounded-full text-[13px] font-semibold bg-[#fdf1db] text-[#b47b3c]">{{ __('Masqué') }}</span>
                                @else
                                    <span class="inline-block px-3.5 py-1.5 rounded-full text-[13px] font-semibold bg-[#e7f0da] text-[#4b6b2c]">{{ __('Actif') }}</span>
                                @endif
                            </td>
                            <td class="p-3.5 border-b border-[#f0e2d0]">
                                <a href="{{ route('seller.products.edit', $product) }}" title="{{ __('Modifier') }}"
                                    class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-[#efe0d1] text-[#7b5e47] mr-1.5">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('seller.products.destroy', $product) }}" class="inline"
                                    onsubmit="return confirm('{{ __('Supprimer ce produit ?') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button title="{{ __('Supprimer') }}" class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-[#f7e0db] text-[#b34a3b]">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-10 text-center text-[#7b5e47]">{{ __('Aucun produit pour le moment.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>{{ $products->links() }}</div>

        <!-- Add product modal -->
        <div x-show="modalOpen" x-cloak class="fixed inset-0 bg-[rgba(30,15,5,.5)] flex items-center justify-center z-[2000] p-5"
            @click.self="modalOpen = false">
            <div class="bg-[#fffaf4] w-full max-w-[560px] rounded-[28px] p-7 max-h-[90vh] overflow-y-auto">
                <h2 class="text-seller-sidebar font-bold text-xl mb-5">{{ __('Ajouter un produit') }}</h2>

                <form method="POST" action="{{ route('seller.products.store') }}" class="space-y-4">
                    @csrf
                    @include('seller.products._form', ['product' => null])

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" @click="modalOpen = false" class="px-5 py-2.5 rounded-2xl bg-[#e7dbcc] text-seller-sidebar font-semibold">{{ __('Annuler') }}</button>
                        <button type="submit" class="px-5 py-2.5 rounded-2xl text-white font-semibold" style="background: linear-gradient(135deg,#c29a6a,#a7754b);">{{ __('Ajouter') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-seller-layout>
