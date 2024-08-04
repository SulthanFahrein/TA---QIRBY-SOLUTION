<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\Criterion;
use App\Models\PairwiseMatrix;
use App\Models\Image;
use App\Services\AHP;

class AHPController extends Controller
{
    public function calculateAHP()
    {
        // Ambil data dari database
        $criteria = Criterion::all()->pluck('name')->toArray();
        $properties = Property::with('images')->get();
        $pairwiseMatrices = PairwiseMatrix::all()->pluck('matrix', 'criterion_name')->toArray();

        // Decode JSON matrices
        foreach ($pairwiseMatrices as $key => $matrix) {
            $pairwiseMatrices[$key] = json_decode($matrix, true);
        }

        $propertyNames = $properties->pluck('name')->toArray();
        $ahp = new AHP($criteria, $propertyNames, $pairwiseMatrices);
        $finalScores = $ahp->calculateFinalScores();

        // Gabungkan skor akhir dengan data properti
        $results = $properties->map(function ($property) use ($finalScores) {
            $property->score = $finalScores[$property->name];
            $property->images = $property->images->pluck('image_url');
            return $property;
        });

        return response()->json($results);
    }
}
