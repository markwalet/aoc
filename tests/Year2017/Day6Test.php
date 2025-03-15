<?php

namespace Tests\Year2017;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day6Test extends TestCase
{
    private array $example = [0, 2, 7, 0];
    private array $input = [10, 3, 15, 10, 5, 15, 5, 15, 9, 2, 5, 8, 5, 2, 3, 6];

    #[Test]
    public function it_can_be_rebalanced(): void
    {
        $this->assertEquals([2, 4, 1, 2], $this->rebalance($this->example));
    }

    #[Test]
    public function it_can_solve_day_6(): void
    {
        $banks = $this->input;
        $results = [];

        $i = 0;

        while (in_array($banks, $results) === false) {
            $results[] = $banks;
            $i++;
            $banks = $this->rebalance($banks);
        }

        $first = array_search($banks, $results);
        $this->assertEquals(14029, $i);
        $this->assertEquals(2765, $i - $first);
    }

    private function rebalance(array $banks): array
    {
        $max = max($banks);

        $key = array_search($max, $banks);

        $spreading = $banks[$key];
        $banks[$key] = 0;

        while ($spreading > 0) {
            $key++;
            if (array_key_exists($key, $banks) === false) {
                $key = 0;
            }
            $banks[$key]++;
            $spreading--;
        }

        return $banks;
    }
}
