<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Cart;
use App\Services\OrderService;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function __construct(
        protected OrderService $orderService,
    ) {}

    /**
     * Show the checkout page.
     */
    public function index()
    {
        $cart = Cart::where('user_id', Auth::id())->with('items.product')->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        return view('checkout.index', compact('cart'));
    }

    /**
     * Process the checkout and create an order.
     */
    public function store(CheckoutRequest $request)
    {
        $cart = Cart::where('user_id', Auth::id())->with('items.product')->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Verify stock availability
        $stockError = $this->orderService->verifyStock($cart);
        if ($stockError) {
            return back()->with('error', $stockError);
        }

        // Create order and process payment
        $order = $this->orderService->createOrder($cart, $request->validated());

        return redirect()->route('orders.confirmation', $order->order_number)
            ->with('success', 'Order placed successfully!');
    }
}
