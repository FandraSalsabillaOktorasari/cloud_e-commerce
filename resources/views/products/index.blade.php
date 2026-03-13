@extends('layouts.app')

@section('title', 'Products — TechStore')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Page Header --}}
    <div class="mb-8">
        <h1 class="text-3xl sm:text-4xl font-bold mb-2">All Products</h1>
        <p class="text-surface-200">Find the perfect tech for your needs</p>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">
        {{-- ═══════ Sidebar Filters ═══════ --}}
        <aside class="w-full lg:w-72 shrink-0" x-data="{ filtersOpen: false }">
            <button @click="filtersOpen = !filtersOpen" class="lg:hidden w-full flex items-center justify-between bg-surface-800/50 border border-white/5 rounded-xl px-4 py-3 mb-4">
                <span class="font-medium"><i class="fas fa-filter mr-2"></i>Filters</span>
                <i class="fas fa-chevron-down transition-transform" :class="filtersOpen && 'rotate-180'"></i>
            </button>

            <div :class="filtersOpen ? 'block' : 'hidden lg:block'">
                <form method="GET" action="{{ route('products.index') }}" id="filter-form">
                    {{-- Preserve search and sort --}}
                    @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                    @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif

                    <div class="bg-surface-800/50 border border-white/5 rounded-2xl p-5 space-y-6">
                        {{-- Categories --}}
                        <div>
                            <h3 class="font-semibold text-sm uppercase tracking-wider text-surface-200 mb-3">Category</h3>
                            <div class="space-y-2">
                                <a href="{{ route('products.index', request()->except('category', 'page')) }}"
                                   class="block px-3 py-2 rounded-lg text-sm transition-all {{ !request('category') ? 'bg-primary-500/10 text-primary-400 font-medium' : 'text-surface-200 hover:bg-white/5' }}">
                                    All Categories
                                </a>
                                @foreach($categories as $category)
                                    <a href="{{ route('products.index', array_merge(request()->except('page'), ['category' => $category->slug])) }}"
                                       class="block px-3 py-2 rounded-lg text-sm transition-all {{ request('category') === $category->slug ? 'bg-primary-500/10 text-primary-400 font-medium' : 'text-surface-200 hover:bg-white/5' }}">
                                        {{ $category->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        {{-- Brand --}}
                        <div>
                            <h3 class="font-semibold text-sm uppercase tracking-wider text-surface-200 mb-3">Brand</h3>
                            <select name="brand" onchange="this.form.submit()" class="w-full px-3 py-2.5 bg-surface-900 border border-white/5 rounded-xl text-sm text-white focus:outline-none focus:border-primary-500/30">
                                <option value="">All Brands</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand }}" {{ request('brand') === $brand ? 'selected' : '' }}>{{ $brand }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Price Range --}}
                        <div>
                            <h3 class="font-semibold text-sm uppercase tracking-wider text-surface-200 mb-3">Price Range</h3>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min" class="w-full px-3 py-2 bg-surface-900 border border-white/5 rounded-xl text-sm text-white placeholder:text-surface-200/40 focus:outline-none focus:border-primary-500/30">
                                <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max" class="w-full px-3 py-2 bg-surface-900 border border-white/5 rounded-xl text-sm text-white placeholder:text-surface-200/40 focus:outline-none focus:border-primary-500/30">
                            </div>
                        </div>

                        {{-- Tech Spec Filters --}}
                        <div>
                            <h3 class="font-semibold text-sm uppercase tracking-wider text-surface-200 mb-3">
                                <i class="fas fa-microchip mr-1"></i> Tech Specs
                            </h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs text-surface-200/60 mb-1">Processor (CPU)</label>
                                    <input type="text" name="processor" value="{{ request('processor') }}" placeholder="e.g. Intel i7, M3 Pro" class="w-full px-3 py-2 bg-surface-900 border border-white/5 rounded-xl text-sm text-white placeholder:text-surface-200/40 focus:outline-none focus:border-primary-500/30">
                                </div>
                                <div>
                                    <label class="block text-xs text-surface-200/60 mb-1">Graphics (GPU)</label>
                                    <input type="text" name="gpu" value="{{ request('gpu') }}" placeholder="e.g. RTX 4070, Iris Xe" class="w-full px-3 py-2 bg-surface-900 border border-white/5 rounded-xl text-sm text-white placeholder:text-surface-200/40 focus:outline-none focus:border-primary-500/30">
                                </div>
                                <div>
                                    <label class="block text-xs text-surface-200/60 mb-1">RAM</label>
                                    <input type="text" name="ram" value="{{ request('ram') }}" placeholder="e.g. 16GB, DDR5" class="w-full px-3 py-2 bg-surface-900 border border-white/5 rounded-xl text-sm text-white placeholder:text-surface-200/40 focus:outline-none focus:border-primary-500/30">
                                </div>
                                <div>
                                    <label class="block text-xs text-surface-200/60 mb-1">Storage</label>
                                    <input type="text" name="storage" value="{{ request('storage') }}" placeholder="e.g. 1TB SSD, NVMe" class="w-full px-3 py-2 bg-surface-900 border border-white/5 rounded-xl text-sm text-white placeholder:text-surface-200/40 focus:outline-none focus:border-primary-500/30">
                                </div>
                            </div>
                        </div>

                        {{-- Apply / Clear --}}
                        <div class="flex gap-2">
                            <button type="submit" class="flex-1 py-2.5 bg-primary-600 hover:bg-primary-500 text-white text-sm font-semibold rounded-xl transition-all">
                                <i class="fas fa-filter mr-1"></i> Apply
                            </button>
                            <a href="{{ route('products.index') }}" class="flex-1 text-center py-2.5 bg-white/5 border border-white/10 text-white text-sm font-medium rounded-xl hover:bg-white/10 transition-all">
                                Clear
                            </a>
                        </div>

                        {{-- Sort --}}
                        <div>
                            <h3 class="font-semibold text-sm uppercase tracking-wider text-surface-200 mb-3">Sort By</h3>
                            <div class="space-y-2">
                                @foreach(['latest' => 'Newest First', 'price_low' => 'Price: Low to High', 'price_high' => 'Price: High to Low', 'popular' => 'Most Popular'] as $key => $label)
                                    <a href="{{ route('products.index', array_merge(request()->except('page'), ['sort' => $key])) }}"
                                       class="block px-3 py-2 rounded-lg text-sm transition-all {{ request('sort', 'latest') === $key ? 'bg-primary-500/10 text-primary-400 font-medium' : 'text-surface-200 hover:bg-white/5' }}">
                                        {{ $label }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </aside>

        {{-- ═══════ Product Grid ═══════ --}}
        <div class="flex-1">
            {{-- Search Bar --}}
            <form method="GET" action="{{ route('products.index') }}" class="mb-6">
                @foreach(request()->except('search', 'page') as $key => $value)
                    @if($value) <input type="hidden" name="{{ $key }}" value="{{ $value }}"> @endif
                @endforeach
                <div class="flex gap-2">
                    <div class="relative flex-1">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-surface-200/50"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products, brands..."
                               class="w-full pl-11 pr-4 py-3 bg-surface-800/50 border border-white/5 rounded-xl text-white placeholder:text-surface-200/40 focus:outline-none focus:border-primary-500/30 focus:ring-2 focus:ring-primary-500/10 transition-all"
                               id="product-search-input">
                    </div>
                    <button type="submit" class="px-6 py-3 bg-primary-600 hover:bg-primary-500 text-white font-medium rounded-xl transition-all" id="product-search-btn">
                        Search
                    </button>
                </div>
            </form>

            {{-- Active Filters & Results Info --}}
            <div class="flex items-center justify-between mb-6 flex-wrap gap-2">
                <p class="text-sm text-surface-200">
                    Showing <span class="text-white font-medium">{{ $products->total() }}</span> products
                    @if(request('search'))
                        for "<span class="text-primary-400">{{ request('search') }}</span>"
                    @endif
                    @if(request('brand'))
                        · Brand: <span class="text-primary-400">{{ request('brand') }}</span>
                    @endif
                </p>
            </div>

            {{-- Grid --}}
            @if($products->count())
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        @include('components.product-card', ['product' => $product])
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @else
                <div class="text-center py-20">
                    <i class="fas fa-search text-4xl text-surface-200/20 mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">No products found</h3>
                    <p class="text-surface-200 mb-6">Try adjusting your search or filter criteria.</p>
                    <a href="{{ route('products.index') }}" class="px-6 py-2.5 bg-primary-600 hover:bg-primary-500 text-white font-medium rounded-xl transition-all">Clear Filters</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
