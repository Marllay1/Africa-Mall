<x-seller-layout>
    <div class="flex h-[calc(100vh-180px)] min-h-[420px] bg-white rounded-[24px] overflow-hidden shadow-[0_10px_25px_rgba(120,70,30,.07)] border border-[#f0e2d0]">
        @include('seller.messages._contacts', ['activeConversation' => null])

        <div class="flex-1 hidden sm:flex items-center justify-center text-[#7b5e47] bg-[#e8ddd0]">
            {{ __('Sélectionnez une conversation') }}
        </div>
    </div>
</x-seller-layout>
