<?php

namespace Tests\Year2016;

use App\Support\Navigation\Direction;
use App\Support\Vectors\Vector2;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day1Test extends TestCase
{
    #[Test]
    public function it_can_solve_day_1a(): void
    {
        $location = new Vector2(0, 0);
        $direction = Direction::UP;
        $steps = explode(', ', $this->lines()[0]);

        foreach ($steps as $step) {
            $turn = $step[0];
            $amount = substr($step, 1);

            $direction = match ($turn) {
                'R' => $direction->turnRight(),
                'L' => $direction->turnLeft(),
            };

            $location = $location->add($direction->vector()->multiply($amount));
        }

        $result = (int) (abs($location->x) + abs($location->y));

        $this->assertEquals(287, $result);
    }

    #[Test]
    public function it_can_solve_day_1b(): void
    {
        $result = 0;
        $location = new Vector2(0, 0);
        $direction = Direction::UP;
        $steps = explode(', ', $this->lines()[0]);

        $visited = ['0-0' => 1];

        foreach ($steps as $step) {
            $turn = $step[0];
            $amount = substr($step, 1);

            $direction = match ($turn) {
                'R' => $direction->turnRight(),
                'L' => $direction->turnLeft(),
            };

            for ($i = 0; $i < (int) $amount; $i++) {
                $location = $location->add($direction->vector());
                $key = $location->x.'-'.$location->y;
                if (array_key_exists($key, $visited)) {
                    $result = (int) (abs($location->x) + abs($location->y));

                    break(2);
                }
                $visited[$key] = 1;
            }

        }

        $this->assertEquals(133, $result);
    }
}
