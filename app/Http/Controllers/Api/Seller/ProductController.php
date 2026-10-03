<?php

namespace App\Http\Controllers\Api\Seller;

use App\Actions\SaveSellerProduct;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        return ProductResource::collection($this->shop($request)->products()->latest()->paginate(15));
    }

    public function store(Request $request, SaveSellerProduct $saveSellerProduct): JsonResponse
    {
        $product = $saveSellerProduct->execute($this->shop($request), $this->validated($request));

        return (new ProductResource($product->load('category', 'shop')))->response()->setStatusCode(201);
    }

    public function update(Request $request, Product $product, SaveSellerProduct $saveSellerProduct): ProductResource
    {
        $this->authorizeProduct($request, $product);

        $product = $saveSellerProduct->execute($this->shop($request), $this->validated($request, $product), $product);

        return new ProductResource($product->load('category', 'shop'));
    }

    public function destroy(Request $request, Product $product): JsonResponse
    {
        $this->authorizeProduct($request, $product);

        $product->delete();

        return response()->json(null, 204);
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
