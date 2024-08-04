<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Property;
use App\Models\Image;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use MatanYadaev\EloquentSpatial\Objects\Point;
use App\Models\Jadwal;

;
class PropertyController extends Controller
{
    public function index()
    {
        // Query untuk mendapatkan data properti dengan koordinat X dan Y
        $property = Property::query()->select([
            '*',
            DB::raw("st_x(koordinat) as koordinat_x"),
            DB::raw("st_y(koordinat) as koordinat_y")
        ]);
        // Mengambil data properti
        $property = $property->get();
        // Mengambil data image
        $images = Image::all();
        // Jika segment pertama dari URL adalah 'api', kembalikan data dalam format JSON
        if (request()->segment(1) == 'api')
            return response()->json([
                'error' => false,
                'data' => $property
            ]);
    
        // Jika segment pertama bukan 'api', kembalikan view
        return view('pages.management', ['property' => $property, 'images' => $images]);
    }


    public function store(Request $request)
    {
        // Mengambil data dari request kecuali '_token', 'koordinat-x', dan 'koordinat-y'
        $data = $request->except(['_token', 'koordinat-x', 'koordinat-y']);
        // Mengubah koordinat dari string ke Point
        $data['koordinat'] = new Point($request->input('koordinat-x'), $request->input('koordinat-y'));

        // Validasi input request
        $request->validate([
            'name' => 'required|string',
            'price' => 'required',
            'status' => 'required',
            'address' => 'required',
            'description' => 'required',
            'sqft' => 'required|integer',
            'bath' => 'required|integer',
            'garage' => 'required|integer',
            'floor' => 'required|integer',
            'bed' => 'required|integer',
        ]);

        // Membuat properti baru
        $new_property = Property::create($data);
    // Jika ada gambar dalam request, simpan masing-masing gambar
        if ($request->has('images')) {
            foreach ($request->file('images') as $image) {
              // Membuat nama gambar yang unik
                $original_image_property = Str::random(10) . $image->getClientOriginalName();
                // Simpan gambar ke folder public/images_property
                $image->storeAs('public/images_property', $original_image_property);
                // Simpan gambar ke database
                Image::create([
                    'property_id' => $new_property->id,
                    'image' => $original_image_property
                ]);
            }
        }
        // Redirect ke halaman properti
        return redirect()->route('property.view')->with('success', 'Property added');
    }

    public function update(Request $request, $id)
    {
        // Mengambil data dari request kecuali '_token', 'koordinat-x', dan 'koordinat-y'
        $data = $request->except(['_token', 'koordinat-x', 'koordinat-y']);
        // Membuat koordinat dari string ke Point
        $data['koordinat'] = new Point($request->input('koordinat-x'), $request->input('koordinat-y'));
        // Validasi input
        $request->validate([
            'image.*' => 'mimes:jpeg,jpg,png',
            'name' => 'required|string',
            'price' => 'required',
            'status' => 'required',
            'description' => 'required',
            'sqft' => 'required|integer',
            'bath' => 'required|integer',
            'garage' => 'required|integer',
            'floor' => 'required|integer',
            'bed' => 'required|integer',
        ]);
        // Mengambil properti berdasarkan ID
        $properties = Property::find($id);
        // Jika ada gambar dalam request, simpan masing-masing gambar
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $image) {
                // Membuat nama gambar yang unik
                $original_image_property = Str::random(10) . $image->getClientOriginalName();
                // Simpan gambar ke folder public/images_property
                $image->storeAs('public/images_property', $original_image_property);
                // Simpan gambar ke database
                Image::create([
                    'property_id' => $properties->id,
                    'image' => $original_image_property
                ]);
            }
        }

        // Mengupdate properti
        $properties->update($data);
        // Redirect ke halaman properti
        return redirect()->route('property.view')->with('success', 'Property updated');
    }

    public function deleteImage($imageId)
    {
        $image = Image::findOrFail($imageId);
        Storage::disk('public')->delete('images_property/' . $image->image);
        $image->delete();

        return redirect()->back()->with('success', 'Image deleted.');
    }

    public function deleted($id)
    {
        // Find the property by ID
        try {
            $property = Property::findOrFail($id);

            // Memanggil semua gambar yang terkait dengan properti
            $images = $property->images;
            // Jika ada jadwal di properti, kembalikan respons dengan pesan kesalahan
            if (Jadwal::where('id_properti', $id)->exists()) {
                return response()->json(['error' => 'Cannot delete property with existing schedules.'], 422);
            }
            // Menghapus properti
            $property->delete();

            // Jika ada gambar yang terkait dengan properti, hapus semua gambar tersebut
            if ($images->isNotEmpty()) {
                foreach ($images as $image) {
                    // Hapus gambar dari folder public/images_property
                    Storage::disk('public')->delete('images_property/' . $image->image);
                    $image->delete();
                }
            }

            return response()->json(['success' => 'Property deleted successfully.']);
        // Jika properti tidak ditemukan, kembalikan respons dengan pesan kesalahan
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Property not found.'], 404);
        // Jika terjadi kesalahan query, kembalikan respons dengan pesan kesalahan
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['error' => 'An error occurred while deleting the property.'], 500);
            // Jika terjadi kesalahan yang tak terduga, kembalikan respons dengan pesan kesalahan
        } catch (\Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }



}


