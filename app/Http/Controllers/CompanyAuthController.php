<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class CompanyAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.company.login');
    }

    // Handle login
    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {

            return redirect()->route('company.dashboard')->with('success', 'Logged in successfully!');

        }
dd("MO");
        return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }

    // Handle logout
    public function logout()
    {
        Auth::logout();
        return redirect()->route('company.login')->with('success', 'Logged out successfully!');
    }

    // Dashboard
    public function dashboard()
    {
        return view('campany.dashboard');
    }
}
