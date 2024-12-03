<?php

namespace Tests\Year2024;

use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day3Test extends TestCase
{
    #[Test]
    public function it_can_parse_the_example(): void
    {
        $score = $this->parse($this->lines('example'));

        $this->assertEquals(161, $score);
    }
    #[Test]
    public function it_can_filter_a_line(): void
    {
        $score = $this->parse(
            $this->filter(collect("xmul(2,4)&mul[3,7]!^don't()_mul(5,5)+mul(32,64](mul(11,8)undo()?mul(8,5))"))
        );

        $this->assertEquals(48, $score);
    }

    #[Test]
    public function it_can_solve_day_3a(): void
    {
        $score = $this->parse($this->lines());

        $this->assertEquals(171183089, $score);
    }

    #[Test]
    public function it_can_solve_day_3b(): void
    {
        $score = $this->parse($this->filter($this->lines()));

        $this->assertEquals(63866497, $score);
    }

    public function parse(Collection $lines): int
    {
        return $lines->map(function (string $line) {
            preg_match_all('/mul\(([0-9]+,[0-9]+)\)/', $line, $matches);

            $sum = 0;
            foreach ($matches[1] as $match) {
                [$a, $b] = explode(',', $match);

                $sum += $a * $b;
            }

            return $sum;
        })->sum();
    }

    private function filter(Collection $lines): Collection
    {
        $enabled = true;
        return $lines->flatMap(function (string $line) use (&$enabled) {
            $parts = explode('don\'t()', $line);
            $result = [];

            if ($enabled) {
                $result[] = array_shift($parts);
            }

            foreach($parts as $part) {
                $exploded = explode('do()', $part, 2);
                if (count($exploded) === 2) {
                    $result[] = $exploded[1];
                    $enabled = true;
                } else {
                    $enabled = false;
                }
            }

            return $result;
        });
    }
}
