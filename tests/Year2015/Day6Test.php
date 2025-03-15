<?php

namespace Tests\Year2015;

use App\Support\Inputs\CharMap;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day6Test extends TestCase
{
    #[Test]
    public function it_can_solve_day_6a(): void
    {
        $grid = CharMap::fromSize(1000, 1000, false);
        $instructions = $this->lines();

        $this->followInstructions($instructions, function (string $command, int $x, int $y) use ($grid) {
            switch ($command) {
                case 'toggle':
                    $grid->replace($y, $x, !$grid->get($y, $x));
                    break;
                case 'on':
                    $grid->replace($y, $x, true);

                    break;
                case 'off':
                    $grid->replace($y, $x, false);
                    break;
            }
        });
        $result = $grid->count(true);

        $this->assertEquals(543903, $result);
    }

    #[Test]
    public function it_can_solve_day_6b(): void
    {
        $grid = CharMap::fromSize(1000, 1000, 0);
        $instructions = $this->lines();

        $this->followInstructions($instructions, function (string $command, int $x, int $y) use ($grid) {
            switch ($command) {
                case 'toggle':
                    $grid->replace($y, $x, $grid->get($y, $x) + 2);
                    break;
                case 'on':
                    $grid->replace($y, $x, $grid->get($y, $x)+1);

                    break;
                case 'off':
                    $grid->replace($y, $x, max(0, $grid->get($y, $x) - 1));
                    break;
            }
        });
        $result = $grid->sum();

        $this->assertEquals(14687245, $result);
    }

    private function followInstructions(Collection $instructions, callable $callback): void
    {
        $instructions->each(function (string $instruction) use ($callback) {
            $instruction = Str::startsWith($instruction, 'turn ') ? substr($instruction, 5) : $instruction;
            [$command, $start, , $end] = explode(' ', $instruction);
            [$startX, $startY] = explode(',', $start);
            [$endX, $endY] = explode(',', $end);
            for ($x = $startX; $x <= $endX; ++$x) {
                for ($y = $startY; $y <= $endY; ++$y) {
                    $callback($command, $x, $y);
                }
            }
        });
    }
}
