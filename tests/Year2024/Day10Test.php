<?php

namespace Tests\Year2024;

use App\Support\Inputs\CharCell;
use App\Support\Inputs\CharMap;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day10Test extends TestCase
{
    #[Test]
    public function it_can_solve_the_example(): void
    {
        $map = $this->map('example');
        $trailheads = $this->getTrailheads($map);

        $this->assertCount(9, $trailheads);

        $uniqueScore = $trailheads->map(fn (CharCell $cell) => $this->scoreTrailhead($cell, $map))->sum();
        $score = $trailheads->map(fn (CharCell $cell) => $this->scoreTrailhead($cell, $map, false))->sum();

        $this->assertEquals(36, $uniqueScore);
        $this->assertEquals(81, $score);
    }

    #[Test]
    public function it_can_solve_day_10a(): void
    {
        $map = $this->map();
        $trailheads = $this->getTrailheads($map);
        $score = $trailheads->map(fn (CharCell $cell) => $this->scoreTrailhead($cell, $map))->sum();

        $this->assertEquals(566, $score);
    }

    #[Test]
    public function it_can_solve_day_10b(): void
    {
        $map = $this->map();
        $trailheads = $this->getTrailheads($map);
        $score = $trailheads->map(fn (CharCell $cell) => $this->scoreTrailhead($cell, $map, false))->sum();

        $this->assertEquals(1324, $score);
    }

    private function getTrailheads(CharMap $map): Collection
    {
        $cells = collect();
        for($y = 0; $y < $map->height; $y++) {
            for($x = 0; $x < $map->width; $x++) {
                if ($map->get($y, $x) === '0') {
                    $cells->push($map->getcell($y, $x));
                }
            }
        }

        return $cells;
    }

    private function scoreTrailhead(CharCell $cell, CharMap $map, bool $countUnique = true): int
    {
        $unique = [];
        $count = 0;
        foreach($this->getTops($cell, $map) as $top) {
            $count++;
            $unique["$top->x-$top->y"] = $top;
        }

        return $countUnique ? count($unique) : $count;
    }

    private function getTops(CharCell $cell, CharMap $map)
    {
        if ($cell->value == '9') {
            yield $cell;
        } else {
            $wanted = (int) $cell->value + 1;

            $coords = [
                [$cell->y - 1, $cell->x],
                [$cell->y + 1, $cell->x],
                [$cell->y, $cell->x - 1],
                [$cell->y, $cell->x + 1],
            ];

            foreach($coords as $c) {
                if ($map->has($c[0], $c[1])) {
                    if ($map->get($c[0], $c[1]) == $wanted) {
                        foreach ($this->getTops($map->getCell($c[0], $c[1]), $map) as $top) {
                            yield $top;
                        }
                    }
                }
            }
        }


    }
}
