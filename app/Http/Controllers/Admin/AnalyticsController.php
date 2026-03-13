<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $thirtyDaysAgo = $now->copy()->subDays(30);

        // KPI Cards
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total');
        $monthlyRevenue = Order::where('status', '!=', 'cancelled')
            ->where('created_at', '>=', $thirtyDaysAgo)->sum('total');
        $totalOrders = Order::where('status', '!=', 'cancelled')->count();
        $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
        $totalCustomers = User::where('role', 'customer')->count();

        // Revenue over last 30 days (for line chart)
        $dailyRevenue = Order::where('status', '!=', 'cancelled')
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as revenue'), DB::raw('COUNT(*) as orders'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Fill in missing days with 0
        $revenueByDay = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i)->format('Y-m-d');
            $dayData = $dailyRevenue->firstWhere('date', $date);
            $revenueByDay[] = [
                'date' => Carbon::parse($date)->format('M d'),
                'revenue' => $dayData ? (float)$dayData->revenue : 0,
                'orders' => $dayData ? $dayData->orders : 0,
            ];
        }

        // Sales by Category (for bar chart)
        $salesByCategory = OrderItem::join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', '!=', 'cancelled')
            ->select('categories.name as category', DB::raw('SUM(order_items.subtotal) as revenue'))
            ->groupBy('categories.name')
            ->orderByDesc('revenue')
            ->get();

        // Top 5 Products by Revenue (for doughnut)
        $topProducts = OrderItem::join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', '!=', 'cancelled')
            ->select('products.name', DB::raw('SUM(order_items.subtotal) as revenue'))
            ->groupBy('products.name')
            ->orderByDesc('revenue')
            ->limit(5)
            ->get();

        return view('admin.analytics', compact(
            'totalRevenue', 'monthlyRevenue', 'totalOrders', 'avgOrderValue', 'totalCustomers',
            'revenueByDay', 'salesByCategory', 'topProducts'
        ));
    }
}
