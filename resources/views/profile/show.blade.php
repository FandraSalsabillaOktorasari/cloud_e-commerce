@extends('layouts.app')

@section('title', 'My Profile — TechStore')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold mb-8">My Profile</h1>

    <div class="space-y-6">
        {{-- Profile Info --}}
        <div class="bg-surface-800/50 border border-white/5 rounded-2xl p-6">
            <h2 class="text-lg font-semibold mb-5 flex items-center gap-2">
                <i class="fas fa-user text-primary-400"></i> Personal Information
            </h2>

            <form method="POST" action="{{ route('profile.update') }}" id="profile-form">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-surface-200 mb-1.5">Full Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                               class="w-full px-4 py-3 bg-surface-900 border border-white/5 rounded-xl text-white placeholder:text-surface-200/40 focus:outline-none focus:border-primary-500/30 focus:ring-2 focus:ring-primary-500/10 transition-all">
                        @error('name') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-surface-200 mb-1.5">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                               class="w-full px-4 py-3 bg-surface-900 border border-white/5 rounded-xl text-white placeholder:text-surface-200/40 focus:outline-none focus:border-primary-500/30 focus:ring-2 focus:ring-primary-500/10 transition-all">
                        @error('email') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <button type="submit" class="mt-6 px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-500 hover:to-primary-400 text-white font-semibold rounded-xl transition-all shadow-lg shadow-primary-500/25">
                    <i class="fas fa-save mr-2"></i> Save Changes
                </button>
            </form>
        </div>

        {{-- Change Password --}}
        <div class="bg-surface-800/50 border border-white/5 rounded-2xl p-6">
            <h2 class="text-lg font-semibold mb-5 flex items-center gap-2">
                <i class="fas fa-lock text-primary-400"></i> Change Password
            </h2>

            <form method="POST" action="{{ route('profile.password') }}" id="password-form">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-surface-200 mb-1.5">Current Password</label>
                        <input type="password" name="current_password" id="current_password" required
                               class="w-full px-4 py-3 bg-surface-900 border border-white/5 rounded-xl text-white placeholder:text-surface-200/40 focus:outline-none focus:border-primary-500/30 focus:ring-2 focus:ring-primary-500/10 transition-all">
                        @error('current_password') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-surface-200 mb-1.5">New Password</label>
                        <input type="password" name="password" id="password" required
                               class="w-full px-4 py-3 bg-surface-900 border border-white/5 rounded-xl text-white placeholder:text-surface-200/40 focus:outline-none focus:border-primary-500/30 focus:ring-2 focus:ring-primary-500/10 transition-all">
                        @error('password') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-surface-200 mb-1.5">Confirm New Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                               class="w-full px-4 py-3 bg-surface-900 border border-white/5 rounded-xl text-white placeholder:text-surface-200/40 focus:outline-none focus:border-primary-500/30 focus:ring-2 focus:ring-primary-500/10 transition-all">
                    </div>
                </div>

                <button type="submit" class="mt-6 px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-500 hover:to-primary-400 text-white font-semibold rounded-xl transition-all shadow-lg shadow-primary-500/25">
                    <i class="fas fa-key mr-2"></i> Update Password
                </button>
            </form>
        </div>

        {{-- Quick Links --}}
        <div class="bg-surface-800/50 border border-white/5 rounded-2xl p-6">
            <h2 class="text-lg font-semibold mb-4">Quick Links</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <a href="{{ route('orders.history') }}" class="flex items-center gap-3 p-4 bg-surface-900/50 rounded-xl hover:bg-surface-900 transition-all group">
                    <div class="w-10 h-10 bg-primary-500/10 rounded-lg flex items-center justify-center"><i class="fas fa-box text-primary-400"></i></div>
                    <div>
                        <p class="font-medium group-hover:text-primary-400 transition-colors">Order History</p>
                        <p class="text-xs text-surface-200">View your past orders</p>
                    </div>
                </a>
                <a href="{{ route('cart.index') }}" class="flex items-center gap-3 p-4 bg-surface-900/50 rounded-xl hover:bg-surface-900 transition-all group">
                    <div class="w-10 h-10 bg-accent-500/10 rounded-lg flex items-center justify-center"><i class="fas fa-shopping-cart text-accent-400"></i></div>
                    <div>
                        <p class="font-medium group-hover:text-accent-400 transition-colors">Shopping Cart</p>
                        <p class="text-xs text-surface-200">{{ $cartCount ?? 0 }} items in cart</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
