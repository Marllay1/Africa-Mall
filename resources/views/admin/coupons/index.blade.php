<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-seller-sidebar leading-tight">
            {{ __('Promotions') }}
        </h2>
    </x-slot>

    @if (session('status'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-lg p-4 mb-4">
            @switch(session('status'))
                @case('coupon-created') {{ __('Code promo créé.') }} @break
                @case('coupon-enabled') {{ __('Code promo activé.') }} @break
                @case('coupon-disabled') {{ __('Code promo désactivé.') }} @break
                @case('coupon-deleted') {{ __('Code promo supprimé.') }} @break
                @case('coupon-in-use') {{ __('Impossible de supprimer : ce code a déjà été utilisé. Désactivez-le plutôt.') }} @break
            @endswitch
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg p-4 mb-4">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="bg-white rounded-[20px] shadow-[0_10px_25px_rgba(120,70,30,.07)] border border-[#f0e2d0] p-5 mb-5">
        <h3 class="text-seller-sidebar font-semibold mb-3">{{ __('Créer un code promo') }}</h3>
        <form method="POST" action="{{ route('admin.coupons.store') }}" class="grid grid-cols-1 sm:grid-cols-6 gap-3">
            @csrf
            <div class="sm:col-span-1">
                <label class="block text-sm text-[#7b5e47] mb-1">{{ __('Code') }}</label>
                <input name="code" type="text" required placeholder="BIENVENUE10" value="{{ old('code') }}"
                    class="w-full bg-admin-bg border border-[#ede3d3] rounded-md text-seller-sidebar px-3 py-2 text-sm uppercase">
            </div>
            <div class="sm:col-span-1">
                <label class="block text-sm text-[#7b5e47] mb-1">{{ __('Type') }}</label>
                <select name="type" class="w-full bg-admin-bg border border-[#ede3d3] rounded-md text-seller-sidebar px-3 py-2 text-sm">
                    <option value="percent">{{ __('Pourcentage') }}</option>
                    <option value="fixed">{{ __('Montant fixe') }}</option>
                </select>
            </div>
            <div class="sm:col-span-1">
                <label class="block text-sm text-[#7b5e47] mb-1">{{ __('Valeur') }}</label>
                <input name="value" type="number" min="1" required placeholder="10"
                    class="w-full bg-admin-bg border border-[#ede3d3] rounded-md text-seller-sidebar px-3 py-2 text-sm">
            </div>
            <div class="sm:col-span-1">
                <label class="block text-sm text-[#7b5e47] mb-1">{{ __('Total min.') }}</label>
                <input name="min_order_total" type="number" min="0" placeholder="{{ __('Optionnel') }}"
                    class="w-full bg-admin-bg border border-[#ede3d3] rounded-md text-seller-sidebar px-3 py-2 text-sm">
            </div>
            <div class="sm:col-span-1">
                <label class="block text-sm text-[#7b5e47] mb-1">{{ __('Limite d\'usage') }}</label>
                <input name="usage_limit" type="number" min="1" placeholder="{{ __('Illimité') }}"
                    class="w-full bg-admin-bg border border-[#ede3d3] rounded-md text-seller-sidebar px-3 py-2 text-sm">
            </div>
            <div class="sm:col-span-1 flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-sm rounded-md">{{ __('Créer') }}</button>
            </div>
            <div class="sm:col-span-3">
                <label class="block text-sm text-[#7b5e47] mb-1">{{ __('Début (optionnel)') }}</label>
                <input name="starts_at" type="datetime-local" class="w-full bg-admin-bg border border-[#ede3d3] rounded-md text-seller-sidebar px-3 py-2 text-sm">
            </div>
            <div class="sm:col-span-3">
                <label class="block text-sm text-[#7b5e47] mb-1">{{ __('Expiration (optionnel)') }}</label>
                <input name="expires_at" type="datetime-local" class="w-full bg-admin-bg border border-[#ede3d3] rounded-md text-seller-sidebar px-3 py-2 text-sm">
            </div>
        </form>
    </div>

    <div class="bg-white rounded-[20px] shadow-[0_10px_25px_rgba(120,70,30,.07)] border border-[#f0e2d0] overflow-hidden">
        <table class="min-w-full divide-y divide-[#f0e2d0] text-sm">
            <thead>
                <tr class="text-left text-[#7b5e47]">
                    <th class="px-6 py-3 font-medium">{{ __('Code') }}</th>
                    <th class="px-6 py-3 font-medium">{{ __('Réduction') }}</th>
                    <th class="px-6 py-3 font-medium">{{ __('Total min.') }}</th>
                    <th class="px-6 py-3 font-medium">{{ __('Usage') }}</th>
                    <th class="px-6 py-3 font-medium">{{ __('Validité') }}</th>
                    <th class="px-6 py-3 font-medium">{{ __('Statut') }}</th>
                    <th class="px-6 py-3 font-medium text-right">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#f0e2d0] text-seller-sidebar">
                @forelse ($coupons as $coupon)
                    <tr>
                        <td class="px-6 py-4 font-medium">{{ $coupon->code }}</td>
                        <td class="px-6 py-4">{{ $coupon->type === 'percent' ? $coupon->value.'%' : number_format($coupon->value, 0, ',', ' ').' XOF' }}</td>
                        <td class="px-6 py-4 text-[#7b5e47]">{{ $coupon->min_order_total ? number_format($coupon->min_order_total, 0, ',', ' ').' XOF' : '—' }}</td>
                        <td class="px-6 py-4 text-[#7b5e47]">{{ $coupon->used_count }}{{ $coupon->usage_limit ? ' / '.$coupon->usage_limit : '' }}</td>
                        <td class="px-6 py-4 text-[#7b5e47] text-xs">
                            @if ($coupon->starts_at || $coupon->expires_at)
                                {{ $coupon->starts_at?->format('d/m/Y') ?? '…' }} → {{ $coupon->expires_at?->format('d/m/Y') ?? '…' }}
                            @else
                                {{ __('Sans limite de date') }}
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if ($coupon->is_active)
                                <span class="text-xs px-2 py-1 rounded-full bg-emerald-100 text-emerald-700">{{ __('Actif') }}</span>
                            @else
                                <span class="text-xs px-2 py-1 rounded-full bg-[#f3e7d9] text-seller-sidebar">{{ __('Inactif') }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <form method="POST" action="{{ route('admin.coupons.toggle', $coupon) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-xs {{ $coupon->is_active ? 'text-[#7b5e47] hover:text-seller-sidebar' : 'text-emerald-600 hover:text-emerald-700' }}">
                                    {{ $coupon->is_active ? __('Désactiver') : __('Activer') }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}" class="inline" onsubmit="return confirm('{{ __('Supprimer ce code ?') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-700 text-xs">{{ __('Supprimer') }}</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-[#a8815a]">{{ __('Aucun code promo.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin-layout>
