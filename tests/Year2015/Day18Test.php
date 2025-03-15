<?php

namespace Tests\Year2015;

use App\Support\Inputs\CharCell;
use App\Support\Inputs\CharMap;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day18Test extends TestCase
{
    #[Test]
    public function it_can_solve_day_18a_example(): void
    {
        $lines = $this->lines('example')->map(fn (string $line) => str_split($line));
        $map = new CharMap($lines->toArray());

        $this->simulate($map, 4);

        $this->assertEquals([
            ['.', '.', '.', '.', '.', '.'],
            ['.', '.', '.', '.', '.', '.'],
            ['.', '.', '#', '#', '.', '.'],
            ['.', '.', '#', '#', '.', '.'],
            ['.', '.', '.', '.', '.', '.'],
            ['.', '.', '.', '.', '.', '.'],
        ], $map->lines);
    }
    #[Test]
    public function it_can_solve_day_18a(): void
    {
        $lines = $this->lines()->map(fn (string $line) => str_split($line));
        $map = new CharMap($lines->toArray());

        $this->simulate($map, 100);
        $result = $map->count('#');

        $this->assertEquals(814, $result);
    }

    #[Test]
    public function it_can_solve_day_18b(): void
    {
        $lines = $this->lines()->map(fn (string $line) => str_split($line));
        $map = new CharMap($lines->toArray());

        $map->replace(0, 0, '#');
        $map->replace(0, $map->width - 1, '#');
        $map->replace($map->height - 1, $map->width - 1, '#');
        $map->replace($map->height - 1, 0, '#');
        for ($i = 0; $i < 100; $i++) {
            $this->step($map);
            $map->replace(0, 0, '#');
            $map->replace(0, $map->width - 1, '#');
            $map->replace($map->height - 1, $map->width - 1, '#');
            $map->replace($map->height - 1, 0, '#');
        }
        $result = $map->count('#');

        $this->assertEquals(924, $result);
    }

    private function simulate(CharMap $map, int $steps): void
    {
        for ($i = 0; $i < $steps; $i++) {
            $this->step($map);
        }
    }

    private function step(CharMap $map)
    {
        $replacements = [];
        for ($x = 0; $x < $map->width; $x++) {
            for ($y = 0; $y < $map->height; $y++) {
                $activeNeighbours = count(array_filter($map->getSurrounding($y, $x), fn (CharCell $cell) => $cell->value === '#'));
                if ($map->get($y, $x) === '#') {
                    if ($activeNeighbours !== 2 && $activeNeighbours !== 3) {
                        $replacements[] = [$y, $x, '.'];
                    }
                } else {
                    if ($activeNeighbours === 3) {
                        $replacements[] = [$y, $x, '#'];
                    }
                }
            }
        }

        foreach($replacements as $replacement) {
            $map->replace($replacement[0], $replacement[1], $replacement[2]);
        }
    }
}
