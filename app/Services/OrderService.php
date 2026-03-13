<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(
        protected PaymentServiceInterface $paymentService,
    ) {}

    /**
     * Calculate order totals from a cart.
     */
    public function calculateTotals(Cart $cart): array
    {
        $subtotal = 0;
        foreach ($cart->items as $item) {
            $subtotal += $item->product->price * $item->quantity;
        }

        $shippingCost = 15000; // Flat rate in IDR

        return [
            'subtotal'      => $subtotal,
            'shipping_cost' => $shippingCost,
            'total'         => $subtotal + $shippingCost,
        ];
    }

    /**
     * Verify stock availability for all cart items.
     */
    public function verifyStock(Cart $cart): ?string
    {
        foreach ($cart->items as $item) {
            if ($item->product->stock < $item->quantity) {
                return "Insufficient stock for {$item->product->name}. Only {$item->product->stock} left.";
            }
        }

        return null; // null = all good
    }

    /**
     * Create an order from the cart, process payment, and clear cart.
     */
    public function createOrder(Cart $cart, array $data): Order
    {
        $totals = $this->calculateTotals($cart);

        return DB::transaction(function () use ($cart, $data, $totals) {
            $order = Order::create([
                'user_id'              => Auth::id(),
                'order_number'         => Order::generateOrderNumber(),
                'status'               => 'pending',
                'subtotal'             => $totals['subtotal'],
                'shipping_cost'        => $totals['shipping_cost'],
                'total'                => $totals['total'],
                'shipping_name'        => $data['shipping_name'],
                'shipping_address'     => $data['shipping_address'],
                'shipping_city'        => $data['shipping_city'],
                'shipping_postal_code' => $data['shipping_postal_code'],
                'shipping_phone'       => $data['shipping_phone'],
                'payment_method'       => $data['payment_method'],
                'payment_status'       => 'unpaid',
                'notes'                => $data['notes'] ?? null,
            ]);

            // Create order items and update stock
            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id'      => $order->id,
                    'product_id'    => $item->product->id,
                    'product_name'  => $item->product->name,
                    'product_price' => $item->product->price,
                    'quantity'      => $item->quantity,
                    'subtotal'      => $item->product->price * $item->quantity,
                ]);

                $item->product->decrement('stock', $item->quantity);
                $item->product->increment('sold_count', $item->quantity);
            }

            // Process payment via the injected payment service
            $paymentResult = $this->paymentService->processPayment($order, $data);

            if ($paymentResult->success) {
                $order->update([
                    'payment_status' => $paymentResult->status,
                    'status'         => 'processing',
                ]);
            }

            // Clear cart
            $cart->items()->delete();

            return $order;
        });
    }
}
