<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();
        $user = Auth::user();

        if ($user->role === 'owner') {
            return redirect()->route('owner.dashboard');
        } elseif ($user->role === 'seller') {
            return redirect()->route('seller.dashboard');
        } elseif ($user->role === 'customer') {
            return redirect()->route('customer.dashboard');
        }

        // fallback
        return redirect('/');
        // return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
    protected function redirectTo() {
        $user = auth()->user();
        if ($user->isOwner()) return '/owner';
        if ($user->isSeller()) return '/seller';
        return '/customer';
    }
    protected function authenticated(Request $request, $user)
    {
        switch ($user->role) {
            case 'customer':
                return redirect()->route('customer.dashboard');
            case 'seller':
                return redirect()->route('seller.dashboard');
            case 'owner':
                return redirect()->route('owner.dashboard');
            default:
                return redirect('/'); // fallback
        }
    }
}
