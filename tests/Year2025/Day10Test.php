<?php

namespace Tests\Year2025;

use App\Support\IntegerSystemSolver;
use Generator;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day10Test extends TestCase
{
    #[Test]
    public function it_can_solve_day_10a(): void
    {
        $points = $this->parse()->map(function (array $line) {
            return $this->fewestPresses($line['state'], $line['switches']);
        })->sum();

        $this->assertEquals(457, $points);
    }

    #[Test]
    public function it_can_solve_day_10b(): void
    {
        ini_set('memory_limit', '-1');
        $points = $this->parse('example')->map(function (array $line) {
            $solution = IntegerSystemSolver::solve($line['joltage'], $line['switches']);
            return $solution ? array_sum($solution) : null;
        })->sum();

        $this->assertEquals(33, $points);
    }

    #[Test]
    public function it_can_solve_day_actual_10b_actual(): void
    {
        ini_set('memory_limit', '-1');
        $points = $this->parse()->map(function (array $line) {
            $solution = IntegerSystemSolver::solve($line['joltage'], $line['switches']);
            return $solution ? array_sum($solution) : null;
        })->sum();

        $this->assertEquals(17576, $points);
    }

    #[Test]
    public function it_can_search_for_the_fewest_presses(): void
    {
        $this->assertEquals(3, $this->fewestPresses('...#.', [
            [0,2,3,4],
            [2,3],
            [0,4],
            [0,1,2],
            [1,2,3,4],
        ]));
    }

    private function fewestPresses(string $goal, array $switches): int
    {
        $states = [
            str_repeat('.', strlen($goal)),
        ];

        $presses = 1;
        while(true) {
            $newStates = [];
            foreach($states as $currentState) {
                foreach($switches as $s) {
                    $state = $currentState;
                    foreach($s as $i) {
                        $state[$i] = $state[$i] === '#' ? '.' : '#';
                    }
                    if ($state === $goal) {
                        return $presses;
                    }
                    $newStates[] = $state;
                }
            }

            $states = $newStates;
            $presses++;
        }
    }

    private function parse(string|null $variant = null): Collection
    {
        return $this->lines($variant)->map(function (string $line) {
            $parts = explode(' ', $line);

            $parsed = [
                'state' => null,
                'switches' => [],
                'joltage' => [],
            ];
            foreach($parts as $p) {
                switch($p[0]) {
                    case '[':
                        $parsed['state'] = substr($p, 1, -1);
                        break;
                    case '(':
                        $parsed['switches'][] = array_map(fn (string $i) => (int) $i, explode(',', substr($p, 1, -1)));
                        break;
                    case '{':
                        $parsed['joltage'] = array_map(fn (string $i) => (int) $i, explode(',', substr($p, 1, -1)));
                        break;
                }
            }
            return $parsed;
        });
    }
}
