<?php

namespace Tests\Year2016;

use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day2Test extends TestCase
{
    private array $map = [
        '0-0' => 1,
        '1-0' => 2,
        '2-0' => 3,
        '0-1' => 4,
        '1-1' => 5,
        '2-1' => 6,
        '0-2' => 7,
        '1-2' => 8,
        '2-2' => 9,
    ];

    private array $secondMap = [
        '2-0' => 1,
        '1-1' => 2,
        '2-1' => 3,
        '3-1' => 4,
        '0-2' => 5,
        '1-2' => 6,
        '2-2' => 7,
        '3-2' => 8,
        '4-2' => 9,
        '1-3' => 'A',
        '2-3' => 'B',
        '3-3' => 'C',
        '2-4' => 'D',
    ];

    #[Test]
    public function it_can_solve_day_2_examples(): void
    {
        $instructions = $this->lines('example');
        $result = $this->mapCode($instructions, $this->map);
        $this->assertEquals('1985', $result);
        $result = $this->mapCode($instructions, $this->secondMap);
        $this->assertEquals('5DB3', $result);
    }

    #[Test]
    public function it_can_solve_day_2a(): void
    {
        $instructions = $this->lines();
        $result = $this->mapCode($instructions, $this->map);
        $this->assertEquals('12578', $result);
    }

    #[Test]
    public function it_can_solve_day_2b(): void
    {
        $instructions = $this->lines();
        $result = $this->mapCode($instructions, $this->secondMap);
        $this->assertEquals('516DD', $result);
    }

    private function mapCode(Collection $instructions, array $map): string
    {
        [$x, $y] = explode('-', array_search(5, $map));
        $code = '';

        foreach ($instructions as $line) {
            foreach (str_split($line) as $char) {
                $newX = $x;
                $newY = $y;

                switch ($char) {
                    case 'U';
                        $newY = $y - 1;
                        break;
                    case 'D':
                        $newY = $y + 1;
                        break;
                    case 'L':
                        $newX = $x - 1;
                        break;
                    case 'R':
                        $newX = $x + 1;
                        break;
                }
                if (array_key_exists($newX.'-'.$newY, $map)) {
                    $x = $newX;
                    $y = $newY;
                }
            }
            $code .= $map[$x.'-'.$y];
        }

        return $code;
    }
}
