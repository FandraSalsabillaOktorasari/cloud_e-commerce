<!DOCTYPE html>
<html lang="en" class="scroll-smooth" x-data x-bind:class="$store.darkMode.on ? 'dark' : ''">
<script>
    // Prevent flash of wrong theme
    if (localStorage.getItem('darkMode') !== 'false') {
        document.documentElement.classList.add('dark');
    }
</script>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="TechStore - Premium laptops, PCs, and tech accessories at the best prices.">

    <title>@yield('title', 'TechStore — Premium Tech, Best Prices')</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    {{-- Heroicons via CDN (for icons) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="bg-surface-950 text-white font-sans antialiased min-h-screen flex flex-col">

    {{-- ═══════════════════════════════════════════════ NAVBAR ═══════════════════════════════════════════════ --}}
    <nav class="sticky top-0 z-50 bg-surface-900/80 backdrop-blur-xl border-b border-white/5" x-data="{ mobileOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                    <div class="w-8 h-8 bg-gradient-to-br from-primary-500 to-accent-500 rounded-lg flex items-center justify-center transition-transform group-hover:scale-110">
                        <i class="fas fa-microchip text-white text-sm"></i>
                    </div>
                    <span class="text-xl font-bold bg-gradient-to-r from-primary-400 to-accent-400 bg-clip-text text-transparent">TechStore</span>
                </a>

                {{-- Desktop Nav Links --}}
                <div class="hidden md:flex items-center gap-1">
                    <a href="{{ route('home') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition-all hover:bg-white/5 {{ request()->routeIs('home') ? 'text-primary-400 bg-white/5' : 'text-surface-200' }}">Home</a>
                    <a href="{{ route('products.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition-all hover:bg-white/5 {{ request()->routeIs('products.*') ? 'text-primary-400 bg-white/5' : 'text-surface-200' }}">Products</a>
                    <a href="{{ route('pc-builder.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition-all hover:bg-white/5 {{ request()->routeIs('pc-builder.*') ? 'text-primary-400 bg-white/5' : 'text-surface-200' }}"><i class="fas fa-wrench mr-1"></i>PC Builder</a>
                    @auth
                        <a href="{{ route('orders.history') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition-all hover:bg-white/5 {{ request()->routeIs('orders.*') ? 'text-primary-400 bg-white/5' : 'text-surface-200' }}">Orders</a>
                    @endauth
                </div>

                {{-- Right Side --}}
                <div class="flex items-center gap-1">
                    {{-- Compare --}}
                    @php $compareCount = count(session('compare', [])); @endphp
                    <a href="{{ route('compare.index') }}" class="relative p-2 rounded-lg transition-all hover:bg-white/5 group" title="Compare">
                        <i class="fas fa-columns text-surface-200 group-hover:text-primary-400 transition-colors"></i>
                        @if($compareCount > 0)
                            <span class="absolute -top-1 -right-1 w-5 h-5 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full text-[10px] font-bold flex items-center justify-center">{{ $compareCount }}</span>
                        @endif
                    </a>

                    {{-- Wishlist --}}
                    @auth
                        @php $wishlistCount = \App\Models\Wishlist::where('user_id', Auth::id())->count(); @endphp
                        <a href="{{ route('wishlist.index') }}" class="relative p-2 rounded-lg transition-all hover:bg-white/5 group" title="Wishlist">
                            <i class="fas fa-heart text-surface-200 group-hover:text-red-400 transition-colors"></i>
                            @if($wishlistCount > 0)
                                <span class="absolute -top-1 -right-1 w-5 h-5 bg-gradient-to-r from-red-500 to-pink-500 rounded-full text-[10px] font-bold flex items-center justify-center">{{ $wishlistCount }}</span>
                            @endif
                        </a>
                    @endauth

                    {{-- Cart --}}
                    <a href="{{ route('cart.index') }}" class="relative p-2 rounded-lg transition-all hover:bg-white/5 group" id="cart-link">
                        <i class="fas fa-shopping-cart text-surface-200 group-hover:text-primary-400 transition-colors"></i>
                        @if(isset($cartCount) && $cartCount > 0)
                            <span class="absolute -top-1 -right-1 w-5 h-5 bg-gradient-to-r from-primary-500 to-accent-500 rounded-full text-[10px] font-bold flex items-center justify-center animate-pulse">{{ $cartCount }}</span>
                        @endif
                    </a>

                    {{-- Dark Mode Toggle --}}
                    <button @click="$store.darkMode.toggle()" class="p-2 rounded-lg transition-all hover:bg-white/5 group" title="Toggle dark mode">
                        <i class="fas text-surface-200 group-hover:text-yellow-400 transition-colors" :class="$store.darkMode.on ? 'fa-sun' : 'fa-moon'"></i>
                    </button>

                    {{-- Auth Links (Desktop) --}}
                    <div class="hidden md:flex items-center gap-2">
                        @auth
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-white/5 transition-all">
                                    <div class="w-8 h-8 bg-gradient-to-br from-primary-600 to-accent-600 rounded-full flex items-center justify-center text-xs font-bold">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                    <span class="text-sm font-medium text-surface-200">{{ Auth::user()->name }}</span>
                                    <i class="fas fa-chevron-down text-[10px] text-surface-200 transition-transform" :class="open && 'rotate-180'"></i>
                                </button>
                                <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-surface-800 border border-white/10 rounded-xl shadow-2xl overflow-hidden">
                                    <a href="{{ route('profile.show') }}" class="flex items-center gap-2 px-4 py-3 text-sm text-surface-200 hover:bg-white/5 transition-all">
                                        <i class="fas fa-user w-4"></i> Profile
                                    </a>
                                    <a href="{{ route('orders.history') }}" class="flex items-center gap-2 px-4 py-3 text-sm text-surface-200 hover:bg-white/5 transition-all">
                                        <i class="fas fa-box w-4"></i> Orders
                                    </a>
                                    @if(Auth::user()->isAdmin())
                                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-4 py-3 text-sm text-accent-400 hover:bg-white/5 transition-all">
                                            <i class="fas fa-shield-alt w-4"></i> Admin Panel
                                        </a>
                                    @endif
                                    <hr class="border-white/5">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="flex items-center gap-2 w-full px-4 py-3 text-sm text-red-400 hover:bg-white/5 transition-all">
                                            <i class="fas fa-sign-out-alt w-4"></i> Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-surface-200 hover:text-white transition-colors">Login</a>
                            <a href="{{ route('register') }}" class="px-4 py-2 bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-500 hover:to-primary-400 text-white text-sm font-semibold rounded-lg transition-all shadow-lg shadow-primary-500/25 hover:shadow-primary-500/40">Register</a>
                        @endauth
                    </div>

                    {{-- Mobile Menu Button --}}
                    <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 rounded-lg hover:bg-white/5 transition-all">
                        <i class="fas" :class="mobileOpen ? 'fa-times' : 'fa-bars'" class="text-surface-200"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="mobileOpen" x-transition class="md:hidden bg-surface-900 border-t border-white/5">
            <div class="px-4 py-4 space-y-1">
                <a href="{{ route('home') }}" class="block px-4 py-3 rounded-lg text-sm font-medium {{ request()->routeIs('home') ? 'text-primary-400 bg-white/5' : 'text-surface-200' }}">Home</a>
                <a href="{{ route('products.index') }}" class="block px-4 py-3 rounded-lg text-sm font-medium {{ request()->routeIs('products.*') ? 'text-primary-400 bg-white/5' : 'text-surface-200' }}">Products</a>
                @auth
                    <a href="{{ route('orders.history') }}" class="block px-4 py-3 rounded-lg text-sm font-medium {{ request()->routeIs('orders.*') ? 'text-primary-400 bg-white/5' : 'text-surface-200' }}">Orders</a>
                    <a href="{{ route('profile.show') }}" class="block px-4 py-3 rounded-lg text-sm font-medium {{ request()->routeIs('profile.*') ? 'text-primary-400 bg-white/5' : 'text-surface-200' }}">Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-3 rounded-lg text-sm font-medium text-red-400">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block px-4 py-3 rounded-lg text-sm font-medium text-surface-200">Login</a>
                    <a href="{{ route('register') }}" class="block px-4 py-3 rounded-lg text-sm font-medium text-primary-400">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- ═══════════════════════════════════ TOAST NOTIFICATIONS (Alpine.js) ══════════════════════════════════ --}}
    <div x-data="toastNotifications()" x-init="init()" class="fixed top-20 right-4 z-[999] space-y-3 w-80" id="toast-container">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-show="toast.visible"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-x-12"
                 x-transition:enter-end="opacity-100 translate-x-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-x-0"
                 x-transition:leave-end="opacity-0 translate-x-12"
                 :class="toast.type === 'success'
                     ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-400'
                     : 'bg-red-500/10 border-red-500/20 text-red-400'"
                 class="border backdrop-blur-xl px-4 py-3 rounded-xl shadow-2xl flex items-start gap-3">
                <i :class="toast.type === 'success' ? 'fas fa-check-circle mt-0.5' : 'fas fa-exclamation-circle mt-0.5'"></i>
                <div class="flex-1">
                    <p class="text-sm font-medium" x-text="toast.message"></p>
                </div>
                <button @click="dismiss(toast)" class="text-current opacity-50 hover:opacity-100 transition-opacity">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>
        </template>
    </div>

    {{-- ═══════════════════════════════════════════ CONTENT ═══════════════════════════════════════════ --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- ═══════════════════════════════════════════ FOOTER ═══════════════════════════════════════════ --}}
    <footer class="bg-surface-900 border-t border-white/5 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                {{-- Brand --}}
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 bg-gradient-to-br from-primary-500 to-accent-500 rounded-lg flex items-center justify-center">
                            <i class="fas fa-microchip text-white text-sm"></i>
                        </div>
                        <span class="text-xl font-bold bg-gradient-to-r from-primary-400 to-accent-400 bg-clip-text text-transparent">TechStore</span>
                    </div>
                    <p class="text-surface-200 text-sm leading-relaxed max-w-md">Your one-stop shop for premium laptops, desktops, PC components, and tech accessories. Quality products at the best prices.</p>
                </div>

                {{-- Quick Links --}}
                <div>
                    <h4 class="font-semibold text-white mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('home') }}" class="text-surface-200 hover:text-primary-400 transition-colors">Home</a></li>
                        <li><a href="{{ route('products.index') }}" class="text-surface-200 hover:text-primary-400 transition-colors">Products</a></li>
                        <li><a href="{{ route('cart.index') }}" class="text-surface-200 hover:text-primary-400 transition-colors">Cart</a></li>
                    </ul>
                </div>

                {{-- Contact --}}
                <div>
                    <h4 class="font-semibold text-white mb-4">Contact</h4>
                    <ul class="space-y-2 text-sm text-surface-200">
                        <li class="flex items-center gap-2"><i class="fas fa-envelope w-4"></i> support@techstore.id</li>
                        <li class="flex items-center gap-2"><i class="fas fa-phone w-4"></i> +62 812-3456-7890</li>
                        <li class="flex items-center gap-2"><i class="fas fa-map-marker-alt w-4"></i> Jakarta, Indonesia</li>
                    </ul>
                </div>
            </div>

            <hr class="border-white/5 my-8">

            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-surface-200">
                <p>&copy; {{ date('Y') }} TechStore. All rights reserved.</p>
                <div class="flex items-center gap-4">
                    <a href="#" class="hover:text-primary-400 transition-colors"><i class="fab fa-instagram text-lg"></i></a>
                    <a href="#" class="hover:text-primary-400 transition-colors"><i class="fab fa-twitter text-lg"></i></a>
                    <a href="#" class="hover:text-primary-400 transition-colors"><i class="fab fa-github text-lg"></i></a>
                </div>
            </div>
        </div>
    </footer>

    {{-- Quick View Modal --}}
    <div x-data="quickViewModal()" @quick-view.window="open($event.detail)" id="quick-view-modal">
        <div x-show="visible" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[998]" @click="close()">
        </div>
        <div x-show="visible" x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
             class="fixed inset-0 z-[999] flex items-center justify-center p-4" @click.self="close()">
            <div class="bg-surface-900 border border-white/10 rounded-3xl shadow-2xl w-full max-w-2xl max-h-[85vh] overflow-y-auto" @click.stop>
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="px-2.5 py-1 bg-white/5 text-surface-200 text-xs font-semibold rounded-lg" x-text="product.brand"></span>
                            <span class="px-2.5 py-1 bg-primary-500/10 text-primary-400 text-xs font-semibold rounded-lg" x-text="product.category"></span>
                        </div>
                        <button @click="close()" class="p-2 hover:bg-white/5 rounded-lg transition-all text-surface-200 hover:text-white">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="aspect-square bg-surface-800/50 border border-white/5 rounded-2xl overflow-hidden">
                            <img :src="product.image" :alt="product.name" class="w-full h-full object-cover" x-show="product.image">
                            <div x-show="!product.image" class="w-full h-full flex items-center justify-center">
                                <i class="fas fa-laptop text-4xl text-surface-200/20"></i>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold mb-2" x-text="product.name"></h3>
                            <p class="text-2xl font-black bg-gradient-to-r from-primary-400 to-accent-400 bg-clip-text text-transparent mb-3" x-text="product.price"></p>
                            <p class="text-sm text-surface-200 mb-4 line-clamp-3" x-text="product.description"></p>
                            <div class="space-y-2 mb-4">
                                <template x-for="(value, key) in product.specs" :key="key">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-surface-200" x-text="key"></span>
                                        <span class="text-white font-medium" x-text="value"></span>
                                    </div>
                                </template>
                            </div>
                            <div class="flex gap-2">
                                <a :href="product.url" class="flex-1 py-2.5 text-center bg-white/5 border border-white/10 text-white text-sm font-medium rounded-xl hover:bg-white/10 transition-all">
                                    View Details
                                </a>
                                <form method="POST" action="{{ route('cart.add') }}" class="flex-1">
                                    @csrf
                                    <input type="hidden" name="product_id" :value="product.id">
                                    <button type="submit" class="w-full py-2.5 bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-500 hover:to-primary-400 text-white text-sm font-semibold rounded-xl transition-all shadow-lg shadow-primary-500/25">
                                        <i class="fas fa-cart-plus mr-1"></i> Add to Cart
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Alpine.js Component Scripts --}}
    <script>
        function toastNotifications() {
            return {
                toasts: [],
                counter: 0,
                init() {
                    // Process Laravel flash messages
                    @if(session('success'))
                        this.add('{{ session("success") }}', 'success');
                    @endif
                    @if(session('error'))
                        this.add('{{ session("error") }}', 'error');
                    @endif
                },
                add(message, type = 'success') {
                    const id = ++this.counter;
                    const toast = { id, message, type, visible: true };
                    this.toasts.push(toast);
                    setTimeout(() => this.dismiss(toast), 4000);
                },
                dismiss(toast) {
                    toast.visible = false;
                    setTimeout(() => {
                        this.toasts = this.toasts.filter(t => t.id !== toast.id);
                    }, 300);
                }
            }
        }

        function quickViewModal() {
            return {
                visible: false,
                product: { name: '', price: '', image: '', brand: '', category: '', description: '', specs: {}, url: '', id: '' },
                open(data) {
                    this.product = data;
                    this.visible = true;
                    document.body.style.overflow = 'hidden';
                },
                close() {
                    this.visible = false;
                    document.body.style.overflow = '';
                }
            }
        }
    </script>

    {{-- Alpine.js Dark Mode Store --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('darkMode', {
                on: localStorage.getItem('darkMode') !== 'false',
                toggle() {
                    this.on = !this.on;
                    localStorage.setItem('darkMode', this.on);
                }
            });
        });
    </script>

    {{-- Alpine.js CDN --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('scripts')
</body>
</html>

