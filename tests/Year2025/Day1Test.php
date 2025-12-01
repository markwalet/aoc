<?php

namespace Tests\Year2025;

use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;
use RuntimeException;
use Tests\TestCase;

class Day1Test extends TestCase
{
    #[Test]
    public function it_can_solve_day_1_example(): void
    {
        $resultA = $this->dialVault(50, $this->lines('example'), false);
        $resultB = $this->dialVault(50, $this->lines('example'), true);

        $this->assertEquals(3, $resultA);
        $this->assertEquals(6, $resultB);
    }

    #[Test]
    public function it_can_solve_day_1(): void
    {
        $resultA = $this->dialVault(50, $this->lines(), false);
        $resultB = $this->dialVault(50, $this->lines(), true);

        $this->assertEquals(1191, $resultA);
        $this->assertEquals(6858, $resultB);
    }

    private function dialVault(int $start, Collection $steps, bool $countEveryClick): int
    {
        $steps = $steps->map(fn (string $step) => match ($step[0]) {
            'R' => (int) substr($step, 1),
            'L' => -(int) substr($step, 1),
            default => throw new RuntimeException('Cannot parse step: '.$step),
        });

        $position = $start;
        $passed = $dialed = 0;
        foreach ($steps as $step) {
            $start = $position;
            $position = $position + ($step % 100);
            $crossing = false;

            if ($position < 0) {
                $position += 100;
                if ($position !== 0 && $start !== 0) {
                    $crossing = true;
                }
            }
            while ($position >= 100) {
                $position -= 100;
                if ($position !== 0 && $start !== 0) {
                    $crossing = true;
                }
            }

            $passed += floor(abs($step / 100)) + ($crossing ? 1 : 0);
            $dialed += $position === 0 ? 1 : 0;
        }

        return $countEveryClick ? $passed + $dialed : $dialed;
    }

}
