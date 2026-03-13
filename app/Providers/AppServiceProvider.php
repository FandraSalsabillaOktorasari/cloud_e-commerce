<?php

namespace App\Providers;

use App\Services\MockPaymentService;
use App\Services\PaymentServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind the PaymentServiceInterface to the MockPaymentService.
        // Swap MockPaymentService for a real gateway (MidtransPaymentService,
        // StripePaymentService, etc.) when ready for production.
        $this->app->bind(PaymentServiceInterface::class, MockPaymentService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

