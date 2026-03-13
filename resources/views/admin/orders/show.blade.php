@extends('admin.layouts.app')

@section('title', 'Order ' . $order->order_number . ' — Admin TechStore')
@section('page-title', 'Order Details')
@section('page-subtitle', $order->order_number)

@section('content')
<div class="max-w-4xl">
    <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-2 text-sm text-surface-200 hover:text-primary-400 transition-colors mb-6">
        <i class="fas fa-arrow-left"></i> Back to Orders
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- ═══════ Order Info ═══════ --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Items --}}
            <div class="bg-surface-800/50 border border-white/5 rounded-2xl overflow-hidden">
                <div class="px-5 py-4 border-b border-white/5">
                    <h2 class="font-semibold">Items Ordered</h2>
                </div>
                <div class="divide-y divide-white/5">
                    @foreach($order->items as $item)
                        <div class="px-5 py-3 flex items-center gap-4">
                            <div class="w-12 h-12 bg-surface-800 rounded-lg overflow-hidden shrink-0">
                                @if($item->product && $item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}" alt="" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center"><i class="fas fa-laptop text-xs text-surface-200/30"></i></div>
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
                <div class="px-5 py-4 border-t border-white/5 space-y-2">
                    <div class="flex justify-between text-sm"><span class="text-surface-200">Subtotal</span><span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between text-sm"><span class="text-surface-200">Shipping</span><span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span></div>
                    <hr class="border-white/5">
                    <div class="flex justify-between text-lg font-bold"><span>Total</span><span class="text-primary-400">Rp {{ number_format($order->total, 0, ',', '.') }}</span></div>
                </div>
            </div>

            {{-- Shipping & Payment --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="bg-surface-800/50 border border-white/5 rounded-2xl p-5">
                    <h3 class="font-semibold mb-3"><i class="fas fa-truck text-primary-400 mr-1"></i> Shipping</h3>
                    <div class="text-sm text-surface-200 space-y-1">
                        <p class="text-white font-medium">{{ $order->shipping_name }}</p>
                        <p>{{ $order->shipping_phone }}</p>
                        <p>{{ $order->shipping_address }}</p>
                        <p>{{ $order->shipping_city }}, {{ $order->shipping_postal_code }}</p>
                    </div>
                </div>
                <div class="bg-surface-800/50 border border-white/5 rounded-2xl p-5">
                    <h3 class="font-semibold mb-3"><i class="fas fa-credit-card text-primary-400 mr-1"></i> Payment</h3>
                    <div class="text-sm text-surface-200 space-y-1">
                        <p class="text-white font-medium">{{ str_replace('_', ' ', ucwords($order->payment_method, '_')) }}</p>
                        @php
                            $paymentColors = ['paid' => 'text-emerald-400', 'unpaid' => 'text-yellow-400', 'failed' => 'text-red-400'];
                        @endphp
                        <p>Status: <span class="{{ $paymentColors[$order->payment_status] ?? '' }} font-medium">{{ ucfirst($order->payment_status) }}</span></p>
                        <p>Date: {{ $order->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
            </div>

            @if($order->notes)
                <div class="bg-surface-800/50 border border-white/5 rounded-2xl p-5">
                    <h3 class="font-semibold mb-2"><i class="fas fa-sticky-note text-primary-400 mr-1"></i> Notes</h3>
                    <p class="text-sm text-surface-200">{{ $order->notes }}</p>
                </div>
            @endif
        </div>

        {{-- ═══════ Status Update ═══════ --}}
        <div>
            <div class="bg-surface-800/50 border border-white/5 rounded-2xl p-5 sticky top-24">
                <h3 class="font-semibold mb-4">Update Status</h3>

                @php
                    $statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
                    $statusColors = [
                        'pending' => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20',
                        'processing' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
                        'shipped' => 'bg-purple-500/10 text-purple-400 border-purple-500/20',
                        'delivered' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                        'cancelled' => 'bg-red-500/10 text-red-400 border-red-500/20',
                    ];
                @endphp

                <div class="mb-4">
                    <p class="text-sm text-surface-200 mb-2">Current Status</p>
                    <span class="px-3 py-1.5 text-sm font-semibold rounded-lg border {{ $statusColors[$order->status] ?? '' }}">
                        <i class="fas fa-circle text-[6px] mr-1"></i>{{ ucfirst($order->status) }}
                    </span>
                </div>

                <form method="POST" action="{{ route('admin.orders.status', $order) }}" x-data="{ status: '{{ $order->status }}' }">
                    @csrf
                    @method('PATCH')

                    <div class="space-y-2 mb-4">
                        @foreach($statuses as $status)
                            <label @click="status = '{{ $status }}'" :class="status === '{{ $status }}' ? '{{ $statusColors[$status] }} border' : 'border border-white/5 hover:border-white/10'"
                                   class="flex items-center gap-3 p-3 rounded-xl cursor-pointer transition-all text-sm">
                                <input type="radio" name="status" value="{{ $status }}" x-model="status" class="hidden">
                                <div :class="status === '{{ $status }}' ? 'scale-100' : 'scale-0'" class="w-2 h-2 bg-current rounded-full transition-transform"></div>
                                <span class="font-medium">{{ ucfirst($status) }}</span>
                            </label>
                        @endforeach
                    </div>

                    <button type="submit" class="w-full py-3 bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-500 hover:to-primary-400 text-white font-semibold rounded-xl transition-all shadow-lg shadow-primary-500/25">
                        <i class="fas fa-save mr-2"></i> Update Status
                    </button>
                </form>

                {{-- Customer Info --}}
                @if($order->user)
                    <div class="mt-6 pt-6 border-t border-white/5">
                        <h3 class="font-semibold mb-3">Customer</h3>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-primary-500/10 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-sm text-primary-400"></i>
                            </div>
                            <div>
                                <p class="font-medium">{{ $order->user->name }}</p>
                                <p class="text-sm text-surface-200">{{ $order->user->email }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
