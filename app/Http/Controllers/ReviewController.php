<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a review for a product.
     */
    public function store(Request $request, $slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        // Check if user already reviewed
        $existing = Review::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($existing) {
            return back()->with('error', 'You have already reviewed this product.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title'  => 'required|string|max:100',
            'body'   => 'required|string|max:1000',
        ]);

        // Check if user actually purchased this product
        $isVerified = OrderItem::whereHas('order', function ($q) {
            $q->where('user_id', Auth::id())->where('status', '!=', 'cancelled');
        })->where('product_id', $product->id)->exists();

        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'rating' => $request->rating,
            'title' => $request->title,
            'body' => $request->body,
            'is_verified_purchase' => $isVerified,
        ]);

        return back()->with('success', 'Review submitted successfully!');
    }

    /**
     * Delete a review.
     */
    public function destroy($id)
    {
        $review = Review::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $review->delete();

        return back()->with('success', 'Review deleted.');
    }
}
