<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CompatibilityService;
use Illuminate\Http\Request;

class PcBuilderController extends Controller
{
    public function __construct(
        protected CompatibilityService $compatibilityService,
    ) {}

    /**
     * Show the PC Builder interface.
     */
    public function index()
    {
        $slots = CompatibilityService::SLOTS;
        $build = session('pc_build', []);

        // Load selected products
        $selectedParts = [];
        foreach ($slots as $slot) {
            $selectedParts[$slot] = isset($build[$slot]) ? Product::find($build[$slot]) : null;
        }

        // Check compatibility
        $warnings = $this->compatibilityService->checkCompatibility($selectedParts);

        // Calculate total price and wattage
        $totalPrice = collect($selectedParts)->filter()->sum('price');
        $estimatedWattage = $this->compatibilityService->getEstimatedWattage($selectedParts);

        return view('pc-builder.index', compact('slots', 'selectedParts', 'warnings', 'totalPrice', 'estimatedWattage'));
    }

    /**
     * Get available products for a specific slot (JSON endpoint).
     */
    public function products(Request $request, string $slot)
    {
        if (!in_array($slot, CompatibilityService::SLOTS)) {
            return response()->json(['error' => 'Invalid slot'], 422);
        }

        $build = session('pc_build', []);
        $query = Product::where('component_type', $slot)->where('stock', '>', 0);

        // DYNAMIC FILTERING LOGIC
        // If a Motherboard or CPU is already selected, filter the other slot to match sockets
        if ($slot === 'motherboard' && isset($build['cpu'])) {
            $cpu = Product::find($build['cpu']);
            if ($cpu) {
                $query->where('socket_type', $cpu->socket_type);
            }
        } elseif ($slot === 'cpu' && isset($build['motherboard'])) {
            $mobo = Product::find($build['motherboard']);
            if ($mobo) {
                $query->where('socket_type', $mobo->socket_type);
            }
        }

        // If a Motherboard or RAM is already selected, filter by memory type
        if ($slot === 'ram' && isset($build['motherboard'])) {
            $mobo = Product::find($build['motherboard']);
            if ($mobo) {
                $query->where('memory_type', $mobo->memory_type);
            }
        } elseif ($slot === 'motherboard' && isset($build['ram'])) {
            $ram = Product::find($build['ram']);
            if ($ram) {
                $query->where('memory_type', $ram->memory_type);
            }
        }

        $products = $query->select('id', 'name', 'brand', 'price', 'image', 'socket_type', 'chipset', 'memory_type', 'form_factor', 'tdp_watts', 'stock')
            ->orderBy('price')
            ->get()
            ->map(function ($p) {
                $p->formatted_price = 'Rp ' . number_format($p->price, 0, ',', '.');
                $p->image_url = $p->image ? asset('storage/' . $p->image) : null;
                return $p;
            });

        return response()->json($products);
    }

    /**
     * Add a part to a build slot.
     */
    public function addPart(Request $request, string $slot)
    {
        if (!in_array($slot, CompatibilityService::SLOTS)) {
            return response()->json(['error' => 'Invalid slot'], 422);
        }

        $request->validate(['product_id' => 'required|exists:products,id']);

        $build = session('pc_build', []);
        $build[$slot] = $request->product_id;
        session(['pc_build' => $build]);

        return response()->json(['success' => true]);
    }

    /**
     * Remove a part from a build slot.
     */
    public function removePart(string $slot)
    {
        $build = session('pc_build', []);
        unset($build[$slot]);
        session(['pc_build' => $build]);

        return response()->json(['success' => true]);
    }

    /**
     * Check compatibility of current build.
     */
    public function check()
    {
        $build = session('pc_build', []);
        $selectedParts = [];
        foreach (CompatibilityService::SLOTS as $slot) {
            $selectedParts[$slot] = isset($build[$slot]) ? Product::find($build[$slot]) : null;
        }

        $warnings = $this->compatibilityService->checkCompatibility($selectedParts);
        $totalPrice = collect($selectedParts)->filter()->sum('price');
        $wattage = $this->compatibilityService->getEstimatedWattage($selectedParts);

        return response()->json([
            'warnings' => $warnings,
            'totalPrice' => $totalPrice,
            'formattedPrice' => 'Rp ' . number_format($totalPrice, 0, ',', '.'),
            'estimatedWattage' => $wattage,
        ]);
    }

    /**
     * Add all parts in the build to the cart.
     */
    public function addAllToCart(Request $request)
    {
        $build = session('pc_build', []);
        if (empty($build)) {
            return back()->with('error', 'Your build is empty.');
        }

        foreach ($build as $slot => $productId) {
            app(\App\Http\Controllers\CartController::class)->add(
                new Request(['product_id' => $productId, 'quantity' => 1])
            );
        }

        session()->forget('pc_build');
        return redirect()->route('cart.index')->with('success', 'All parts added to cart!');
    }

    /**
     * Clear the build.
     */
    public function clear()
    {
        session()->forget('pc_build');
        return redirect()->route('pc-builder.index')->with('success', 'Build cleared.');
    }
}
