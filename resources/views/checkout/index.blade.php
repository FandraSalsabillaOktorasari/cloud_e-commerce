@extends('layouts.app')

@section('title', 'Checkout — TechStore')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold mb-8">Checkout</h1>

    <form method="POST" action="{{ route('checkout.store') }}" id="checkout-form">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- ═══════ Shipping Form ═══════ --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-surface-800/50 border border-white/5 rounded-2xl p-6">
                    <h2 class="text-lg font-semibold mb-5 flex items-center gap-2">
                        <i class="fas fa-truck text-primary-400"></i> Shipping Information
                    </h2>

                    <div class="space-y-4">
                        {{-- Full Name --}}
                        <div>
                            <label for="shipping_name" class="block text-sm font-medium text-surface-200 mb-1.5">Full Name</label>
                            <input type="text" name="shipping_name" id="shipping_name" value="{{ old('shipping_name', Auth::user()->name) }}" required
                                   class="w-full px-4 py-3 bg-surface-900 border border-white/5 rounded-xl text-white placeholder:text-surface-200/40 focus:outline-none focus:border-primary-500/30 focus:ring-2 focus:ring-primary-500/10 transition-all">
                            @error('shipping_name') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Phone --}}
                        <div>
                            <label for="shipping_phone" class="block text-sm font-medium text-surface-200 mb-1.5">Phone Number</label>
                            <input type="tel" name="shipping_phone" id="shipping_phone" value="{{ old('shipping_phone') }}" required placeholder="+62 8xx-xxxx-xxxx"
                                   class="w-full px-4 py-3 bg-surface-900 border border-white/5 rounded-xl text-white placeholder:text-surface-200/40 focus:outline-none focus:border-primary-500/30 focus:ring-2 focus:ring-primary-500/10 transition-all">
                            @error('shipping_phone') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Address --}}
                        <div>
                            <label for="shipping_address" class="block text-sm font-medium text-surface-200 mb-1.5">Address</label>
                            <textarea name="shipping_address" id="shipping_address" rows="3" required placeholder="Street address, building, apartment..."
                                      class="w-full px-4 py-3 bg-surface-900 border border-white/5 rounded-xl text-white placeholder:text-surface-200/40 focus:outline-none focus:border-primary-500/30 focus:ring-2 focus:ring-primary-500/10 transition-all resize-none">{{ old('shipping_address') }}</textarea>
                            @error('shipping_address') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            {{-- City --}}
                            <div>
                                <label for="shipping_city" class="block text-sm font-medium text-surface-200 mb-1.5">City</label>
                                <input type="text" name="shipping_city" id="shipping_city" value="{{ old('shipping_city') }}" required
                                       class="w-full px-4 py-3 bg-surface-900 border border-white/5 rounded-xl text-white placeholder:text-surface-200/40 focus:outline-none focus:border-primary-500/30 focus:ring-2 focus:ring-primary-500/10 transition-all">
                                @error('shipping_city') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Postal Code --}}
                            <div>
                                <label for="shipping_postal_code" class="block text-sm font-medium text-surface-200 mb-1.5">Postal Code</label>
                                <input type="text" name="shipping_postal_code" id="shipping_postal_code" value="{{ old('shipping_postal_code') }}" required
                                       class="w-full px-4 py-3 bg-surface-900 border border-white/5 rounded-xl text-white placeholder:text-surface-200/40 focus:outline-none focus:border-primary-500/30 focus:ring-2 focus:ring-primary-500/10 transition-all">
                                @error('shipping_postal_code') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Notes --}}
                        <div>
                            <label for="notes" class="block text-sm font-medium text-surface-200 mb-1.5">Notes <span class="text-surface-200/40">(optional)</span></label>
                            <textarea name="notes" id="notes" rows="2" placeholder="Any special delivery instructions..."
                                      class="w-full px-4 py-3 bg-surface-900 border border-white/5 rounded-xl text-white placeholder:text-surface-200/40 focus:outline-none focus:border-primary-500/30 focus:ring-2 focus:ring-primary-500/10 transition-all resize-none">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Payment Method --}}
                <div class="bg-surface-800/50 border border-white/5 rounded-2xl p-6">
                    <h2 class="text-lg font-semibold mb-5 flex items-center gap-2">
                        <i class="fas fa-credit-card text-primary-400"></i> Payment Method
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3" x-data="{ selected: '{{ old('payment_method', 'bank_transfer') }}' }">
                        @foreach(['bank_transfer' => ['Bank Transfer', 'fa-university'], 'credit_card' => ['Credit Card', 'fa-credit-card'], 'e_wallet' => ['E-Wallet', 'fa-wallet']] as $value => [$label, $icon])
                            <label @click="selected = '{{ $value }}'" :class="selected === '{{ $value }}' ? 'border-primary-500 bg-primary-500/5' : 'border-white/5 hover:border-white/10'"
                                   class="flex items-center gap-3 p-4 border rounded-xl cursor-pointer transition-all">
                                <input type="radio" name="payment_method" value="{{ $value }}" x-model="selected" class="hidden">
                                <div :class="selected === '{{ $value }}' ? 'bg-primary-500 border-primary-500' : 'border-white/20'"
                                     class="w-5 h-5 border-2 rounded-full flex items-center justify-center shrink-0 transition-all">
                                    <div x-show="selected === '{{ $value }}'" class="w-2 h-2 bg-white rounded-full"></div>
                                </div>
                                <i class="fas {{ $icon }} text-surface-200"></i>
                                <span class="text-sm font-medium">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('payment_method') <p class="text-red-400 text-sm mt-2">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- ═══════ Order Summary ═══════ --}}
            <div>
                <div class="bg-surface-800/50 border border-white/5 rounded-2xl p-6 sticky top-24">
                    <h2 class="text-lg font-semibold mb-5">Order Summary</h2>

                    <div class="space-y-3 mb-6 max-h-64 overflow-y-auto pr-2">
                        @foreach($cart->items as $item)
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-surface-800 rounded-lg overflow-hidden shrink-0">
                                    @if($item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}" alt="" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center"><i class="fas fa-laptop text-xs text-surface-200/30"></i></div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium truncate">{{ $item->product->name }}</p>
                                    <p class="text-xs text-surface-200">× {{ $item->quantity }}</p>
                                </div>
                                <p class="text-sm font-medium shrink-0">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                            </div>
                        @endforeach
                    </div>

                    <div class="space-y-3 border-t border-white/5 pt-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-surface-200">Subtotal</span>
                            <span class="font-medium">Rp {{ number_format($cart->total, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-surface-200">Shipping</span>
                            <span class="font-medium">Rp {{ number_format(15000, 0, ',', '.') }}</span>
                        </div>
                        <hr class="border-white/5">
                        <div class="flex justify-between">
                            <span class="text-lg font-semibold">Total</span>
                            <span class="text-xl font-black bg-gradient-to-r from-primary-400 to-accent-400 bg-clip-text text-transparent">Rp {{ number_format($cart->total + 15000, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <button type="submit" class="w-full mt-6 py-3.5 bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-500 hover:to-primary-400 text-white font-semibold rounded-xl transition-all shadow-lg shadow-primary-500/25 hover:shadow-primary-500/40" id="place-order-btn">
                        <i class="fas fa-lock mr-2"></i> Place Order
                    </button>

                    <p class="text-xs text-center text-surface-200/40 mt-3">
                        <i class="fas fa-shield-alt mr-1"></i> Secure checkout — your data is protected
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
