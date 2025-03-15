<?php

namespace Tests\Year2015;

use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day14Test extends TestCase
{
    #[Test]
    public function it_can_solve_day_14_examples(): void
    {
        $reindeer = $this->parse($this->lines('example'));

        $positionResult = $this->simulate($reindeer, 1000, 'position');
        $scoreResult = $this->simulate($reindeer, 1000, 'score');
        $this->assertEquals([
            'Comet' => 1120,
            'Dancer' => 1056,
        ], $positionResult);
        $this->assertEquals([
            'Comet' => 312,
            'Dancer' => 689,
        ], $scoreResult);
    }

    #[Test]
    public function it_can_solve_day_14a(): void
    {
        $reindeer = $this->parse($this->lines());

        $result = $this->simulate($reindeer, 2503, 'position');
        $max = max($result);

        $this->assertEquals(2640, $max);
    }

    #[Test]
    public function it_can_solve_day_14b(): void
    {
        $reindeer = $this->parse($this->lines());

        $result = $this->simulate($reindeer, 2503, 'score');
        $max = max($result);

        $this->assertEquals(1102, $max);
    }

    private function simulate(array $reindeer, int $steps, string $field): array
    {
        for ($i = 0; $i < $steps; $i++) {
            $topPosition = 0;
            for ($j = 0; $j < count($reindeer); $j++) {
                $mode = $reindeer[$j]['mode'];

                if ($reindeer[$j]['duration'] === $reindeer[$j]['interval'][$mode]) {
                    $reindeer[$j]['duration'] = 0;
                    $reindeer[$j]['mode'] = $mode = match ($mode) {
                        'fly' => 'sleep',
                        'sleep' => 'fly',
                    };
                }

                if ($mode === 'fly') {
                    $reindeer[$j]['position'] += $reindeer[$j]['speed'];
                }

                $reindeer[$j]['duration']++;
                $topPosition = max($topPosition, $reindeer[$j]['position']);
            }

            for ($j = 0; $j < count($reindeer); $j++) {
                if ($reindeer[$j]['position'] === $topPosition) {
                    $reindeer[$j]['score']++;
                }
            }
        }

        return collect($reindeer)->mapWithKeys(fn (array $r) => [$r['name'] => $r[$field]])->toArray();
    }

    private function parse(Collection $lines): array
    {
        return $lines->map(function (string $line) {
            [$name, , , $speed, , , $duration, , , , , , , $sleep,] = explode(' ', $line);

            return [
                'name' => $name,
                'speed' => (int) $speed,
                'score' => 0,

                'position' => 0,
                'mode' => 'fly',
                'duration' => 0,

                'interval' => [
                    'sleep' => (int) $sleep,
                    'fly' => (int) $duration,
                ],
            ];
        })->values()->toArray();
    }
}
