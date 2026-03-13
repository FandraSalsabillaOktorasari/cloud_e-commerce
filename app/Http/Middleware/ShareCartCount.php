<?php

namespace App\Http\Middleware;

use App\Models\Cart;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class ShareCartCount
{
    /**
     * Share the cart items count with all views.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $cartCount = 0;

        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->with('items')->first();
        } else {
            $cart = Cart::where('session_id', session()->getId())->with('items')->first();
        }

        if ($cart) {
            $cartCount = $cart->items->sum('quantity');
        }

        View::share('cartCount', $cartCount);

        return $next($request);
    }
}
