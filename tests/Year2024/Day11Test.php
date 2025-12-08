<?php

namespace Tests\Year2024;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day11Test extends TestCase
{
    #[Test]
    public function it_can_solve_day_11(): void
    {
        $data = ['890', '0', '1', '935698', '68001', '3441397', '7221', '27'];

        $resultA = $resultB = 0;
        $memory = [];
        foreach ($data as $d) {
            $resultA += $this->dfs($d, 25, $memory);
            $resultB += $this->dfs($d, 75, $memory);
        }

        $this->assertEquals(194782, $resultA);
        $this->assertEquals(233007586663131, $resultB);
    }

    private function dfs(string $number, int $depth, array &$memory = []): int
    {
        if ($depth === 0) {
            return 1;
        }

        if (array_key_exists($number.'-'.$depth, $memory) === false) {
            if ($number === '0') {
                $result = $this->dfs('1', $depth - 1, $memory);
            } elseif (strlen($number) % 2 === 0) {
                [$first, $second] = array_map(fn (string $n) => ltrim($n, '0'), str_split($number, strlen($number) / 2));

                $result = $this->dfs($first === '' ? '0' : $first, $depth - 1, $memory)
                    + $this->dfs($second === '' ? '0' : $second, $depth - 1, $memory);
            } else {
                $result = $this->dfs(bcmul($number, '2024'), $depth - 1, $memory);
            }

            $memory[$number.'-'.$depth] = $result;
        }

        return $memory[$number.'-'.$depth];
    }
}
