<?php

namespace Tests\Year2017;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day2Test extends TestCase
{
    #[Test]
    public function it_can_solve_day_2a(): void
    {
        $difference = $this->lines()
            ->map(fn (string $line) => array_map('intval', explode("\t", $line)))
            ->map(fn (array $numbers) => max($numbers) - min($numbers))
            ->sum();

        $this->assertEquals(42299, $difference);
    }

    #[Test]
    public function it_can_solve_day_2b(): void
    {
        $difference = $this->lines()
            ->map(fn (string $line) => array_map('intval', explode("\t", $line)))
            ->map(function (array $numbers) {
                for ($i = 0; $i < count($numbers); $i++) {
                    for ($j = 0; $j < count($numbers); $j++) {
                        if ($i !== $j && $numbers[$i] % $numbers[$j] === 0) {
                            return (int) $numbers[$i] / $numbers[$j];
                        }
                    }
                }

                $this->fail('Could not solve day 2b.');
            })
            ->sum();

        $this->assertEquals(277, $difference);
    }
}
