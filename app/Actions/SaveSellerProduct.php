<?php

namespace App\Actions;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Shop;
use Illuminate\Support\Str;

class SaveSellerProduct
{
    /**
     * @param  array{name: string, category_id?: int|null, description?: string|null, price: int, discount_price?: int|null, devise: string, stock: int, image_url?: string|null, is_active: bool, gallery_urls?: string|null}  $validated
     */
    public function execute(Shop $shop, array $validated, ?Product $product = null): Product
    {
        if ($product === null) {
            $product = new Product($validated);
            $product->shop_id = $shop->id;
            $product->category_id = $validated['category_id'] ?? null;
            $product->slug = $this->uniqueSlug($shop, $validated['name']);
        } else {
            $product->fill($validated);
            $product->category_id = $validated['category_id'] ?? null;

            if ($validated['name'] !== $product->getOriginal('name')) {
                $product->slug = $this->uniqueSlug($shop, $validated['name'], $product->id);
            }
        }

        $product->save();

        $this->syncGallery($product, $validated['gallery_urls'] ?? '');

        return $product;
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
