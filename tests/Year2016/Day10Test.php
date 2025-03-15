<?php

namespace Tests\Year2016;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day10Test extends TestCase
{
    #[Test]
    public function it_can_solve_day_10_example(): void
    {
        [$bots, $outputs] = $this->parse($this->lines('example'));

        $this->assertEquals([
            1 => 2,
            2 => 3,
            0 => 5,
        ], $outputs);
    }

    #[Test]
    public function it_can_solve_day_10a(): void
    {
        [$bots,] = $this->parse($this->lines());
        $index = array_search([17, 61], $bots);

        $this->assertEquals(161, $index);
    }

    #[Test]
    public function it_can_solve_day_10(): void
    {
        [,$outputs] = $this->parse($this->lines());
        $result = $outputs[0] * $outputs[1] * $outputs[2];

        $this->assertEquals(133163, $result);
    }

    private function parse(Collection $lines)
    {
        [$initial, $instructions] = $lines->partition(fn ($line) => str_starts_with($line, 'value'));
        $bots = $outputs = [];

        $initial->each(function (string $line) use (&$bots) {
            [, $value, , , , $bot] = array_map('intval', explode(' ', $line));
            $result = array_merge($bots[$bot] ?? [], [$value]);
            sort($result);
            $bots[$bot] = $result;
        });

        $instructions = $instructions->keyBy(function (string $line) {
            [, $bot,] = explode(' ', $line, 3);

            return $bot;
        });

        while ($instructions->isNotEmpty()) {
            $executed = [];
            foreach ($instructions as $bot => $instruction) {
                if (count(Arr::get($bots, $bot, [])) === 2) {
                    $executed[] = $bot;

                    [, , , , , $lowType, $lowTarget, , , , $highType, $highTarget] = explode(' ', $instruction);

                    $lowValue = $bots[$bot][0];
                    $highValue = $bots[$bot][1];
                    switch ($lowType) {
                        case 'bot':
                            $result = array_merge($bots[$lowTarget] ?? [], [$lowValue]);
                            sort($result);
                            $bots[$lowTarget] = $result;
                            break;
                        case 'output':
                            $outputs[$lowTarget] = $lowValue;
                            break;
                    }

                    switch ($highType) {
                        case 'bot':
                            $result = array_merge($bots[$highTarget] ?? [], [$highValue]);
                            sort($result);
                            $bots[$highTarget] = $result;
                            break;
                        default:
                        case 'output':
                            $outputs[$highTarget] = $highValue;
                            break;
                    }
                }
            }
            foreach ($executed as $bot) {
                unset($instructions[$bot]);
            }
        }

        return [$bots, $outputs];
    }
}
