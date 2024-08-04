<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Data_User;
use App\Models\Favorite;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FavoriteAPI extends Controller
{
    public function index(): JsonResponse
{
    try {
        $favorite = Favorite::with(['pengguna', 'properti.images'])->get();

        return response()->json([
            'status' => true,
            'message' => 'Data ditemukan',
            'favorite' => $favorite,
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
        ]);

        $favorite = new Favorite();
        $favorite->id_properti = $request->input('id_properti');
        $favorite->id_pengguna = $request->input('id_pengguna');
        $favorite->save();

        return response()->json(['message' => 'Favorite created successfully', 'favorite' => $favorite], 201);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}


    public function destroy($id_favorite): JsonResponse
    {
        try {
            $favorite = Favorite::where('id_favorite', $id_favorite)->first();

            if ($favorite) {
                $favorite->delete();
                return response()->json(['message' => 'Favorite deleted successfully']);
            }

            return response()->json(['message' => 'Favorite not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
