<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertyAPI extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Property::with('images')->orderBy('name', 'asc')->get();
        return response()->json([
            'status' => true,
            'message' => 'Data ditemukan',
            'data' => $data
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $dataProperty = new Property;
        $dataProperty->name = $request->name;
        $dataProperty->price = $request->price;
        $dataProperty->status = $request->status;
        $dataProperty->koordinat = $request->koordinat;
        $dataProperty->address = $request->address;
        $dataProperty->description = $request->description;
        $dataProperty->sqft = $request->sqft;
        $dataProperty->bath = $request->bath;
        $dataProperty->garage = $request->garage;
        $dataProperty->floor = $request->floor;
        $dataProperty->bed = $request->bed;

        $post =$dataProperty->save();
        
        if ($request->has('images')) {
            foreach ($request->images as $image) {
                Image::create([
                    'property_id' => $dataProperty->id,
                    'image' => $image
                ]);
            }
        }
        return response()->json([
            'status' => true,
            'message' => 'Sukses memasukkan data'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Property::with('images')->find($id);
        if ($data) {
            return response()->json([
                'status' => true,
                'message' => 'Data Ditemukan',
                'data' => $data
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }

    

}
