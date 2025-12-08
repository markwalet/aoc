<?php

namespace Tests\Year2020;

use App\Support\Inputs\CharMap;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day3Test extends TestCase
{
    #[Test]
    public function it_can_solve_day_3(): void
    {
        $map = $this->map();
        $resultA = $this->countTrees($map, [1, 3]);
        $resultB = 1;
        foreach([[1, 1], [1, 3], [1, 5], [1, 7], [2, 1]] as $slope) {
            $resultB *= $this->countTrees($map, $slope);
        }

        $this->assertEquals(191, $resultA);
        $this->assertEquals(1478615040, $resultB);
    }

    private function countTrees(CharMap $map, array $slope): int
    {
        for ($row = 0, $col = 0, $count = 0; $row < $map->height; $row += $slope[0], $col = ($col + $slope[1]) % $map->width) {
            if ($map->get($row, $col) === '#') {
                $count++;
            }
        }

        return $count;
    }

}
