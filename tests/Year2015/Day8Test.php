<?php

namespace Tests\Year2015;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day8Test extends TestCase
{
    #[Test]
    public function it_can_solve_day_8a_example(): void
    {
        $result = $this->lines('example')
            ->sum(fn (string $line) => $this->literalLength($line) - $this->characterCount($line));

        $this->assertEquals(12, $result);
    }

    #[Test]
    public function it_can_solve_day_8b_example(): void
    {
        $result = $this->lines('example')
            ->sum(fn (string $line) => $this->encodedLength($line) - $this->literalLength($line));

        $this->assertEquals(19, $result);
    }

    #[Test]
    public function it_can_solve_day_8a(): void
    {
        $result = $this->lines()
            ->sum(fn (string $line) => $this->literalLength($line) - $this->characterCount($line));

        $this->assertEquals(1371, $result);
    }

    #[Test]
    public function it_can_solve_day_8b(): void
    {
        $result = $this->lines()
            ->sum(fn (string $line) => $this->encodedLength($line) - $this->literalLength($line));

        $this->assertEquals(2117, $result);
    }

    private function literalLength(string $input): int
    {
        return strlen($input);
    }

    private function characterCount(string $input): int
    {
        return strlen(stripcslashes($input)) - 2;
    }

    private function encodedLength(string $input): int
    {
        return strlen(addcslashes($input, '"\\')) + 2;
    }
}
