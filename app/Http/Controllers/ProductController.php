<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of products with optional filtering.
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Filter by category
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter by brand
        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filter by Processor (JSON specs)
        if ($request->filled('processor')) {
            $query->where('specifications->Processor', 'like', "%{$request->processor}%");
        }

        // Filter by GPU (JSON specs)
        if ($request->filled('gpu')) {
            $query->where('specifications->GPU', 'like', "%{$request->gpu}%");
        }

        // Filter by RAM (JSON specs)
        if ($request->filled('ram')) {
            $query->where('specifications->RAM', 'like', "%{$request->ram}%");
        }

        // Filter by Storage (JSON specs)
        if ($request->filled('storage')) {
            $query->where('specifications->Storage', 'like', "%{$request->storage}%");
        }

        // Search by name or description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%");
            });
        }

        // Sort
        $sort = $request->get('sort', 'latest');
        $query = match ($sort) {
            'price_low'  => $query->orderBy('price', 'asc'),
            'price_high' => $query->orderBy('price', 'desc'),
            'popular'    => $query->orderByDesc('sold_count'),
            default      => $query->latest(),
        };

        $products   = $query->paginate(12)->withQueryString();
        $categories = Category::all();

        // Get unique brands for filter sidebar
        $brands = Product::select('brand')->distinct()->whereNotNull('brand')->orderBy('brand')->pluck('brand');

        return view('products.index', compact('products', 'categories', 'brands'));
    }

    /**
     * Display a single product detail page.
     */
    public function show(string $slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        // Increment view count
        $product->increment('view_count');

        // Get related products from the same category
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }
}
