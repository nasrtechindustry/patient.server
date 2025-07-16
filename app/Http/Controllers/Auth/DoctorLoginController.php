<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorLoginController extends Controller
{
    // Show the login form
    public function showLoginForm()
    {
        return view('auth.doctor-login'); // create this Blade view
    }

    // Handle login post
    public function login(Request $request)
    {
        $credentials = $request->only('name', 'password');

        if (Auth::guard('doctor')->attempt($credentials)) {
            return redirect()->intended(route('doctor.dashboard'));
        }

        return back()->withErrors([
            'name' => 'Invalid credentials',
        ])->withInput();
    }

    // Logout
    public function logout()
    {
        Auth::guard('doctor')->logout();
        return redirect(route('doctor.login'));
    }
}
