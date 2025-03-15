<?php

namespace Tests\Year2016;

use App\Support\Inputs\CharMap;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day8Test extends TestCase
{
    #[Test]
    public function it_can_simulate_example(): void
    {
        $map = CharMap::fromSize(7, 3, '.');
        $this->lines('example')->each(fn (string $instruction) => $this->executeInstruction($map, $instruction));

        $result = $map->count('#');
        $this->assertEquals(6, $result);
        $this->assertEquals([
            ['.', '#', '.', '.', '#', '.', '#'],
            ['#', '.', '#', '.', '.', '.', '.'],
            ['.', '#', '.', '.', '.', '.', '.'],
        ], $map->lines);
    }
    #[Test]
    public function it_can_solve_day_8a(): void
    {
        $map = CharMap::fromSize(50, 6, '.');
        $this->lines()->each(fn (string $instruction) => $this->executeInstruction($map, $instruction));

        $result = $map->count('#');
        $this->assertEquals(115, $result);
    }

    private function executeInstruction(CharMap $map, string $instruction): void
    {
        [$command, $data] = explode(' ', $instruction, 2);
        switch ($command) {
            case 'rect':
                [$w, $h] = explode('x', $data);

                for ($x = 0; $x < $w; $x++) {
                    for ($y = 0; $y < $h; $y++) {
                        $map->replace($y, $x, '#');
                    }
                }
                break;
            case 'rotate':
                [$direction, $position, , $amount] = explode(' ', $data);
                [, $position] = explode('=', $position, 2);

                switch ($direction) {
                    case 'column':
                        $data = array_map(fn (array $data) => $data[$position], $map->lines);
                        $new = $this->move($data, $amount);

                        for ($i = 0; $i < $map->height; $i++) {
                            $map->replace($i, $position, $new[$i]);
                        }

                        break;
                    case 'row':
                        $data = $map->lines[$position];
                        $new = $this->move($data, $amount);

                        for ($i = 0; $i < $map->width; $i++) {
                            $map->replace($position, $i, $new[$i]);
                        }
                        break;
                }
                break;
        }
    }

    private function move(array $data, string $amount): array
    {
        for ($i = 0; $i < $amount; $i++) {
            $last = array_pop($data);
            array_unshift($data, $last);
        }

        return $data;
    }
}
