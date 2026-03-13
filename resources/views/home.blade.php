@extends('layouts.app')

@section('title', 'TechStore — Premium Tech, Best Prices')

@section('content')
{{-- ═══════════════════════════════════ HERO SECTION ═══════════════════════════════════ --}}
<section class="relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-primary-900/40 via-surface-950 to-accent-900/20"></div>
    <div class="absolute inset-0">
        <div class="absolute top-20 left-10 w-72 h-72 bg-primary-500/10 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-accent-500/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-28 lg:py-36">
        <div class="text-center max-w-3xl mx-auto">
            <div class="inline-flex items-center gap-2 bg-primary-500/10 border border-primary-500/20 text-primary-400 px-4 py-1.5 rounded-full text-sm font-medium mb-6">
                <i class="fas fa-bolt"></i> New Arrivals Available
            </div>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black leading-tight mb-6">
                Premium Tech at
                <span class="bg-gradient-to-r from-primary-400 via-accent-400 to-primary-400 bg-clip-text text-transparent">Unbeatable Prices</span>
            </h1>
            <p class="text-lg sm:text-xl text-surface-200 mb-10 leading-relaxed">
                Discover the latest laptops, desktops, PC components, and accessories. From gaming rigs to professional workstations, we have it all.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('products.index') }}" class="w-full sm:w-auto px-8 py-3.5 bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-500 hover:to-primary-400 text-white font-semibold rounded-xl transition-all shadow-lg shadow-primary-500/25 hover:shadow-primary-500/40 hover:-translate-y-0.5" id="hero-shop-btn">
                    <i class="fas fa-shopping-bag mr-2"></i> Shop Now
                </a>
                <a href="#trending" class="w-full sm:w-auto px-8 py-3.5 bg-white/5 border border-white/10 hover:bg-white/10 text-white font-semibold rounded-xl transition-all hover:-translate-y-0.5">
                    <i class="fas fa-fire mr-2"></i> See Trending
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════ CATEGORIES ═══════════════════════════════════ --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-2xl sm:text-3xl font-bold">Browse Categories</h2>
        <a href="{{ route('products.index') }}" class="text-primary-400 hover:text-primary-300 text-sm font-medium transition-colors">View All <i class="fas fa-arrow-right ml-1"></i></a>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
        @foreach($categories as $category)
            <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="group relative bg-surface-800/50 border border-white/5 rounded-2xl p-6 text-center transition-all hover:bg-surface-800 hover:border-primary-500/20 hover:-translate-y-1 hover:shadow-xl hover:shadow-primary-500/5" id="category-{{ $category->slug }}">
                <div class="w-14 h-14 mx-auto mb-4 bg-gradient-to-br from-primary-500/20 to-accent-500/20 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    @switch($category->slug)
                        @case('laptops')
                            <i class="fas fa-laptop text-2xl text-primary-400"></i>
                            @break
                        @case('desktops')
                            <i class="fas fa-desktop text-2xl text-primary-400"></i>
                            @break
                        @case('pc-components')
                            <i class="fas fa-microchip text-2xl text-primary-400"></i>
                            @break
                        @case('peripherals')
                            <i class="fas fa-keyboard text-2xl text-primary-400"></i>
                            @break
                        @case('accessories')
                            <i class="fas fa-headphones text-2xl text-primary-400"></i>
                            @break
                        @default
                            <i class="fas fa-cube text-2xl text-primary-400"></i>
                    @endswitch
                </div>
                <h3 class="font-semibold text-sm text-surface-200 group-hover:text-white transition-colors">{{ $category->name }}</h3>
                <p class="text-xs text-surface-200/60 mt-1">{{ $category->products->count() }} products</p>
            </a>
        @endforeach
    </div>
</section>

{{-- ═══════════════════════════════════ TRENDING ═══════════════════════════════════ --}}
<section id="trending" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="flex items-center justify-between mb-8">
        <div>
            <div class="flex items-center gap-2 text-orange-400 mb-2">
                <i class="fas fa-fire"></i>
                <span class="text-sm font-semibold uppercase tracking-wider">Trending Now</span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-bold">Most Popular Products</h2>
        </div>
        <a href="{{ route('products.index', ['sort' => 'popular']) }}" class="text-primary-400 hover:text-primary-300 text-sm font-medium transition-colors hidden sm:inline">View All <i class="fas fa-arrow-right ml-1"></i></a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($trendingProducts as $product)
            @include('components.product-card', ['product' => $product])
        @endforeach
    </div>
</section>

{{-- ═══════════════════════════════════ BEST SELLERS ═══════════════════════════════════ --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="flex items-center justify-between mb-8">
        <div>
            <div class="flex items-center gap-2 text-emerald-400 mb-2">
                <i class="fas fa-trophy"></i>
                <span class="text-sm font-semibold uppercase tracking-wider">Best Sellers</span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-bold">Top Selling Products</h2>
        </div>
        <a href="{{ route('products.index', ['sort' => 'popular']) }}" class="text-primary-400 hover:text-primary-300 text-sm font-medium transition-colors hidden sm:inline">View All <i class="fas fa-arrow-right ml-1"></i></a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($bestSellers as $product)
            @include('components.product-card', ['product' => $product])
        @endforeach
    </div>
</section>

{{-- ═══════════════════════════════════ CTA BANNER ═══════════════════════════════════ --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="relative overflow-hidden bg-gradient-to-r from-primary-900/60 to-accent-900/60 rounded-3xl p-8 sm:p-12 border border-white/5">
        <div class="absolute inset-0 bg-gradient-to-r from-primary-600/10 to-accent-600/10"></div>
        <div class="absolute top-0 right-0 w-64 h-64 bg-primary-400/10 rounded-full blur-3xl"></div>
        <div class="relative flex flex-col sm:flex-row items-center justify-between gap-6">
            <div>
                <h3 class="text-2xl sm:text-3xl font-bold mb-2">Ready to Build Your Dream Setup?</h3>
                <p class="text-surface-200">Browse our complete catalog and find the perfect tech for your needs.</p>
            </div>
            <a href="{{ route('products.index') }}" class="shrink-0 px-8 py-3.5 bg-white text-surface-900 font-semibold rounded-xl hover:bg-surface-100 transition-all shadow-lg hover:-translate-y-0.5">
                Explore All Products
            </a>
        </div>
    </div>
</section>
@endsection
