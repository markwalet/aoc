<?php

namespace Tests\Year2015;

use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day16Test extends TestCase
{
    private array $filters = [
        'children' => 3,
        'cats' => 7,
        'samoyeds' => 2,
        'pomeranians' => 3,
        'akitas' => 0,
        'vizslas' => 0,
        'goldfish' => 5,
        'trees' => 3,
        'cars' => 2,
        'perfumes' => 1,
    ];

    #[Test]
    public function it_can_solve_day_16a(): void
    {
        $sues = $this->getSues();

        $result = 0;
        foreach ($sues as $sue => $specs) {
            foreach ($specs as $feature => $value) {
                if ($this->filters[$feature] !== $value) {
                    continue(2);
                }
            }

            $result = $sue;
            break;
        }

        $this->assertEquals(213, $result);
    }

    #[Test]
    public function it_can_solve_day_16b(): void
    {
        $sues = $this->getSues();

        $result = 0;
        foreach ($sues as $sue => $specs) {
            foreach ($specs as $feature => $value) {
                if (in_array($feature, ['pomeranians', 'goldfish'])) {
                    if ($this->filters[$feature] <= $value) {
                        continue(2);
                    }
                } elseif (in_array($feature, ['cats', 'trees'])) {
                    if ($this->filters[$feature] >= $value) {
                        continue(2);
                    }
                } else {
                    if ($this->filters[$feature] !== $value) {
                        continue(2);
                    }
                }
            }

            $result = $sue;
            break;
        }

        $this->assertEquals(323, $result);
    }

    private function getSues(): Collection
    {
        return $this->lines()
            ->mapWithKeys(function (string $line) {
                [$sue, $specs] = explode(': ', $line, 2);
                $number = intval(explode(' ', $sue)[1]);

                $specs = collect(explode(', ', $specs))->mapWithKeys(function ($s) {
                    [$a, $b] = explode(': ', $s);

                    return [$a => intval($b)];
                });

                return [$number => $specs];
            });
    }
}
