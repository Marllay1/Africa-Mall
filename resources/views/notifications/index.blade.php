<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-choco-dark leading-tight">
            {{ __('Notifications') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-2xl border border-beige divide-y divide-beige">
                @forelse ($notifications as $notification)
                    <a href="{{ $notification->data['url'] }}" class="block p-5 hover:bg-cream/60 transition {{ $notification->read_at ? '' : 'bg-cream/40' }}">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-bell mt-1 {{ $notification->read_at ? 'text-choco-soft' : 'text-choco' }}"></i>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-choco-dark">{{ $notification->data['title'] }}</p>
                                <p class="text-sm text-choco-soft">{{ $notification->data['body'] }}</p>
                                <p class="text-xs text-choco-soft mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="p-10 text-center text-choco-soft">{{ __('Aucune notification pour le moment.') }}</div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
