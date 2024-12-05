<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // Store a new review
    public function store(Request $request, $productId)
    {
        $request->validate([
            'review' => 'nullable|string|max:1000',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $product = Product::findOrFail($productId);

        // Create a review
        $review = Review::create([
            'user_id' => Auth::id(),
            'product_id' => $productId,
            'review' => $request->input('review'),
            'rating' => $request->input('rating'),
        ]);

        // Recalculate average rating for the product
        $averageRating = $product->reviews()->avg('rating');

        // Update the product's rating
        $product->update(['rating' => $averageRating]);

        return response()->json([
            'message' => 'Review added successfully.',
            'review' => $review,
            'product_rating' => $averageRating,
        ]);
    }

    // Fetch all reviews for a product
    public function index($productId)
    {
        $product = Product::with('reviews.user')->findOrFail($productId);

        return response()->json($product->reviews);
    }
}
