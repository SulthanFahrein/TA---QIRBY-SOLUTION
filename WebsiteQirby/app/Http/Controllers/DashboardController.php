<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use Illuminate\Http\Request;
use App\Models\Property;


//menghitung dan menampilkan jumlah jadwal dan properti berdasarkan status tertentu, 
//kemudian mengirimkan data tersebut ke tampilan (view).
class DashboardController extends Controller
{
    public function schedule()
    {
        $jumlahScheduleAccept = Jadwal::query()->where('jadwal_diterima', '=', 'accept')->count();
        $jumlahSchedulePending = Jadwal::query()->where('jadwal_diterima', '=', 'pending')->count();
        $jumlahScheduleDone = Jadwal::query()->where('jadwal_diterima', '=', 'done')->count();
        $jumlahScheduleReject = Jadwal::query()->where('jadwal_diterima', '=', 'reject')->count();
        $jadwal = Jadwal::count();

        // Replace these with your actual logic for counting properties
        $jumlahPropertiPending = Property::query()->where('status', '=', 'pending')->count();
        $jumlahPropertiTerjual = Property::query()->where('status', '=', 'sold')->count();
        $jumlahPropertiTersedia = Property::query()->where('status', '=', 'ready')->count();
        $totalProperties = Property::count();

        return view('pages.home', [
            'jumlah_Schedule_Accept' => $jumlahScheduleAccept,
            'jumlah_Schedule_Pending' => $jumlahSchedulePending,
            'jumlah_Schedule_Done' => $jumlahScheduleDone,
            'jumlah_Schedule_Reject' => $jumlahScheduleReject,
            'jadwal' => $jadwal,
            'jumlah_properti_pending' => $jumlahPropertiPending,
            'jumlah_properti_terjual' => $jumlahPropertiTerjual,
            'jumlah_properti_tersedia' => $jumlahPropertiTersedia,
            'property' => $totalProperties
        ]);
    }

}
