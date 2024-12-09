<?php

namespace Tests\Year2024;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day7Test extends TestCase
{
    #[Test]
    public function it_can_solve_day_7a(): void
    {
        $result = $this->lines()
            ->map(fn (string $line) => $this->sumValidEquation($line, false))
            ->sum();

        $this->assertEquals(1620690235709, $result);
    }

    #[Test]
    public function it_can_solve_day_7b(): void
    {
        $result = $this->lines()
            ->map(fn (string $line) => $this->sumValidEquation($line, true))
            ->sum();

        $this->assertEquals(145397611075341, $result);
    }

    private function sumValidEquation(string $line, bool $concat): int
    {
        [$sum, $parts] = explode(': ', $line, 2);
        $parts = array_map('intval', explode(' ', $parts));
        $sum = (int) $sum;

        $first = array_shift($parts);
        if ($this->validateSums($sum, $parts, $first, $concat)) {
            return $sum;
        }

        return 0;
    }

    private function validateSums(int $sum, array $parts, int $result, bool $concat): bool
    {
        if (count($parts) === 0) {
            return $sum === $result;
        } elseif ($result > $sum) {
            return false;
        }

        $newPart = array_shift($parts);

        return $this->validateSums($sum, $parts, $result + $newPart, $concat)
            || $this->validateSums($sum, $parts, $result * $newPart, $concat)
            || ($concat && $this->validateSums($sum, $parts, $result.$newPart, true));
    }
}
