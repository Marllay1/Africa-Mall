<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-seller-sidebar leading-tight">
            {{ __('Moyens de paiement') }}
        </h2>
    </x-slot>

    @if (session('status'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-lg p-4 mb-4">
            @switch(session('status'))
                @case('payment-method-created') {{ __('Moyen de paiement ajouté.') }} @break
                @case('payment-method-updated') {{ __('Moyen de paiement mis à jour.') }} @break
                @case('payment-method-enabled') {{ __('Moyen de paiement activé.') }} @break
                @case('payment-method-disabled') {{ __('Moyen de paiement désactivé.') }} @break
                @case('payment-method-deleted') {{ __('Moyen de paiement supprimé.') }} @break
                @case('payment-method-in-use') {{ __('Impossible de supprimer : ce moyen de paiement a déjà été utilisé dans des commandes. Désactivez-le plutôt.') }} @break
            @endswitch
        </div>
    @endif

    <div class="bg-white rounded-[20px] shadow-[0_10px_25px_rgba(120,70,30,.07)] border border-[#f0e2d0] p-5 mb-5">
        <h3 class="text-seller-sidebar font-semibold mb-3">{{ __('Ajouter un moyen de paiement') }}</h3>
        <form method="POST" action="{{ route('admin.payment-methods.store') }}" class="grid grid-cols-1 sm:grid-cols-5 gap-3">
            @csrf
            <div class="sm:col-span-1">
                <label class="block text-sm text-[#7b5e47] mb-1">{{ __('Code') }}</label>
                <input name="code" type="text" required placeholder="paypal"
                    class="w-full bg-admin-bg border border-[#ede3d3] rounded-md text-seller-sidebar px-3 py-2 text-sm">
            </div>
            <div class="sm:col-span-1">
                <label class="block text-sm text-[#7b5e47] mb-1">{{ __('Nom affiché') }}</label>
                <input name="label" type="text" required placeholder="PayPal"
                    class="w-full bg-admin-bg border border-[#ede3d3] rounded-md text-seller-sidebar px-3 py-2 text-sm">
            </div>
            <div class="sm:col-span-1">
                <label class="block text-sm text-[#7b5e47] mb-1">{{ __('Icône (emoji)') }}</label>
                <input name="icon" type="text" maxlength="10" placeholder="💰"
                    class="w-full bg-admin-bg border border-[#ede3d3] rounded-md text-seller-sidebar px-3 py-2 text-sm">
            </div>
            <div class="sm:col-span-1">
                <label class="block text-sm text-[#7b5e47] mb-1">{{ __('Ordre d\'affichage') }}</label>
                <input name="position" type="number" min="0" value="{{ $paymentMethods->count() }}"
                    class="w-full bg-admin-bg border border-[#ede3d3] rounded-md text-seller-sidebar px-3 py-2 text-sm">
            </div>
            <div class="sm:col-span-1 flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-sm rounded-md">{{ __('Ajouter') }}</button>
            </div>
            <div class="sm:col-span-5">
                <label class="block text-sm text-[#7b5e47] mb-1">{{ __('Instructions affichées au client (optionnel)') }}</label>
                <textarea name="instructions" rows="2" placeholder="{{ __('Ex. : vous recevrez un SMS de confirmation après validation.') }}"
                    class="w-full bg-admin-bg border border-[#ede3d3] rounded-md text-seller-sidebar px-3 py-2 text-sm"></textarea>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-[20px] shadow-[0_10px_25px_rgba(120,70,30,.07)] border border-[#f0e2d0] overflow-hidden">
        <table class="min-w-full divide-y divide-[#f0e2d0] text-sm">
            <thead>
                <tr class="text-left text-[#7b5e47]">
                    <th class="px-6 py-3 font-medium">{{ __('Ordre') }}</th>
                    <th class="px-6 py-3 font-medium">{{ __('Code') }}</th>
                    <th class="px-6 py-3 font-medium">{{ __('Nom affiché') }}</th>
                    <th class="px-6 py-3 font-medium">{{ __('Icône') }}</th>
                    <th class="px-6 py-3 font-medium">{{ __('Instructions') }}</th>
                    <th class="px-6 py-3 font-medium">{{ __('Statut') }}</th>
                    <th class="px-6 py-3 font-medium text-right">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#f0e2d0] text-seller-sidebar">
                @forelse ($paymentMethods as $method)
                    <tr>
                        <td class="px-6 py-4">
                            <input name="position" type="number" min="0" value="{{ $method->position }}" form="form-{{ $method->id }}"
                                class="w-16 bg-admin-bg border border-[#ede3d3] rounded-md text-seller-sidebar px-2 py-1 text-sm">
                        </td>
                        <td class="px-6 py-4 text-[#7b5e47]">{{ $method->code }}</td>
                        <td class="px-6 py-4">
                            <input name="label" type="text" value="{{ $method->label }}" form="form-{{ $method->id }}"
                                class="w-full bg-admin-bg border border-[#ede3d3] rounded-md text-seller-sidebar px-2 py-1 text-sm">
                        </td>
                        <td class="px-6 py-4">
                            <input name="icon" type="text" maxlength="10" value="{{ $method->icon }}" form="form-{{ $method->id }}"
                                class="w-16 bg-admin-bg border border-[#ede3d3] rounded-md text-seller-sidebar px-2 py-1 text-sm">
                        </td>
                        <td class="px-6 py-4 max-w-xs">
                            <input name="instructions" type="text" value="{{ $method->instructions }}" form="form-{{ $method->id }}"
                                class="w-full bg-admin-bg border border-[#ede3d3] rounded-md text-seller-sidebar px-2 py-1 text-sm">
                        </td>
                        <td class="px-6 py-4">
                            @if ($method->is_active)
                                <span class="text-xs px-2 py-1 rounded-full bg-emerald-100 text-emerald-700">{{ __('Actif') }}</span>
                            @else
                                <span class="text-xs px-2 py-1 rounded-full bg-[#f3e7d9] text-seller-sidebar">{{ __('Inactif') }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <form id="form-{{ $method->id }}" method="POST" action="{{ route('admin.payment-methods.update', $method) }}" class="inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="text-amber-600 hover:text-amber-700 text-xs">{{ __('Enregistrer') }}</button>
                            </form>
                            <form method="POST" action="{{ route('admin.payment-methods.toggle', $method) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-xs {{ $method->is_active ? 'text-[#7b5e47] hover:text-seller-sidebar' : 'text-emerald-600 hover:text-emerald-700' }}">
                                    {{ $method->is_active ? __('Désactiver') : __('Activer') }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.payment-methods.destroy', $method) }}" class="inline" onsubmit="return confirm('{{ __('Supprimer ce moyen de paiement ?') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-700 text-xs">{{ __('Supprimer') }}</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-[#a8815a]">{{ __('Aucun moyen de paiement.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin-layout>
