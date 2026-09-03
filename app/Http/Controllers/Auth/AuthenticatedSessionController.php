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
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        $role = Auth::user()->role;

        return match ($role) {
            'admin' => redirect()->route('dashboard')->with('success', "Login Done Successfully"),
            'waiter' => redirect()->route('orders.index')->with('success', "Login Done Successfully"),
            'cashier' => redirect()->route('billing_page')->with('success', "Login Done Successfully"),
            'kitchen_staff' => redirect()->route('kitchen')->with('success', "Login Done Successfully"),

            // default => route('home'),
        };
    }
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            return redirect()->intended('/dashboard');
        }

        return back()->with('error','Invalid credentials');
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
}
