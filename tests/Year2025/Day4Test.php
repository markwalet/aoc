<?php

namespace Tests\Year2025;

use App\Support\Inputs\CharMap;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day4Test extends TestCase
{
    #[Test]
    public function it_can_solve_day_4_example(): void
    {
        $resultA = $this->solve($this->map('example'));
        $resultB = $this->solve($this->map('example'), PHP_INT_MAX);

        $this->assertEquals(13, $resultA);
        $this->assertEquals(43, $resultB);
    }

    #[Test]
    public function it_can_solve_day_4(): void
    {
        $resultA = $this->solve($this->map());
        $resultB = $this->solve($this->map(), PHP_INT_MAX);

        $this->assertEquals(1478, $resultA);
        $this->assertEquals(9120, $resultB);
    }

    private function solve(CharMap $map, int $maxIterations = 1): int
    {
        $score = 0;
        for ($i = 0; $i < $maxIterations; $i++) {
            $cleaning = [];
            for ($x = 0; $x < $map->width; $x++) {
                for ($y = 0; $y < $map->height; $y++) {
                    if ($map->get($y, $x) !== '@') {
                        continue;
                    }
                    $surrounding = 0;
                    foreach ($map->getSurrounding($y, $x) as $s) {
                        if ($s->value === '@') {
                            $surrounding++;
                        }
                        if ($surrounding >= 4) {
                            continue 2;
                        }
                    }
                    $cleaning[] = [$x, $y];
                }
            }

            if (count($cleaning) === 0) {
                return $score;
            }

            $score += count($cleaning);

            foreach ($cleaning as $c) {
                $map->replace($c[1], $c[0], '.');
            }
        }

        return $score;
    }
}
