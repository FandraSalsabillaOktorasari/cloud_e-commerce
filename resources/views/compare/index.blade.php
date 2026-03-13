@extends('layouts.app')

@section('title', 'Compare Products — TechStore')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl sm:text-4xl font-bold">Compare Products</h1>
            <p class="text-surface-200 mt-1">Side-by-side specification comparison</p>
        </div>
        @if($products->count())
            <a href="{{ route('compare.clear') }}" class="px-4 py-2 bg-red-500/10 border border-red-500/20 text-red-400 text-sm font-medium rounded-xl hover:bg-red-500/20 transition-all">
                <i class="fas fa-trash mr-1"></i> Clear All
            </a>
        @endif
    </div>

    @if($products->count() === 0)
        <div class="text-center py-20">
            <div class="w-20 h-20 mx-auto mb-6 bg-surface-800/50 rounded-full flex items-center justify-center">
                <i class="fas fa-columns text-3xl text-surface-200/30"></i>
            </div>
            <h2 class="text-xl font-semibold mb-2">No Products to Compare</h2>
            <p class="text-surface-200 mb-6">Add products to your compare list from the product listing page.</p>
            <a href="{{ route('products.index') }}" class="px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-500 text-white font-semibold rounded-xl hover:from-primary-500 hover:to-primary-400 transition-all">
                Browse Products
            </a>
        </div>
    @else
        {{-- Comparison Table --}}
        <div class="overflow-x-auto">
            <div class="min-w-[640px]">
                {{-- Product Headers --}}
                <div class="grid gap-4 mb-6" style="grid-template-columns: 180px repeat({{ $products->count() }}, 1fr);">
                    <div></div>
                    @foreach($products as $product)
                        <div class="bg-surface-800/50 border border-white/5 rounded-2xl p-4 text-center relative group">
                            {{-- Remove Button --}}
                            <form method="POST" action="{{ route('compare.toggle', $product->id) }}" class="absolute top-2 right-2">
                                @csrf
                                <button type="submit" class="p-1.5 bg-red-500/10 text-red-400 rounded-lg hover:bg-red-500/20 transition-all opacity-0 group-hover:opacity-100">
                                    <i class="fas fa-times text-xs"></i>
                                </button>
                            </form>

                            <div class="aspect-square w-full max-w-[160px] mx-auto mb-3 bg-surface-800 rounded-xl overflow-hidden">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <i class="fas fa-laptop text-3xl text-surface-200/20"></i>
                                    </div>
                                @endif
                            </div>

                            <h3 class="font-semibold text-sm mb-1 line-clamp-2">
                                <a href="{{ route('products.show', $product->slug) }}" class="hover:text-primary-400 transition-colors">{{ $product->name }}</a>
                            </h3>
                            <p class="text-xs text-primary-400 mb-2">{{ $product->brand ?? '' }} • {{ $product->category->name }}</p>
                            <p class="text-lg font-bold bg-gradient-to-r from-primary-400 to-accent-400 bg-clip-text text-transparent mb-3">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </p>

                            <form method="POST" action="{{ route('cart.add') }}">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button type="submit" class="w-full py-2 bg-primary-600/20 border border-primary-500/20 text-primary-400 text-xs font-semibold rounded-lg hover:bg-primary-600 hover:text-white transition-all {{ $product->stock <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                    <i class="fas fa-cart-plus mr-1"></i> Add to Cart
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>

                {{-- Spec Rows --}}
                <div class="bg-surface-800/50 border border-white/5 rounded-2xl overflow-hidden">
                    {{-- General Info --}}
                    @php $rowBg = false; @endphp
                    @foreach(['Brand', 'Category', 'Stock'] as $field)
                        <div class="grid gap-4 {{ $rowBg ? 'bg-white/[0.02]' : '' }}" style="grid-template-columns: 180px repeat({{ $products->count() }}, 1fr);">
                            <div class="px-5 py-3.5 text-sm font-medium text-surface-200 border-r border-white/5">{{ $field }}</div>
                            @foreach($products as $product)
                                <div class="px-5 py-3.5 text-sm text-white font-medium">
                                    @if($field === 'Brand') {{ $product->brand ?? '—' }}
                                    @elseif($field === 'Category') {{ $product->category->name }}
                                    @elseif($field === 'Stock')
                                        <span class="{{ $product->stock > 5 ? 'text-emerald-400' : ($product->stock > 0 ? 'text-orange-400' : 'text-red-400') }}">
                                            {{ $product->stock > 0 ? $product->stock . ' units' : 'Out of stock' }}
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        @php $rowBg = !$rowBg; @endphp
                    @endforeach

                    {{-- Separator --}}
                    <div class="grid gap-4 bg-primary-500/5 border-y border-primary-500/10" style="grid-template-columns: 180px repeat({{ $products->count() }}, 1fr);">
                        <div class="px-5 py-2 text-xs font-bold text-primary-400 uppercase tracking-wider border-r border-white/5">Technical Specs</div>
                        @foreach($products as $p) <div></div> @endforeach
                    </div>

                    {{-- Spec Comparison Rows --}}
                    @foreach($allSpecs as $specKey)
                        <div class="grid gap-4 {{ $loop->even ? 'bg-white/[0.02]' : '' }}" style="grid-template-columns: 180px repeat({{ $products->count() }}, 1fr);">
                            <div class="px-5 py-3.5 text-sm font-medium text-surface-200 border-r border-white/5">{{ $specKey }}</div>
                            @foreach($products as $product)
                                @php
                                    $val = $product->specifications[$specKey] ?? null;
                                    $values = $products->map(fn($p) => $p->specifications[$specKey] ?? null)->filter()->unique();
                                    $isDifferent = $values->count() > 1;
                                @endphp
                                <div class="px-5 py-3.5 text-sm {{ $val ? 'text-white font-medium' : 'text-surface-200/30' }} {{ $isDifferent && $val ? 'bg-primary-500/5' : '' }}">
                                    {{ $val ?? '—' }}
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Add More Products (if < 3) --}}
        @if($products->count() < 3)
            <div class="mt-6 text-center">
                <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white/5 border border-white/10 text-white font-medium rounded-xl hover:bg-white/10 transition-all">
                    <i class="fas fa-plus"></i> Add More Products ({{ 3 - $products->count() }} slot{{ 3 - $products->count() > 1 ? 's' : '' }} left)
                </a>
            </div>
        @endif
    @endif
</div>
@endsection
