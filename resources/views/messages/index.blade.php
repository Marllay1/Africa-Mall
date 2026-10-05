<x-app-layout>
    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="flex h-[calc(100vh-230px)] min-h-[420px] bg-white rounded-[24px] overflow-hidden shadow-sm border border-beige">
                @include('messages._list', ['conversations' => $conversations, 'activeConversation' => null])

                <div class="flex-1 hidden sm:flex flex-col items-center justify-center text-center px-6 bg-cream/40">
                    <div class="w-16 h-16 rounded-full bg-white flex items-center justify-center mb-4 shadow-sm">
                        <i class="fas fa-comments text-2xl text-choco-soft"></i>
                    </div>
                    <p class="text-choco-soft">{{ __('Sélectionnez une conversation pour afficher les messages.') }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
