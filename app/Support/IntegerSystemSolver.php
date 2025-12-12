<?php

namespace App\Support;

/**
 * Solves a system of linear equations over integers (a Diophantine system)
 * where each coefficient is either 0 or 1.
 *
 * It finds a non-negative integer solution vector `x` for `A*x = b` that minimizes `sum(x)`.
 * The matrix `A` is implicitly defined by the `$coefficientVectors`, where each vector is a row.
 * The vector `b` is `$targets`.
 *
 * This implementation uses a clever recursive approach that solves for the solution
 * vector `x` one bit at a time, from least significant to most significant, using memoization.
 */
class IntegerSystemSolver
{
    private array $memo = [];
    private array $targets;
    private array $coefficientVectors;
    private array $parityPatterns;

    /**
     * @param array $targets The target vector `b`.
     * @param array $coefficientVectors The coefficient matrix `A`, where each element is a vector representing the columns where the value is 1.
     * @return array|null The solution vector `x`, or null if no solution exists.
     */
    public static function solve(array $targets, array $coefficientVectors): ?array
    {
        $solver = new self($targets, $coefficientVectors);
        $result = $solver->solveRecursive($targets);
        return $result['solution'];
    }

    private function __construct(array $targets, array $coefficientVectors)
    {
        $this->targets = $targets;
        $this->coefficientVectors = $coefficientVectors;
        $this->parityPatterns = $this->precomputeParityPatterns();
    }

    private function precomputeParityPatterns(): array
    {
        $numVectors = count($this->coefficientVectors);
        $patterns = [];
        for ($mask = 0; $mask < (1 << $numVectors); $mask++) {
            $parity = $this->computeParity($mask);
            if (!isset($patterns[$parity])) {
                $patterns[$parity] = [];
            }
            $patterns[$parity][] = $mask;
        }
        return $patterns;
    }

    private function computeParity(int $vectorMask): int
    {
        $numTargets = count($this->targets);
        $numVectors = count($this->coefficientVectors);
        $finalParity = 0;

        for ($j = 0; $j < $numTargets; $j++) {
            $paritySum = 0;
            for ($b = 0; $b < $numVectors; $b++) {
                if ((($vectorMask >> $b) & 1) && in_array($j, $this->coefficientVectors[$b])) {
                    $paritySum++;
                }
            }
            $targetParity = $paritySum % 2;
            $finalParity |= ($targetParity << $j);
        }
        return $finalParity;
    }

    private function solveRecursive(array $target): array
    {
        $key = implode(',', $target);
        if (isset($this->memo[$key])) {
            return $this->memo[$key];
        }

        if (count(array_filter($target, fn($t) => $t != 0)) === 0) {
            return ['cost' => 0, 'solution' => array_fill(0, count($this->coefficientVectors), 0)];
        }

        $targetParity = 0;
        foreach ($target as $i => $t) {
            if (($t & 1) === 1) {
                $targetParity |= (1 << $i);
            }
        }

        if (!isset($this->parityPatterns[$targetParity])) {
            return $this->memo[$key] = ['cost' => null, 'solution' => null];
        }

        $possibleSolutions = [];
        foreach ($this->parityPatterns[$targetParity] as $pattern) {
            $newTarget = $this->applyPattern($pattern, $target);
            if ($newTarget === null) {
                continue;
            }

            $subResult = $this->solveRecursive($newTarget);

            if ($subResult['cost'] !== null && $subResult['solution'] !== null) {
                $popCount = substr_count(decbin($pattern), '1');
                $totalCost = $popCount + 2 * $subResult['cost'];
                $solution = $this->combineSolution($pattern, $subResult['solution']);
                $possibleSolutions[] = ['cost' => $totalCost, 'solution' => $solution];
            }
        }
        
        if (empty($possibleSolutions)) {
            return $this->memo[$key] = ['cost' => null, 'solution' => null];
        }
        
        usort($possibleSolutions, fn($a, $b) => $a['cost'] <=> $b['cost']);
        
        return $this->memo[$key] = $possibleSolutions[0];
    }

    private function applyPattern(int $pattern, array $target): ?array
    {
        $newTarget = $target;
        $numVectors = count($this->coefficientVectors);

        for ($j = 0; $j < count($target); $j++) {
            $subtrahend = 0;
            for ($b = 0; $b < $numVectors; $b++) {
                if (((($pattern >> $b) & 1) == 1) && in_array($j, $this->coefficientVectors[$b])) {
                    $subtrahend++;
                }
            }
            $newTarget[$j] -= $subtrahend;
        }

        foreach ($newTarget as $val) {
            if ($val < 0 || ($val & 1) === 1) {
                return null;
            }
        }

        return array_map(fn($x) => $x / 2, $newTarget);
    }

    private function combineSolution(int $pattern, array $subSolution): array
    {
        $solution = $subSolution;
        $numVectors = count($this->coefficientVectors);
        for ($b = 0; $b < $numVectors; $b++) {
            $lsb = ($pattern >> $b) & 1;
            $solution[$b] = $subSolution[$b] * 2 + $lsb;
        }
        return $solution;
    }
}
