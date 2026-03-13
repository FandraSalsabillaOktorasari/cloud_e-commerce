{{-- Reusable Product Card Component --}}
@php
    $specs = $product->specifications ?? [];
    $specData = is_array($specs) ? $specs : (is_string($specs) ? json_decode($specs, true) : []);
@endphp
<div class="group relative bg-surface-800/50 border border-white/5 rounded-2xl overflow-hidden transition-all hover:border-primary-500/20 hover:-translate-y-1 hover:shadow-xl hover:shadow-primary-500/5">
    <a href="{{ route('products.show', $product->slug) }}" class="block" id="product-card-{{ $product->id }}">
        {{-- Image --}}
        <div class="aspect-square bg-surface-800 relative overflow-hidden">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            @else
                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary-900/30 to-accent-900/30">
                    <i class="fas fa-laptop text-4xl text-surface-200/30"></i>
                </div>
            @endif

            {{-- Badges --}}
            <div class="absolute top-3 left-3 flex flex-col gap-2">
                @if($product->is_trending)
                    <span class="px-2.5 py-1 bg-orange-500/90 text-white text-[10px] font-bold uppercase rounded-lg backdrop-blur-sm">
                        <i class="fas fa-fire mr-1"></i>Trending
                    </span>
                @endif
                @if($product->stock <= 3 && $product->stock > 0)
                    <span class="px-2.5 py-1 bg-red-500/90 text-white text-[10px] font-bold uppercase rounded-lg backdrop-blur-sm">
                        Low Stock
                    </span>
                @endif
            </div>

            {{-- Floating Actions (Wishlist & Compare) --}}
            <div class="absolute top-3 right-3 flex flex-col gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-20">
                {{-- Wishlist Toggle --}}
                @auth
                <button type="button" 
                        @click.stop.prevent="fetch('{{ route('wishlist.toggle', $product->id) }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } }).then(() => window.location.reload())"
                        class="w-8 h-8 rounded-full bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-white hover:bg-red-500 hover:border-red-500 transition-all pointer-events-auto"
                        title="Add to Wishlist">
                    <i class="fas fa-heart {{ Auth::user()->wishlist && Auth::user()->wishlist->contains('product_id', $product->id) ? 'text-white' : 'text-white/70' }}"></i>
                </button>
                @else
                <a href="{{ route('login') }}" 
                   class="w-8 h-8 rounded-full bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-white hover:bg-red-500 hover:border-red-500 transition-all pointer-events-auto"
                   title="Login to Wishlist">
                    <i class="fas fa-heart text-white/70"></i>
                </a>
                @endauth

                {{-- Compare Toggle --}}
                <button type="button"
                        @click.stop.prevent="fetch('{{ route('compare.toggle', $product->id) }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } }).then(() => window.location.reload())"
                        class="w-8 h-8 rounded-full bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-white hover:bg-primary-500 hover:border-primary-500 transition-all pointer-events-auto"
                        title="Add to Compare">
                    <i class="fas fa-exchange-alt {{ session('compare_list') && in_array($product->id, session('compare_list')) ? 'text-white' : 'text-white/70' }}"></i>
                </button>
            </div>
        </div>

        {{-- Info --}}
        <div class="p-4">
            <div class="flex items-center gap-2 mb-1">
                <p class="text-xs text-primary-400 font-medium">{{ $product->category->name ?? 'Uncategorized' }}</p>
                @if($product->brand)
                    <span class="text-[10px] text-surface-200/50">•</span>
                    <p class="text-xs text-surface-200/60">{{ $product->brand }}</p>
                @endif
            </div>
            <h3 class="font-semibold text-sm text-white group-hover:text-primary-400 transition-colors line-clamp-2 mb-2">{{ $product->name }}</h3>
            <div class="flex items-center justify-between">
                <p class="text-lg font-bold text-white">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                <span class="text-xs text-surface-200/60">{{ $product->sold_count }} sold</span>
            </div>
        </div>
    </a>

    {{-- Quick View Overlay (OUTSIDE the <a> tag) --}}
    <div class="absolute inset-x-0 top-0 aspect-square flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none z-10"
         x-data>
        <div class="absolute inset-0 bg-black/40"></div>
        <button type="button"
                class="relative pointer-events-auto px-4 py-2 bg-white/10 backdrop-blur-md border border-white/20 text-white text-sm font-medium rounded-xl hover:bg-white/20 transition-all transform translate-y-2 group-hover:translate-y-0 duration-300"
                @click.stop.prevent="window.dispatchEvent(new CustomEvent('quick-view', { detail: {
                    id: {{ $product->id }},
                    name: '{{ addslashes($product->name) }}',
                    price: 'Rp {{ number_format($product->price, 0, ',', '.') }}',
                    image: '{{ $product->image ? asset('storage/' . $product->image) : '' }}',
                    brand: '{{ addslashes($product->brand ?? '') }}',
                    category: '{{ addslashes($product->category->name ?? 'Uncategorized') }}',
                    description: '{{ addslashes(Str::limit($product->description, 200)) }}',
                    specs: {{ json_encode($specData) }},
                    url: '{{ route('products.show', $product->slug) }}'
                } }))">
            <i class="fas fa-eye mr-1"></i> Quick View
        </button>
    </div>

    {{-- Add to Cart --}}
    <div class="px-4 pb-4">
        <form method="POST" action="{{ route('cart.add') }}">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <button type="submit" class="w-full py-2.5 bg-primary-600/20 border border-primary-500/20 text-primary-400 text-sm font-semibold rounded-xl hover:bg-primary-600 hover:text-white transition-all {{ $product->stock <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                @if($product->stock > 0)
                    <i class="fas fa-cart-plus mr-1"></i> Add to Cart
                @else
                    <i class="fas fa-times-circle mr-1"></i> Out of Stock
                @endif
            </button>
        </form>
    </div>
</div>
