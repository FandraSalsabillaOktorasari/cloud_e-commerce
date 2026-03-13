<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the user's wishlist.
     */
    public function index()
    {
        $wishlistItems = Wishlist::where('user_id', Auth::id())
            ->with('product.category')
            ->latest()
            ->get();

        return view('wishlist.index', compact('wishlistItems'));
    }

    /**
     * Toggle a product in the wishlist.
     */
    public function toggle(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        $existing = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->first();

        if ($existing) {
            $existing->delete();

            if ($request->wantsJson()) {
                return response()->json(['action' => 'removed', 'wishlisted' => false]);
            }
            return back()->with('success', $product->name . ' removed from wishlist.');
        }

        Wishlist::create([
            'user_id' => Auth::id(),
            'product_id' => $productId,
        ]);

        if ($request->wantsJson()) {
            return response()->json(['action' => 'added', 'wishlisted' => true]);
        }
        return back()->with('success', $product->name . ' added to wishlist!');
    }
}
