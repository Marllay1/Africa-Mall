<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        if ($request->boolean('featured')) {
            $featured = Product::query()
                ->with(['shop', 'category'])
                ->where('is_active', true)
                ->whereNotNull('image_url')
                ->latest()
                ->take(5)
                ->get();

            return ProductResource::collection($featured);
        }

        $products = Product::query()
            ->with(['shop', 'category'])
            ->where('is_active', true)
            ->when($request->filled('q'), fn ($query) => $query->where('name', 'like', '%'.$request->string('q').'%'))
            ->when($request->filled('category'), fn ($query) => $query->where('category_id', $request->integer('category')))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return ProductResource::collection($products);
    }

    public function show(Product $product): ProductResource
    {
        abort_unless($product->is_active, 404);

        return new ProductResource($product->load('shop', 'category'));
    }
}
