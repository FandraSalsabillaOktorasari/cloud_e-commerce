@extends('layouts.app')

@section('title', 'Shopping Cart — TechStore')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold mb-8">Shopping Cart</h1>

    @if($cart && $cart->items->count())
        <div class="space-y-4 mb-8">
            @foreach($cart->items as $item)
                <div class="bg-surface-800/50 border border-white/5 rounded-2xl p-4 sm:p-6 flex flex-col sm:flex-row items-start sm:items-center gap-4" id="cart-item-{{ $item->id }}">
                    {{-- Image --}}
                    <div class="w-full sm:w-24 h-40 sm:h-24 bg-surface-800 rounded-xl overflow-hidden shrink-0">
                        @if($item->product->image)
                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary-900/30 to-accent-900/30">
                                <i class="fas fa-laptop text-2xl text-surface-200/30"></i>
                            </div>
                        @endif
                    </div>

                    {{-- Details --}}
                    <div class="flex-1 min-w-0">
                        <a href="{{ route('products.show', $item->product->slug) }}" class="font-semibold text-white hover:text-primary-400 transition-colors">{{ $item->product->name }}</a>
                        <p class="text-sm text-surface-200 mt-1">{{ $item->product->category->name ?? '' }}</p>
                        <p class="text-lg font-bold text-primary-400 mt-2">Rp {{ number_format($item->product->price, 0, ',', '.') }}</p>
                    </div>

                    {{-- Quantity & Remove --}}
                    <div class="flex items-center gap-4 w-full sm:w-auto">
                        <form method="POST" action="{{ route('cart.update', $item) }}" class="flex items-center" x-data="{ qty: {{ $item->quantity }} }">
                            @csrf
                            @method('PATCH')
                            <div class="flex items-center bg-surface-900 border border-white/5 rounded-xl overflow-hidden">
                                <button type="submit" @click="qty = Math.max(1, qty - 1); $refs.q.value = qty" class="px-3 py-2 hover:bg-white/5 transition-all">
                                    <i class="fas fa-minus text-xs text-surface-200"></i>
                                </button>
                                <input type="number" name="quantity" x-ref="q" x-model="qty" min="1" max="{{ $item->product->stock }}" class="w-12 text-center bg-transparent border-x border-white/5 py-2 text-white text-sm focus:outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                <button type="submit" @click="qty = Math.min({{ $item->product->stock }}, qty + 1); $refs.q.value = qty" class="px-3 py-2 hover:bg-white/5 transition-all">
                                    <i class="fas fa-plus text-xs text-surface-200"></i>
                                </button>
                            </div>
                        </form>

                        <form method="POST" action="{{ route('cart.remove', $item) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2.5 text-red-400 hover:bg-red-500/10 rounded-xl transition-all" title="Remove">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </div>

                    {{-- Subtotal --}}
                    <div class="text-right shrink-0 w-full sm:w-auto">
                        <p class="text-xs text-surface-200 sm:mb-1">Subtotal</p>
                        <p class="text-lg font-bold text-white">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Cart Summary --}}
        <div class="bg-surface-800/50 border border-white/5 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <span class="text-surface-200">Total Items</span>
                <span class="font-medium">{{ $cart->items_count }} items</span>
            </div>
            <div class="flex items-center justify-between mb-6">
                <span class="text-lg font-semibold">Total</span>
                <span class="text-2xl font-black bg-gradient-to-r from-primary-400 to-accent-400 bg-clip-text text-transparent">Rp {{ number_format($cart->total, 0, ',', '.') }}</span>
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('products.index') }}" class="flex-1 text-center py-3 bg-white/5 border border-white/10 text-white font-medium rounded-xl hover:bg-white/10 transition-all">
                    <i class="fas fa-arrow-left mr-2"></i> Continue Shopping
                </a>
                @auth
                    <a href="{{ route('checkout.index') }}" class="flex-1 text-center py-3 bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-500 hover:to-primary-400 text-white font-semibold rounded-xl transition-all shadow-lg shadow-primary-500/25" id="checkout-btn">
                        Proceed to Checkout <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="flex-1 text-center py-3 bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-500 hover:to-primary-400 text-white font-semibold rounded-xl transition-all shadow-lg shadow-primary-500/25">
                        Login to Checkout <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                @endauth
            </div>
        </div>
    @else
        {{-- Empty Cart --}}
        <div class="text-center py-20">
            <div class="w-24 h-24 mx-auto mb-6 bg-surface-800/50 rounded-full flex items-center justify-center">
                <i class="fas fa-shopping-cart text-4xl text-surface-200/20"></i>
            </div>
            <h2 class="text-2xl font-bold mb-3">Your cart is empty</h2>
            <p class="text-surface-200 mb-8">Looks like you haven't added any products yet.</p>
            <a href="{{ route('products.index') }}" class="px-8 py-3.5 bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-500 hover:to-primary-400 text-white font-semibold rounded-xl transition-all shadow-lg shadow-primary-500/25">
                <i class="fas fa-shopping-bag mr-2"></i> Start Shopping
            </a>
        </div>
    @endif
</div>
@endsection
