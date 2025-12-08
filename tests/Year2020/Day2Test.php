<?php

namespace Tests\Year2020;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day2Test extends TestCase
{
    #[Test]
    public function it_can_solve_day_2a(): void
    {
        $result = $this->lines()->filter(function (string $line) {
            [$rules, $password] = explode(': ', $line);
            [$range, $letter] = explode(' ', $rules);
            [$min, $max] = explode('-', $range);

            $count = substr_count($password, $letter);

            return $count >= $min && $count <= $max;
        })->count();

        $this->assertEquals(528, $result);
    }

    #[Test]
    public function it_can_solve_day_2b(): void
    {
        $result = $this->lines()->filter(function (string $line) {
            [$rules, $password] = explode(': ', $line);
            [$range, $letter] = explode(' ', $rules);
            [$first, $second] = explode('-', $range);
            $first = (int) $first - 1;
            $second = (int) $second - 1;

            return ($password[$first] ?? '') === $letter xor ($password[$second] ?? '') === $letter;
        })->count();

        $this->assertEquals(497, $result);
    }

}
