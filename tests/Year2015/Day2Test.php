<?php

namespace Tests\Year2015;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day2Test extends TestCase
{
    #[Test]
    public function it_can_calculate_required_dimensions(): void
    {
        $this->assertEquals(58, $this->wrappingPaperFromString('2x3x4'));
    }

    #[Test]
    public function it_can_calculate_required_ribbon(): void
    {
        $this->assertEquals(34, $this->ribbonLengthFromString('2x3x4'));
    }

    #[Test]
    public function it_can_solve_day_2a(): void
    {
        $sum = $this->lines()->sum(function (string $line) {
            return $this->wrappingPaperFromString($line);
        });

        $this->assertEquals(1586300, $sum);
    }

    #[Test]
    public function it_can_solve_day_2b(): void
    {
        $sum = $this->lines()->sum(function (string $line) {
            return $this->ribbonLengthFromString($line);
        });

        $this->assertEquals(3737498, $sum);
    }

    private function wrappingPaperFromString(string $input): int
    {
        [$length, $width, $height] = array_map('intval', explode('x', $input));

        return $this->wrappingPaper($length, $width, $height);
    }

    private function ribbonLengthFromString(string $input): int
    {
        [$length, $width, $height] = array_map('intval', explode('x', $input));

        return $this->ribbonLength($length, $width, $height);
    }

    private function wrappingPaper(int $length, int $width, int $height): int
    {
        $sides = [$length * $width, $length * $height, $height * $width];

        sort($sides);

        return array_sum([
            $sides[0] * 3,
            $sides[1] * 2,
            $sides[2] * 2,
        ]);
    }

    private function ribbonLength(int $length, int $width, int $height): int
    {
        $sides = [$length, $width, $height];

        sort($sides);

        return array_sum([
            $sides[0] * 2,
            $sides[1] * 2,
            $length * $width * $height,
        ]);
    }
}
