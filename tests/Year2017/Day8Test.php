<?php

namespace Tests\Year2017;

use App\Support\Collections\ArrayWithDefault;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day8Test extends TestCase
{
    #[Test]
    public function it_can_solve_the_example(): void
    {
        $lines = $this->lines('example');
        $registry = new ArrayWithDefault(0);

        $result = $this->testMaximumMemory($registry, $lines);

        $this->assertEquals(1, $result->last());
        $this->assertEquals(10, $result->max());
    }

    #[Test]
    public function it_can_solve_day_8(): void
    {
        $lines = $this->lines();
        $registry = new ArrayWithDefault(0);

        $result = $this->testMaximumMemory($registry, $lines);
        $this->assertEquals(4448, $result->last());
        $this->assertEquals(6582, $result->max());
    }

    private function testMaximumMemory(ArrayWithDefault $registry, Collection $lines): Collection
    {
        return $lines->map(function (string $line) use (&$registry) {
            $this->executeCommand($registry, $line);

            return $registry->count() === 0 ? 0 : max($registry->toArray());
        });
    }

    private function executeCommand(ArrayWithDefault $registry, string $line): void
    {
        [$target, $operation, $value, , $testTarget, $testOperation, $testValue] = explode(' ', $line);
        $value = (int) $value;
        $testValue = (int) $testValue;

        $test = match ($testOperation) {
            '>' => fn ($v) => $v > $testValue,
            '<' => fn ($v) => $v < $testValue,
            '>=' => fn ($v) => $v >= $testValue,
            '<=' => fn ($v) => $v <= $testValue,
            '!=' => fn ($v) => $v != $testValue,
            '==' => fn ($v) => $v == $testValue,
        };
        if ($test($registry[$testTarget])) {
            $operation = match ($operation) {
                'inc' => fn (int $v) => $v + $value,
                'dec' => fn (int $v) => $v - $value,
            };

            $registry[$target] = $operation($registry[$target]);
        }
    }
}
