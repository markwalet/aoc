<?php

namespace Tests\Year2017;

use App\Support\Navigation\Direction;
use App\Support\Vectors\Vector2;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day3Test extends TestCase
{
    #[Test]
    public function it_can_walkSpiral(): void
    {
        $this->assertTrue($this->findLocationInSpiral(1)->is(0, 0));
        $this->assertTrue($this->findLocationInSpiral(12)->is(2, -1));
        $this->assertEquals(2, $this->findLocationInSpiral(23)->manhattan());
        $this->assertEquals(31, $this->findLocationInSpiral(1024)->manhattan());
    }

    #[Test]
    public function it_can_solve_day_3a(): void
    {
        $result = $this->findLocationInSpiral(368078)->manhattan();

        $this->assertEquals(371, $result);
    }

    #[Test]
    public function it_can_solve_day_3b(): void
    {
        $locations = [];
        foreach($this->walkSpiral() as $l) {
            $sum = 0;
            for ($x = $l->x - 1; $x <= $l->x + 1; $x++) {
                for($y = $l->y - 1; $y <= $l->y + 1; $y++) {
                    if ($l->x !== $x || $l->y !== $y) {
                        $sum += $locations["$x-$y"] ?? 0;
                    }
                }
            }
            if ($sum > 368078) {
                $this->assertEquals(369601, $sum);

                return;
            }

            $locations[$l->x.'-'.$l->y] = max(1, $sum);
        }
    }

    private function findLocationInSpiral(int $steps): Vector2
    {
        $i = 0;
        foreach($this->walkSpiral() as $l) {
            $i++;

            if ($i === $steps) {
                return $l;
            }
        }
    }

    private function walkSpiral(): \Generator
    {
        $location = new Vector2(0, 0);
        $sideStep = 0;
        $sideLength = 1;
        $direction = Direction::RIGHT;

        while (true) {
            yield $location;
            $location = $location->add($direction->vector());
            $sideStep++;
            if ($sideStep === $sideLength) {
                $sideStep = 0;
                $direction = $direction->turnLeft();
                if ($direction === Direction::LEFT || $direction === Direction::RIGHT) {
                    $sideLength++;
                }
            }
        }
    }
}
