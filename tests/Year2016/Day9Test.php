<?php

namespace Tests\Year2016;

use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day9Test extends TestCase
{
    #[Test]
    public function it_can_decompress(): void
    {
        $this->assertEquals('ADVENT', $this->decompress('ADVENT'));
        $this->assertEquals('ABBBBBC', $this->decompress('A(1x5)BC'));
        $this->assertEquals('XYZXYZXYZ', $this->decompress('(3x3)XYZ'));
        $this->assertEquals('ABCBCDEFEFG', $this->decompress('A(2x2)BCD(2x2)EFG'));
        $this->assertEquals('(1x3)A', $this->decompress('(6x1)(1x3)A'));
        $this->assertEquals('X(3x3)ABC(3x3)ABCY', $this->decompress('X(8x2)(3x3)ABCY'));
        $this->assertEquals(445, $this->decompressionSize('(25x3)(3x3)ABC(2x3)XY(5x2)PQRSTX(18x9)(3x2)TWO(5x7)SEVEN'));
    }

    #[Test]
    public function it_can_solve_day_9a(): void
    {
        $result = strlen($this->decompress($this->lines()[0]));

        $this->assertEquals(98135, $result);
    }

    #[Test]
    public function it_can_solve_day_9b(): void
    {
        $result = $this->decompressionSize($this->lines()[0]);

        $this->assertEquals(10964557606, $result);
    }

    private function decompressionSize(string $input): int
    {
        $resultCount = 0;
        while (Str::contains($input, ')')) {
            [$part, $input] = explode(')', $input, 2);
            [$before, $repeat] = explode('(', $part, 2);
            $resultCount += strlen($before);
            [$size, $times] = explode('x', $repeat);

            $repeating = substr($input, 0, $size);
            $input = substr($input, $size);
            $resultCount += $times * $this->decompressionSize($repeating);
        }

        $resultCount += strlen($input);

        return $resultCount;
    }

    private function decompress(string $input): string
    {
        $result = '';
        while (Str::contains($input, ')')) {
            [$part, $input] = explode(')', $input, 2);
            [$before, $repeat] = explode('(', $part, 2);
            $result .= $before;
            [$size, $times] = explode('x', $repeat);

            $repeating = substr($input, 0, $size);
            $input = substr($input, $size);
            $result .= str_repeat($repeating, $times);
        }

        $result .= $input;

        return $result;
    }
}
