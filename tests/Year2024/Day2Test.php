<?php

namespace Tests\Year2024;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day2Test extends TestCase
{
    #[Test]
    public function can_check_it_is_safe(): void
    {
        $this->assertTrue($this->checkIfSafe('7 6 4 2 1'));
        $this->assertFalse($this->checkIfSafe('1 2 7 8 9'));
        $this->assertFalse($this->checkIfSafe('9 7 6 2 1'));
        $this->assertFalse($this->checkIfSafe('1 3 2 4 5'));
        $this->assertFalse($this->checkIfSafe('8 6 4 4 1'));
        $this->assertTrue($this->checkIfSafe('1 3 6 7 9'));

        $this->assertTrue($this->checkIfSafe('7 6 4 2 1', 1));
        $this->assertFalse($this->checkIfSafe('1 2 7 8 9', 1));
        $this->assertFalse($this->checkIfSafe('9 7 6 2 1', 1));
        $this->assertTrue($this->checkIfSafe('1 3 2 4 5', 1));
        $this->assertTrue($this->checkIfSafe('8 6 4 4 1', 1));
        $this->assertTrue($this->checkIfSafe('1 3 6 7 9', 1));
        $this->assertTrue($this->checkIfSafe('11 3 6 7 9', 1));
    }

    #[Test]
    public function it_can_solve_day_2a(): void
    {
        $result = $this->lines()
            ->filter(fn (string $line) => $this->checkIfSafe($line))
            ->count();

        $this->assertEquals(483, $result);
    }

    #[Test]
    public function it_can_solve_day_2b(): void
    {
        $result = $this->lines()
            ->filter(fn (string $line) => $this->checkIfSafe($line, 1))
            ->count();

        $this->assertEquals(528, $result);
    }

    private function checkIfSafe(string $line, int $allowedRepairs = 0): bool
    {
        $numbers = array_map('intval', explode(' ', $line));
        $allowed = $numbers[0] > $numbers[1] ? [-1, -2, -3] : [1, 2, 3];

        for ($i = 0; $i < count($numbers) - 1; $i++) {
            $a = $numbers[$i];
            $b = $numbers[$i + 1];

            if (! in_array($b - $a, $allowed)) {
                if ($allowedRepairs > 0) {
                    for ($i = 0; $i < count($numbers); $i++) {
                        $tmp = $numbers[$i];
                        unset($numbers[$i]);

                        if ($this->checkIfSafe(implode(' ', $numbers), $allowedRepairs - 1)) {
                            return true;
                        }

                        $numbers[$i] = $tmp;
                        ksort($numbers);
                    }
                }

                return false;
            }
        }

        return true;
    }
}
