@extends('layouts.app')

@section('title', 'Login — TechStore')

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
            <h1 class="text-2xl font-bold">Welcome back</h1>
            <p class="text-surface-200 mt-1">Log in to your account to continue</p>
        </div>

        {{-- Form --}}
        <div class="bg-surface-800/50 border border-white/5 rounded-2xl p-6 sm:p-8">
            <form method="POST" action="{{ route('login') }}" id="login-form">
                @csrf

                <div class="space-y-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-surface-200 mb-1.5">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                               class="w-full px-4 py-3 bg-surface-900 border border-white/5 rounded-xl text-white placeholder:text-surface-200/40 focus:outline-none focus:border-primary-500/30 focus:ring-2 focus:ring-primary-500/10 transition-all"
                               placeholder="your@email.com">
                        @error('email') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-surface-200 mb-1.5">Password</label>
                        <input type="password" name="password" id="password" required
                               class="w-full px-4 py-3 bg-surface-900 border border-white/5 rounded-xl text-white placeholder:text-surface-200/40 focus:outline-none focus:border-primary-500/30 focus:ring-2 focus:ring-primary-500/10 transition-all"
                               placeholder="••••••••">
                        @error('password') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded border-white/10 bg-surface-900 text-primary-600 focus:ring-primary-500/20">
                            <span class="text-sm text-surface-200">Remember me</span>
                        </label>
                    </div>
                </div>

                <button type="submit" class="w-full mt-6 py-3.5 bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-500 hover:to-primary-400 text-white font-semibold rounded-xl transition-all shadow-lg shadow-primary-500/25 hover:shadow-primary-500/40" id="login-btn">
                    <i class="fas fa-sign-in-alt mr-2"></i> Log In
                </button>
            </form>
        </div>

        <p class="text-center text-sm text-surface-200 mt-6">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-primary-400 hover:text-primary-300 font-medium transition-colors">Create one</a>
        </p>
    </div>
</div>
@endsection
