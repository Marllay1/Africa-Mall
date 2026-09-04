<x-seller-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-choco-dark leading-tight">
                {{ __('Mes produits') }}
            </h2>
            <a href="{{ route('seller.products.create') }}" class="px-4 py-2 bg-choco text-white text-sm rounded-md hover:bg-choco-light">
                {{ __('Nouveau produit') }}
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">

        @if (session('status'))
            <div class="bg-green-50 border border-green-200 text-green-700 text-sm rounded-md p-4">
                @switch(session('status'))
                    @case('product-created') {{ __('Produit créé.') }} @break
                    @case('product-updated') {{ __('Produit mis à jour.') }} @break
                    @case('product-deleted') {{ __('Produit supprimé.') }} @break
                @endswitch
            </div>
        @endif

        <div class="bg-white shadow-sm rounded-2xl border border-beige overflow-hidden">
            <table class="min-w-full divide-y divide-beige text-sm">
                <thead class="bg-cream text-left text-choco-soft">
                    <tr>
                        <th class="px-6 py-3 font-medium">{{ __('Nom') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Prix') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Stock') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Statut') }}</th>
                        <th class="px-6 py-3 font-medium text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-beige">
                    @forelse ($products as $product)
                        <tr>
                            <td class="px-6 py-4 text-choco-dark">{{ $product->name }}</td>
                            <td class="px-6 py-4 text-choco-dark">{{ number_format($product->price, 0, ',', ' ') }} {{ $product->devise }}</td>
                            <td class="px-6 py-4 text-choco-dark">{{ $product->stock }}</td>
                            <td class="px-6 py-4">
                                <span class="text-xs px-2 py-1 rounded-full {{ $product->is_active ? 'bg-green-100 text-green-700' : 'bg-beige text-choco-soft' }}">
                                    {{ $product->is_active ? __('Visible') : __('Masqué') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right space-x-3">
                                <a href="{{ route('seller.products.edit', $product) }}" class="text-choco text-xs font-medium hover:underline">{{ __('Modifier') }}</a>
                                <form method="POST" action="{{ route('seller.products.destroy', $product) }}" class="inline"
                                    onsubmit="return confirm('{{ __('Supprimer ce produit ?') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-500 text-xs font-medium hover:underline">{{ __('Supprimer') }}</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-choco-soft">{{ __('Aucun produit pour le moment.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>{{ $products->links() }}</div>

    </div>
</x-seller-layout>
