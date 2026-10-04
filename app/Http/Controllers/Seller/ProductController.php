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
            'categories' => Category::orderBy('name')->get(),
            'galleryUrls' => $product->images()->pluck('url')->implode("\n"),
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
}
