<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Catégories') }}
        </h2>
    </x-slot>

    @if (session('status'))
        <div class="bg-emerald-900/40 border border-emerald-700 text-emerald-200 text-sm rounded-lg p-4 mb-4">
            @switch(session('status'))
                @case('category-created') {{ __('Catégorie créée.') }} @break
                @case('category-updated') {{ __('Catégorie mise à jour.') }} @break
                @case('category-deleted') {{ __('Catégorie supprimée.') }} @break
                @case('category-in-use') {{ __('Impossible de supprimer : des produits utilisent encore cette catégorie.') }} @break
            @endswitch
        </div>
    @endif

    <div class="bg-gray-800 rounded-lg p-5 mb-5">
        <form method="POST" action="{{ route('admin.categories.store') }}" class="flex items-end gap-3">
            @csrf
            <div class="flex-1">
                <label for="name" class="block text-sm text-gray-400 mb-1">{{ __('Nouvelle catégorie') }}</label>
                <input id="name" name="name" type="text" required
                    class="w-full bg-gray-900 border border-gray-700 rounded-md text-gray-100 px-3 py-2 text-sm">
            </div>
            <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-sm rounded-md">{{ __('Ajouter') }}</button>
        </form>
    </div>

    <div class="bg-gray-800 rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-700 text-sm">
            <thead>
                <tr class="text-left text-gray-400">
                    <th class="px-6 py-3 font-medium">{{ __('Nom') }}</th>
                    <th class="px-6 py-3 font-medium">{{ __('Produits') }}</th>
                    <th class="px-6 py-3 font-medium text-right">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700 text-gray-200">
                @forelse ($categories as $category)
                    <tr>
                        <td class="px-6 py-4">
                            <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="flex items-center gap-2">
                                @csrf
                                @method('PUT')
                                <input name="name" value="{{ $category->name }}"
                                    class="bg-gray-900 border border-gray-700 rounded-md text-gray-100 px-2 py-1 text-sm w-48">
                                <button type="submit" class="text-amber-400 hover:text-amber-300 text-xs">{{ __('Enregistrer') }}</button>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-gray-400">{{ $category->products_count }}</td>
                        <td class="px-6 py-4 text-right">
                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('{{ __('Supprimer cette catégorie ?') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-300">{{ __('Supprimer') }}</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-8 text-center text-gray-500">{{ __('Aucune catégorie.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin-layout>
