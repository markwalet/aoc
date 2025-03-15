<?php

namespace Tests\Year2015;

use App\Support\Generators\PermutationGenerator;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day13Test extends TestCase
{

    #[Test]
    public function it_can_solve_day_13a_example(): void
    {
        $lines = $this->lines('example');

        $this->assertEquals(['Alice', 'Bob', 'Carol', 'David'], $this->persons($lines)->toArray());
        $score = $this->calculateScore(['David', 'Alice', 'Bob', 'Carol'], $this->influences($lines));
        $this->assertEquals(330, $score);

        $result = $this->solve($this->persons($lines), $this->influences($lines));
        $this->assertEquals(330, $result);
    }

    #[Test]
    public function it_can_solve_day_13a(): void
    {
        $lines = $this->lines();
        $persons = $this->persons($lines);
        $influences = $this->influences($lines);
        $result = $this->solve($persons, $influences);

        $this->assertEquals(618, $result);
    }

    #[Test]
    public function it_can_solve_day_13b(): void
    {
        $lines = $this->lines();
        $persons = $this->persons($lines);
        $persons->push('Mark');
        $influences = $this->influences($lines);
        $result = $this->solve($persons, $influences);

        $this->assertEquals(601, $result);
    }

    private function solve(Collection $persons, Collection $influences): int
    {
        return PermutationGenerator::for($persons->toArray())->generate()
            ->reduce(function (int $max, array $permutation) use ($influences) {
                return max($max, $this->calculateScore($permutation, $influences));
            }, 0);
    }

    private function calculateScore(array $permutation, Collection $influences)
    {
        $count= count($permutation) - 1;
        $score = ($influences[$permutation[$count].'-'.$permutation[0]] ?? 0)
            + ($influences[$permutation[0].'-'.$permutation[$count]] ?? 0);
        for ($i = 0; $i < $count; $i++) {
            $score += $influences[$permutation[$i].'-'.$permutation[$i + 1]] ?? 0;
            $score += $influences[$permutation[$i + 1].'-'.$permutation[$i]] ?? 0;
        }

        return $score;
    }

    private function influences(Collection $lines): Collection
    {
        return $lines->mapWithKeys(function (string $line) {
            [$person, , $direction, $amount, , , , , , , $other] = explode(' ', $line);

            $amount = match ($direction) {
                'gain' => (int) $amount,
                'lose' => -(int) $amount,
            };
            $other = rtrim($other, '.');

            return [$person.'-'.$other => $amount];
        });
    }

    private function persons(Collection $lines): Collection
    {
        return $lines->map(function (string $line) {
            [$person,] = explode(' ', $line, 2);

            return $person;
        })->unique()->values();
    }
}
