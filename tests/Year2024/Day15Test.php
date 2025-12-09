<?php

namespace Tests\Year2024;

use App\Support\Inputs\CharMap;
use App\Support\Navigation\Direction;
use App\Support\Vectors\Vector2;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day15Test extends TestCase
{
    #[Test]
    public function it_can_solve_day_15a(): void
    {
        /** @var CharMap $map */
        /** Collection<int, Direction> $movements */
        [$map, $movements] = $this->parse();

        $cursor = $map->firstWhere('@');
        $movements->each(function (Direction $direction) use ($map, &$cursor) {
            $cursor = $this->executeMovement($map, $cursor, $direction);
        });

        $result = $this->sumBoxScore($map);
        $this->assertEquals(1438161, $result);
    }

    private function sumBoxScore(CharMap $map): int
    {
        $score = 0;
        for($y = 0; $y < $map->height; $y++) {
            for($x = 0; $x < $map->width; $x++) {
                if ($map->get(new Vector2($x, $y)) === 'O') {
                    $score += $y * 100 + $x;
                }
            }
        }

        return $score;
    }

    private function executeMovement(CharMap $map, Vector2 $bot, Direction $direction): Vector2
    {
        $newBot = $bot->add($direction);
        $moving = [
            ['position' => $bot, 'replacement' => '.'],
            ['position' => $newBot, 'replacement' => '@'],
        ];

        $cursor = clone $bot;

        while (true) {
            $cursor->move($direction);

            switch ($map->get($cursor)) {
                case '#': // No space, abort.
                    return $bot;
                case '.': // Execute move.
                    break 2;
                case 'O': // Add box to moving list.
                    $moving[] = [
                        'position' => $cursor->add($direction),
                        'replacement' => 'O',
                    ];
                    break;
            }
        }

        /** @var array{position: Vector2, replacement: string} $p */
        foreach($moving as $p) {
            $map->replace($p['position']->y, $p['position']->x, $p['replacement']);
        }

        return $newBot;
    }

    private function parse(string|null $variant = null): array
    {
        [$rawMap, $instructions] = $this->lines($variant, PHP_EOL.PHP_EOL);

        $map = new CharMap(collect(explode(PHP_EOL, $rawMap))->map(fn (string $line) => str_split($line))->toArray());
        $movements = collect(str_split(str_replace(PHP_EOL, '', $instructions)))->map(fn (string $i) => match ($i) {
            '^' => Direction::UP,
            'v' => Direction::DOWN,
            '<' => Direction::LEFT,
            '>' => Direction::RIGHT,
        });

        return [$map, $movements];
    }

}
