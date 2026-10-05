<?php

namespace App\Actions;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Shop;
use Illuminate\Support\Str;

class SaveSellerProduct
{
    /**
     * @param  array{name: string, category_id?: int|null, subcategory_id?: int|null, description?: string|null, price: int, discount_price?: int|null, devise: string, stock: int, image_url?: string|null, is_active: bool, gallery_urls?: string|null, weight_kg?: float|null, length_cm?: float|null, width_cm?: float|null, height_cm?: float|null, variants?: array}  $validated
     */
    public function execute(Shop $shop, array $validated, ?Product $product = null): Product
    {
        if ($product === null) {
            $product = new Product($validated);
            $product->shop_id = $shop->id;
            $product->category_id = $validated['category_id'] ?? null;
            $product->subcategory_id = $validated['subcategory_id'] ?? null;
            $product->slug = $this->uniqueSlug($shop, $validated['name']);
        } else {
            $product->fill($validated);
            $product->category_id = $validated['category_id'] ?? null;
            $product->subcategory_id = $validated['subcategory_id'] ?? null;

            if ($validated['name'] !== $product->getOriginal('name')) {
                $product->slug = $this->uniqueSlug($shop, $validated['name'], $product->id);
            }
        }

        $product->save();

        $this->syncGallery($product, $validated['gallery_urls'] ?? '');
        $this->syncVariants($product, $validated['variants'] ?? []);

        return $product;
    }

    /**
     * @param  array<int, array{label?: string, value?: string, stock?: int, price?: int|null}>  $variants
     */
    private function syncVariants(Product $product, array $variants): void
    {
        $product->variants()->delete();

        foreach ($variants as $row) {
            $label = trim($row['label'] ?? '');
            $value = trim($row['value'] ?? '');

            if ($label === '' || $value === '') {
                continue;
            }

            $variant = new ProductVariant([
                'label' => $label,
                'value' => $value,
                'stock' => (int) ($row['stock'] ?? 0),
                'price' => filled($row['price'] ?? null) ? (int) $row['price'] : null,
            ]);
            $variant->product_id = $product->id;
            $variant->save();
        }
    }

    private function syncGallery(Product $product, string $galleryUrls): void
    {
        $product->images()->delete();

        $urls = collect(preg_split('/\r\n|\r|\n/', $galleryUrls))
            ->map(fn ($url) => trim($url))
            ->filter()
            ->values();

        foreach ($urls as $position => $url) {
            $image = new ProductImage(['url' => $url, 'position' => $position]);
            $image->product_id = $product->id;
            $image->save();
        }
    }

    private function uniqueSlug(Shop $shop, string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $suffix = 2;

        while (
            $shop->products()
                ->where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
