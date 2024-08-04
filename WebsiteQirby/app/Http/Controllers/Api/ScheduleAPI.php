<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Data_User;
use App\Models\Jadwal;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ScheduleAPI extends Controller
{
    public function index(): JsonResponse
{
    try {
        $jadwal = Jadwal::with(['pengguna', 'properti.images'])->get();

        return response()->json([
            'status' => true,
            'message' => 'Data ditemukan',
            'jadwal' => $jadwal,
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

public function store(Request $request): JsonResponse
{
    try {
        $this->validate($request, [
            'id_properti' => 'required|exists:property,id',
            'id_pengguna' => 'required|exists:data_user,id',
            'tanggal' => 'required|date',
            'pukul' => 'required|date_format:H:i',
        ]);

        $jadwal = new Jadwal();
        $jadwal->id_properti = $request->input('id_properti');
        $jadwal->id_pengguna = $request->input('id_pengguna');
        $jadwal->tanggal = $request->input('tanggal');
        $jadwal->pukul = $request->input('pukul');
        $jadwal->save();

        return response()->json(['message' => 'Schedule created successfully', 'jadwal' => $jadwal], 201);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

public function update(int $id_jadwal, Request $request): JsonResponse
{
    try {
        $this->validate($request, [
            'tanggal' => 'required|date',
            'pukul' => 'required|date_format:H:i',
        ]);

        $jadwal = Jadwal::find($id_jadwal);

        if ($jadwal) {
            $jadwal->update([
                'tanggal' => $request->input('tanggal'),
                'pukul' => $request->input('pukul'),
            ]);

            return response()->json(['message' => 'Schedule updated successfully', 'jadwal' => $jadwal]);
        }

        return response()->json(['message' => 'Schedule not found'], 404);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

    public function destroy($id_jadwal): JsonResponse
    {
        try {
            $jadwal = Jadwal::where('id_jadwal', $id_jadwal)->first();

            if ($jadwal) {
                $jadwal->delete();
                return response()->json(['message' => 'Schedule deleted successfully']);
            }

            return response()->json(['message' => 'Schedule not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
