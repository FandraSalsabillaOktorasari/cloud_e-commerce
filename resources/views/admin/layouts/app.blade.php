<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin — TechStore')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-surface-950 text-white font-sans min-h-screen flex">

    {{-- ═══════ Sidebar ═══════ --}}
    <aside class="w-64 bg-surface-900 border-r border-white/5 fixed h-full z-40 hidden lg:block">
        <div class="p-6 border-b border-white/5">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                <div class="w-9 h-9 bg-gradient-to-br from-primary-500 to-accent-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-microchip text-white text-sm"></i>
                </div>
                <div>
                    <span class="text-lg font-bold bg-gradient-to-r from-primary-400 to-accent-400 bg-clip-text text-transparent">TechStore</span>
                    <span class="block text-[10px] text-surface-200/50 uppercase tracking-widest -mt-1">Admin Panel</span>
                </div>
            </a>
        </div>

        <nav class="p-4 space-y-1">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-primary-500/10 text-primary-400' : 'text-surface-200 hover:bg-white/5 hover:text-white' }}">
                <i class="fas fa-chart-pie w-5 text-center"></i> Dashboard
            </a>
            <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.products.*') ? 'bg-primary-500/10 text-primary-400' : 'text-surface-200 hover:bg-white/5 hover:text-white' }}">
                <i class="fas fa-box w-5 text-center"></i> Products
            </a>
            <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.orders.*') ? 'bg-primary-500/10 text-primary-400' : 'text-surface-200 hover:bg-white/5 hover:text-white' }}">
                <i class="fas fa-shopping-bag w-5 text-center"></i> Orders
            </a>
            <a href="{{ route('admin.analytics') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.analytics') ? 'bg-primary-500/10 text-primary-400' : 'text-surface-200 hover:bg-white/5 hover:text-white' }}">
                <i class="fas fa-chart-line w-5 text-center"></i> Analytics
            </a>

            <div class="pt-4 mt-4 border-t border-white/5">
                <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-surface-200 hover:bg-white/5 hover:text-white transition-all">
                    <i class="fas fa-store w-5 text-center"></i> View Store
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-red-400 hover:bg-red-500/10 transition-all">
                        <i class="fas fa-sign-out-alt w-5 text-center"></i> Logout
                    </button>
                </form>
            </div>
        </nav>
    </aside>

    {{-- ═══════ Main Content ═══════ --}}
    <div class="flex-1 lg:ml-64">
        {{-- Top Bar --}}
        <header class="sticky top-0 z-30 bg-surface-950/80 backdrop-blur-xl border-b border-white/5 px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold">@yield('page-title', 'Dashboard')</h1>
                    <p class="text-sm text-surface-200">@yield('page-subtitle', '')</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-sm text-surface-200">{{ Auth::user()->name }}</span>
                    <div class="w-8 h-8 bg-primary-500/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-xs text-primary-400"></i>
                    </div>
                </div>
            </div>
        </header>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mx-6 mt-4 px-4 py-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-xl text-sm">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mx-6 mt-4 px-4 py-3 bg-red-500/10 border border-red-500/20 text-red-400 rounded-xl text-sm">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            </div>
        @endif

        {{-- Page Content --}}
        <main class="p-6">
            @yield('content')
        </main>
    </div>

</body>
</html>
