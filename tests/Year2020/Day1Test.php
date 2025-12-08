<?php

namespace Tests\Year2020;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day1Test extends TestCase
{
    #[Test]
    public function it_can_solve_day_1a(): void
    {
        $input = $this->lines();

        $result = 0;
        for ($i = 0; $i < $input->count() - 1; $i++) {
            for ($j = $i + 1; $j < $input->count(); $j++) {
                if ($input[$i] + $input[$j] === 2020) {
                    $result = $input[$i] * $input[$j];
                    break 2;
                }
            }
        }

        $this->assertEquals(926464, $result);
    }

    #[Test]
    public function it_can_solve_day_1b(): void
    {
        $input = $this->lines();

        $result = 0;
        for ($i = 0; $i < $input->count() - 1; $i++) {
            for ($j = $i + 1; $j < $input->count(); $j++) {
                for ($k = $j + 1; $k < $input->count(); $k++) {
                    if ($input[$i] + $input[$j] + $input[$k] === 2020) {
                        $result = $input[$i] * $input[$j] * $input[$k];
                        break 3;
                    }
                }
            }
        }

        $this->assertEquals(65656536, $result);
    }
}
