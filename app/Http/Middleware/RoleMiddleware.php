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
        if (! Auth::check()) {
            return redirect('/login');
        }

        $userRole = Auth::user()->role;
        $effectiveRole = $userRole === 'admin' ? 'owner' : $userRole;

        if ($effectiveRole === $role) {
            return $next($request);
        }

        return match ($effectiveRole) {
            'owner'    => redirect()->route('owner.dashboard'),
            'seller'   => redirect()->route('seller.dashboard'),
            'customer' => redirect()->route('customer.dashboard'),
            default    => abort(403, 'Unauthorized'),
        };
    }
}
