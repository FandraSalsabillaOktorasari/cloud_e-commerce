<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Str;

/**
 * Mock payment gateway for testing the checkout flow.
 *
 * Simulates a successful payment every time.
 * Replace this binding with a real gateway (Midtrans, Stripe, PayPal)
 * in AppServiceProvider when ready for production.
 */
class MockPaymentService implements PaymentServiceInterface
{
    public function processPayment(Order $order, array $data): PaymentResult
    {
        // Simulate processing delay (can be removed in production)
        // Simulate a successful payment with a mock transaction ID
        $transactionId = 'MOCK-' . strtoupper(Str::random(12));

        return PaymentResult::success(
            transactionId: $transactionId,
            message: 'Payment processed successfully via Mock Gateway.',
        );
    }
}
