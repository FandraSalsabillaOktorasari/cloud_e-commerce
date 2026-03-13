@extends('admin.layouts.app')

@section('title', 'Orders — Admin TechStore')
@section('page-title', 'Order Management')
@section('page-subtitle', 'View and manage customer orders')

@section('content')
<div class="bg-surface-800/50 border border-white/5 rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-white/5 text-left text-surface-200">
                    <th class="px-5 py-3 font-medium">Order</th>
                    <th class="px-5 py-3 font-medium">Customer</th>
                    <th class="px-5 py-3 font-medium">Items</th>
                    <th class="px-5 py-3 font-medium">Total</th>
                    <th class="px-5 py-3 font-medium">Payment</th>
                    <th class="px-5 py-3 font-medium">Status</th>
                    <th class="px-5 py-3 font-medium">Date</th>
                    <th class="px-5 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($orders as $order)
                    @php
                        $statusColors = [
                            'pending' => 'bg-yellow-500/10 text-yellow-400',
                            'processing' => 'bg-blue-500/10 text-blue-400',
                            'shipped' => 'bg-purple-500/10 text-purple-400',
                            'delivered' => 'bg-emerald-500/10 text-emerald-400',
                            'cancelled' => 'bg-red-500/10 text-red-400',
                        ];
                        $paymentColors = [
                            'paid' => 'text-emerald-400',
                            'unpaid' => 'text-yellow-400',
                            'failed' => 'text-red-400',
                        ];
                    @endphp
                    <tr class="hover:bg-white/[0.02]">
                        <td class="px-5 py-3">
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-primary-400 hover:text-primary-300 font-medium">{{ $order->order_number }}</a>
                        </td>
                        <td class="px-5 py-3">{{ $order->user->name ?? 'N/A' }}</td>
                        <td class="px-5 py-3 text-surface-200">{{ $order->items->count() }}</td>
                        <td class="px-5 py-3 font-medium">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                        <td class="px-5 py-3">
                            <span class="font-medium {{ $paymentColors[$order->payment_status] ?? 'text-surface-200' }}">{{ ucfirst($order->payment_status) }}</span>
                        </td>
                        <td class="px-5 py-3">
                            <span class="px-2 py-1 text-xs font-semibold rounded-lg {{ $statusColors[$order->status] ?? '' }}">{{ ucfirst($order->status) }}</span>
                        </td>
                        <td class="px-5 py-3 text-surface-200">{{ $order->created_at->format('d M Y, H:i') }}</td>
                        <td class="px-5 py-3">
                            <a href="{{ route('admin.orders.show', $order) }}" class="p-2 text-primary-400 hover:bg-primary-500/10 rounded-lg transition-all" title="View Details">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-5 py-10 text-center text-surface-200">No orders yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">
    {{ $orders->links() }}
</div>
@endsection
