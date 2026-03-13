<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CompareController extends Controller
{
    /**
     * Show the comparison page.
     */
    public function index()
    {
        $compareIds = session('compare', []);
        $products = Product::with('category')
            ->whereIn('id', $compareIds)
            ->get();

        // Build a union of all spec keys across selected products
        $allSpecs = [];
        foreach ($products as $product) {
            if ($product->specifications) {
                foreach ($product->specifications as $key => $value) {
                    if (!in_array($key, $allSpecs)) {
                        $allSpecs[] = $key;
                    }
                }
            }
        }

        return view('compare.index', compact('products', 'allSpecs'));
    }

    /**
     * Toggle a product in the compare list (max 3).
     */
    public function toggle(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        $compare = session('compare', []);

        if (in_array($productId, $compare)) {
            $compare = array_values(array_diff($compare, [$productId]));
            session(['compare' => $compare]);

            if ($request->wantsJson()) {
                return response()->json(['action' => 'removed', 'count' => count($compare)]);
            }
            return back()->with('success', $product->name . ' removed from comparison.');
        }

        if (count($compare) >= 3) {
            if ($request->wantsJson()) {
                return response()->json(['action' => 'full', 'count' => count($compare)], 422);
            }
            return back()->with('error', 'You can compare up to 3 products at a time.');
        }

        $compare[] = (int)$productId;
        session(['compare' => $compare]);

        if ($request->wantsJson()) {
            return response()->json(['action' => 'added', 'count' => count($compare)]);
        }
        return back()->with('success', $product->name . ' added to comparison.');
    }

    /**
     * Clear the compare list.
     */
    public function clear()
    {
        session()->forget('compare');
        return redirect()->route('compare.index')->with('success', 'Comparison list cleared.');
    }
}
