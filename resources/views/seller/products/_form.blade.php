@php
    $product = $product ?? null;
    $galleryUrls = $galleryUrls ?? '';
    $variants = $variants ?? collect();
@endphp

<div>
    <x-input-label for="name" :value="__('Nom du produit')" />
    <x-text-input id="name" name="name" class="block mt-1 w-full" :value="old('name', $product?->name)" required />
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>

<div x-data="{
        categories: {{ \Illuminate\Support\Js::from($categories->map(fn ($c) => ['id' => $c->id, 'name' => $c->name, 'children' => $c->children->map(fn ($s) => ['id' => $s->id, 'name' => $s->name])->values()])) }},
        categoryId: '{{ old('category_id', $product?->category_id) }}',
        subcategoryId: '{{ old('subcategory_id', $product?->subcategory_id) }}',
    }" class="grid grid-cols-2 gap-4">
    <div>
        <x-input-label for="category_id" :value="__('Catégorie')" />
        <select id="category_id" name="category_id" x-model="categoryId" @change="subcategoryId = ''"
            class="block mt-1 w-full border-[#e0cfb5] focus:border-seller-accent focus:ring-seller-accent rounded-2xl">
            <option value="">{{ __('Aucune') }}</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
    </div>
    <div x-show="(categories.find(c => String(c.id) === String(categoryId))?.children ?? []).length > 0">
        <x-input-label for="subcategory_id" :value="__('Sous-catégorie')" />
        <select id="subcategory_id" name="subcategory_id" x-model="subcategoryId"
            class="block mt-1 w-full border-[#e0cfb5] focus:border-seller-accent focus:ring-seller-accent rounded-2xl">
            <option value="">{{ __('Aucune') }}</option>
            <template x-for="sub in (categories.find(c => String(c.id) === String(categoryId))?.children ?? [])" :key="sub.id">
                <option :value="sub.id" x-text="sub.name"></option>
            </template>
        </select>
        <x-input-error :messages="$errors->get('subcategory_id')" class="mt-2" />
    </div>
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

<div class="grid grid-cols-4 gap-4">
    <div>
        <x-input-label for="weight_kg" :value="__('Poids (kg)')" />
        <x-text-input id="weight_kg" name="weight_kg" type="number" step="0.001" min="0" class="block mt-1 w-full" :value="old('weight_kg', $product?->weight_kg)" />
        <x-input-error :messages="$errors->get('weight_kg')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="length_cm" :value="__('Longueur (cm)')" />
        <x-text-input id="length_cm" name="length_cm" type="number" step="0.01" min="0" class="block mt-1 w-full" :value="old('length_cm', $product?->length_cm)" />
        <x-input-error :messages="$errors->get('length_cm')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="width_cm" :value="__('Largeur (cm)')" />
        <x-text-input id="width_cm" name="width_cm" type="number" step="0.01" min="0" class="block mt-1 w-full" :value="old('width_cm', $product?->width_cm)" />
        <x-input-error :messages="$errors->get('width_cm')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="height_cm" :value="__('Hauteur (cm)')" />
        <x-text-input id="height_cm" name="height_cm" type="number" step="0.01" min="0" class="block mt-1 w-full" :value="old('height_cm', $product?->height_cm)" />
        <x-input-error :messages="$errors->get('height_cm')" class="mt-2" />
    </div>
</div>

<div x-data="{
        variants: {{ \Illuminate\Support\Js::from($variants->map(fn ($v) => ['label' => $v->label, 'value' => $v->value, 'stock' => $v->stock, 'price' => $v->price])) }},
    }" class="space-y-3">
    <x-input-label :value="__('Variantes (optionnel — ex. Taille: M, Couleur: Rouge)')" />
    <template x-for="(variant, index) in variants" :key="index">
        <div class="flex flex-wrap items-end gap-2 bg-[#faf3ea] rounded-2xl p-3">
            <div class="flex-1 min-w-[100px]">
                <label class="text-xs text-[#7b5e47]">{{ __('Attribut') }}</label>
                <input type="text" :name="`variants[${index}][label]`" x-model="variant.label" placeholder="{{ __('Taille') }}"
                    class="block mt-1 w-full border-[#e0cfb5] focus:border-seller-accent focus:ring-seller-accent rounded-xl text-sm">
            </div>
            <div class="flex-1 min-w-[100px]">
                <label class="text-xs text-[#7b5e47]">{{ __('Valeur') }}</label>
                <input type="text" :name="`variants[${index}][value]`" x-model="variant.value" placeholder="M"
                    class="block mt-1 w-full border-[#e0cfb5] focus:border-seller-accent focus:ring-seller-accent rounded-xl text-sm">
            </div>
            <div class="w-24">
                <label class="text-xs text-[#7b5e47]">{{ __('Stock') }}</label>
                <input type="number" min="0" :name="`variants[${index}][stock]`" x-model.number="variant.stock"
                    class="block mt-1 w-full border-[#e0cfb5] focus:border-seller-accent focus:ring-seller-accent rounded-xl text-sm">
            </div>
            <div class="w-28">
                <label class="text-xs text-[#7b5e47]">{{ __('Prix (optionnel)') }}</label>
                <input type="number" min="0" :name="`variants[${index}][price]`" x-model.number="variant.price"
                    class="block mt-1 w-full border-[#e0cfb5] focus:border-seller-accent focus:ring-seller-accent rounded-xl text-sm">
            </div>
            <button type="button" @click="variants.splice(index, 1)" class="w-9 h-9 rounded-xl bg-[#f7e0db] text-[#b34a3b]">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </template>
    <button type="button" @click="variants.push({ label: '', value: '', stock: 0, price: null })"
        class="text-sm font-semibold text-seller-sidebar">
        <i class="fas fa-plus-circle"></i> {{ __('Ajouter une variante') }}
    </button>
</div>

<div class="flex items-center gap-2">
    <input type="checkbox" id="is_active" name="is_active" value="1" @checked(old('is_active', $product?->is_active ?? true))
        class="rounded border-[#e0cfb5] text-seller-accent">
    <x-input-label for="is_active" :value="__('Produit visible dans le marché')" />
</div>
