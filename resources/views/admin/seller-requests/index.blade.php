<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Demandes de souscription Vendeur') }}
        </h2>
    </x-slot>

    <div class="space-y-8">

        @if (session('status'))
            <div class="bg-emerald-900/50 border border-emerald-700 text-emerald-200 text-sm rounded-md p-4">
                @switch(session('status'))
                    @case('seller-request-approved')
                        {{ __('Demande approuvée, la boutique a été créée.') }}
                        @break
                    @case('seller-request-rejected')
                        {{ __('Demande refusée.') }}
                        @break
                @endswitch
            </div>
        @endif

        <div class="bg-gray-800 rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-700">
                <h3 class="text-gray-100 font-semibold">{{ __('En attente') }} ({{ $pending->count() }})</h3>
            </div>
            <table class="min-w-full divide-y divide-gray-700 text-sm">
                <thead>
                    <tr class="text-left text-gray-400">
                        <th class="px-6 py-3 font-medium">{{ __('Compte') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Boutique') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Adresse') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Soumis le') }}</th>
                        <th class="px-6 py-3 font-medium text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700 text-gray-200">
                    @forelse ($pending as $request)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="font-medium">{{ $request->user->name }}</div>
                                <div class="text-gray-400 text-xs">{{ $request->user->email }}</div>
                            </td>
                            <td class="px-6 py-4">{{ $request->shop_name }}</td>
                            <td class="px-6 py-4">{{ $request->adresse }}</td>
                            <td class="px-6 py-4">{{ $request->submitted_at?->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <form method="POST" action="{{ route('admin.seller-requests.approve', $request) }}" class="inline">
                                    @csrf
                                    <button class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs rounded-md">
                                        {{ __('Approuver') }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.seller-requests.reject', $request) }}" class="inline">
                                    @csrf
                                    <button class="px-3 py-1.5 bg-red-600 hover:bg-red-500 text-white text-xs rounded-md">
                                        {{ __('Rejeter') }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">{{ __('Aucune demande en attente.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bg-gray-800 rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-700">
                <h3 class="text-gray-100 font-semibold">{{ __('Décisions récentes') }}</h3>
            </div>
            <table class="min-w-full divide-y divide-gray-700 text-sm">
                <thead>
                    <tr class="text-left text-gray-400">
                        <th class="px-6 py-3 font-medium">{{ __('Compte') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Boutique') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Statut') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Traité par') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Le') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700 text-gray-200">
                    @forelse ($reviewed as $request)
                        <tr>
                            <td class="px-6 py-4">{{ $request->user->name }}</td>
                            <td class="px-6 py-4">{{ $request->shop_name }}</td>
                            <td class="px-6 py-4 capitalize">{{ $request->status }}</td>
                            <td class="px-6 py-4">{{ $request->reviewer?->name ?? '—' }}</td>
                            <td class="px-6 py-4">{{ $request->reviewed_at?->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">{{ __('Aucune décision pour le moment.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</x-admin-layout>
