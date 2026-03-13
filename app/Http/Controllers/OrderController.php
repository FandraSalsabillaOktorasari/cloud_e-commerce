<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display the order confirmation page.
     */
    public function confirmation(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->with('items.product')
            ->firstOrFail();

        return view('orders.confirmation', compact('order'));
    }

    /**
     * Display the user's order history.
     */
    public function history()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('items')
            ->latest()
            ->paginate(10);

        return view('orders.history', compact('orders'));
    }

    /**
     * Display a single order detail.
     */
    public function show(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->with('items.product')
            ->firstOrFail();

        return view('orders.show', compact('order'));
    }

    /**
     * Show the guest order tracking form.
     */
    public function trackForm()
    {
        return view('orders.track');
    }

    /**
     * Track a guest order by order number + email.
     */
    public function track(Request $request)
    {
        $request->validate([
            'order_number' => 'required|string',
            'email' => 'required|email',
        ]);

        $order = Order::where('order_number', $request->order_number)
            ->where('guest_email', $request->email)
            ->with('items.product')
            ->first();

        if (!$order) {
            return back()->withInput()->with('error', 'Order not found. Please check your order number and email.');
        }

        return view('orders.track', compact('order'));
    }
}
