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
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            // Regenerasi ID Sesi Wajib untuk Keamanan
            $request->session()->regenerate();
            
            session(['login_timestamp' => now()->format('d M Y, H:i:s') . ' WIB']);
            Cookie::queue('del_last_pustakawan_email', $request->email, 10080);

            return redirect()->route('buku.index');
        }

        return back()->with('error', 'Kredensial email atau password tidak valid.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect langsung tanpa membawa flash message success
        return redirect()->route('login');
    }
}