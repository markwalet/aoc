<?php

namespace Tests\Year2017;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day5Test extends TestCase
{
    #[Test]
    public function it_can_solve_day_5a(): void
    {
        $maze = $this->lines()->toArray();
        $result = $this->walkMaze($maze, fn (int $oldValue) => $oldValue + 1);
        $this->assertEquals(318883, $result);
    }

    #[Test]
    public function it_can_solve_day_5b(): void
    {
        $maze = $this->lines()->toArray();
        $result = $this->walkMaze($maze, fn (int $oldValue) => $oldValue >= 3 ? $oldValue - 1 : $oldValue + 1);
        $this->assertEquals(23948711, $result);
    }

    private function walkMaze(array $maze, callable $newValue): int
    {
        $step = 0;
        $cursor = 0;

        while (array_key_exists($cursor, $maze)) {
            $delta = $maze[$cursor];
            $maze[$cursor] = $newValue($maze[$cursor]);
            $cursor += $delta;
            $step++;
        }

        return $step;
    }
}
