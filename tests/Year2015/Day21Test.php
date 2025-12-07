<?php

namespace Tests\Year2015;

use Generator;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Year2015\Support\Player;
use Tests\Year2015\Support\Weapon;

class Day21Test extends TestCase
{
    #[Test]
    public function it_can_solve_day_21(): void
    {
        $player = new Player(100, 0, 0);
        $boss = new Player(109, 8, 2);
        $minCostToWin = PHP_INT_MAX;
        $maxCostToLose = 0;
        foreach ($this->upgradeGenerator() as $upgrades) {
            if ($this->simulateBattle($player, $boss, $upgrades)) {
                $minCostToWin = min($minCostToWin, array_sum(array_map(fn (Weapon $w) => $w->cost, $upgrades)));
            } else {
                $maxCostToLose = max($maxCostToLose, array_sum(array_map(fn (Weapon $w) => $w->cost, $upgrades)));
            }
        }

        $this->assertEquals(111, $minCostToWin);
        $this->assertEquals(188, $maxCostToLose);
    }

    private function upgradeGenerator(): Generator
    {
        $weapons = [
            new Weapon(8, 4, 0),
            new Weapon(10, 5, 0),
            new Weapon(25, 6, 0),
            new Weapon(40, 7, 0),
            new Weapon(74, 8, 0),
        ];
        $armors = [
            new Weapon(0, 0, 0), // Extra
            new Weapon(13, 0, 1),
            new Weapon(31, 0, 2),
            new Weapon(53, 0, 3),
            new Weapon(75, 0, 4),
            new Weapon(102, 0, 5),
        ];
        $rings = [
            new Weapon(25, 1, 0),
            new Weapon(50, 2, 0),
            new Weapon(100, 3, 0),
            new Weapon(20, 0, 1),
            new Weapon(40, 0, 2),
            new Weapon(80, 0, 3),
        ];
        foreach ($weapons as $weapon) {
            foreach ($armors as $armor) {
                yield [$weapon, $armor];

                // With armor, with rings
                for ($i = 0; $i < count($rings); $i++) {
                    yield [$weapon, $armor, $rings[$i]];
                    yield [$weapon, $armor, $rings[$i]];

                    for ($j = 0; $j < count($rings); $j++) {
                        if ($i !== $j) {
                            yield [$weapon, $armor, $rings[$i], $rings[$j]];
                        }
                    }
                }
            }
        }
    }

    private function simulateBattle(Player $player, Player $boss, array $upgrades = []): bool
    {
        $player = clone $player;
        $boss = clone $boss;
        foreach ($upgrades as $u) {
            $player->armor += $u->armor;
            $player->damage += $u->damage;
        }
        $playerMoves = ceil($boss->health / max($player->damage - $boss->armor, 1));
        $bossMoves = ceil($player->health / max($boss->damage - $player->armor, 1));

        return $playerMoves <= $bossMoves;
    }
}
