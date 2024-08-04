<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Login_Controller extends Controller
{
    public function index()
    {
        return view('pages.landing');
    }

    public function authenticate(Request $request)
    {
        // Validasi input
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        // Ambil username dan password dari request
        $credentials = $request->only('username', 'password');

        // Mencoba melakukan otentikasi
        if (Auth::attempt($credentials)) {
            // Jika berhasil, regenerasi sesi
            $request->session()->regenerate();

         // Redirect ke dashboard admin
            return redirect()->route('admin.dashboard');
        }

        // Jika otentikasi gagal, kembalikan ke halaman login dengan pesan error
        return back()->withErrors([
            'error' => 'Your credentials are wrong'
        ])->withInput();

        // dd($credentials);
    }

}
