<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Mes produits') }}
            </h2>
            <a href="{{ route('seller.products.create') }}" class="px-4 py-2 bg-gray-800 text-white text-sm rounded-md">
                {{ __('Nouveau produit') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="bg-green-100 border border-green-300 text-green-800 text-sm rounded-md p-4">
                    @switch(session('status'))
                        @case('product-created') {{ __('Produit créé.') }} @break
                        @case('product-updated') {{ __('Produit mis à jour.') }} @break
                        @case('product-deleted') {{ __('Produit supprimé.') }} @break
                    @endswitch
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 text-left text-gray-500">
                        <tr>
                            <th class="px-6 py-3 font-medium">{{ __('Nom') }}</th>
                            <th class="px-6 py-3 font-medium">{{ __('Prix') }}</th>
                            <th class="px-6 py-3 font-medium">{{ __('Stock') }}</th>
                            <th class="px-6 py-3 font-medium">{{ __('Statut') }}</th>
                            <th class="px-6 py-3 font-medium text-right">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($products as $product)
                            <tr>
                                <td class="px-6 py-4">{{ $product->name }}</td>
                                <td class="px-6 py-4">{{ number_format($product->price, 0, ',', ' ') }} {{ $product->devise }}</td>
                                <td class="px-6 py-4">{{ $product->stock }}</td>
                                <td class="px-6 py-4">
                                    <span class="text-xs px-2 py-1 rounded-full {{ $product->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                        {{ $product->is_active ? __('Visible') : __('Masqué') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <a href="{{ route('seller.products.edit', $product) }}" class="text-indigo-600 text-xs">{{ __('Modifier') }}</a>
                                    <form method="POST" action="{{ route('seller.products.destroy', $product) }}" class="inline"
                                        onsubmit="return confirm('{{ __('Supprimer ce produit ?') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-500 text-xs">{{ __('Supprimer') }}</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">{{ __('Aucun produit pour le moment.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div>{{ $products->links() }}</div>

        </div>
    </div>
</x-app-layout>
