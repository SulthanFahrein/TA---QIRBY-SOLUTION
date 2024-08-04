<?php

namespace App\Http\Controllers;

use App\Models\Data_User;
use App\Models\Jadwal;
use App\Models\Property;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ScheduleController extends Controller
{
    public function indeks(): Factory|\Illuminate\Foundation\Application|View|Application
    { //mengambil semua jadwal
        $jadwal = Jadwal::all();
        //mengambil semua data_user
        $pengguna = Data_User::all();
        //mengambil semua property
        $properti = Property::all();
        //menampilkan jadwal, pengguna, dan properti
        return view('pages.schedule', [
            'jadwal' => $jadwal,
            'pengguna' => $pengguna,
            'properti' => $properti
        ]);
    }

    public function tambah(Request $request): RedirectResponse
    {
        $pengguna = $request->input('pengguna');
        $properti = $request->input('properti');
        $tanggal = $request->input('tanggal');
        $pukul = $request->input('pukul');
        $catatan = $request->input('catatan');

        // Membuat a new jadwal
        $jadwal = new Jadwal();
        // Menetapkan nilai untuk atribut jadwal
        $jadwal->{'id_pengguna'} = $pengguna;
        $jadwal->{'id_properti'} = $properti;
        $jadwal->{'tanggal'} = $tanggal;
        $jadwal->{'pukul'} = $pukul;
        $jadwal->{'catatan'} = $catatan;

        // menyimpan jadwal
        $jadwal->save();
        // Redirect to schedule page
        return to_route('schedule');
    }

    public function update(int $id, Request $request)
    {
        // Mencari jadwal berdasarkan ID
        $jadwal = Jadwal::findOrFail($id);

        // Validate input data
        $validatedData = $request->validate([
            'pic' => '',
            'catatan' => '',
            'status' => 'required|in:accept,reject,done', // Assuming jadwal_diterima can be 'accept', 'reject', or 'done'
        ]);

        // merubah jadwal
        $jadwal->pic = $validatedData['pic'];
        $jadwal->catatan = $validatedData['catatan'];
        $jadwal->jadwal_diterima = $validatedData['status'];

        // Save the updated schedule
        $jadwal->save();

        // Redirect back with success message
        return redirect()->route('schedule')->with('success', 'Schedule updated successfully');
    }


    public function hapus(int $id)
{
    $jadwal = Jadwal::find($id);

    if ($jadwal) {
        $jadwal->delete();

        // Check if the request is AJAX
        if (request()->ajax()) {
            return response()->json(['success' => 'Schedule deleted']);
        }

        // Redirect for non-AJAX requests
        return Redirect::route('schedule')->with('success', 'Schedule deleted');
    }

    // Return JSON error response for AJAX
    if (request()->ajax()) {
        return response()->json(['error' => 'Schedule not found'], 404);
    }

    // Redirect for non-AJAX requests
    return Redirect::route('schedule')->with('error', 'Schedule not found');
}
}
