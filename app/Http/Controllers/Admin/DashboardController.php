<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Show the admin dashboard with stats overview.
     */
    public function index()
    {
        $stats = [
            'total_products'  => Product::count(),
            'total_orders'    => Order::count(),
            'total_revenue'   => Order::where('payment_status', 'paid')->sum('total'),
            'total_customers' => User::where('role', 'customer')->count(),
        ];

        // Low stock products (5 or fewer)
        $lowStockProducts = Product::where('stock', '<=', 5)
            ->orderBy('stock')
            ->get();

        // Recent orders (latest 10)
        $recentOrders = Order::with('user')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'lowStockProducts', 'recentOrders'));
    }
}
