@php $product = $product ?? null; $galleryUrls = $galleryUrls ?? ''; @endphp

<div>
    <x-input-label for="name" :value="__('Nom du produit')" />
    <x-text-input id="name" name="name" class="block mt-1 w-full" :value="old('name', $product?->name)" required />
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>

<div>
    <x-input-label for="category_id" :value="__('Catégorie')" />
    <select id="category_id" name="category_id" class="block mt-1 w-full border-[#e0cfb5] focus:border-seller-accent focus:ring-seller-accent rounded-2xl">
        <option value="">{{ __('Aucune') }}</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}" @selected(old('category_id', $product?->category_id) == $category->id)>{{ $category->name }}</option>
        @endforeach
    </select>
    <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
</div>

<div>
    <x-input-label for="description" :value="__('Description')" />
    <textarea id="description" name="description" rows="4"
        class="block mt-1 w-full border-[#e0cfb5] focus:border-seller-accent focus:ring-seller-accent rounded-2xl">{{ old('description', $product?->description) }}</textarea>
    <x-input-error :messages="$errors->get('description')" class="mt-2" />
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <x-input-label for="price" :value="__('Prix')" />
        <x-text-input id="price" name="price" type="number" min="0" class="block mt-1 w-full" :value="old('price', $product?->price)" required />
        <x-input-error :messages="$errors->get('price')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="discount_price" :value="__('Prix réduit (optionnel)')" />
        <x-text-input id="discount_price" name="discount_price" type="number" min="0" class="block mt-1 w-full" :value="old('discount_price', $product?->discount_price)" />
        <x-input-error :messages="$errors->get('discount_price')" class="mt-2" />
    </div>
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <x-input-label for="devise" :value="__('Devise')" />
        <select id="devise" name="devise" class="block mt-1 w-full border-[#e0cfb5] focus:border-seller-accent focus:ring-seller-accent rounded-2xl">
            @foreach (['XOF' => 'Franc CFA (XOF)', 'USD' => 'Dollar américain (USD)', 'EUR' => 'Euro (EUR)'] as $code => $label)
                <option value="{{ $code }}" @selected(old('devise', $product?->devise ?? 'XOF') === $code)>{{ $label }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('devise')" class="mt-2" />
    </div>
</div>

<div>
    <x-input-label for="stock" :value="__('Stock')" />
    <x-text-input id="stock" name="stock" type="number" min="0" class="block mt-1 w-full" :value="old('stock', $product?->stock ?? 0)" required />
    <x-input-error :messages="$errors->get('stock')" class="mt-2" />
</div>

<div>
    <x-input-label for="image_url" :value="__('URL de l\'image principale')" />
    <x-text-input id="image_url" name="image_url" type="url" class="block mt-1 w-full" :value="old('image_url', $product?->image_url)" placeholder="https://..." />
    <x-input-error :messages="$errors->get('image_url')" class="mt-2" />
</div>

<div>
    <x-input-label for="gallery_urls" :value="__('Images supplémentaires (une URL par ligne)')" />
    <textarea id="gallery_urls" name="gallery_urls" rows="3" placeholder="https://...&#10;https://..."
        class="block mt-1 w-full border-[#e0cfb5] focus:border-seller-accent focus:ring-seller-accent rounded-2xl">{{ old('gallery_urls', $galleryUrls) }}</textarea>
    <x-input-error :messages="$errors->get('gallery_urls')" class="mt-2" />
</div>

<div class="flex items-center gap-2">
    <input type="checkbox" id="is_active" name="is_active" value="1" @checked(old('is_active', $product?->is_active ?? true))
        class="rounded border-[#e0cfb5] text-seller-accent">
    <x-input-label for="is_active" :value="__('Produit visible dans le marché')" />
</div>
