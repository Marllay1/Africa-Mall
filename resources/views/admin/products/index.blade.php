<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Produits') }}
        </h2>
    </x-slot>

    @if (session('status'))
        <div class="bg-emerald-900/40 border border-emerald-700 text-emerald-200 text-sm rounded-lg p-4 mb-4">
            @switch(session('status'))
                @case('product-hidden') {{ __('Produit masqué.') }} @break
                @case('product-shown') {{ __('Produit rendu visible.') }} @break
                @case('product-deleted') {{ __('Produit supprimé.') }} @break
            @endswitch
        </div>
    @endif

    <div class="bg-gray-800 rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-700 text-sm">
            <thead>
                <tr class="text-left text-gray-400">
                    <th class="px-6 py-3 font-medium">{{ __('Produit') }}</th>
                    <th class="px-6 py-3 font-medium">{{ __('Boutique') }}</th>
                    <th class="px-6 py-3 font-medium">{{ __('Prix') }}</th>
                    <th class="px-6 py-3 font-medium">{{ __('Stock') }}</th>
                    <th class="px-6 py-3 font-medium">{{ __('Statut') }}</th>
                    <th class="px-6 py-3 font-medium">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700 text-gray-200">
                @forelse ($products as $product)
                    <tr>
                        <td class="px-6 py-4">{{ $product->name }}</td>
                        <td class="px-6 py-4 text-gray-400">{{ $product->shop->name }}</td>
                        <td class="px-6 py-4">{{ number_format($product->effectivePrice(), 0, ',', ' ') }} {{ $product->devise }}</td>
                        <td class="px-6 py-4">{{ $product->stock }}</td>
                        <td class="px-6 py-4">
                            @if ($product->is_active)
                                <span class="text-xs px-2 py-1 rounded-full bg-emerald-900 text-emerald-200">{{ __('Visible') }}</span>
                            @else
                                <span class="text-xs px-2 py-1 rounded-full bg-gray-700 text-gray-300">{{ __('Masqué') }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <form method="POST" action="{{ route('admin.products.toggle-visibility', $product) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-amber-400 hover:text-amber-300">
                                        {{ $product->is_active ? __('Masquer') : __('Afficher') }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('{{ __('Supprimer ce produit ?') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-300">{{ __('Supprimer') }}</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">{{ __('Aucun produit pour le moment.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 text-gray-300">{{ $products->links() }}</div>
</x-admin-layout>
