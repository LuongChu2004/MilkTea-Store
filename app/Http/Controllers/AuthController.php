<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $username = $request->username;
        $password = $request->password;

        if ($username === 'Admin' && $password === '1010') {
            // Admin logic
            session(['admin_logged_in' => true]);
            return redirect('/admin');
        }

        $user = User::where('username', $username)->first();

        if ($user && $user->password === $password) { // Plain text fallback
            Auth::login($user);
            return redirect('/');
        }

        return back()->with('error', 'Tài khoản hoặc mật khẩu không chính xác!');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users',
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required',
            'password' => 'required|min:4'
        ]);

        $user = User::create([
            'username' => $request->username,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => $request->password // Keeping plain text to match legacy for now
        ]);

        Auth::login($user);
        return redirect('/')->with('success', 'Đăng ký thành công!');
    }

    public function logout()
    {
        Auth::logout();
        session()->forget('admin_logged_in');
        return redirect('/');
    }
}
