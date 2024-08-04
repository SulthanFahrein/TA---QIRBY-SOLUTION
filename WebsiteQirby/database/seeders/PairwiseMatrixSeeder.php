<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PairwiseMatrix;

class PairwiseMatrixSeeder extends Seeder
{
    public function run()
    {
        $pairwiseMatrices = [
            'criteria' => [
                [1, 2, 3, 4],
                [1 / 2, 1, 3 / 2, 4 / 2],
                [1 / 3, 2 / 3, 1, 4 / 3],
                [1 / 4, 2 / 4, 3 / 4, 1]
            ],
            'Facility' => [
                [1, 3, 5, 7],
                [1 / 3, 1, 2, 4],
                [1 / 5, 1 / 2, 1, 3],
                [1 / 7, 1 / 4, 1 / 3, 1]
            ],
            'Access' => [
                [1, 1 / 2, 4, 2],
                [2, 1, 7, 5],
                [1 / 4, 1 / 7, 1, 1 / 3],
                [1 / 2, 1 / 5, 3, 1]
            ],
            'Distance' => [
                [1, 3, 5, 2],
                [1 / 3, 1, 3, 1],
                [1 / 5, 1 / 3, 1, 1 / 2],
                [1 / 2, 1, 2, 1]
            ],
            'Price' => [
                [1, 2, 1 / 3, 3],
                [1 / 2, 1, 1 / 5, 2],
                [3, 5, 1, 4],
                [1 / 3, 1 / 2, 1 / 4, 1]
            ]
        ];

        foreach ($pairwiseMatrices as $criterion => $matrix) {
            PairwiseMatrix::create([
                'criterion_name' => $criterion,
                'matrix' => json_encode($matrix)
            ]);
        }
    }
}
