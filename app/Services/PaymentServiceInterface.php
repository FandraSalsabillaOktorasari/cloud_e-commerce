<?php

namespace App\Services;

use App\Models\Order;

/**
 * Interface for payment processing.
 *
 * Implement this interface to integrate a real payment gateway
 * (e.g., Midtrans, Stripe, PayPal). Swap the binding in
 * AppServiceProvider to switch implementations.
 */
interface PaymentServiceInterface
{
    /**
     * Process a payment for the given order.
     *
     * @param  Order  $order  The order to process payment for.
     * @param  array  $data   Payment-specific data (card info, token, etc.).
     * @return PaymentResult  The result of the payment attempt.
     */
    public function processPayment(Order $order, array $data): PaymentResult;
}
