@extends('layouts.app')

@section('title', 'My Wishlist — TechStore')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl sm:text-4xl font-bold">My Wishlist</h1>
        <p class="text-surface-200 mt-1">{{ $wishlistItems->count() }} {{ Str::plural('item', $wishlistItems->count()) }} saved for later</p>
    </div>

    @if($wishlistItems->isEmpty())
        <div class="text-center py-20">
            <div class="w-20 h-20 mx-auto mb-6 bg-surface-800/50 rounded-full flex items-center justify-center">
                <i class="fas fa-heart text-3xl text-surface-200/30"></i>
            </div>
            <h2 class="text-xl font-semibold mb-2">Your Wishlist is Empty</h2>
            <p class="text-surface-200 mb-6">Browse our products and save your favorites for later.</p>
            <a href="{{ route('products.index') }}" class="px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-500 text-white font-semibold rounded-xl hover:from-primary-500 hover:to-primary-400 transition-all">
                Browse Products
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($wishlistItems as $item)
                <div class="group bg-surface-800/50 border border-white/5 rounded-2xl overflow-hidden transition-all hover:border-primary-500/20 hover:-translate-y-1 hover:shadow-xl hover:shadow-primary-500/5 relative">
                    {{-- Wishlist Remove Button --}}
                    <form method="POST" action="{{ route('wishlist.toggle', $item->product_id) }}" class="absolute top-3 right-3 z-10">
                        @csrf
                        <button type="submit" class="p-2 bg-red-500/80 text-white rounded-full hover:bg-red-600 transition-all shadow-lg" title="Remove from wishlist">
                            <i class="fas fa-heart text-sm"></i>
                        </button>
                    </form>

                    <a href="{{ route('products.show', $item->product->slug) }}" class="block">
                        <div class="aspect-square bg-surface-800 relative overflow-hidden">
                            @if($item->product->image)
                                <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary-900/30 to-accent-900/30">
                                    <i class="fas fa-laptop text-4xl text-surface-200/30"></i>
                                </div>
                            @endif
                        </div>
                        <div class="p-4">
                            <p class="text-xs text-primary-400 font-medium mb-1">{{ $item->product->category->name ?? 'Uncategorized' }}</p>
                            <h3 class="font-semibold text-sm text-white group-hover:text-primary-400 transition-colors line-clamp-2 mb-2">{{ $item->product->name }}</h3>
                            <p class="text-lg font-bold text-white">Rp {{ number_format($item->product->price, 0, ',', '.') }}</p>
                        </div>
                    </a>

                    <div class="px-4 pb-4">
                        <form method="POST" action="{{ route('cart.add') }}">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                            <button type="submit" class="w-full py-2.5 bg-primary-600/20 border border-primary-500/20 text-primary-400 text-sm font-semibold rounded-xl hover:bg-primary-600 hover:text-white transition-all {{ $item->product->stock <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $item->product->stock <= 0 ? 'disabled' : '' }}>
                                <i class="fas fa-cart-plus mr-1"></i> Add to Cart
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
