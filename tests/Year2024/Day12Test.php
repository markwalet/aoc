<?php

namespace Tests\Year2024;

use App\Support\Inputs\CharMap;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day12Test extends TestCase
{
    #[Test]
    public function it_can_calculate_the_fence_price(): void
    {
        $map = $this->map('example');
        $price = $this->getFencePrice($map);

        $this->assertEquals(1930, $price);
    }
    #[Test]
    public function it_can_solve_day_12a(): void
    {
        $map = $this->map();
        $price = $this->getFencePrice($map);

        $this->assertEquals(1449902, $price);
    }

    private function getFencePrice(CharMap $map): float|int
    {
        $score = 0;
        for ($y = 0; $y < $map->height; $y++) {
            for ($x = 0; $x < $map->width; $x++) {
                if ($map->get($y, $x) !== strtolower($map->get($y, $x))) {
                    $search = $map->get($y, $x);
                    [$area, $perimeter] = $this->areaSize($map, $y, $x, $search);

                    $score += $perimeter * $area;
                }
            }
        }

        return $score;
    }

    private function areaSize(CharMap $map, int $y, int $x, string $search): array
    {
        $neighbors = [
            [$y - 1, $x],
            [$y + 1, $x],
            [$y, $x + 1],
            [$y, $x - 1],
        ];
        $map->replace($y, $x, strtolower($search));
        $area = 1;
        $perimeter = 0;

        foreach ($neighbors as $neighbor) {
            if ($map->has($neighbor[0], $neighbor[1])) {
                $value = $map->get($neighbor[0], $neighbor[1]);
                if ($value === $search) {
                    [$plusArea, $plusPerimeter] = $this->areaSize($map, $neighbor[0], $neighbor[1], $search);

                    $area += $plusArea;
                    $perimeter += $plusPerimeter;
                } elseif ($value !== strtolower($search)) {
                    $perimeter++;
                }
            } else {
                $perimeter++;
            }
        }

        return [$area, $perimeter];
    }
}
