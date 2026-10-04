<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        return view('admin.products.index', [
            'products' => Product::with('shop')->latest()->paginate(20),
        ]);
    }

    public function toggleVisibility(Product $product): RedirectResponse
    {
        $product->is_active = ! $product->is_active;
        $product->save();

        return back()->with('status', $product->is_active ? 'product-shown' : 'product-hidden');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return back()->with('status', 'product-deleted');
    }
}
