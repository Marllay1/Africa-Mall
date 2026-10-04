<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdvertisementController extends Controller
{
    public function index(Request $request): View
    {
        $shop = $request->user()->sellerProfile->shop;

        return view('seller.advertisements.index', [
            'isPremium' => $shop->isPremium(),
            'products' => $shop->products()->where('is_active', true)->get(),
            'advertisements' => $shop->advertisements()->with('product')->latest()->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $shop = $request->user()->sellerProfile->shop;

        abort_unless($shop->isPremium(), 403);

        $validated = $request->validate([
            'product_id' => ['required', 'integer'],
            'budget' => ['required', 'integer', 'min:1000'],
            'duration_days' => ['required', 'integer', 'min:1', 'max:30'],
        ]);

        $product = $shop->products()->where('is_active', true)->findOrFail($validated['product_id']);

        $advertisement = new Advertisement([
            'budget' => $validated['budget'],
            'duration_days' => $validated['duration_days'],
            'status' => 'pending',
        ]);
        $advertisement->shop_id = $shop->id;
        $advertisement->product_id = $product->id;
        $advertisement->save();

        return back()->with('status', 'advertisement-submitted');
    }
}
