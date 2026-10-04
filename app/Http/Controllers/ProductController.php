<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class ProductController extends Controller
{
    private const SORTS = ['latest', 'price_asc', 'price_desc', 'popularity', 'rating'];

    public function index(Request $request): View
    {
        $sort = in_array($request->string('sort')->toString(), self::SORTS, true) ? $request->string('sort')->toString() : 'latest';

        $products = Product::query()
            ->with('shop.activePremiumSubscriptions')
            ->withAvg('reviews', 'rating')
            ->withSum('orderItems', 'quantity')
            ->where('is_active', true)
            ->when($request->filled('q'), fn ($query) => $query->where('name', 'like', '%'.$request->string('q').'%'))
            ->when($request->filled('category'), fn ($query) => $query->where('category_id', $request->integer('category')))
            ->when($request->filled('price_min'), fn ($query) => $query->where('price', '>=', $request->integer('price_min')))
            ->when($request->filled('price_max'), fn ($query) => $query->where('price', '<=', $request->integer('price_max')))
            ->when($request->filled('rating'), fn ($query) => $query->whereRaw(
                '(select avg(rating) from reviews where reviews.product_id = products.id) >= ?',
                [$request->integer('rating')]
            ))
            ->when($sort === 'price_asc', fn ($query) => $query->orderBy('price'))
            ->when($sort === 'price_desc', fn ($query) => $query->orderByDesc('price'))
            ->when($sort === 'popularity', fn ($query) => $query->orderByDesc('order_items_sum_quantity'))
            ->when($sort === 'rating', fn ($query) => $query->orderByDesc('reviews_avg_rating'))
            ->when($sort === 'latest', fn ($query) => $query->premiumFirst()->latest())
            ->paginate(12)
            ->withQueryString();

        $hasFilters = $request->filled('q') || $request->filled('category') || $request->filled('price_min')
            || $request->filled('price_max') || $request->filled('rating') || $request->filled('sort');
        $showHero = ! $hasFilters;

        $sponsored = collect();

        if ($showHero) {
            $sponsored = Advertisement::with('product.shop')->currentlyActive()->inRandomOrder()->take(4)->get();
            Advertisement::whereIn('id', $sponsored->pluck('id'))->increment('impressions_count');
        }

        return view('products.index', [
            'products' => $products,
            'categories' => Category::orderBy('name')->get(),
            'sponsored' => $sponsored,
            'sort' => $sort,
            'recommended' => $showHero ? $this->recommendationsFor($request->user()) : collect(),
            'featured' => $showHero ? $this->heroCarousel() : collect(),
        ]);
    }

    /**
     * Premium sellers' newest products get priority in the hero carousel; falls
     * back to the newest products overall if no Premium shop has anything to show.
     */
    private function heroCarousel(): Collection
    {
        $premium = Product::query()
            ->with('shop.activePremiumSubscriptions')
            ->where('is_active', true)
            ->whereNotNull('image_url')
            ->whereHas('shop.activePremiumSubscriptions')
            ->latest()
            ->take(5)
            ->get();

        if ($premium->isNotEmpty()) {
            return $premium;
        }

        return Product::query()
            ->with('shop.activePremiumSubscriptions')
            ->where('is_active', true)
            ->whereNotNull('image_url')
            ->latest()
            ->take(5)
            ->get();
    }

    /**
     * Products from categories the customer has already bought from, excluding what they already own.
     * Hidden entirely when there isn't enough purchase history to personalize anything.
     */
    private function recommendationsFor(?User $user): Collection
    {
        if (! $user) {
            return collect();
        }

        $purchasedCategoryIds = OrderItem::query()
            ->whereHas('order', fn ($query) => $query->where('user_id', $user->id)->where('status', '!=', 'cancelled'))
            ->with('product')
            ->get()
            ->pluck('product.category_id')
            ->filter()
            ->unique();

        if ($purchasedCategoryIds->isEmpty()) {
            return collect();
        }

        $purchasedProductIds = $user->orders()->with('items')->get()->pluck('items.*.product_id')->flatten();

        return Product::query()
            ->with('shop.activePremiumSubscriptions')
            ->where('is_active', true)
            ->whereIn('category_id', $purchasedCategoryIds)
            ->whereNotIn('id', $purchasedProductIds)
            ->latest()
            ->take(8)
            ->get();
    }

    public function show(Request $request, Product $product): View
    {
        abort_unless($product->is_active, 404);

        $product->load('shop.sellerProfile', 'shop.activePremiumSubscriptions', 'category', 'images', 'reviews.user');
        $user = $request->user();
        $isOwnShop = $user !== null && $product->shop->sellerProfile->user_id === $user->id;

        if (! $isOwnShop) {
            $product->increment('views_count');
        }

        $similarProducts = Product::query()
            ->with('shop.activePremiumSubscriptions')
            ->where('is_active', true)
            ->where('id', '!=', $product->id)
            ->when($product->category_id, fn ($query) => $query->where('category_id', $product->category_id), fn ($query) => $query->whereRaw('1 = 0'))
            ->latest()
            ->take(4)
            ->get();

        $excludedIds = $similarProducts->pluck('id')->push($product->id);

        $recommendedProducts = Product::query()
            ->with('shop.activePremiumSubscriptions')
            ->where('is_active', true)
            ->whereNotIn('id', $excludedIds)
            ->where('shop_id', $product->shop_id)
            ->latest()
            ->take(4)
            ->get();

        if ($recommendedProducts->count() < 4) {
            $recommendedProducts = $recommendedProducts->concat(
                Product::query()
                    ->with('shop.activePremiumSubscriptions')
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
            'isOwnShop' => $isOwnShop,
            'canReview' => $product->hasBeenPurchasedBy($user) && ! $product->hasBeenReviewedBy($user),
            'similarProducts' => $similarProducts,
            'recommendedProducts' => $recommendedProducts,
            'paymentMethods' => PaymentMethod::active()->get(),
        ]);
    }
}
