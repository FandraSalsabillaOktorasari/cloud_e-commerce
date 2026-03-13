@extends('admin.layouts.app')

@section('title', 'Analytics — Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold mb-8"><i class="fas fa-chart-line text-primary-400 mr-2"></i>Analytics Dashboard</h1>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
        <div class="bg-surface-800/50 border border-white/5 rounded-2xl p-5">
            <p class="text-xs text-surface-200 uppercase tracking-wider mb-1">Total Revenue</p>
            <p class="text-2xl font-bold text-emerald-400">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        </div>
        <div class="bg-surface-800/50 border border-white/5 rounded-2xl p-5">
            <p class="text-xs text-surface-200 uppercase tracking-wider mb-1">Monthly Revenue</p>
            <p class="text-2xl font-bold text-blue-400">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</p>
        </div>
        <div class="bg-surface-800/50 border border-white/5 rounded-2xl p-5">
            <p class="text-xs text-surface-200 uppercase tracking-wider mb-1">Total Orders</p>
            <p class="text-2xl font-bold text-primary-400">{{ $totalOrders }}</p>
        </div>
        <div class="bg-surface-800/50 border border-white/5 rounded-2xl p-5">
            <p class="text-xs text-surface-200 uppercase tracking-wider mb-1">Avg Order Value</p>
            <p class="text-2xl font-bold text-yellow-400">Rp {{ number_format($avgOrderValue, 0, ',', '.') }}</p>
        </div>
        <div class="bg-surface-800/50 border border-white/5 rounded-2xl p-5">
            <p class="text-xs text-surface-200 uppercase tracking-wider mb-1">Customers</p>
            <p class="text-2xl font-bold text-accent-400">{{ $totalCustomers }}</p>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        {{-- Revenue Trend (Line Chart, spans 2 cols) --}}
        <div class="lg:col-span-2 bg-surface-800/50 border border-white/5 rounded-2xl p-6">
            <h2 class="text-lg font-semibold mb-4">Revenue Trend (Last 30 Days)</h2>
            <canvas id="revenueChart" height="120"></canvas>
        </div>

        {{-- Top Products (Doughnut) --}}
        <div class="bg-surface-800/50 border border-white/5 rounded-2xl p-6">
            <h2 class="text-lg font-semibold mb-4">Top Products by Revenue</h2>
            <canvas id="topProductsChart" height="200"></canvas>
        </div>
    </div>

    {{-- Sales by Category (Bar Chart) --}}
    <div class="bg-surface-800/50 border border-white/5 rounded-2xl p-6">
        <h2 class="text-lg font-semibold mb-4">Sales by Category</h2>
        <canvas id="categoryChart" height="80"></canvas>
    </div>
</div>

{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    const chartColors = ['#7c3aed','#06b6d4','#f59e0b','#ef4444','#10b981','#ec4899','#8b5cf6'];

    // Revenue Line Chart
    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode(collect($revenueByDay)->pluck('date')) !!},
            datasets: [{
                label: 'Revenue (Rp)',
                data: {!! json_encode(collect($revenueByDay)->pluck('revenue')) !!},
                borderColor: '#7c3aed',
                backgroundColor: 'rgba(124,58,237,0.1)',
                fill: true,
                tension: 0.4,
                pointRadius: 2,
                pointHoverRadius: 6,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: ctx => 'Rp ' + ctx.raw.toLocaleString('id-ID') } },
            },
            scales: {
                y: { ticks: { callback: v => 'Rp ' + (v/1000000).toFixed(0) + 'M' }, grid: { color: 'rgba(255,255,255,0.05)' } },
                x: { grid: { display: false }, ticks: { maxTicksLimit: 10 } },
            }
        }
    });

    // Top Products Doughnut
    new Chart(document.getElementById('topProductsChart'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($topProducts->pluck('name')) !!},
            datasets: [{
                data: {!! json_encode($topProducts->pluck('revenue')) !!},
                backgroundColor: chartColors,
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom', labels: { color: '#a0a0b0', font: { size: 11 }, padding: 10, boxWidth: 12 } },
                tooltip: { callbacks: { label: ctx => ctx.label + ': Rp ' + Number(ctx.raw).toLocaleString('id-ID') } },
            }
        }
    });

    // Sales by Category Bar Chart
    new Chart(document.getElementById('categoryChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($salesByCategory->pluck('category')) !!},
            datasets: [{
                label: 'Revenue',
                data: {!! json_encode($salesByCategory->pluck('revenue')) !!},
                backgroundColor: chartColors,
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: ctx => 'Rp ' + Number(ctx.raw).toLocaleString('id-ID') } },
            },
            scales: {
                y: { ticks: { callback: v => 'Rp ' + (v/1000000).toFixed(0) + 'M' }, grid: { color: 'rgba(255,255,255,0.05)' } },
                x: { grid: { display: false } },
            }
        }
    });
</script>
@endsection
