<?php

namespace App\Services;

use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InventoryForecastService
{
    /**
     * Get forecast data for all products with stock.
     * Returns: product name, current stock, daily sales rate, days until stockout, urgency level.
     */
    public function getForecast(int $lookbackDays = 30): array
    {
        $since = Carbon::now()->subDays($lookbackDays);

        // Get daily sales rate for each product
        $salesData = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', '!=', 'cancelled')
            ->where('orders.created_at', '>=', $since)
            ->select('order_items.product_id', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('order_items.product_id')
            ->pluck('total_sold', 'product_id');

        $products = Product::where('stock', '>', 0)->orderBy('stock')->get();
        $forecasts = [];

        foreach ($products as $product) {
            $totalSold = $salesData[$product->id] ?? 0;
            $dailyRate = $lookbackDays > 0 ? $totalSold / $lookbackDays : 0;
            $daysUntilStockout = $dailyRate > 0 ? round($product->stock / $dailyRate) : null;

            $urgency = 'safe'; // > 30 days or no sales
            if ($daysUntilStockout !== null) {
                if ($daysUntilStockout <= 7) $urgency = 'critical';
                elseif ($daysUntilStockout <= 14) $urgency = 'warning';
                elseif ($daysUntilStockout <= 30) $urgency = 'caution';
            }

            $forecasts[] = [
                'product' => $product,
                'stock' => $product->stock,
                'total_sold_30d' => $totalSold,
                'daily_rate' => round($dailyRate, 2),
                'days_until_stockout' => $daysUntilStockout,
                'urgency' => $urgency,
            ];
        }

        // Sort by urgency (critical first)
        usort($forecasts, function ($a, $b) {
            $order = ['critical' => 0, 'warning' => 1, 'caution' => 2, 'safe' => 3];
            $cmp = ($order[$a['urgency']] ?? 4) <=> ($order[$b['urgency']] ?? 4);
            if ($cmp === 0) return ($a['days_until_stockout'] ?? PHP_INT_MAX) <=> ($b['days_until_stockout'] ?? PHP_INT_MAX);
            return $cmp;
        });

        return $forecasts;
    }
}
