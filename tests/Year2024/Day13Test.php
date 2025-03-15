<?php

namespace Tests\Year2024;

use App\Support\Vectors\Vector2;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day13Test extends TestCase
{
    #[Test]
    public function it_can_calculate_the_minimum_amount_of_button_pushes(): void
    {
        $result = $this->calculateMinSteps(new Vector2(8400, 5400), new Vector2(94, 34), new Vector2(22, 67));

        $this->assertEquals(280, $result);
    }

    #[Test]
    public function it_can_solve_day_13a(): void
    {
        $result = $this->lines(null, PHP_EOL.PHP_EOL)
            ->map(function (string $data) {
                [$buttonA, $buttonB, $prize] = array_filter(explode(PHP_EOL, $data));
                preg_match('/Prize: X=(\d+), Y=(\d+)/', $prize, $matches);
                $prize = new Vector2((int) $matches[1], (int) $matches[2]);
                preg_match('/Button A: X\+(\d+), Y\+(\d+)/', $buttonA, $matches);
                $buttonA = new Vector2((int) $matches[1], (int) $matches[2]);
                preg_match('/Button B: X\+(\d+), Y\+(\d+)/', $buttonB, $matches);
                $buttonB = new Vector2((int) $matches[1], (int) $matches[2]);

                return $this->calculateMinSteps($prize, $buttonA, $buttonB);
            })
            ->sum();

        $this->assertEquals(37297, $result);
    }

    #[Test]
    public function it_can_solve_day_13b(): void
    {
        $result = $this->lines(null, PHP_EOL.PHP_EOL)
            ->map(function (string $data) {
                [$buttonA, $buttonB, $prize] = array_filter(explode(PHP_EOL, $data));
                preg_match('/Prize: X=(\d+), Y=(\d+)/', $prize, $matches);
                $prize = new Vector2((int) $matches[1], (int) $matches[2]);
                $prize->move(new Vector2(10000000000000, 10000000000000));
                preg_match('/Button A: X\+(\d+), Y\+(\d+)/', $buttonA, $matches);
                $buttonA = new Vector2((int) $matches[1], (int) $matches[2]);
                preg_match('/Button B: X\+(\d+), Y\+(\d+)/', $buttonB, $matches);
                $buttonB = new Vector2((int) $matches[1], (int) $matches[2]);

                return $this->calculateMinSteps($prize, $buttonA, $buttonB);
            })
            ->sum();

        $this->assertEquals(83197086729371, $result);
    }

    private function calculateMinSteps(Vector2 $target, Vector2 $a, Vector2 $b): int
    {
        $det = $a->x * $b->y - $a->y * $b->x;
        $ad = $b->y * $target->x - $b->x * $target->y;
        $bd = $a->x * $target->y - $a->y * $target->x;

        return ($ad % $det === 0 && $bd % $det === 0) ? (3 * $ad + $bd) / $det : 0;
    }
}
