<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display product management listing.
     */
    public function index()
    {
        $products = Product::with('category')
            ->latest()
            ->paginate(15);

        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $categories = Category::all();

        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created product.
     */
    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);

        // Filter out empty specification values
        if (isset($data['specifications'])) {
            $data['specifications'] = array_filter($data['specifications'], fn($v) => !empty($v));
        }

        // Handle primary image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        // Handle multiple images
        if ($request->hasFile('images')) {
            $imagesPaths = [];
            foreach ($request->file('images') as $img) {
                $imagesPaths[] = $img->store('products', 'public');
            }
            $data['images'] = $imagesPaths;
        }

        // Handle trending checkbox
        $data['is_trending'] = $request->has('is_trending');

        Product::create($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Show the form for editing a product.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);

        // Filter out empty specification values
        if (isset($data['specifications'])) {
            $data['specifications'] = array_filter($data['specifications'], fn($v) => !empty($v));
        }

        // Handle primary image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        // Handle multiple images
        if ($request->hasFile('images')) {
            // Delete old images
            if ($product->images) {
                foreach ($product->images as $oldImg) {
                    Storage::disk('public')->delete($oldImg);
                }
            }
            $imagesPaths = [];
            foreach ($request->file('images') as $img) {
                $imagesPaths[] = $img->store('products', 'public');
            }
            $data['images'] = $imagesPaths;
        }

        $data['is_trending'] = $request->has('is_trending');

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified product.
     */
    public function destroy(Product $product)
    {
        // Delete images from storage
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        if ($product->images) {
            foreach ($product->images as $img) {
                Storage::disk('public')->delete($img);
            }
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
