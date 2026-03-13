@extends('layouts.app')

@section('title', $product->name . ' — TechStore')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-surface-200 mb-8">
        <a href="{{ route('home') }}" class="hover:text-primary-400 transition-colors">Home</a>
        <i class="fas fa-chevron-right text-[10px]"></i>
        <a href="{{ route('products.index') }}" class="hover:text-primary-400 transition-colors">Products</a>
        <i class="fas fa-chevron-right text-[10px]"></i>
        <a href="{{ route('products.index', ['category' => $product->category->slug]) }}" class="hover:text-primary-400 transition-colors">{{ $product->category->name }}</a>
        <i class="fas fa-chevron-right text-[10px]"></i>
        <span class="text-white font-medium truncate max-w-[200px]">{{ $product->name }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16">
        {{-- ═══════ Image Carousel (Alpine.js) ═══════ --}}
        <div x-data="imageCarousel()" class="space-y-4">
            {{-- Main Image --}}
            <div class="relative aspect-square bg-surface-800/50 border border-white/5 rounded-3xl overflow-hidden group">
                <template x-for="(img, index) in images" :key="index">
                    <div x-show="activeIndex === index"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="absolute inset-0">
                        <img :src="img" :alt="'{{ $product->name }}'" class="w-full h-full object-cover">
                    </div>
                </template>

                {{-- Fallback for no images --}}
                <template x-if="images.length === 0">
                    <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-primary-900/30 to-accent-900/30">
                        <i class="fas fa-laptop text-6xl text-surface-200/20 mb-4"></i>
                        <span class="text-surface-200/40 text-sm">No image available</span>
                    </div>
                </template>

                {{-- Navigation Arrows --}}
                <template x-if="images.length > 1">
                    <div>
                        <button @click="prev()" class="absolute left-3 top-1/2 -translate-y-1/2 w-10 h-10 bg-black/50 backdrop-blur-sm rounded-full flex items-center justify-center text-white hover:bg-black/70 transition-all opacity-0 group-hover:opacity-100">
                            <i class="fas fa-chevron-left text-sm"></i>
                        </button>
                        <button @click="next()" class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 bg-black/50 backdrop-blur-sm rounded-full flex items-center justify-center text-white hover:bg-black/70 transition-all opacity-0 group-hover:opacity-100">
                            <i class="fas fa-chevron-right text-sm"></i>
                        </button>
                    </div>
                </template>

                {{-- Dots indicator --}}
                <template x-if="images.length > 1">
                    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex items-center gap-2">
                        <template x-for="(img, index) in images" :key="'dot-'+index">
                            <button @click="activeIndex = index"
                                    :class="activeIndex === index ? 'w-6 bg-primary-400' : 'w-2 bg-white/50 hover:bg-white/80'"
                                    class="h-2 rounded-full transition-all duration-300"></button>
                        </template>
                    </div>
                </template>
            </div>

            {{-- Thumbnail Strip --}}
            <template x-if="images.length > 1">
                <div class="grid grid-cols-5 gap-2">
                    <template x-for="(img, index) in images" :key="'thumb-'+index">
                        <button @click="activeIndex = index"
                                :class="activeIndex === index ? 'border-primary-500/50 ring-2 ring-primary-500/20' : 'border-white/5 hover:border-white/15'"
                                class="aspect-square bg-surface-800/50 border rounded-xl overflow-hidden transition-all duration-200">
                            <img :src="img" :alt="''" class="w-full h-full object-cover">
                        </button>
                    </template>
                </div>
            </template>
        </div>

        {{-- ═══════ Product Info ═══════ --}}
        <div>
            {{-- Brand & Badges --}}
            <div class="flex items-center gap-2 mb-3 flex-wrap">
                @if($product->brand)
                    <span class="px-3 py-1 bg-white/5 text-surface-200 text-xs font-semibold rounded-lg">{{ $product->brand }}</span>
                @endif
                <span class="px-3 py-1 bg-primary-500/10 text-primary-400 text-xs font-semibold rounded-lg">{{ $product->category->name }}</span>
                @if($product->is_trending)
                    <span class="px-3 py-1 bg-orange-500/10 text-orange-400 text-xs font-semibold rounded-lg"><i class="fas fa-fire mr-1"></i>Trending</span>
                @endif
                @if($product->stock > 0 && $product->stock <= 5)
                    <span class="px-3 py-1 bg-red-500/10 text-red-400 text-xs font-semibold rounded-lg animate-pulse"><i class="fas fa-exclamation-triangle mr-1"></i>Only {{ $product->stock }} left!</span>
                @endif
            </div>

            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-4" id="product-title">{{ $product->name }}</h1>

            {{-- Stats --}}
            <div class="flex items-center gap-4 text-sm text-surface-200 mb-6">
                <span><i class="fas fa-eye mr-1"></i> {{ number_format($product->view_count) }} views</span>
                <span><i class="fas fa-shopping-bag mr-1"></i> {{ $product->sold_count }} sold</span>
                <span class="{{ $product->stock > 0 ? 'text-emerald-400' : 'text-red-400' }}">
                    <i class="fas fa-{{ $product->stock > 0 ? 'check-circle' : 'times-circle' }} mr-1"></i>
                    {{ $product->stock > 0 ? $product->stock . ' in stock' : 'Out of stock' }}
                </span>
            </div>

            {{-- Price --}}
            <div class="bg-surface-800/50 border border-white/5 rounded-2xl p-6 mb-6">
                <p class="text-sm text-surface-200 mb-1">Price</p>
                <p class="text-3xl sm:text-4xl font-black bg-gradient-to-r from-primary-400 to-accent-400 bg-clip-text text-transparent" id="product-price">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </p>
            </div>

            {{-- Add to Cart with AJAX Toast --}}
            <form method="POST" action="{{ route('cart.add') }}" class="mb-8" id="add-to-cart-form"
                  x-data="addToCartForm()" @submit.prevent="submit($el)">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div class="flex items-center gap-4">
                    <div class="flex items-center bg-surface-800/50 border border-white/5 rounded-xl overflow-hidden">
                        <button type="button" @click="qty = Math.max(1, qty - 1)" class="px-4 py-3 hover:bg-white/5 transition-all text-surface-200">
                            <i class="fas fa-minus text-sm"></i>
                        </button>
                        <input type="number" name="quantity" x-model="qty" min="1" max="{{ $product->stock }}" value="1"
                               class="w-16 text-center bg-transparent border-x border-white/5 py-3 text-white focus:outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                        <button type="button" @click="qty = Math.min({{ $product->stock }}, qty + 1)" class="px-4 py-3 hover:bg-white/5 transition-all text-surface-200">
                            <i class="fas fa-plus text-sm"></i>
                        </button>
                    </div>
                <div class="flex-1 flex gap-3">
                    <button type="submit" :disabled="loading || {{ $product->stock <= 0 ? 'true' : 'false' }}" class="flex-1 py-3.5 bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-500 hover:to-primary-400 text-white font-semibold rounded-xl transition-all shadow-lg shadow-primary-500/25 hover:shadow-primary-500/40 disabled:opacity-50 disabled:cursor-not-allowed" id="add-to-cart-btn">
                        <template x-if="!loading">
                            <span>
                                @if($product->stock > 0)
                                    <i class="fas fa-cart-plus mr-2"></i> Add to Cart
                                @else
                                    <i class="fas fa-times-circle mr-2"></i> Out of Stock
                                @endif
                            </span>
                        </template>
                        <template x-if="loading">
                            <span><i class="fas fa-spinner fa-spin mr-2"></i>Adding...</span>
                        </template>
                    </button>

                    {{-- Wishlist Toggle --}}
                    @auth
                    <button type="button" 
                            @click.stop.prevent="fetch('{{ route('wishlist.toggle', $product->id) }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } }).then(() => window.location.reload())"
                            class="w-12 h-12 rounded-xl bg-surface-800 border {{ Auth::user()->wishlist && Auth::user()->wishlist->contains('product_id', $product->id) ? 'border-red-500/50 text-red-500' : 'border-white/5 text-surface-200' }} hover:bg-red-500/10 hover:border-red-500 transition-all flex items-center justify-center group"
                            title="Add to Wishlist">
                        <i class="fas fa-heart group-hover:scale-110 transition-transform"></i>
                    </button>
                    @else
                    <a href="{{ route('login') }}" 
                       class="w-12 h-12 rounded-xl bg-surface-800 border border-white/5 text-surface-200 hover:bg-red-500/10 hover:border-red-500 transition-all flex items-center justify-center group"
                       title="Login to Wishlist">
                        <i class="fas fa-heart group-hover:scale-110 transition-transform"></i>
                    </a>
                    @endauth

                    {{-- Compare Toggle --}}
                    <button type="button"
                            @click.stop.prevent="fetch('{{ route('compare.toggle', $product->id) }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } }).then(() => window.location.reload())"
                            class="w-12 h-12 rounded-xl bg-surface-800 border {{ session('compare_list') && in_array($product->id, session('compare_list')) ? 'border-primary-500/50 text-primary-400' : 'border-white/5 text-surface-200' }} hover:bg-primary-500/10 hover:border-primary-500 transition-all flex items-center justify-center group"
                            title="Add to Compare">
                        <i class="fas fa-exchange-alt group-hover:scale-110 transition-transform"></i>
                    </button>
                </div>
            </div>
            </form>

            {{-- Description --}}
            <div class="mb-8">
                <h2 class="text-lg font-semibold mb-3">Description</h2>
                <p class="text-surface-200 leading-relaxed">{{ $product->description }}</p>
            </div>

            {{-- Specifications --}}
            @if($product->specifications)
                <div>
                    <h2 class="text-lg font-semibold mb-3"><i class="fas fa-microchip text-primary-400 mr-2"></i>Specifications</h2>
                    <div class="bg-surface-800/50 border border-white/5 rounded-2xl overflow-hidden divide-y divide-white/5">
                        @foreach($product->specifications as $key => $value)
                            <div class="flex items-center justify-between px-5 py-3.5 hover:bg-white/[0.02] transition-colors">
                                <span class="text-sm text-surface-200 font-medium">{{ $key }}</span>
                                <span class="text-sm text-white font-medium text-right">{{ $value }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
    {{-- ═══════ Reviews & Ratings ═══════ --}}
    <section class="mt-16 pt-16 border-t border-white/5">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold">
                <i class="fas fa-star text-yellow-400 mr-2"></i>Reviews & Ratings
            </h2>
            @if($product->reviews->count())
                <div class="flex items-center gap-3">
                    <span class="text-3xl font-black text-yellow-400">{{ $product->average_rating }}</span>
                    <div>
                        <div class="flex gap-0.5">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star text-sm {{ $i <= round($product->average_rating) ? 'text-yellow-400' : 'text-surface-200/20' }}"></i>
                            @endfor
                        </div>
                        <p class="text-xs text-surface-200 mt-0.5">{{ $product->reviews->count() }} {{ Str::plural('review', $product->reviews->count()) }}</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Review Form --}}
        @auth
            @php $existingReview = $product->reviews->where('user_id', Auth::id())->first(); @endphp
            @if(!$existingReview)
                <div class="bg-surface-800/50 border border-white/5 rounded-2xl p-6 mb-8" x-data="{ rating: 0, hoverRating: 0 }">
                    <h3 class="text-lg font-semibold mb-4">Write a Review</h3>
                    <form method="POST" action="{{ route('reviews.store', $product->slug) }}">
                        @csrf
                        {{-- Star Picker --}}
                        <div class="mb-4">
                            <label class="block text-sm text-surface-200 mb-2">Your Rating</label>
                            <div class="flex gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <button type="button"
                                            @click="rating = {{ $i }}"
                                            @mouseenter="hoverRating = {{ $i }}"
                                            @mouseleave="hoverRating = 0"
                                            class="text-2xl transition-colors focus:outline-none"
                                            :class="(hoverRating || rating) >= {{ $i }} ? 'text-yellow-400' : 'text-surface-200/20 hover:text-yellow-400/50'">
                                        <i class="fas fa-star"></i>
                                    </button>
                                @endfor
                            </div>
                            <input type="hidden" name="rating" x-bind:value="rating">
                            @error('rating') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm text-surface-200 mb-2">Title</label>
                            <input type="text" name="title" value="{{ old('title') }}" required maxlength="100"
                                   class="w-full px-4 py-3 bg-surface-900/50 border border-white/10 rounded-xl text-white placeholder-surface-200/30 focus:outline-none focus:border-primary-500/50 transition-colors"
                                   placeholder="Sum up your experience">
                            @error('title') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm text-surface-200 mb-2">Your Review</label>
                            <textarea name="body" rows="4" required maxlength="1000"
                                      class="w-full px-4 py-3 bg-surface-900/50 border border-white/10 rounded-xl text-white placeholder-surface-200/30 focus:outline-none focus:border-primary-500/50 transition-colors resize-none"
                                      placeholder="Share your thoughts about this product...">{{ old('body') }}</textarea>
                            @error('body') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-500 text-white font-semibold rounded-xl hover:from-primary-500 hover:to-primary-400 transition-all">
                            <i class="fas fa-paper-plane mr-2"></i> Submit Review
                        </button>
                    </form>
                </div>
            @endif
        @else
            <div class="bg-surface-800/50 border border-white/5 rounded-2xl p-6 mb-8 text-center">
                <p class="text-surface-200"><a href="{{ route('login') }}" class="text-primary-400 hover:underline">Log in</a> to write a review.</p>
            </div>
        @endauth

        {{-- Review List --}}
        @if($product->reviews->count())
            <div class="space-y-4">
                @foreach($product->reviews()->with('user')->latest()->get() as $review)
                    <div class="bg-surface-800/30 border border-white/5 rounded-2xl p-5">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-primary-600 to-accent-600 rounded-full flex items-center justify-center text-xs font-bold">
                                    {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-semibold text-sm">{{ $review->user->name }}</span>
                                        @if($review->is_verified_purchase)
                                            <span class="px-2 py-0.5 bg-emerald-500/10 text-emerald-400 text-[10px] font-bold rounded-lg">
                                                <i class="fas fa-check-circle mr-0.5"></i> Verified Purchase
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <div class="flex gap-0.5">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star text-[10px] {{ $i <= $review->rating ? 'text-yellow-400' : 'text-surface-200/20' }}"></i>
                                            @endfor
                                        </div>
                                        <span class="text-xs text-surface-200/50">{{ $review->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                            @auth
                                @if(Auth::id() === $review->user_id)
                                    <form method="POST" action="{{ route('reviews.destroy', $review->id) }}">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-1.5 text-surface-200/30 hover:text-red-400 transition-colors" title="Delete review">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                        <h4 class="font-semibold text-sm mb-1">{{ $review->title }}</h4>
                        <p class="text-sm text-surface-200 leading-relaxed">{{ $review->body }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-10">
                <i class="fas fa-comment-slash text-3xl text-surface-200/20 mb-3"></i>
                <p class="text-surface-200">No reviews yet. Be the first to review this product!</p>
            </div>
        @endif
    </section>

    {{-- ═══════ Related Products ═══════ --}}
    @if($relatedProducts->count())
        <section class="mt-16 pt-16 border-t border-white/5">
            <h2 class="text-2xl font-bold mb-8">Related Products</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $related)
                    @include('components.product-card', ['product' => $related])
                @endforeach
            </div>
        </section>
    @endif
</div>

@push('scripts')
<script>
    function imageCarousel() {
        return {
            activeIndex: 0,
            images: [
                @if($product->image)
                    '{{ asset("storage/" . $product->image) }}',
                @endif
                @if($product->images)
                    @foreach($product->images as $img)
                        '{{ asset("storage/" . $img) }}',
                    @endforeach
                @endif
            ],
            next() {
                this.activeIndex = (this.activeIndex + 1) % this.images.length;
            },
            prev() {
                this.activeIndex = (this.activeIndex - 1 + this.images.length) % this.images.length;
            },
            init() {
                // Auto-rotate every 5 seconds if multiple images
                if (this.images.length > 1) {
                    setInterval(() => this.next(), 5000);
                }
            }
        }
    }

    function addToCartForm() {
        return {
            qty: 1,
            loading: false,
            submit(form) {
                this.loading = true;
                form.submit();
            }
        }
    }
</script>
@endpush
@endsection
