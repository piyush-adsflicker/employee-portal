<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    public function processLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard')->with('success', 'Welcome back Admin!');
        }

        return back()->with('error', 'Invalid credentials');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }


    // Employee Login
    public function showLoginFormEmp()
    {
        return view('employee.login');
    }

    public function loginEmp(Request $request)
    {
        $request->validate([
            'emp_id' => ['required', 'string'],
            'password' => ['required'],
        ]);

        $credentials = [
            'emp_id' => $request->emp_id,
            'password' => $request->password,
        ];

        // 1. Check if ID and Password match
        if (Auth::attempt($credentials, $request->boolean('remember'))) {

            // 2. Check if the account is active
            if (!Auth::user()->is_active) {
                // Account is suspended/inactive: Log them out immediately
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->with('error', 'Your account is currently inactive or suspended. Please contact HR.')->onlyInput('emp_id');
            }

            // Account is active: Proceed to dashboard
            $request->session()->regenerate();
            return redirect()->intended(route('employee.dashboard'));
        }

        return back()->with('error', 'Invalid Employee ID or password.')->onlyInput('emp_id');
    }

    public function logoutEmp(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
