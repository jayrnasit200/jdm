<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        if (Auth::user()->role !== $role) {
            // Optional redirect logic for mismatched roles
            switch (Auth::user()->role) {
                case 'admin':
                    return redirect()->route('owner.dashboard');
                case 'seller':
                    return redirect()->route('seller.dashboard');
                case 'customer':
                    return redirect()->route('customer.dashboard');
                default:
                    abort(403, 'Unauthorized');
            }
        }

        return $next($request);
    }
}
