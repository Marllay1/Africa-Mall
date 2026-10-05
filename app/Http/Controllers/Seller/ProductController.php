<?php

namespace App\Http\Controllers\Seller;

use App\Actions\SaveSellerProduct;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = $this->shop($request)->products()->with('subcategory')->latest();

        if ($search = trim((string) $request->query('search'))) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($categoryId = $request->query('category_id')) {
            $query->where('category_id', $categoryId);
        }

        switch ($request->query('status')) {
            case 'active':
                $query->where('is_active', true)->where('stock', '>', 0);
                break;
            case 'inactive':
                $query->where('is_active', false);
                break;
            case 'out_of_stock':
                $query->where('stock', 0);
                break;
        }

        return view('seller.products.index', [
            'products' => $query->paginate(15)->withQueryString(),
            'categories' => Category::topLevel()->orderBy('name')->get(),
        ]);
    }

    public function create(Request $request): View
    {
        return view('seller.products.create', [
            'categories' => Category::topLevel()->with('children')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request, SaveSellerProduct $saveSellerProduct): RedirectResponse
    {
        $saveSellerProduct->execute($this->shop($request), $this->validated($request));

        return redirect()->route('seller.products.index')->with('status', 'product-created');
    }

    public function edit(Request $request, Product $product): View
    {
        $this->authorizeProduct($request, $product);

        return view('seller.products.edit', [
            'product' => $product,
            'categories' => Category::topLevel()->with('children')->orderBy('name')->get(),
            'galleryUrls' => $product->images()->pluck('url')->implode("\n"),
            'variants' => $product->variants()->get(),
        ]);
    }

    public function update(Request $request, Product $product, SaveSellerProduct $saveSellerProduct): RedirectResponse
    {
        $this->authorizeProduct($request, $product);

        $saveSellerProduct->execute($this->shop($request), $this->validated($request, $product), $product);

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
            'subcategory_id' => ['nullable', 'exists:categories,id'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'integer', 'min:0'],
            'discount_price' => ['nullable', 'integer', 'min:0', 'lt:price'],
            'devise' => ['required', 'string', 'max:10'],
            'stock' => ['required', 'integer', 'min:0'],
            'image_url' => ['nullable', 'url', 'max:2048'],
            'gallery_urls' => ['nullable', 'string'],
            'weight_kg' => ['nullable', 'numeric', 'min:0'],
            'length_cm' => ['nullable', 'numeric', 'min:0'],
            'width_cm' => ['nullable', 'numeric', 'min:0'],
            'height_cm' => ['nullable', 'numeric', 'min:0'],
            'variants' => ['nullable', 'array'],
            'variants.*.label' => ['nullable', 'string', 'max:100'],
            'variants.*.value' => ['nullable', 'string', 'max:100'],
            'variants.*.stock' => ['nullable', 'integer', 'min:0'],
            'variants.*.price' => ['nullable', 'integer', 'min:0'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        if ($validated['subcategory_id'] ?? null) {
            abort_unless(
                Category::where('id', $validated['subcategory_id'])->whereNotNull('parent_id')->exists(),
                422,
                __('La sous-catégorie sélectionnée est invalide.')
            );
        }

        return $validated;
    }
}
