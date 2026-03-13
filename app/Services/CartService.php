<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartService
{
    /**
     * Get or create a cart for the current user/session.
     */
    public function getOrCreateCart(): Cart
    {
        if (Auth::check()) {
            return Cart::firstOrCreate(['user_id' => Auth::id()]);
        }

        $sessionId = session()->getId();
        return Cart::firstOrCreate(['session_id' => $sessionId]);
    }

    /**
     * Load a cart with items and products.
     */
    public function getCartWithItems(): Cart
    {
        $cart = $this->getOrCreateCart();
        $cart->load('items.product.category');
        return $cart;
    }

    /**
     * Add a product to the cart.
     */
    public function add(Product $product, int $quantity = 1): array
    {
        if ($product->stock < $quantity) {
            return ['success' => false, 'message' => 'Not enough stock available.'];
        }

        $cart = $this->getOrCreateCart();
        $cartItem = $cart->items()->where('product_id', $product->id)->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $quantity;
            if ($product->stock < $newQuantity) {
                return ['success' => false, 'message' => 'Not enough stock to add more of this item.'];
            }
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity'   => $quantity,
            ]);
        }

        return ['success' => true, 'message' => "{$product->name} added to cart!"];
    }

    /**
     * Update the quantity of a cart item (with ownership check).
     */
    public function update(CartItem $cartItem, int $quantity): array
    {
        $cart = $this->getOrCreateCart();
        if ($cartItem->cart_id !== $cart->id) {
            return ['success' => false, 'message' => 'Unauthorized.', 'code' => 403];
        }

        if ($cartItem->product->stock < $quantity) {
            return ['success' => false, 'message' => 'Not enough stock available.'];
        }

        $cartItem->update(['quantity' => $quantity]);
        return ['success' => true, 'message' => 'Cart updated.'];
    }

    /**
     * Remove a cart item (with ownership check).
     */
    public function remove(CartItem $cartItem): array
    {
        $cart = $this->getOrCreateCart();
        if ($cartItem->cart_id !== $cart->id) {
            return ['success' => false, 'message' => 'Unauthorized.', 'code' => 403];
        }

        $cartItem->delete();
        return ['success' => true, 'message' => 'Item removed from cart.'];
    }

    /**
     * Merge guest cart items into the authenticated user's cart.
     */
    public function mergeGuestCart(string $sessionId): void
    {
        $guestCart = Cart::where('session_id', $sessionId)->first();

        if (!$guestCart || $guestCart->items->isEmpty()) {
            return;
        }

        $userCart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        foreach ($guestCart->items as $guestItem) {
            $existingItem = $userCart->items()->where('product_id', $guestItem->product_id)->first();

            if ($existingItem) {
                $newQty = min($existingItem->quantity + $guestItem->quantity, $guestItem->product->stock);
                $existingItem->update(['quantity' => $newQty]);
            } else {
                $userCart->items()->create([
                    'product_id' => $guestItem->product_id,
                    'quantity'   => $guestItem->quantity,
                ]);
            }
        }

        $guestCart->items()->delete();
        $guestCart->delete();
    }
}
