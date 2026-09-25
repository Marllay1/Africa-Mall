<x-seller-layout>
    <x-slot name="header">
        <h2 class="text-seller-sidebar font-semibold text-xl">{{ __('Notifications') }}</h2>
    </x-slot>

    <div class="bg-white rounded-[24px] shadow-[0_10px_25px_rgba(120,70,30,.07)] border border-[#f0e2d0] divide-y divide-[#f0e2d0]">
        @forelse ($notifications as $notification)
            <a href="{{ $notification->data['url'] }}" class="block p-5 hover:bg-[#faf5ec] transition {{ $notification->read_at ? '' : 'bg-[#fdf7ee]' }}">
                <div class="flex items-start gap-3">
                    <i class="fas fa-bell mt-1 {{ $notification->read_at ? 'text-[#c8b9a4]' : 'text-[#c29a6a]' }}"></i>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-seller-sidebar">{{ $notification->data['title'] }}</p>
                        <p class="text-sm text-[#7b5e47]">{{ $notification->data['body'] }}</p>
                        <p class="text-xs text-[#a5876a] mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            </a>
        @empty
            <div class="p-10 text-center text-[#7b5e47]">{{ __('Aucune notification pour le moment.') }}</div>
        @endforelse
    </div>
</x-seller-layout>
