<?php

namespace Tests\Year2024;

use App\Support\Inputs\CharCell;
use App\Support\Inputs\CharMap;
use App\Support\Navigation\Direction;
use App\Support\Vectors\Vector2;
use PHPUnit\Framework\Attributes\Test;
use RuntimeException;
use Tests\TestCase;

class Day6Test extends TestCase
{
    #[Test]
    public function it_can_solve_day_6a(): void
    {
        $map = $this->map();
        $count = $this->getPath($map);

        $this->assertEquals(5461, $count);
    }

    #[Test]
    public function it_can_check_if_a_path_is_a_loop(): void
    {
        $map = $this->map('example');
        $this->assertNotEquals(-1, $this->getPath($map));
        $map->replace(7, 6, '#');
        $this->assertEquals(-1, $this->getPath($map));
    }

    #[Test]
    public function it_can_solve_day_6b(): void
    {
        $map = $this->map();
        $routeSize = $this->findLoops($map);

        $this->assertEquals(1836, $routeSize);
    }

    private function getPath(CharMap $map, Vector2|null $start = null, Direction|null $direction = null, array|null $path = null): int
    {
        $position = $start ?? $this->getStartingPosition($map);
        $direction = $direction ?? Direction::UP;
        $path = $path ?? [
            "$position->x-$position->y" => [$direction->value],
        ];

        while (true) {
            $newPosition = $position->add($direction->vector());
            while ($map->has($newPosition->y, $newPosition->x) && $map->get($newPosition->y, $newPosition->x) === '#') {
                $direction = $direction->turnRight();
                $newPosition = $position->add($direction->vector());
            }
            $position = $newPosition;
            if ($map->has($position->y, $position->x) === false) {
                return count($path);
            }
            if (array_key_exists("$position->x-$position->y", $path) && in_array($direction->value, $path["$position->x-$position->y"])) {
                return -1;
            }

            $path["$position->x-$position->y"][] = $direction->value;
        }
    }

    private function getStartingPosition(CharMap $map): Vector2
    {
        for ($x = 0; $x < $map->width; $x++) {
            for ($y = 0; $y < $map->height; $y++) {
                if ($map->get($y, $x) === '^') {
                    return new Vector2($x, $y);
                }
            }
        }

        throw new RuntimeException('No starting position found');
    }

    private function findLoops(CharMap $map): int
    {
        $position = $start = $this->getStartingPosition($map);
        $direction = $startDirection = Direction::UP;

        $loops = [];
        $visited = [];

        while (true) {
            $previousPosition = $position;
            $previousDirection = $direction;
            $newPosition = $position->add($direction->vector());
            while ($map->has($newPosition->y, $newPosition->x) && $map->get($newPosition->y, $newPosition->x) === '#') {
                $direction = $direction->turnRight();
                $newPosition = $position->add($direction->vector());
            }
            $position = $newPosition;

            if ($map->has($position->y, $position->x) === false) {
                return count($loops);
            }
            if ($position->is($start) || isset($visited["$position->x-$position->y"])) {
                continue;
            }
            $map->replace($position->y, $position->x, '#');
            if ($this->getPath($map, $previousPosition, $previousDirection, $visited) === -1) {
                $loops["$position->x-$position->y"] = true;
            }
            $visited["$position->x-$position->y"][] = $direction;
            $map->replace($position->y, $position->x, '.');
        }
    }

}
