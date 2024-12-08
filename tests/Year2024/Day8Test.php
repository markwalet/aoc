<?php

namespace Tests\Year2024;

use App\Support\Collections\ArrayWithDefault;
use App\Support\Inputs\CharMap;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day8Test extends TestCase
{
    #[Test]
    public function it_can_solve_day_6a(): void
    {
        $map = $this->map();
        $result = $this->countAntinodes($map);

        $this->assertEquals(398, $result);
    }

    #[Test]
    public function it_can_solve_day_6b(): void
    {
        $map = $this->map();
        $result = $this->countAntinodes($map, 0, 200);

        $this->assertEquals(1333, $result);
    }

    private function countAntinodes(CharMap $map, int $repeatStart = 1, int $repeatTo = 1): int
    {
        $cells = [];
        for($x = 0; $x < $map->width; $x++) {
            for ($y = 0; $y < $map->height; $y++) {
                $cell = $map->getCell($y, $x);
                if ($cell->value !== '.') {
                    if (isset($cells[$cell->value]) === false) {
                        $cells[$cell->value] = [];
                    }
                    $cells[$cell->value][] = $cell;
                }
            }
        }

        $antiNodes = [];
        foreach($cells as $group) {
            foreach ($group as $cellA) {
                foreach ($group as $cellB) {
                    if ($cellA->x === $cellB->x && $cellA->y === $cellB->y) {
                        continue;
                    }

                    $diffX = $cellA->x - $cellB->x;
                    $diffY = $cellA->y - $cellB->y;
                    for ($i = $repeatStart; $i <= $repeatTo; $i++) {
                        $x = $cellA->x + $diffX * $i;
                        $y = $cellA->y + $diffY * $i;

                        if ($map->has($y, $x)) {
                            if ($map->get($y, $x) !== '.') {
                                $map->replace($y, $x, '#');
                            }
                            $antiNodes["$y-$x"] = true;
                        } else {
                            break;
                        }
                    }
                }
            }
        }

        return count($antiNodes);
    }
}
