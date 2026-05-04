<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController 
{
    /**
     * Show login form.
     *
     * @return \Illuminate\View\View
     */
    public function login()
    {
        return view('auth.login');
    }

    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function authenticate(Request $request)
    {
        // 1. Validate the input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Attempt to log the user in
        if (Auth::attempt($credentials)) {
            // Prevent session fixation attacks
            $request->session()->regenerate();

            // Redirect to /tickets (your support dashboard)
            return redirect()->intended('/tickets')
                ->with('success', 'Welcome back! You are now logged in.');
        }

        // 3. Return with error if authentication fails
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // Clear the session data
        $request->session()->invalidate();

        // Regenerate the CSRF token to prevent cross-site request forgery
        $request->session()->regenerateToken();

        // Redirect back to login with a success message
        return redirect('/login')->with('success', 'You have been logged out successfully.');
    }
}