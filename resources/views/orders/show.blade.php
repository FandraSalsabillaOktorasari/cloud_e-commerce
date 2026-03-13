@extends('layouts.app')

@section('title', 'Order ' . $order->order_number . ' — TechStore')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Back link --}}
    <a href="{{ route('orders.history') }}" class="inline-flex items-center gap-2 text-sm text-surface-200 hover:text-primary-400 transition-colors mb-6">
        <i class="fas fa-arrow-left"></i> Back to Order History
    </a>

    {{-- Order Details Card --}}
    <div class="bg-surface-800/50 border border-white/5 rounded-2xl overflow-hidden">
        {{-- Header --}}
        <div class="bg-gradient-to-r from-primary-900/40 to-accent-900/40 p-6 border-b border-white/5">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                <div>
                    <p class="text-sm text-surface-200">Order Number</p>
                    <p class="text-xl font-bold text-primary-400">{{ $order->order_number }}</p>
                    <p class="text-sm text-surface-200 mt-1">{{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div class="flex gap-2">
                    @php
                        $statusColors = [
                            'pending' => 'bg-yellow-500/10 text-yellow-400',
                            'processing' => 'bg-blue-500/10 text-blue-400',
                            'shipped' => 'bg-purple-500/10 text-purple-400',
                            'delivered' => 'bg-emerald-500/10 text-emerald-400',
                            'cancelled' => 'bg-red-500/10 text-red-400',
                        ];
                        $paymentColors = [
                            'paid' => 'bg-emerald-500/10 text-emerald-400',
                            'unpaid' => 'bg-yellow-500/10 text-yellow-400',
                            'failed' => 'bg-red-500/10 text-red-400',
                        ];
                    @endphp
                    <span class="px-3 py-1.5 text-xs font-semibold rounded-lg {{ $statusColors[$order->status] ?? '' }}">
                        <i class="fas fa-circle text-[6px] mr-1"></i>{{ ucfirst($order->status) }}
                    </span>
                    <span class="px-3 py-1.5 text-xs font-semibold rounded-lg {{ $paymentColors[$order->payment_status] ?? '' }}">
                        {{ ucfirst($order->payment_status) }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Items --}}
        <div class="p-6 border-b border-white/5">
            <h3 class="font-semibold mb-4">Items</h3>
            <div class="space-y-4">
                @foreach($order->items as $item)
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-surface-800 rounded-xl overflow-hidden shrink-0">
                            @if($item->product && $item->product->image)
                                <img src="{{ asset('storage/' . $item->product->image) }}" alt="" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center"><i class="fas fa-laptop text-surface-200/30"></i></div>
                            @endif
                        </div>
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
            <div class="flex justify-between text-sm"><span class="text-surface-200">Subtotal</span><span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span></div>
            <div class="flex justify-between text-sm"><span class="text-surface-200">Shipping</span><span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span></div>
            <hr class="border-white/5">
            <div class="flex justify-between text-lg font-bold"><span>Total</span><span class="bg-gradient-to-r from-primary-400 to-accent-400 bg-clip-text text-transparent">Rp {{ number_format($order->total, 0, ',', '.') }}</span></div>
        </div>

        {{-- Shipping & Payment --}}
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
                    <p>Payment: <span class="{{ $order->payment_status === 'paid' ? 'text-emerald-400' : 'text-yellow-400' }}">{{ ucfirst($order->payment_status) }}</span></p>
                </div>
            </div>
        </div>

        @if($order->notes)
            <div class="px-6 pb-6">
                <h3 class="font-semibold mb-2"><i class="fas fa-sticky-note text-primary-400 text-sm mr-1"></i> Notes</h3>
                <p class="text-sm text-surface-200 bg-surface-900/50 p-3 rounded-xl">{{ $order->notes }}</p>
            </div>
        @endif
    </div>
</div>
@endsection
