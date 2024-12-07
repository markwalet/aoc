<?php

namespace Tests\Year2024;

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
        [$path, $looping] = $this->getPath($map);

        $this->assertCount(5461, $path);
        $this->assertFalse($looping);
    }

    #[Test]
    public function it_can_check_if_a_path_is_a_loop(): void
    {
        $map = $this->map('example');
        $this->assertFalse($this->getPath($map)[1]);
        $map->replace(7, 6, '#');
        $this->assertTrue($this->getPath($map)[1]);
    }

    #[Test]
    public function it_can_solve_day_6b(): void
    {
        $map = $this->map();
        $routeSize = $this->findLoops($map);

        $this->assertEquals(1836, $routeSize);
    }

    private function getPath(CharMap $map): array
    {
        $position = $this->getStartingPosition($map);
        $direction = Direction::UP;
        $path = [
            "$position->x-$position->y" => $direction,
        ];

        while (true) {
            $newPosition = $position->add($direction->vector());
            while ($map->has($newPosition->y, $newPosition->x) && $map->get($newPosition->y, $newPosition->x) === '#') {
                $direction = $direction->turnRight();
                $newPosition = $position->add($direction->vector());
            }
            $position = $newPosition;
            if ($map->has($position->y, $position->x) === false) {
                return [$path, false];
            }
            if (array_key_exists("$position->x-$position->y", $path) && $direction === $path["$position->x-$position->y"]) {
                return [$path, true];
            }

            $path["$position->x-$position->y"] = $direction;
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
        $direction = Direction::UP;
        $loops = [];

        while (true) {
            $newPosition = $position->add($direction->vector());
            while ($map->has($newPosition->y, $newPosition->x) && $map->get($newPosition->y, $newPosition->x) === '#') {
                $direction = $direction->turnRight();
                $newPosition = $position->add($direction->vector());
            }
            $position = $newPosition;

            if ($map->has($position->y, $position->x) === false) {
                return count($loops);
            }
            if ($position->is($start)) {
                continue;
            }
            $map->replace($position->y, $position->x, '#');
            if ($this->getPath($map)[1]) {
                $loops["$position->x-$position->y"] = true;
            }
            $map->replace($position->y, $position->x, '.');
        }
    }

}
