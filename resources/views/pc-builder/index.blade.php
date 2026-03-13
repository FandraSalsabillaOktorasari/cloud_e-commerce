@extends('layouts.app')

@section('title', 'PC Builder — TechStore')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="pcBuilder()">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl sm:text-4xl font-bold"><i class="fas fa-wrench text-primary-400 mr-2"></i>PC Builder</h1>
            <p class="text-surface-200 mt-1">Pick your parts and we'll check compatibility</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('pc-builder.clear') }}" class="px-4 py-2 bg-red-500/10 border border-red-500/20 text-red-400 text-sm font-medium rounded-xl hover:bg-red-500/20 transition-all">
                <i class="fas fa-trash mr-1"></i> Clear Build
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Component Slots --}}
        <div class="lg:col-span-2 space-y-4">
            @php
                $slotIcons = ['cpu' => 'microchip', 'motherboard' => 'server', 'ram' => 'memory', 'gpu' => 'tv', 'storage' => 'hdd', 'psu' => 'bolt'];
                $slotNames = ['cpu' => 'Processor (CPU)', 'motherboard' => 'Motherboard', 'ram' => 'Memory (RAM)', 'gpu' => 'Graphics Card (GPU)', 'storage' => 'Storage (SSD/HDD)', 'psu' => 'Power Supply (PSU)'];
            @endphp

            @foreach($slots as $slot)
                <div class="bg-surface-800/50 border border-white/5 rounded-2xl p-5 transition-all hover:border-primary-500/10"
                     :class="hasWarning('{{ $slot }}') ? 'border-red-500/30 bg-red-500/5' : ''">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-primary-500/10 rounded-xl flex items-center justify-center">
                                <i class="fas fa-{{ $slotIcons[$slot] }} text-primary-400 text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold uppercase tracking-wide text-surface-200">{{ $slotNames[$slot] }}</h3>
                                @if($selectedParts[$slot])
                                    <p class="text-white font-medium">{{ $selectedParts[$slot]->name }}</p>
                                    <p class="text-sm text-primary-400">Rp {{ number_format($selectedParts[$slot]->price, 0, ',', '.') }}</p>
                                @else
                                    <p class="text-surface-200/50 text-sm">No component selected</p>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            @if($selectedParts[$slot])
                                <form method="POST" action="{{ route('pc-builder.remove', $slot) }}">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-red-400/50 hover:text-red-400 transition-colors">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            @endif
                            <button @click="openSelector('{{ $slot }}')"
                                    class="px-4 py-2 bg-primary-600/20 border border-primary-500/20 text-primary-400 text-sm font-medium rounded-xl hover:bg-primary-600 hover:text-white transition-all">
                                <i class="fas fa-{{ $selectedParts[$slot] ? 'exchange-alt' : 'plus' }} mr-1"></i>
                                {{ $selectedParts[$slot] ? 'Change' : 'Select' }}
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Summary Sidebar --}}
        <div class="space-y-6">
            {{-- Price Summary --}}
            <div class="bg-surface-800/50 border border-white/5 rounded-2xl p-6 sticky top-24">
                <h2 class="text-lg font-semibold mb-4"><i class="fas fa-calculator text-primary-400 mr-2"></i>Build Summary</h2>
                <div class="space-y-2 mb-4">
                    @foreach($slots as $slot)
                        <div class="flex justify-between text-sm">
                            <span class="text-surface-200">{{ $slotNames[$slot] }}</span>
                            <span class="{{ $selectedParts[$slot] ? 'text-white font-medium' : 'text-surface-200/30' }}">
                                {{ $selectedParts[$slot] ? 'Rp ' . number_format($selectedParts[$slot]->price, 0, ',', '.') : '—' }}
                            </span>
                        </div>
                    @endforeach
                </div>
                <div class="border-t border-white/10 pt-4 mb-4">
                    <div class="flex justify-between items-center mb-1 text-sm">
                        <span class="text-surface-200">Estimated Wattage</span>
                        <span class="text-white font-medium" x-text="estimatedWattage + 'W'">{{ $estimatedWattage }}W</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-semibold">Total</span>
                        <span class="text-2xl font-bold bg-gradient-to-r from-primary-400 to-accent-400 bg-clip-text text-transparent" x-text="formattedPrice">
                            Rp {{ number_format($totalPrice, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <form method="POST" action="{{ route('pc-builder.add-all-to-cart') }}">
                    @csrf
                    <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-primary-600 to-primary-500 text-white font-semibold rounded-xl hover:from-primary-500 hover:to-primary-400 transition-all shadow-lg shadow-primary-500/25 disabled:opacity-50 disabled:cursor-not-allowed" {{ $totalPrice <= 0 ? 'disabled' : '' }}>
                        <i class="fas fa-cart-plus mr-2"></i> Add All to Cart
                    </button>
                </form>
            </div>

            {{-- Compatibility Warnings --}}
            @if(count($warnings))
                <div class="bg-surface-800/50 border border-white/5 rounded-2xl p-6">
                    <h2 class="text-lg font-semibold mb-4"><i class="fas fa-exclamation-triangle text-yellow-400 mr-2"></i>Compatibility</h2>
                    <div class="space-y-3">
                        @foreach($warnings as $w)
                            <div class="flex items-start gap-3 text-sm">
                                <i class="fas fa-{{ $w['type'] === 'error' ? 'times-circle text-red-400' : ($w['type'] === 'warning' ? 'exclamation-triangle text-yellow-400' : 'info-circle text-blue-400') }} mt-0.5"></i>
                                <span class="{{ $w['type'] === 'error' ? 'text-red-300' : ($w['type'] === 'warning' ? 'text-yellow-300' : 'text-blue-300') }}">
                                    {{ $w['message'] }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Component Selector Modal --}}
    <div x-show="selectorOpen" x-transition.opacity class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4" @click.self="selectorOpen = false" style="display: none;">
        <div x-show="selectorOpen" x-transition class="bg-surface-900 border border-white/10 rounded-2xl w-full max-w-2xl max-h-[80vh] overflow-hidden shadow-2xl">
            <div class="p-6 border-b border-white/5 flex items-center justify-between">
                <h2 class="text-lg font-semibold">Select <span x-text="slotName" class="text-primary-400"></span></h2>
                <button @click="selectorOpen = false" class="p-2 hover:bg-white/5 rounded-lg transition-all"><i class="fas fa-times text-surface-200"></i></button>
            </div>
            <div class="p-6 overflow-y-auto max-h-[60vh]">
                <div x-show="loading" class="text-center py-10"><i class="fas fa-spinner fa-spin text-2xl text-primary-400"></i></div>
                <template x-if="!loading && products.length === 0">
                    <div class="text-center py-10 text-surface-200">No components available for this slot.</div>
                </template>
                <div x-show="!loading" class="space-y-3">
                    <template x-for="product in products" :key="product.id">
                        <div class="flex items-center justify-between bg-surface-800/50 border border-white/5 rounded-xl p-4 hover:border-primary-500/20 transition-all cursor-pointer"
                             @click="selectProduct(product)">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 bg-surface-800 rounded-lg overflow-hidden flex-shrink-0">
                                    <template x-if="product.image_url">
                                        <img :src="product.image_url" :alt="product.name" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!product.image_url">
                                        <div class="w-full h-full flex items-center justify-center"><i class="fas fa-microchip text-surface-200/20"></i></div>
                                    </template>
                                </div>
                                <div>
                                    <p class="font-medium text-sm text-white" x-text="product.name"></p>
                                    <p class="text-xs text-surface-200" x-text="product.brand"></p>
                                    <div class="flex gap-2 mt-1">
                                        <template x-if="product.socket_type"><span class="text-[10px] text-primary-400 bg-primary-500/10 px-1.5 py-0.5 rounded" x-text="product.socket_type"></span></template>
                                        <template x-if="product.memory_type"><span class="text-[10px] text-blue-400 bg-blue-500/10 px-1.5 py-0.5 rounded" x-text="product.memory_type"></span></template>
                                        <template x-if="product.tdp_watts"><span class="text-[10px] text-orange-400 bg-orange-500/10 px-1.5 py-0.5 rounded" x-text="product.tdp_watts + 'W'"></span></template>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-primary-400 text-sm" x-text="product.formatted_price"></p>
                                <p class="text-[10px] text-surface-200" x-text="product.stock + ' in stock'"></p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function pcBuilder() {
        return {
            selectorOpen: false,
            currentSlot: '',
            slotName: '',
            products: [],
            loading: false,
            warnings: @json($warnings),
            totalPrice: {{ $totalPrice }},
            formattedPrice: 'Rp {{ number_format($totalPrice, 0, ',', '.') }}',
            estimatedWattage: {{ $estimatedWattage }},

            hasWarning(slot) {
                return this.warnings.some(w => w.type === 'error' && w.parts && w.parts.includes(slot));
            },

            openSelector(slot) {
                const names = {cpu:'Processor',motherboard:'Motherboard',ram:'Memory',gpu:'Graphics Card',storage:'Storage',psu:'Power Supply'};
                this.currentSlot = slot;
                this.slotName = names[slot] || slot;
                this.selectorOpen = true;
                this.loading = true;
                this.products = [];

                fetch(`/pc-builder/products/${slot}`)
                    .then(r => r.json())
                    .then(data => { this.products = data; this.loading = false; })
                    .catch(() => { this.loading = false; });
            },

            selectProduct(product) {
                fetch(`/pc-builder/add/${this.currentSlot}`, {
                    method: 'POST',
                    headers: {'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content},
                    body: JSON.stringify({product_id: product.id}),
                }).then(() => {
                    this.selectorOpen = false;
                    window.location.reload();
                });
            }
        }
    }
</script>
@endpush
@endsection
