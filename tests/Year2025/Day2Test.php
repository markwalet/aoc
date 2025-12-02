<?php

namespace Tests\Year2025;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use PHPUnit\Framework\Attributes\Test;
use RuntimeException;
use Tests\TestCase;

class Day2Test extends TestCase
{
    public static $divisionCache = [];

    #[Test]
    public function it_can_solve_day_2_example(): void
    {
        $rows = $this->parse($this->puzzleInput('example'));
        $scoreA = $this->sumRepeatedDigits($rows, false);
        $scoreB = $this->sumRepeatedDigits($rows, true);

        $this->assertEquals(1227775554, $scoreA);
        $this->assertEquals(4174379265, $scoreB);
    }

    #[Test]
    public function it_can_solve_day_2(): void
    {
        $rows = $this->parse($this->puzzleInput());
        $scoreA = $this->sumRepeatedDigits($rows, false);
        $scoreB = $this->sumRepeatedDigits($rows, true);

        $this->assertEquals(34826702005, $scoreA);
        $this->assertEquals(43287141963, $scoreB);
    }

    private function parse(string $input)
    {
        return collect(explode(',', $input))
            ->map(fn (string $node) => array_map('intval', explode('-', $node)));
    }

    private function sumRepeatedDigits(Collection $rows, bool $countAllParts): int
    {
        return $rows->sum(function (array $row) use ($countAllParts) {
            $sum = 0;
            for ($i = $row[0]; $i <= $row[1]; $i++) {
                foreach($this->divisiblePartLengths(strlen($i), $countAllParts) as $length) {
                    for ($x = 0; $x < (strlen($i) / $length) - 1; $x++) {
                        if (substr($i, $length * $x, $length) !== substr($i, $length * ($x + 1), $length)) {
                            continue 2;
                        }
                    }

                    $sum += $i;
                    break;
                }
            }

            return $sum;
        });
    }

    #[Test]
    public function test_divisible_part_lengths(): void
    {
        $this->assertEquals([1], $this->divisiblePartLengths(2, true));
    }

    private function divisiblePartLengths(int $length, bool $findAllParts): array
    {
        $key = "divisible_part_lengths_{$length}_".($findAllParts ? 'all' : 'half');
        if (array_key_exists($key, self::$divisionCache)) {
            return self::$divisionCache[$key];
        }

        $divisions = [];
        if ($findAllParts) {
            for ($i = 1; $i <= $length / 2; $i++) {
                if ($length % $i === 0) {
                    $divisions[] = $i;
                }
            }
        } else {
            if ($length % 2 === 0) {
                $divisions[] = $length / 2;
            }
        }

        return self::$divisionCache[$key] = $divisions;
    }

}
