<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-seller-sidebar leading-tight">
            {{ __('Catégories') }}
        </h2>
    </x-slot>

    @if (session('status'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-lg p-4 mb-4">
            @switch(session('status'))
                @case('category-created') {{ __('Catégorie créée.') }} @break
                @case('category-updated') {{ __('Catégorie mise à jour.') }} @break
                @case('category-deleted') {{ __('Catégorie supprimée.') }} @break
                @case('category-in-use') {{ __('Impossible de supprimer : des produits utilisent encore cette catégorie.') }} @break
                @case('category-has-children') {{ __('Impossible de supprimer : cette catégorie a encore des sous-catégories.') }} @break
            @endswitch
        </div>
    @endif

    <div class="bg-white rounded-[20px] shadow-[0_10px_25px_rgba(120,70,30,.07)] border border-[#f0e2d0] p-5 mb-5">
        <form method="POST" action="{{ route('admin.categories.store') }}" class="flex items-end gap-3">
            @csrf
            <div class="flex-1">
                <label for="name" class="block text-sm text-[#7b5e47] mb-1">{{ __('Nouvelle catégorie / sous-catégorie') }}</label>
                <input id="name" name="name" type="text" required
                    class="w-full bg-admin-bg border border-[#ede3d3] rounded-md text-seller-sidebar px-3 py-2 text-sm">
            </div>
            <div class="flex-1">
                <label for="parent_id" class="block text-sm text-[#7b5e47] mb-1">{{ __('Catégorie parente (optionnel)') }}</label>
                <select id="parent_id" name="parent_id" class="w-full bg-admin-bg border border-[#ede3d3] rounded-md text-seller-sidebar px-3 py-2 text-sm">
                    <option value="">{{ __('Aucune — nouvelle catégorie principale') }}</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-sm rounded-md">{{ __('Ajouter') }}</button>
        </form>
    </div>

    <div class="bg-white rounded-[20px] shadow-[0_10px_25px_rgba(120,70,30,.07)] border border-[#f0e2d0] overflow-hidden">
        <table class="min-w-full divide-y divide-[#f0e2d0] text-sm">
            <thead>
                <tr class="text-left text-[#7b5e47]">
                    <th class="px-6 py-3 font-medium">{{ __('Nom') }}</th>
                    <th class="px-6 py-3 font-medium">{{ __('Catégorie parente') }}</th>
                    <th class="px-6 py-3 font-medium">{{ __('Produits') }}</th>
                    <th class="px-6 py-3 font-medium text-right">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#f0e2d0] text-seller-sidebar">
                @forelse ($categories as $category)
                    <tr>
                        <td class="px-6 py-4">
                            <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="flex items-center gap-2">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="parent_id" value="">
                                <input name="name" value="{{ $category->name }}"
                                    class="bg-admin-bg border border-[#ede3d3] rounded-md text-seller-sidebar px-2 py-1 text-sm w-48">
                                <button type="submit" class="text-amber-600 hover:text-amber-700 text-xs">{{ __('Enregistrer') }}</button>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-[#7b5e47]">—</td>
                        <td class="px-6 py-4 text-[#7b5e47]">{{ $category->products_count }}</td>
                        <td class="px-6 py-4 text-right">
                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('{{ __('Supprimer cette catégorie ?') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-700">{{ __('Supprimer') }}</button>
                            </form>
                        </td>
                    </tr>
                    @foreach ($category->children as $sub)
                        <tr class="bg-admin-bg/40">
                            <td class="px-6 py-4 pl-12">
                                <form method="POST" action="{{ route('admin.categories.update', $sub) }}" class="flex items-center gap-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="parent_id" value="{{ $category->id }}">
                                    <i class="fas fa-angle-right text-[#c9ae8c]"></i>
                                    <input name="name" value="{{ $sub->name }}"
                                        class="bg-white border border-[#ede3d3] rounded-md text-seller-sidebar px-2 py-1 text-sm w-44">
                                    <button type="submit" class="text-amber-600 hover:text-amber-700 text-xs">{{ __('Enregistrer') }}</button>
                                </form>
                            </td>
                            <td class="px-6 py-4 text-[#7b5e47]">{{ $category->name }}</td>
                            <td class="px-6 py-4 text-[#7b5e47]">{{ $sub->products_count }}</td>
                            <td class="px-6 py-4 text-right">
                                <form method="POST" action="{{ route('admin.categories.destroy', $sub) }}" onsubmit="return confirm('{{ __('Supprimer cette sous-catégorie ?') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-700">{{ __('Supprimer') }}</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-[#a8815a]">{{ __('Aucune catégorie.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin-layout>
