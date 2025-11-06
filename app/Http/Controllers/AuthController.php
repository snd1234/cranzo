<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class AuthController extends Controller
{
    //
    public function showLoginForm()
    {
    
        return view('auth.login');
    }
    public function login(Request $request)
    {   
        $credentials = $request->only('email', 'password');
        if (auth()->attempt($credentials)) {
            return redirect()->intended('home');
        }
        return back()->withErrors(['email' => 'Invalid credentials.']); 
        // Validate and process login
    }   
    public function logout(Request $request)
    {
        auth()->logout();
        return redirect('/login');
        // Process logout
    }
}
