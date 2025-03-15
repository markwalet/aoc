<?php

namespace Tests\Year2015;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day7Test extends TestCase
{
    #[Test]
    public function it_can_solve_day_7a_example(): void
    {
        $memory = $this->executeProgram($this->lines('example'));

        $this->assertEquals([
            'd' => 72,
            'e' => 507,
            'f' => 492,
            'g' => 114,
            'h' => 65412,
            'i' => 65079,
            'x' => 123,
            'y' => 456,
        ], $memory);
    }

    #[Test]
    public function it_can_solve_day_7a_example_in_reverse(): void
    {
        $memory = $this->executeProgram($this->lines('example')->reverse());

        $this->assertEquals([
            'd' => 72,
            'e' => 507,
            'f' => 492,
            'g' => 114,
            'h' => 65412,
            'i' => 65079,
            'x' => 123,
            'y' => 456,
        ], $memory);
    }

    #[Test]
    public function it_can_solve_day_7a(): void
    {
        $memory = $this->executeProgram($this->lines());

        ksort($memory);

        $this->assertEquals(3176, $memory['a']);
    }

    #[Test]
    public function it_can_solve_day_7b(): void
    {
        $lines = $this->lines()->map(fn (string $line) => Str::endsWith($line, ' -> b') ? "3176 -> b" : $line);
        $memory = $this->executeProgram($lines);

        ksort($memory);

        $this->assertEquals(14710, $memory['a']);
    }

    private function executeProgram(Collection $input): array
    {
        $input = $input->map(fn (string $line) => explode(' -> ', $line));
        /** @var Collection $input */
        /** @var Collection $program */
        [$input, $program] = $input->partition(fn (array $command) => ! is_numeric($command[0]));
        $initiated = $program->map(fn (array $command) => $command[1]);

        while ($input->isNotEmpty()) {
            $input = $input->reject(function (array $command) use (&$initiated, &$program) {
                $dependencies = match (true) {
                    str_contains($command[0], 'AND') => explode(' AND ', $command[0]),
                    str_contains($command[0], 'OR') => explode(' OR ', $command[0]),
                    str_contains($command[0], 'LSHIFT') => explode(' LSHIFT ', $command[0]),
                    str_contains($command[0], 'RSHIFT') => explode(' RSHIFT ', $command[0]),
                    str_contains($command[0], 'NOT ') => explode('NOT ', $command[0]),
                    default => [$command[0]],
                };

                $dependencies = array_filter($dependencies, fn ($d) => $d !== '' && !is_numeric($d));
                foreach($dependencies as $dependency) {
                    if ($initiated->doesntContain($dependency)) {
                        return false;
                    }
                }

                $initiated->push($command[1]);
                $program->push($command);

                return true;
            });
        }

        $memory = $program->reduce($this->executeInstruction(...), []);
        ksort($memory);

        return $memory;
    }

    private function executeInstruction(array $memory, array $command): array
    {
        [$operation, $target] = $command;

        if (Str::doesntContain($operation, ' ')) {
            $function = 'EQ';
            [$a, $b] = ['', $operation];
        } else {
            foreach (['AND', 'OR', 'LSHIFT', 'RSHIFT', 'NOT'] as $search) {
                if (str_contains($operation, $search)) {
                    $function = $search;
                    [$a, $b] = array_map('trim', explode("$search ", $operation));
                    break;
                }
            }
        }

        $a = $a === '' ? '' : $this->getFromMemory($memory, $a);
        $b = $b === '' ? '' : $this->getFromMemory($memory, $b);

        $callback = match($function) {
            'EQ' => fn (string $a, int $b) => $b,
            'AND' => fn (int $a, int $b) => $a & $b,
            'OR' => fn (int $a, int $b) => $a | $b,
            'LSHIFT' => fn (int $a, int $b) => $a << $b,
            'RSHIFT' => fn (int $a, int $b) => $a >> $b,
            'NOT' => function (string $a, int $b) {
                $temporary = str_pad(decbin($b), 16, '0', STR_PAD_LEFT);
                $temporary = str_replace('0', '*', $temporary);
                $temporary = str_replace('1', '0', $temporary);
                $temporary = str_replace('*', '1', $temporary);

                return bindec($temporary);
            },
        };
        $memory[$target] = $callback($a, $b);

        return $memory;
    }

    private function getFromMemory($memory, string $address): int
    {
        return (is_numeric($address)) ? (int) $address : $memory[$address];
    }
}
