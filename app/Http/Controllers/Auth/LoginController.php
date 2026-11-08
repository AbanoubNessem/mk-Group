<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Try to authenticate using username (email or name field)
        $credentials = $request->only('username', 'password');
        
        // Check if username is an email or regular username
        $field = filter_var($credentials['username'], FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
        
        $attempt = Auth::attempt([
            $field => $credentials['username'],
            'password' => $credentials['password']
        ], $request->boolean('remember'));

        if ($attempt) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard.index'));
        }

        throw ValidationException::withMessages([
            'username' => ['بيانات الدخول غير صحيحة.'],
        ]);
    }

    /**
     * Handle a logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }
}



