<?php
require_once 'ahp.php';

// Definisikan kriteria dan alternatif
$criteria = ['Facility', 'Access', 'Distance', 'Price'];
$alternatives = ['Option 1', 'Option 2', 'Option 3', 'Option 4'];

// Definisikan matriks perbandingan berpasangan
$pairwiseMatrices = [
    'criteria' => [
        [1, 2, 3, 4],
        [1/2, 1, 3/2, 4/2],
        [1/3, 2/3, 1, 4/3],
        [1/4, 2/4, 3/4, 1]
    ],
    'Facility' => [
        [1, 3, 5, 7],
        [1/3, 1, 2, 4],
        [1/5, 1/2, 1, 3],
        [1/7, 1/4, 1/3, 1]
    ],
    'Access' => [
        [1, 1/2, 4, 2],
        [2, 1, 7, 5],
        [1/4, 1/7, 1, 1/3],
        [1/2, 1/5, 3, 1]
    ],
    'Distance' => [
        [1, 3, 5, 2],
        [1/3, 1, 3, 1],
        [1/5, 1/3, 1, 1/2],
        [1/2, 1, 2, 1]
    ],
    'Price' => [
        [1, 2, 1/3, 3],
        [1/2, 1, 1/5, 2],
        [3, 5, 1, 4],
        [1/3, 1/2, 1/4, 1]
    ]
];

$ahp = new AHP($criteria, $alternatives, $pairwiseMatrices);
$finalScores = $ahp->calculateFinalScores();

echo "Final Scores:\n";
foreach ($finalScores as $alternative => $score) {
    echo "$alternative: $score\n";
}
