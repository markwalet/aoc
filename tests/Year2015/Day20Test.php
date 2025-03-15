<?php

namespace Tests\Year2015;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day20Test extends TestCase
{
    #[Test]
    public function it_can_calculate_scores(): void
    {
        $this->assertEquals(10, $this->score(1));
        $this->assertEquals(30, $this->score(2));
        $this->assertEquals(40, $this->score(3));
        $this->assertEquals(70, $this->score(4));
        $this->assertEquals(60, $this->score(5));
        $this->assertEquals(120, $this->score(6));
    }

    #[Test]
    public function it_can_solve_day_20a(): void
    {
        ini_set('memory_limit', '-1');
        $houses = [];

        for($i = 1; $i <= 3400000; $i++) {
            for($j = $i; $j <= 3400000; $j += $i) {
                if (array_key_exists($j, $houses) === false) {
                    $houses[$j] = 0;
                }
                $houses[$j] += $i * 10;
            }
            if ($houses[$i] > 34_000_000) {
                $this->assertEquals(786240, $i);
                break;
            }
        }
    }

    #[Test]
    public function it_can_solve_day_20b(): void
    {
        ini_set('memory_limit', '-1');
        $houses = [];

        for($i = 1; $i <= 3400000; $i++) {
            $t = 0;
            for($j = $i; $j <= 3400000; $j += $i) {
                if (array_key_exists($j, $houses) === false) {
                    $houses[$j] = 0;
                }
                $houses[$j] += $i * 11;
                $t++;
                if ($t === 50) {
                    break;
                }
            }
            if ($houses[$i] > 34_000_000) {
                $this->assertEquals(831600, $i);
                break;
            }
        }
    }

    private function score(int $house): int
    {
        $score = $house * 10;
        for ($i = 1; $i <= $house / 2; $i++) {
            if ($house % $i === 0) {
                $score += $i * 10;
            }
        }

        return $score;
    }
}
