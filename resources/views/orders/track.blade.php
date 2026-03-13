@extends('layouts.app')

@section('title', 'Track Order — TechStore')

@section('content')
<div class="max-w-lg mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="text-center mb-8">
        <div class="w-16 h-16 mx-auto mb-4 bg-primary-500/10 rounded-full flex items-center justify-center">
            <i class="fas fa-search text-2xl text-primary-400"></i>
        </div>
        <h1 class="text-3xl font-bold">Track Your Order</h1>
        <p class="text-surface-200 mt-2">Enter your order number and email to check your order status.</p>
    </div>

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 rounded-xl text-red-400 text-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-surface-800/50 border border-white/5 rounded-2xl p-6">
        <form method="POST" action="{{ route('orders.track.submit') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm text-surface-200 mb-2">Order Number</label>
                <input type="text" name="order_number" value="{{ old('order_number') }}" required
                       class="w-full px-4 py-3 bg-surface-900/50 border border-white/10 rounded-xl text-white placeholder-surface-200/30 focus:outline-none focus:border-primary-500/50 transition-colors"
                       placeholder="e.g., ORD-20260309-A1B2C3">
                @error('order_number') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="mb-6">
                <label class="block text-sm text-surface-200 mb-2">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full px-4 py-3 bg-surface-900/50 border border-white/10 rounded-xl text-white placeholder-surface-200/30 focus:outline-none focus:border-primary-500/50 transition-colors"
                       placeholder="your@email.com">
                @error('email') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-primary-600 to-primary-500 text-white font-semibold rounded-xl hover:from-primary-500 hover:to-primary-400 transition-all shadow-lg shadow-primary-500/25">
                <i class="fas fa-search mr-2"></i> Track Order
            </button>
        </form>
    </div>

    @if(isset($order))
        <div class="mt-8 bg-surface-800/50 border border-white/5 rounded-2xl p-6">
            <h2 class="text-lg font-semibold mb-4">Order #{{ $order->order_number }}</h2>
            <div class="space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-surface-200">Status</span>
                    <span class="font-medium px-3 py-1 rounded-lg text-xs uppercase
                        @if($order->status === 'completed') bg-emerald-500/10 text-emerald-400
                        @elseif($order->status === 'processing') bg-blue-500/10 text-blue-400
                        @elseif($order->status === 'cancelled') bg-red-500/10 text-red-400
                        @else bg-yellow-500/10 text-yellow-400
                        @endif">{{ $order->status }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-surface-200">Date</span>
                    <span class="text-white">{{ $order->created_at->format('M d, Y h:i A') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-surface-200">Total</span>
                    <span class="text-white font-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
