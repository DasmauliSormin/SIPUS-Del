<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            // Wajib regenerate session ID demi keamanan
            $request->session()->regenerate();

            // Menyimpan cookie HttpOnly untuk audit sesi pustakawan
            Cookie::queue('last_pustakawan_login', Auth::user()->email, 60 * 24, null, null, false, true);

            return redirect()->intended(route('buku.index'))->with('success', 'Selamat datang kembali, Pustakawan Del!');
        }

        return back()->withErrors([
            'email' => 'Kredensial email atau password pustakawan salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        // Wajib invalidate & regenerate CSRF token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Berhasil keluar dari sistem.');
    }
}