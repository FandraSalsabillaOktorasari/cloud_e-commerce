@extends('admin.layouts.app')

@section('title', 'Edit Product — Admin TechStore')
@section('page-title', 'Edit Product')
@section('page-subtitle', $product->name)

@section('content')
<div class="max-w-3xl">
    <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" id="edit-product-form">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            {{-- Basic Info --}}
            <div class="bg-surface-800/50 border border-white/5 rounded-2xl p-6">
                <h2 class="text-lg font-semibold mb-5"><i class="fas fa-info-circle text-primary-400 mr-2"></i>Basic Information</h2>
                <div class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-surface-200 mb-1.5">Product Name *</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required class="w-full px-4 py-3 bg-surface-900 border border-white/5 rounded-xl text-white focus:outline-none focus:border-primary-500/30 focus:ring-2 focus:ring-primary-500/10 transition-all">
                            @error('name') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="brand" class="block text-sm font-medium text-surface-200 mb-1.5">Brand *</label>
                            <input type="text" name="brand" id="brand" value="{{ old('brand', $product->brand) }}" required class="w-full px-4 py-3 bg-surface-900 border border-white/5 rounded-xl text-white focus:outline-none focus:border-primary-500/30 focus:ring-2 focus:ring-primary-500/10 transition-all">
                            @error('brand') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-surface-200 mb-1.5">Category *</label>
                            <select name="category_id" id="category_id" required class="w-full px-4 py-3 bg-surface-900 border border-white/5 rounded-xl text-white focus:outline-none focus:border-primary-500/30 focus:ring-2 focus:ring-primary-500/10 transition-all">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="price" class="block text-sm font-medium text-surface-200 mb-1.5">Price (IDR) *</label>
                            <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}" required min="0" class="w-full px-4 py-3 bg-surface-900 border border-white/5 rounded-xl text-white focus:outline-none focus:border-primary-500/30 focus:ring-2 focus:ring-primary-500/10 transition-all">
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-surface-200 mb-1.5">Description *</label>
                        <textarea name="description" id="description" rows="4" required class="w-full px-4 py-3 bg-surface-900 border border-white/5 rounded-xl text-white focus:outline-none focus:border-primary-500/30 focus:ring-2 focus:ring-primary-500/10 transition-all resize-none">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="stock" class="block text-sm font-medium text-surface-200 mb-1.5">Stock *</label>
                            <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock) }}" required min="0" class="w-full px-4 py-3 bg-surface-900 border border-white/5 rounded-xl text-white focus:outline-none focus:border-primary-500/30 focus:ring-2 focus:ring-primary-500/10 transition-all">
                        </div>
                        <div class="flex items-end pb-1">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="is_trending" value="1" {{ old('is_trending', $product->is_trending) ? 'checked' : '' }} class="w-5 h-5 rounded border-white/10 bg-surface-900 text-primary-600 focus:ring-primary-500/20">
                                <span class="text-sm font-medium text-surface-200"><i class="fas fa-fire text-orange-400 mr-1"></i> Mark as Trending</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Specifications --}}
            <div class="bg-surface-800/50 border border-white/5 rounded-2xl p-6">
                <h2 class="text-lg font-semibold mb-5"><i class="fas fa-microchip text-primary-400 mr-2"></i>Technical Specifications</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @php $specs = $product->specifications ?? []; @endphp
                    @foreach(['Processor', 'GPU', 'RAM', 'Storage', 'Display'] as $spec)
                        <div>
                            <label for="spec-{{ Str::slug($spec) }}" class="block text-sm font-medium text-surface-200 mb-1.5">{{ $spec }}</label>
                            <input type="text" name="specifications[{{ $spec }}]" id="spec-{{ Str::slug($spec) }}" value="{{ old("specifications.$spec", $specs[$spec] ?? '') }}" class="w-full px-4 py-3 bg-surface-900 border border-white/5 rounded-xl text-white focus:outline-none focus:border-primary-500/30 focus:ring-2 focus:ring-primary-500/10 transition-all">
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Images --}}
            <div class="bg-surface-800/50 border border-white/5 rounded-2xl p-6">
                <h2 class="text-lg font-semibold mb-5"><i class="fas fa-images text-primary-400 mr-2"></i>Product Images</h2>

                @if($product->image)
                    <div class="mb-4">
                        <p class="text-sm text-surface-200 mb-2">Current Image</p>
                        <div class="w-32 h-32 bg-surface-800 rounded-xl overflow-hidden">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="" class="w-full h-full object-cover">
                        </div>
                    </div>
                @endif

                <div class="space-y-4">
                    <div>
                        <label for="image" class="block text-sm font-medium text-surface-200 mb-1.5">Replace Primary Image</label>
                        <input type="file" name="image" id="image" accept="image/*" class="w-full px-4 py-3 bg-surface-900 border border-white/5 rounded-xl text-white file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:bg-primary-500/10 file:text-primary-400 file:font-medium">
                    </div>
                    <div>
                        <label for="images" class="block text-sm font-medium text-surface-200 mb-1.5">Replace Additional Images</label>
                        <input type="file" name="images[]" id="images" accept="image/*" multiple class="w-full px-4 py-3 bg-surface-900 border border-white/5 rounded-xl text-white file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:bg-primary-500/10 file:text-primary-400 file:font-medium">
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3 mt-6">
            <button type="submit" class="px-8 py-3 bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-500 hover:to-primary-400 text-white font-semibold rounded-xl transition-all shadow-lg shadow-primary-500/25">
                <i class="fas fa-save mr-2"></i> Update Product
            </button>
            <a href="{{ route('admin.products.index') }}" class="px-6 py-3 bg-white/5 border border-white/10 text-white font-medium rounded-xl hover:bg-white/10 transition-all">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
