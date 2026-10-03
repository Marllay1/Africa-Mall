<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ShopResource;
use App\Models\Shop;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ShopController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return ShopResource::collection(
            Shop::with('sellerProfile.user')->withCount('products', 'orders')->latest()->paginate(20)
        );
    }
}
