@extends('layouts.app')

@section('title', 'Order History — TechStore')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold mb-8">Order History</h1>

    @if($orders->count())
        <div class="space-y-4">
            @foreach($orders as $order)
                <a href="{{ route('orders.show', $order->order_number) }}" class="block bg-surface-800/50 border border-white/5 rounded-2xl p-5 sm:p-6 hover:border-primary-500/20 transition-all group" id="order-{{ $order->order_number }}">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div>
                            <p class="text-sm text-surface-200 mb-1">{{ $order->created_at->format('d M Y, H:i') }}</p>
                            <p class="font-semibold text-primary-400 group-hover:text-primary-300 transition-colors">{{ $order->order_number }}</p>
                            <p class="text-sm text-surface-200 mt-1">{{ $order->items->count() }} item(s)</p>
                        </div>

                        <div class="flex items-center gap-4">
                            {{-- Status Badge --}}
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-500/10 text-yellow-400',
                                    'processing' => 'bg-blue-500/10 text-blue-400',
                                    'shipped' => 'bg-purple-500/10 text-purple-400',
                                    'delivered' => 'bg-emerald-500/10 text-emerald-400',
                                    'cancelled' => 'bg-red-500/10 text-red-400',
                                ];
                            @endphp
                            <span class="px-3 py-1.5 text-xs font-semibold rounded-lg {{ $statusColors[$order->status] ?? 'bg-white/5 text-surface-200' }}">
                                <i class="fas fa-circle text-[6px] mr-1.5"></i>{{ ucfirst($order->status) }}
                            </span>

                            <div class="text-right">
                                <p class="text-lg font-bold text-white">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                            </div>

                            <i class="fas fa-chevron-right text-surface-200/30 group-hover:text-primary-400 transition-colors"></i>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $orders->links() }}
        </div>
    @else
        <div class="text-center py-20">
            <div class="w-24 h-24 mx-auto mb-6 bg-surface-800/50 rounded-full flex items-center justify-center">
                <i class="fas fa-box-open text-4xl text-surface-200/20"></i>
            </div>
            <h2 class="text-2xl font-bold mb-3">No orders yet</h2>
            <p class="text-surface-200 mb-8">Start shopping and your orders will appear here.</p>
            <a href="{{ route('products.index') }}" class="px-8 py-3.5 bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-500 hover:to-primary-400 text-white font-semibold rounded-xl transition-all shadow-lg shadow-primary-500/25">
                <i class="fas fa-shopping-bag mr-2"></i> Shop Now
            </a>
        </div>
    @endif
</div>
@endsection
