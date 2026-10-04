<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-seller-sidebar leading-tight">
            {{ __('Journaux applicatifs') }}
        </h2>
    </x-slot>

    <div class="space-y-4">
        <a href="{{ route('admin.settings.edit') }}" class="inline-flex items-center gap-2 text-sm text-[#7b5e47] hover:text-seller-sidebar">
            <i class="fas fa-arrow-left"></i> {{ __('Retour aux paramètres') }}
        </a>

        <div class="bg-white rounded-[20px] shadow-[0_10px_25px_rgba(120,70,30,.07)] border border-[#f0e2d0] p-4">
            <p class="text-xs text-[#a8815a] mb-3">{{ __('200 dernières lignes, les plus récentes en premier.') }}</p>
            <div class="bg-[#2b1f16] text-[#f3e7d9] rounded-lg p-4 text-xs font-mono overflow-x-auto max-h-[70vh] overflow-y-auto space-y-1">
                @forelse ($lines as $line)
                    <div class="whitespace-pre-wrap break-all">{{ $line }}</div>
                @empty
                    <p class="text-[#a8815a]">{{ __('Aucun log disponible.') }}</p>
                @endforelse
            </div>
        </div>
    </div>
</x-admin-layout>
