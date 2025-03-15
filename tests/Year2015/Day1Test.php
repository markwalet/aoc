<?php

namespace Tests\Year2015;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day1Test extends TestCase
{
    #[Test]
    public function it_can_solve_basic_examples(): void
    {
        $this->assertEquals(0, ($this->finalFloor('(())')));
        $this->assertEquals(3, ($this->finalFloor('(((')));
        $this->assertEquals(1, ($this->finalFloor('((())')));
        $this->assertEquals(1, ($this->firstBasement(')')));
        $this->assertEquals(5, ($this->firstBasement('()())')));
    }

    #[Test]
    public function it_can_solve_day_1a(): void
    {
        $input = $this->lines()[0];
        $result = $this->finalFloor($input);

        $this->assertEquals(280, $result);
    }

    #[Test]
    public function it_can_solve_day_1b(): void
    {
        $input = $this->lines()[0];
        $result = $this->firstBasement($input);

        $this->assertEquals(1797, $result);
    }

    private function finalFloor(string $input): int
    {
        return substr_count($input, '(') - substr_count($input, ')');
    }

    private function firstBasement(string $input): int
    {
        $net = 1;
        for ($i = 0; $i < strlen($input); $i++) {
            $net += match ($input[$i]) {
                '(' => 1,
                ')' => -1,
            };

            if ($net === 0) {
                return $i + 1;
            }
        }

        return -1;
    }
}
