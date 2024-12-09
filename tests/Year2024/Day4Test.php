<?php

namespace Tests\Year2024;

use App\Support\Inputs\CharMap;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day4Test extends TestCase
{
    #[Test]
    public function it_can_solve_the_example(): void
    {
        $score = $this->searchXmas($this->map('example'));

        $this->assertEquals(18, $score);
    }

    #[Test]
    public function it_can_solve_day_4a(): void
    {
        $score = $this->searchXmas($this->map());

        $this->assertEquals(2644, $score);
    }

    #[Test]
    public function it_can_solve_day_4b(): void
    {
        $score = $this->searchXmas2($this->map());

        $this->assertEquals(1952, $score);
    }

    private function searchXmas(CharMap $map): int
    {
        $directions = [[-1, -1], [-1, 0,], [-1, 1], [0, -1], [0, 1], [1, -1], [1, 0], [1, 1]];
        $score = 0;
        for ($x = 0; $x < $map->width; $x++) {
            for ($y = 0; $y < $map->height; $y++) {
                foreach ($directions as $direction) {
                    if ($map->get($x, $y) === 'X'
                        && $map->has($x + $direction[0] * 3, $y + $direction[1] * 3)
                        && $map->get($x + $direction[0] * 1, $y + $direction[1] * 1) === 'M'
                        && $map->get($x + $direction[0] * 2, $y + $direction[1] * 2) === 'A'
                        && $map->get($x + $direction[0] * 3, $y + $direction[1] * 3) === 'S') {
                        $score++;
                    }
                }
            }
        }

        return $score;
    }

    private function searchXmas2(CharMap $map): int
    {
        $score = 0;
        for ($x = 0; $x < $map->width; $x++) {
            for ($y = 0; $y < $map->height; $y++) {
                if ($map->get($y, $x) !== 'A') {
                    continue;
                }

                $n = $map->getSurrounding($y, $x);

                if (count($n) !== 8) {
                    continue;
                }

                if (($n[0]->value === 'M' && $n[7]->value === 'S') || $n[0]->value === 'S' && $n[7]->value === 'M') {
                    if (($n[2]->value === 'M' && $n[5]->value === 'S') || ($n[2]->value === 'S' && $n[5]->value === 'M')) {
                        $score++;
                    }
                }
            }
        }

        return $score;
    }
}
