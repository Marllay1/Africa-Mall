<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::query()
            ->with('shop')
            ->where('is_active', true)
            ->when($request->filled('q'), fn ($query) => $query->where('name', 'like', '%'.$request->string('q').'%'))
            ->when($request->filled('category'), fn ($query) => $query->where('category_id', $request->integer('category')))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $showHero = ! $request->filled('q') && ! $request->filled('category');

        return view('products.index', [
            'products' => $products,
            'categories' => Category::orderBy('name')->get(),
            'featured' => $showHero
                ? Product::query()->with('shop')->where('is_active', true)->whereNotNull('image_url')->latest()->take(5)->get()
                : collect(),
        ]);
    }

    public function show(Request $request, Product $product): View
    {
        abort_unless($product->is_active, 404);

        $product->load('shop.sellerProfile', 'category', 'images', 'reviews.user');
        $user = $request->user();

        $similarProducts = Product::query()
            ->with('shop')
            ->where('is_active', true)
            ->where('id', '!=', $product->id)
            ->when($product->category_id, fn ($query) => $query->where('category_id', $product->category_id), fn ($query) => $query->whereRaw('1 = 0'))
            ->latest()
            ->take(4)
            ->get();

        $excludedIds = $similarProducts->pluck('id')->push($product->id);

        $recommendedProducts = Product::query()
            ->with('shop')
            ->where('is_active', true)
            ->whereNotIn('id', $excludedIds)
            ->where('shop_id', $product->shop_id)
            ->latest()
            ->take(4)
            ->get();

        if ($recommendedProducts->count() < 4) {
            $recommendedProducts = $recommendedProducts->concat(
                Product::query()
                    ->with('shop')
                    ->where('is_active', true)
                    ->whereNotIn('id', $excludedIds->merge($recommendedProducts->pluck('id')))
                    ->latest()
                    ->take(4 - $recommendedProducts->count())
                    ->get()
            );
        }

        return view('products.show', [
            'product' => $product,
            'averageRating' => $product->averageRating(),
            'reviewsCount' => $product->reviewsCount(),
            'salesCount' => $product->salesCount(),
            'isFavorited' => $product->isFavoritedBy($user),
            'isOwnShop' => $user !== null && $product->shop->sellerProfile->user_id === $user->id,
            'canReview' => $product->hasBeenPurchasedBy($user) && ! $product->hasBeenReviewedBy($user),
            'similarProducts' => $similarProducts,
            'recommendedProducts' => $recommendedProducts,
        ]);
    }
}
