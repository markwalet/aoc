<?php

namespace Tests\Year2025;

use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day6Test extends TestCase
{
    #[Test]
    public function it_can_solve_day_6_example(): void
    {
        $resultA = $this->solve($this->parseA($this->lines('example')));
        $resultB = $this->solve($this->parseB($this->lines('example', trim: false)));

        $this->assertEquals(4277556, $resultA);
        $this->assertEquals(3263827, $resultB);
    }

    #[Test]
    public function it_can_solve_day_6(): void
    {
        $resultA = $this->solve($this->parseA($this->lines()));
        $resultB = $this->solve($this->parseB($this->lines(trim: false)));

        $this->assertEquals(6169101504608, $resultA);
        $this->assertEquals(10442199710797, $resultB);
    }

    private function solve(Collection $parsed): int
    {
        return $parsed->map(function (array $items) {
            $operation = array_pop($items);

            return match ($operation) {
                '*' => array_reduce($items, fn ($carry, $item) => $carry * $item, 1),
                '+' => array_sum($items),
            };
        })->sum();
    }

    private function parseA(Collection $lines): Collection
    {
        $lines = $lines->map(function (string $line) {
            return array_values(array_filter(explode(' ', $line)));
        });

        $result = [];
        for ($i = 0; $i < count($lines[0]); $i++) {
            $result[$i] = [];
            for ($j = 0; $j < count($lines); $j++) {
                $result[$i][] = $lines[$j][$i];
            }
        }

        return collect($result);
    }

    private function parseB(Collection $lines): Collection
    {
        $operations = $lines->pop();
        $result = collect();

        $length = 0;
        foreach($lines as $line) {
            $length = max($length, strlen($line));
        }

        $operation = [];
        $currentOperation = $operations[0];
        for ($i = 0; $i < $length; $i++) {
            if (($operations[$i] ?? ' ') !== ' ' && $i !== 0) {
                $operation[] = $currentOperation;
                $result->push(array_map('trim', $operation));
                $operation = [];
                $currentOperation = $operations[$i];
            }

            foreach ($lines as $l => $line) {
                if (($lines[$l][$i] ?? ' ') !== ' ') {
                    $operation[$i] = array_key_exists($i, $operation) ? $operation[$i].$lines[$l][$i] : $lines[$l][$i];
                }
            }
        }
        $operation[] = $currentOperation;
        $result->push(array_map('trim', $operation));

        return $result;
    }
}
