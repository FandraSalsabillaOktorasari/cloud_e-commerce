<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home/dashboard page with trending and best-seller products.
     */
    public function index()
    {
        $trendingProducts = Product::where('is_trending', true)
            ->orWhere('view_count', '>=', 100)
            ->orderByDesc('view_count')
            ->take(8)
            ->get();

        $bestSellers = Product::orderByDesc('sold_count')
            ->take(8)
            ->get();

        $categories = Category::all();

        return view('home', compact('trendingProducts', 'bestSellers', 'categories'));
    }
}
