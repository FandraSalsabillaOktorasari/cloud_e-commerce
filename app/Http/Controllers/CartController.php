<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\UpdateCartRequest;
use App\Models\CartItem;
use App\Models\Product;
use App\Services\CartService;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cartService,
    ) {}

    /**
     * Display the shopping cart.
     */
    public function index()
    {
        $cart = $this->cartService->getCartWithItems();

        return view('cart.index', compact('cart'));
    }

    /**
     * Add a product to the cart.
     */
    public function add(AddToCartRequest $request)
    {
        $product  = Product::findOrFail($request->product_id);
        $quantity = $request->get('quantity', 1);

        $result = $this->cartService->add($product, $quantity);

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        return redirect()->route('cart.index')->with('success', $result['message']);
    }

    /**
     * Update the quantity of a cart item.
     */
    public function update(UpdateCartRequest $request, CartItem $cartItem)
    {
        $result = $this->cartService->update($cartItem, $request->quantity);

        if (!$result['success']) {
            if (isset($result['code']) && $result['code'] === 403) {
                abort(403);
            }
            return back()->with('error', $result['message']);
        }

        return back()->with('success', $result['message']);
    }

    /**
     * Remove a cart item.
     */
    public function remove(CartItem $cartItem)
    {
        $result = $this->cartService->remove($cartItem);

        if (!$result['success']) {
            if (isset($result['code']) && $result['code'] === 403) {
                abort(403);
            }
            return back()->with('error', $result['message']);
        }

        return back()->with('success', $result['message']);
    }
}
