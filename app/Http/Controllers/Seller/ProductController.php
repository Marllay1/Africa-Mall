<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Shop;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        return view('seller.products.index', [
            'products' => $this->shop($request)->products()->latest()->paginate(15),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function create(Request $request): View
    {
        return view('seller.products.create', [
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $shop = $this->shop($request);
        $validated = $this->validated($request);

        $product = new Product($validated);
        $product->shop_id = $shop->id;
        $product->category_id = $validated['category_id'] ?? null;
        $product->slug = $this->uniqueSlug($shop, $validated['name']);
        $product->save();

        $this->syncGallery($product, $validated['gallery_urls'] ?? '');

        return redirect()->route('seller.products.index')->with('status', 'product-created');
    }

    public function edit(Request $request, Product $product): View
    {
        $this->authorizeProduct($request, $product);

        return view('seller.products.edit', [
            'product' => $product,
            'categories' => Category::orderBy('name')->get(),
            'galleryUrls' => $product->images()->pluck('url')->implode("\n"),
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $this->authorizeProduct($request, $product);

        $validated = $this->validated($request, $product);

        $product->fill($validated);
        $product->category_id = $validated['category_id'] ?? null;

        if ($validated['name'] !== $product->getOriginal('name')) {
            $product->slug = $this->uniqueSlug($this->shop($request), $validated['name'], $product->id);
        }

        $product->save();

        $this->syncGallery($product, $validated['gallery_urls'] ?? '');

        return redirect()->route('seller.products.index')->with('status', 'product-updated');
    }

    public function destroy(Request $request, Product $product): RedirectResponse
    {
        $this->authorizeProduct($request, $product);

        $product->delete();

        return redirect()->route('seller.products.index')->with('status', 'product-deleted');
    }

    private function shop(Request $request): Shop
    {
        return $request->user()->sellerProfile->shop;
    }

    private function authorizeProduct(Request $request, Product $product): void
    {
        abort_unless($product->shop_id === $this->shop($request)->id, 403);
    }

    private function validated(Request $request, ?Product $product = null): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'integer', 'min:0'],
            'discount_price' => ['nullable', 'integer', 'min:0', 'lt:price'],
            'devise' => ['required', 'string', 'max:10'],
            'stock' => ['required', 'integer', 'min:0'],
            'image_url' => ['nullable', 'url', 'max:2048'],
            'gallery_urls' => ['nullable', 'string'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        return $validated;
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
