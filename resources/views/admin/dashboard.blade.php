@extends('admin.layouts.app')

@section('title', 'Dashboard — Admin TechStore')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Overview of your store performance')

@section('content')
{{-- Stats Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-8">
    <div class="bg-surface-800/50 border border-white/5 rounded-2xl p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-primary-500/10 rounded-xl flex items-center justify-center">
                <i class="fas fa-box text-primary-400"></i>
            </div>
            <span class="text-2xl font-black text-white">{{ $stats['total_products'] }}</span>
        </div>
        <p class="text-sm text-surface-200">Total Products</p>
    </div>

    <div class="bg-surface-800/50 border border-white/5 rounded-2xl p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-emerald-500/10 rounded-xl flex items-center justify-center">
                <i class="fas fa-shopping-bag text-emerald-400"></i>
            </div>
            <span class="text-2xl font-black text-white">{{ $stats['total_orders'] }}</span>
        </div>
        <p class="text-sm text-surface-200">Total Orders</p>
    </div>

    <div class="bg-surface-800/50 border border-white/5 rounded-2xl p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-accent-500/10 rounded-xl flex items-center justify-center">
                <i class="fas fa-wallet text-accent-400"></i>
            </div>
            <span class="text-xl font-black text-white">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</span>
        </div>
        <p class="text-sm text-surface-200">Total Revenue</p>
    </div>

    <div class="bg-surface-800/50 border border-white/5 rounded-2xl p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-purple-500/10 rounded-xl flex items-center justify-center">
                <i class="fas fa-users text-purple-400"></i>
            </div>
            <span class="text-2xl font-black text-white">{{ $stats['total_customers'] }}</span>
        </div>
        <p class="text-sm text-surface-200">Total Customers</p>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    {{-- ═══════ Low Stock Alerts ═══════ --}}
    <div class="xl:col-span-1">
        <div class="bg-surface-800/50 border border-white/5 rounded-2xl overflow-hidden">
            <div class="px-5 py-4 border-b border-white/5 flex items-center gap-2">
                <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                <h2 class="font-semibold">Low Stock Alerts</h2>
                <span class="ml-auto px-2 py-0.5 bg-yellow-500/10 text-yellow-400 text-xs font-bold rounded-lg">{{ $lowStockProducts->count() }}</span>
            </div>

            @if($lowStockProducts->count())
                <div class="divide-y divide-white/5">
                    @foreach($lowStockProducts as $product)
                        <div class="px-5 py-3 flex items-center justify-between">
                            <div class="min-w-0">
                                <p class="text-sm font-medium truncate">{{ $product->name }}</p>
                                <p class="text-xs text-surface-200">{{ $product->category->name ?? '' }}</p>
                            </div>
                            <span class="px-2.5 py-1 text-xs font-bold rounded-lg shrink-0 {{ $product->stock <= 0 ? 'bg-red-500/10 text-red-400' : ($product->stock <= 3 ? 'bg-orange-500/10 text-orange-400' : 'bg-yellow-500/10 text-yellow-400') }}">
                                {{ $product->stock }} left
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-5 text-center text-sm text-surface-200">
                    <i class="fas fa-check-circle text-emerald-400 mr-1"></i> All products are well-stocked
                </div>
            @endif
        </div>
    </div>

    {{-- ═══════ Recent Orders ═══════ --}}
    <div class="xl:col-span-2">
        <div class="bg-surface-800/50 border border-white/5 rounded-2xl overflow-hidden">
            <div class="px-5 py-4 border-b border-white/5 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="fas fa-clock text-primary-400"></i>
                    <h2 class="font-semibold">Recent Orders</h2>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="text-primary-400 hover:text-primary-300 text-sm font-medium transition-colors">View All</a>
            </div>

            @if($recentOrders->count())
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-white/5 text-left text-surface-200">
                                <th class="px-5 py-3 font-medium">Order</th>
                                <th class="px-5 py-3 font-medium">Customer</th>
                                <th class="px-5 py-3 font-medium">Total</th>
                                <th class="px-5 py-3 font-medium">Status</th>
                                <th class="px-5 py-3 font-medium">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @foreach($recentOrders as $order)
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-500/10 text-yellow-400',
                                        'processing' => 'bg-blue-500/10 text-blue-400',
                                        'shipped' => 'bg-purple-500/10 text-purple-400',
                                        'delivered' => 'bg-emerald-500/10 text-emerald-400',
                                        'cancelled' => 'bg-red-500/10 text-red-400',
                                    ];
                                @endphp
                                <tr class="hover:bg-white/[0.02]">
                                    <td class="px-5 py-3">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="text-primary-400 hover:text-primary-300 font-medium">{{ $order->order_number }}</a>
                                    </td>
                                    <td class="px-5 py-3">{{ $order->user->name ?? 'N/A' }}</td>
                                    <td class="px-5 py-3 font-medium">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                    <td class="px-5 py-3">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-lg {{ $statusColors[$order->status] ?? '' }}">{{ ucfirst($order->status) }}</span>
                                    </td>
                                    <td class="px-5 py-3 text-surface-200">{{ $order->created_at->format('d M, H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-5 text-center text-sm text-surface-200">No orders yet.</div>
            @endif
        </div>
    </div>
</div>
@endsection
