<?php

namespace Tests\Year2025;

use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Throwable;

class Day3Test extends TestCase
{
    #[Test]
    public function it_can_solve_day_3_example(): void
    {
        $rows = $this->lines('example');
        $maxA = $this->sumMaximumJoltage($rows, 2);
        $maxB = $this->sumMaximumJoltage($rows, 12);

        $this->assertEquals(357, $maxA);
        $this->assertEquals(3121910778619, $maxB);
    }

    #[Test]
    public function it_can_solve_day_3(): void
    {
        $rows = $this->lines();
        $maxA = $this->sumMaximumJoltage($rows, 2);
        $maxB = $this->sumMaximumJoltage($rows, 12);

        $this->assertEquals(17316, $maxA);
        $this->assertEquals(171741365473332, $maxB);
    }

    private function sumMaximumJoltage(Collection $lines, int $batteryCount): int
    {
        return $lines->sum(function (string $line) use ($batteryCount) {
            $batteries = array_map('intval', str_split($line));

            return $this->maximumJoltage($batteries, $batteryCount);
        });
    }

    private function maximumJoltage(array $batteries, int $remaining): int
    {
        $sorted = $batteries;
        arsort($sorted, SORT_NUMERIC);
        $keys = array_keys($sorted);

        for ($i = 0; $i < count($keys); $i++) {
            $sliced = array_values(array_slice($batteries, $keys[$i] + 1));

            if (count($sliced) >= $remaining - 1) {
                return $remaining === 1
                    ? $sorted[$keys[$i]]
                    : $sorted[$keys[$i]] * pow(10, $remaining - 1) + $this->maximumJoltage($sliced, $remaining - 1);
            }
        }
    }
}
