<?php

namespace App\Http\Controllers\Branch;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class AuthBranchController extends Controller
{
    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {

            return redirect()->route('branch.dashboard')->with('success', 'تم تسجيل الدخول بنجاح!');

        }
        return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }

    // Handle logout
    public function logout()
    {
        Auth::logout();
        return redirect()->route('company.login')->with('success', 'تم تسجيل الخروج!');
    }

    // Dashboard
    public function dashboard()
    {
        return view('branch.dashboard');
    }
}
