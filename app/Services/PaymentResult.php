<?php

namespace App\Services;

/**
 * Value object representing the result of a payment attempt.
 */
class PaymentResult
{
    public function __construct(
        public readonly bool   $success,
        public readonly string $transactionId,
        public readonly string $status,
        public readonly string $message = '',
    ) {}

    /**
     * Create a successful payment result.
     */
    public static function success(string $transactionId, string $message = 'Payment successful'): self
    {
        return new self(
            success: true,
            transactionId: $transactionId,
            status: 'paid',
            message: $message,
        );
    }

    /**
     * Create a failed payment result.
     */
    public static function failure(string $message = 'Payment failed'): self
    {
        return new self(
            success: false,
            transactionId: '',
            status: 'failed',
            message: $message,
        );
    }
}
