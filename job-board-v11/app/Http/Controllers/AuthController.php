<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('auth.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * This method receives a request object that contains the user's email and password.
     * It validates the input data and then attempts to authenticate the user using the Laravel Auth::attempt method.
     * If the authentication is successful, it redirects the user to the intended page (or the homepage if there is no intended page).
     * If the authentication fails, it redirects the user back to the previous page with an error message.
     *
     * @param \Illuminate\Http\Request $request The request object containing the user's email and password.
     * @return \Illuminate\Http\RedirectResponse The redirect response based on the authentication result.
     */
    public function store(Request $request)
    {
        // Validate the input data
        request()->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Extract the email and password from the request
        $credentials = $request->only('email', 'password');

        // Determine whether the user wants to be remembered
        $remember = $request->filled('remember');

        // Attempt to authenticate the user
        if (Auth::attempt($credentials, $remember)) {
            // Authentication successful, redirect to the intended page or homepage
            return redirect()->intended('/');
        } else {
            // Authentication failed, redirect back with an error message
            return redirect()->back()->with('error', 'Invalid email or password');
        }
    }

    /**
     * Remove the authenticated user from the application.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy()
    {
        // Logout the authenticated user
        Auth::logout();

        // Invalidate the session to prevent fixation attacks
        request()->session()->invalidate();

        // Regenerate the CSRF token
        request()->session()->regenerateToken();

        // Redirect the user to the homepage
        return redirect('/');
    }
}
