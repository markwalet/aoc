<?php

namespace Tests\Year2024;

use App\Support\Vectors\Vector2;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Year2024\Support\Bot;

class Day14Test extends TestCase
{
    #[Test]
    public function it_can_solve_day_14(): void
    {
        $mapSize = new Vector2(101, 103);
        $bots = $this->parse();
        $this->simulate($bots, $mapSize, 100);

        $result = $this->calculateQuadrantSafety($bots, $mapSize);

        $this->assertEquals(215476074, $result);
        // Starting from tick 23, the bots form a pattern on the map every 101 frames.
        //After manually simulating. A Christmas tree pattern appears at frame 6285.
    }

    private function calculateQuadrantSafety(Collection $bots, Vector2 $mapSize): int
    {
        $qX = floor($mapSize->x / 2);
        $qY = floor($mapSize->y / 2);

        return collect([
            'top-left' => $this->filterBots($bots, new Vector2(0, 0), new Vector2($qX, $qY)),
            'top-right' => $this->filterBots($bots, new Vector2($qX + 1, 0), new Vector2($mapSize->x, $qY)),
            'bottom-left' => $this->filterBots($bots, new Vector2(0, $qY + 1), new Vector2($qX, $mapSize->y)),
            'bottom-right' => $this->filterBots($bots, new Vector2($qX + 1, $qY + 1), $mapSize),
        ])->reduce(fn (int $carry, Collection $bots) => $carry * $bots->count(), 1);
    }

    private function filterBots(Collection $bots, Vector2 $min, Vector2 $max): Collection
    {
        return $bots->filter(function (Bot $bot) use ($min, $max) {
            return $bot->position->x >= $min->x
                && $bot->position->x < $max->x
                && $bot->position->y >= $min->y
                && $bot->position->y < $max->y;
        });
    }

    private function simulate(Collection $bots, Vector2 $mapSize, int $ticks): void
    {
        for ($i = 0; $i < $ticks; $i++) {
            $this->tick($bots, $mapSize);
        }
    }

    private function tick(Collection $bots, Vector2 $mapSize): void
    {
        $bots->each(function (Bot $bot) use ($mapSize) {
            $bot->move($mapSize);
        });
    }

    private function parse(string|null $variant = null): Collection
    {
        return $this->lines($variant)->map(function (string $line) {
            preg_match_all('/p=([\d-]+),([\d-]+) v=([\d-]+),([\d-]+)/m', $line, $matches, PREG_SET_ORDER);

            return new Bot(
                position: new Vector2($matches[0][1], $matches[0][2]),
                velocity: new Vector2($matches[0][3], $matches[0][4]),
            );
        });
    }
}
