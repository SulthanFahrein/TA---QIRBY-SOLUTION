<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

// memperbarui profil pengguna
class AdminController extends Controller
{
    public function update(Request $request)
    {
        $id = Auth::user()->getAuthIdentifier();

        $nama = $request->input('nama');
        $emailAddress = $request->input('email-address');
        $password = Hash::make($request->input('password'));

        User::find($id)->update([
            'name' => $nama,
            'email' => $emailAddress,
            'password' => $password
        ]);

        return to_route('admin')->with('success', 'Your profile has been updated');
    }
}
