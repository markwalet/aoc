<?php

namespace Tests\Year2025\Support;

class IngredientList
{
    private array $ingredients = [];
    private array $freshness = [];

    public function __construct()
    {
    }

    public function addIngredient(int $ingredient): void
    {
        $this->ingredients[] = $ingredient;
    }

    public function addFreshness(int $start, int $end): void
    {
        $this->freshness[] = [$start, $end];
    }

    public function countFreshIngredients(): int
    {
        $count = 0;
        foreach ($this->ingredients as $i) {
            foreach ($this->freshness as $f) {
                if ($i >= $f[0] && $i <= $f[1]) {
                    $count++;
                    continue 2;
                }
            }
        }

        return $count;
    }

    public function countAvailableFresh(): int
    {
        $ranges = $this->freshness;
        usort($ranges, fn ($a, $b): int => $a[0] <=> $b[0]);

        for ($i = 0; $i < count($ranges) - 1; $i++) {
            for ($j = $i + 1; $j < count($ranges); $j++) {
                if ($ranges[$j][0] >= $ranges[$i][0] && $ranges[$j][0] <= $ranges[$i][1]) {
                    $ranges[$i][1] = max($ranges[$i][1], $ranges[$j][1]);
                    unset($ranges[$j]);
                    $ranges = array_values($ranges);
                    $j--;
                }
            }
        }

        $sum = 0;
        foreach($ranges as $r) {
            $sum += $r[1] - $r[0] + 1;
        }

        return $sum;
    }
}
