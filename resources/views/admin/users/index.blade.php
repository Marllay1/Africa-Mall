<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-seller-sidebar leading-tight">
            {{ __('Utilisateurs') }}
        </h2>
    </x-slot>

    @if (session('status'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-md p-4 mb-4">
            @switch(session('status'))
                @case('user-blocked') {{ __('Compte bloqué.') }} @break
                @case('user-unblocked') {{ __('Compte débloqué.') }} @break
            @endswitch
        </div>
    @endif

    <div class="bg-white rounded-[20px] shadow-[0_10px_25px_rgba(120,70,30,.07)] border border-[#f0e2d0] overflow-hidden">
        <table class="min-w-full divide-y divide-[#f0e2d0] text-sm">
            <thead>
                <tr class="text-left text-[#7b5e47]">
                    <th class="px-6 py-3 font-medium">{{ __('Nom') }}</th>
                    <th class="px-6 py-3 font-medium">{{ __('Email') }}</th>
                    <th class="px-6 py-3 font-medium">{{ __('Rôle') }}</th>
                    <th class="px-6 py-3 font-medium">{{ __('Inscrit le') }}</th>
                    <th class="px-6 py-3 font-medium text-right">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#f0e2d0] text-seller-sidebar">
                @foreach ($users as $user)
                    <tr>
                        <td class="px-6 py-4">{{ $user->name }}</td>
                        <td class="px-6 py-4">{{ $user->email }}</td>
                        <td class="px-6 py-4">
                            @if ($user->is_admin)
                                <span class="text-xs px-2 py-1 rounded-full bg-purple-100 text-purple-700">Admin</span>
                            @endif
                            @if ($user->sellerProfile)
                                <span class="text-xs px-2 py-1 rounded-full bg-amber-100 text-amber-700 capitalize">Seller · {{ $user->sellerProfile->status }}</span>
                            @endif
                            @unless ($user->is_admin || $user->sellerProfile)
                                <span class="text-xs px-2 py-1 rounded-full bg-[#f3e7d9] text-seller-sidebar">Customer</span>
                            @endunless
                            @if ($user->is_blocked)
                                <span class="text-xs px-2 py-1 rounded-full bg-red-100 text-red-700">{{ __('Bloqué') }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-[#7b5e47]">{{ $user->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-right">
                            @unless ($user->is_admin)
                                @if ($user->is_blocked)
                                    <form method="POST" action="{{ route('admin.users.unblock', $user) }}" class="inline">
                                        @csrf
                                        <button class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs rounded-md">{{ __('Débloquer') }}</button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.users.block', $user) }}" class="inline" onsubmit="return confirm('{{ __('Bloquer ce compte ?') }}')">
                                        @csrf
                                        <button class="px-3 py-1.5 bg-red-600 hover:bg-red-500 text-white text-xs rounded-md">{{ __('Bloquer') }}</button>
                                    </form>
                                @endif
                            @endunless
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4 text-seller-sidebar">{{ $users->links() }}</div>
</x-admin-layout>
