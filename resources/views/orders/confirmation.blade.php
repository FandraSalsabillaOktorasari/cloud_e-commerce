@extends('layouts.app')

@section('title', 'Order Confirmed — TechStore')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    {{-- Success Header --}}
    <div class="text-center mb-10">
        <div class="w-20 h-20 mx-auto mb-6 bg-emerald-500/10 border border-emerald-500/20 rounded-full flex items-center justify-center">
            <i class="fas fa-check text-3xl text-emerald-400"></i>
        </div>
        <h1 class="text-3xl font-bold mb-3">Order Confirmed!</h1>
        <p class="text-surface-200">Thank you for your purchase. Your order has been placed successfully.</p>
    </div>

    {{-- Order Details Card --}}
    <div class="bg-surface-800/50 border border-white/5 rounded-2xl overflow-hidden">
        {{-- Order Header --}}
        <div class="bg-gradient-to-r from-primary-900/40 to-accent-900/40 p-6 border-b border-white/5">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                <div>
                    <p class="text-sm text-surface-200">Order Number</p>
                    <p class="text-xl font-bold text-primary-400" id="order-number">{{ $order->order_number }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-surface-200">Status</p>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-500/10 text-emerald-400 text-sm font-semibold rounded-lg">
                        <i class="fas fa-circle text-[6px]"></i> {{ ucfirst($order->payment_status) }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Items --}}
        <div class="p-6 border-b border-white/5">
            <h3 class="font-semibold mb-4">Items Ordered</h3>
            <div class="space-y-3">
                @foreach($order->items as $item)
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <p class="font-medium truncate">{{ $item->product_name }}</p>
                            <p class="text-sm text-surface-200">Rp {{ number_format($item->product_price, 0, ',', '.') }} × {{ $item->quantity }}</p>
                        </div>
                        <p class="font-semibold shrink-0">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Totals --}}
        <div class="p-6 border-b border-white/5 space-y-2">
            <div class="flex justify-between text-sm">
                <span class="text-surface-200">Subtotal</span>
                <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-surface-200">Shipping</span>
                <span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
            </div>
            <hr class="border-white/5">
            <div class="flex justify-between text-lg font-bold">
                <span>Total</span>
                <span class="bg-gradient-to-r from-primary-400 to-accent-400 bg-clip-text text-transparent">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Shipping & Payment Info --}}
        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <h3 class="font-semibold mb-3 flex items-center gap-2"><i class="fas fa-truck text-primary-400 text-sm"></i> Shipping</h3>
                <div class="text-sm text-surface-200 space-y-1">
                    <p class="text-white font-medium">{{ $order->shipping_name }}</p>
                    <p>{{ $order->shipping_address }}</p>
                    <p>{{ $order->shipping_city }}, {{ $order->shipping_postal_code }}</p>
                    <p>{{ $order->shipping_phone }}</p>
                </div>
            </div>
            <div>
                <h3 class="font-semibold mb-3 flex items-center gap-2"><i class="fas fa-credit-card text-primary-400 text-sm"></i> Payment</h3>
                <div class="text-sm text-surface-200 space-y-1">
                    <p class="text-white font-medium">{{ str_replace('_', ' ', ucwords($order->payment_method, '_')) }}</p>
                    <p>Payment Status: <span class="text-emerald-400">{{ ucfirst($order->payment_status) }}</span></p>
                    <p>Order Date: {{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="flex flex-col sm:flex-row gap-3 mt-8">
        <a href="{{ route('orders.history') }}" class="flex-1 text-center py-3 bg-white/5 border border-white/10 text-white font-medium rounded-xl hover:bg-white/10 transition-all">
            <i class="fas fa-box mr-2"></i> View Order History
        </a>
        <a href="{{ route('products.index') }}" class="flex-1 text-center py-3 bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-500 hover:to-primary-400 text-white font-semibold rounded-xl transition-all shadow-lg shadow-primary-500/25">
            <i class="fas fa-shopping-bag mr-2"></i> Continue Shopping
        </a>
    </div>
</div>
@endsection
