<?php

namespace Tests\Year2017;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day4Test extends TestCase
{
    #[Test]
    public function it_can_solve_day_4a(): void
    {
        $validPasswords = $this->lines()
            ->map(fn (string $line) => explode(' ', $line))
            ->filter(fn (array $words) => count(array_unique($words)) === count($words))
            ->count();
        $this->assertEquals(325, $validPasswords);
    }

    #[Test]
    public function it_can_solve_day_4b(): void
    {
        $validPasswords = $this->lines()
            ->map(fn (string $line) => array_map(function (string $word) {
                $chars = str_split($word);
                sort($chars);

                return join('', $chars);
            }, explode(' ', $line)))
            ->filter(fn (array $words) => count(array_unique($words)) === count($words))
            ->count();
        $this->assertEquals(119, $validPasswords);
    }
}
