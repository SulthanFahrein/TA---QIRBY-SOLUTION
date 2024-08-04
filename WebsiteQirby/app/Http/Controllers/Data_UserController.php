<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Data_user;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;

//mengambil semua data_user dari model Data_user
class Data_UserController extends Controller
{
    public function index()
    {
        $title = 'Data User';
        $users = Data_user::get();

        return view('pages.data-user', compact('title', 'users'));
    }

    //Mengedit data_user dari model Data_user
    public function edit(Request $request)
    {
        if (!$request->ajax())
            abort(403);
        //mncari data_user dalam database berdasarkan ID
        $user = Data_user::find($request->id);

        return response()->json($user);
    }

    //Memperbarui datauser
    public function update(Request $request)
    {
        //Memeriksa apakah permintaan AJAX
        if (!$request->ajax())
            abort(403);

     //Mencari pengguna berdasarkan ID yang diberikan dalam permintaan
        $user = Data_user::find($request->id);

        // Memeriksa apakah pengguna ditemukan
        if (!$user)
            return response()->json(['error' => 'User not found'], 404);

        //Memperbarui data pengguna dengan data yang diterima dari permintaan
        $user->update($request->all());

        // Menampilkan respons JSON kosong yang menandakan keberhasilan
        return response()->json();
    }

    public function destroy($id)
    {
        try {
        // Mencari pengguna berdasarkan ID, atau gagal jika tidak ditemukan
            $user = Data_user::findOrFail($id);

        // Memeriksa apakah pengguna memiliki catatan terkait di tabel Jadwal
            if (Jadwal::where('id_pengguna', $id)->exists()) {
                return response()->json(['error' => 'Cannot delete user with existing bookings.'], 422);
            }

        // Menghapus pengguna
            $user->delete();
            return response()->json(['success' => 'User deleted successfully.']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Jika pengguna tidak ditemukan
            return response()->json(['error' => 'User not found.'], 404);
        } catch (\Illuminate\Database\QueryException $e) {
            // Jika terjadi kesalahan query
            return response()->json(['error' => 'An error occurred while deleting the user.'], 500);
        } catch (\Exception $e) {
            // Jika terjadi kesalahan yang tak terduga
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }
}
