<?php

namespace Tests\Year2025;

use App\Support\Collections\ArrayWithDefault;
use App\Support\Inputs\CharMap;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day7Test extends TestCase
{
    #[Test]
    public function it_can_solve_day_6_example(): void
    {
        $resultA = $this->countSplits($this->map('example'));
        $resultB = $this->countSplits($this->map('example'), true);

        $this->assertEquals(21, $resultA);
        $this->assertEquals(40, $resultB);
    }

    #[Test]
    public function it_can_solve_day_6(): void
    {
        $resultA = $this->countSplits($this->map());
        $resultB = $this->countSplits($this->map(), true);

        $this->assertEquals(1524, $resultA);
        $this->assertEquals(32982105837605, $resultB);
    }

    private function countSplits(CharMap $map, bool $countBeams = false): int
    {
        for ($x = 0; $x < $map->width; $x++) {
            if ($map->get(0, $x) === 'S') {
                break;
            }
        }

        $beams = new ArrayWithDefault(default: 0);
        $beams[$x] = 1;

        $splits = 0;
        for ($y = 0; $y < $map->height; $y++) {
            $newBeams = $beams;
            foreach ($beams->toArray() as $b => $count) {
                if ($map->get($y, $b) === '^') {
                    $splits += $countBeams ? $count : 1;
                    $newBeams[$b - 1] = $newBeams[$b - 1] + $beams[$b];
                    $newBeams[$b + 1] = $newBeams[$b + 1] + $beams[$b];
                    unset($newBeams[$b]);
                } else {
                    $newBeams[$b] = $beams[$b];
                }
            }
            $beams = $newBeams;
        }

        return $countBeams ? array_sum($beams->toArray()) : $splits;
    }

    private function countTimelines(CharMap $map, int|null $x = null, int $y = 0)
    {
        if ($x === null) {
            for ($x = 0; $x < $map->width; $x++) {
                if ($map->get(0, $x) === 'S') {
                    break;
                }
            }
        }

        for ($i = $y; $i < $map->height; $i++) {
            if ($map->get($i, $x) === '^') {
                return 1
                    + $this->countTimelines($map, $x - 1, $i)
                    + $this->countTimelines($map, $x + 1, $i);
            }
        }

        return 0;
    }

}
