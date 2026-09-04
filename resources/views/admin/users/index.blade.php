<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Utilisateurs') }}
        </h2>
    </x-slot>

    <div class="bg-gray-800 rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-700 text-sm">
            <thead>
                <tr class="text-left text-gray-400">
                    <th class="px-6 py-3 font-medium">{{ __('Nom') }}</th>
                    <th class="px-6 py-3 font-medium">{{ __('Email') }}</th>
                    <th class="px-6 py-3 font-medium">{{ __('Rôle') }}</th>
                    <th class="px-6 py-3 font-medium">{{ __('Inscrit le') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700 text-gray-200">
                @foreach ($users as $user)
                    <tr>
                        <td class="px-6 py-4">{{ $user->name }}</td>
                        <td class="px-6 py-4">{{ $user->email }}</td>
                        <td class="px-6 py-4">
                            @if ($user->is_admin)
                                <span class="text-xs px-2 py-1 rounded-full bg-purple-900 text-purple-200">Admin</span>
                            @endif
                            @if ($user->sellerProfile)
                                <span class="text-xs px-2 py-1 rounded-full bg-amber-900 text-amber-200 capitalize">Seller · {{ $user->sellerProfile->status }}</span>
                            @endif
                            @unless ($user->is_admin || $user->sellerProfile)
                                <span class="text-xs px-2 py-1 rounded-full bg-gray-700 text-gray-300">Customer</span>
                            @endunless
                        </td>
                        <td class="px-6 py-4 text-gray-400">{{ $user->created_at->format('d/m/Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4 text-gray-300">{{ $users->links() }}</div>
</x-admin-layout>
