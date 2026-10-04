<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product): JsonResponse
    {
        abort_unless($product->hasBeenPurchasedBy($request->user()), 403);
        abort_if($product->hasBeenReviewedBy($request->user()), 403);

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:2000'],
        ]);

        $review = new Review($validated);
        $review->product_id = $product->id;
        $review->user_id = $request->user()->id;
        $review->save();

        return response()->json(['review' => $review], 201);
    }
}
