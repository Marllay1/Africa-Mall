<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function toggle(Request $request, Product $product): RedirectResponse
    {
        $favorite = Favorite::where('user_id', $request->user()->id)
            ->where('product_id', $product->id)
            ->first();

        if ($favorite) {
            $favorite->delete();

            return back()->with('status', 'favorite-removed');
        }

        $favorite = new Favorite;
        $favorite->user_id = $request->user()->id;
        $favorite->product_id = $product->id;
        $favorite->save();

        return back()->with('status', 'favorite-added');
    }
}
