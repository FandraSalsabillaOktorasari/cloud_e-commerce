@extends('layouts.app')

@section('title', 'Register — TechStore')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
        {{-- Header --}}
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mb-6 group">
                <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-accent-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-microchip text-white"></i>
                </div>
                <span class="text-2xl font-bold bg-gradient-to-r from-primary-400 to-accent-400 bg-clip-text text-transparent">TechStore</span>
            </a>
            <h1 class="text-2xl font-bold">Create an account</h1>
            <p class="text-surface-200 mt-1">Join TechStore and start shopping</p>
        </div>

        {{-- Form --}}
        <div class="bg-surface-800/50 border border-white/5 rounded-2xl p-6 sm:p-8">
            <form method="POST" action="{{ route('register') }}" id="register-form">
                @csrf

                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-surface-200 mb-1.5">Full Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                               class="w-full px-4 py-3 bg-surface-900 border border-white/5 rounded-xl text-white placeholder:text-surface-200/40 focus:outline-none focus:border-primary-500/30 focus:ring-2 focus:ring-primary-500/10 transition-all"
                               placeholder="John Doe">
                        @error('name') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-surface-200 mb-1.5">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                               class="w-full px-4 py-3 bg-surface-900 border border-white/5 rounded-xl text-white placeholder:text-surface-200/40 focus:outline-none focus:border-primary-500/30 focus:ring-2 focus:ring-primary-500/10 transition-all"
                               placeholder="your@email.com">
                        @error('email') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-surface-200 mb-1.5">Password</label>
                        <input type="password" name="password" id="password" required
                               class="w-full px-4 py-3 bg-surface-900 border border-white/5 rounded-xl text-white placeholder:text-surface-200/40 focus:outline-none focus:border-primary-500/30 focus:ring-2 focus:ring-primary-500/10 transition-all"
                               placeholder="Minimum 8 characters">
                        @error('password') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-surface-200 mb-1.5">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                               class="w-full px-4 py-3 bg-surface-900 border border-white/5 rounded-xl text-white placeholder:text-surface-200/40 focus:outline-none focus:border-primary-500/30 focus:ring-2 focus:ring-primary-500/10 transition-all"
                               placeholder="Repeat your password">
                    </div>
                </div>

                <button type="submit" class="w-full mt-6 py-3.5 bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-500 hover:to-primary-400 text-white font-semibold rounded-xl transition-all shadow-lg shadow-primary-500/25 hover:shadow-primary-500/40" id="register-btn">
                    <i class="fas fa-user-plus mr-2"></i> Create Account
                </button>
            </form>
        </div>

        <p class="text-center text-sm text-surface-200 mt-6">
            Already have an account?
            <a href="{{ route('login') }}" class="text-primary-400 hover:text-primary-300 font-medium transition-colors">Log in</a>
        </p>
    </div>
</div>
@endsection
