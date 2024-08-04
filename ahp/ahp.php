<?php
class AHP {
    private $criteria;
    private $alternatives;
    private $pairwiseMatrices;

    public function __construct($criteria, $alternatives, $pairwiseMatrices) {
        $this->criteria = $criteria;
        $this->alternatives = $alternatives;
        $this->pairwiseMatrices = $pairwiseMatrices;
    }

    public function calculateWeights($matrix) {
        $size = count($matrix);
        $weights = array_fill(0, $size, 0);
        $columnSums = array_fill(0, $size, 0);

        // Calculate column sums
        for ($i = 0; $i < $size; $i++) {
            for ($j = 0; $j < $size; $j++) {
                $columnSums[$i] += $matrix[$j][$i];
            }
        }

        // Normalize the matrix
        for ($i = 0; $i < $size; $i++) {
            for ($j = 0; $j < $size; $j++) {
                $matrix[$j][$i] /= $columnSums[$i];
            }
        }

        // Calculate the weights
        for ($i = 0; $i < $size; $i++) {
            for ($j = 0; $j < $size; $j++) {
                $weights[$i] += $matrix[$i][$j];
            }
            $weights[$i] /= $size;
        }

        return $weights;
    }

    public function calculateConsistencyRatio($matrix, $weights) {
        $size = count($matrix);
        $lambdaMax = 0;
        for ($i = 0; $i < $size; $i++) {
            $sum = 0;
            for ($j = 0; $j < $size; $j++) {
                $sum += $matrix[$i][$j] * $weights[$j];
            }
            $lambdaMax += $sum / $weights[$i];
        }
        $lambdaMax /= $size;
        $ci = ($lambdaMax - $size) / ($size - 1);
        $ri = [0, 0.58, 0.9, 1.12, 1.24, 1.32, 1.41, 1.45, 1.49, 1.51, 1.48, 1.56, 1.57, 1.59]; // Random index values for matrix sizes

        $cr = $ci / $ri[$size];
        return $cr;
    }

    public function calculateFinalScores() {
        $criteriaWeights = $this->calculateWeights($this->pairwiseMatrices['criteria']);
        $alternativeScores = array_fill_keys($this->alternatives, 0);

        foreach ($this->criteria as $index => $criterion) {
            $alternativeMatrix = $this->pairwiseMatrices[$criterion];
            $alternativeWeights = $this->calculateWeights($alternativeMatrix);
            foreach ($this->alternatives as $altIndex => $alternative) {
                $alternativeScores[$alternative] += $criteriaWeights[$index] * $alternativeWeights[$altIndex];
            }
        }

        return $alternativeScores;
    }
}
