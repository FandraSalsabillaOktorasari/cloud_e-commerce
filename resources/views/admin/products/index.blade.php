@extends('admin.layouts.app')

@section('title', 'Products — Admin TechStore')
@section('page-title', 'Product Management')
@section('page-subtitle', 'Manage your product catalog')

@section('content')
<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-surface-200">Showing {{ $products->total() }} products</p>
    <a href="{{ route('admin.products.create') }}" class="px-5 py-2.5 bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-500 hover:to-primary-400 text-white text-sm font-semibold rounded-xl transition-all shadow-lg shadow-primary-500/25" id="create-product-btn">
        <i class="fas fa-plus mr-2"></i> Add Product
    </a>
</div>

<div class="bg-surface-800/50 border border-white/5 rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-white/5 text-left text-surface-200">
                    <th class="px-5 py-3 font-medium">Product</th>
                    <th class="px-5 py-3 font-medium">Brand</th>
                    <th class="px-5 py-3 font-medium">Category</th>
                    <th class="px-5 py-3 font-medium">Price</th>
                    <th class="px-5 py-3 font-medium">Stock</th>
                    <th class="px-5 py-3 font-medium">Sold</th>
                    <th class="px-5 py-3 font-medium">Status</th>
                    <th class="px-5 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @foreach($products as $product)
                    <tr class="hover:bg-white/[0.02]">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-surface-800 rounded-lg overflow-hidden shrink-0">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center"><i class="fas fa-laptop text-xs text-surface-200/30"></i></div>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="font-medium truncate max-w-[200px]">{{ $product->name }}</p>
                                    @if($product->is_trending)
                                        <span class="text-[10px] text-orange-400 font-semibold"><i class="fas fa-fire mr-0.5"></i>Trending</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-surface-200">{{ $product->brand ?? '-' }}</td>
                        <td class="px-5 py-3 text-surface-200">{{ $product->category->name ?? '-' }}</td>
                        <td class="px-5 py-3 font-medium">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td class="px-5 py-3">
                            <span class="{{ $product->stock <= 5 ? ($product->stock <= 0 ? 'text-red-400' : 'text-yellow-400') : 'text-emerald-400' }} font-medium">
                                {{ $product->stock }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-surface-200">{{ $product->sold_count }}</td>
                        <td class="px-5 py-3">
                            @if($product->stock <= 0)
                                <span class="px-2 py-1 bg-red-500/10 text-red-400 text-xs font-semibold rounded-lg">Out of Stock</span>
                            @elseif($product->stock <= 5)
                                <span class="px-2 py-1 bg-yellow-500/10 text-yellow-400 text-xs font-semibold rounded-lg">Low Stock</span>
                            @else
                                <span class="px-2 py-1 bg-emerald-500/10 text-emerald-400 text-xs font-semibold rounded-lg">In Stock</span>
                            @endif
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.products.edit', $product) }}" class="p-2 text-primary-400 hover:bg-primary-500/10 rounded-lg transition-all" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Delete this product?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-400 hover:bg-red-500/10 rounded-lg transition-all" title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">
    {{ $products->links() }}
</div>
@endsection
