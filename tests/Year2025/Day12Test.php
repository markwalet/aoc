<?php

namespace Tests\Year2025;

use App\Support\Inputs\CharMap;
use App\Support\Vectors\Vector2;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Year2025\Support\Region;
use Tests\Year2025\Support\Shape;

class Day12Test extends TestCase
{
    #[Test]
    public function it_can_solve_day_12()
    {
        $input = $this->puzzleInput();
        [$shapes, $regions] = $this->parseInput($input);

        $result = $this->solve($shapes, $regions);
        $this->assertEquals(487, $result);
    }

    /**
     * @param array<int, Shape> $shapes
     * @param array<int, Region> $regions
     * @return int
     */
    private function solve(array $shapes, array $regions): int
    {
        $solvableCount = 0;

        foreach ($regions as $region) {
            $totalShapeSize = Collection::make($region->shapeRequirements)
                ->map(fn (int $count, int $shapeId) => $count * $shapes[$shapeId]->size)
                ->sum();

            if ($totalShapeSize > $region->size) {
                continue; // Cannot fit if total size exceeds region size
            }
            // Assume that all shapes are 3x3. Try an easy fit first:
            $maxPresents = floor($region->width / 3) * floor($region->height / 3);
            if (array_sum($region->shapeRequirements) <= $maxPresents) {
                $solvableCount++;
                continue;
            }


            $presents = [];
            foreach ($region->shapeRequirements as $shapeId => $count) {
                for ($i = 0; $i < $count; $i++) {
                    $presents[] = $shapes[$shapeId];
                }
            }

            usort($presents, fn (Shape $a, Shape $b) => $b->size <=> $a->size);

            if ($this->canFit(clone $region->map, $presents)) {
                $solvableCount++;
            }
        }

        return $solvableCount;
    }

    /**
     * @param CharMap $map
     * @param array<int, Shape> $presents
     * @return bool
     */
    private function canFit(CharMap $map, array $presents): bool
    {
        if (empty($presents)) {
            return true;
        }

        $present = array_shift($presents);

        for ($y = 0; $y < $map->height; $y++) {
            for ($x = 0; $x < $map->width; $x++) {
                foreach ($present->getTransformations() as $transformation) {
                    if ($this->canPlace($map, $transformation, $x, $y)) {
                        $this->place($map, $transformation, $x, $y);

                        if ($this->canFit($map, $presents)) {
                            return true;
                        }

                        $this->remove($map, $transformation, $x, $y); // Backtrack
                    }
                }
            }
        }

        return false;
    }

    /**
     * @param CharMap $map
     * @param array<int, Vector2> $points
     * @param int $offsetX
     * @param int $offsetY
     * @return bool
     */
    private function canPlace(CharMap $map, array $points, int $offsetX, int $offsetY): bool
    {
        foreach ($points as $point) {
            $newX = $offsetX + $point->x;
            $newY = $offsetY + $point->y;

            if (!$map->has($newY, $newX) || $map->get($newY, $newX) === true) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param CharMap $map
     * @param array<int, Vector2> $points
     * @param int $offsetX
     * @param int $offsetY
     */
    private function place(CharMap $map, array $points, int $offsetX, int $offsetY): void
    {
        foreach ($points as $point) {
            $map->replace($offsetY + $point->y, $offsetX + $point->x, true);
        }
    }

    /**
     * @param CharMap $map
     * @param array<int, Vector2> $points
     * @param int $offsetX
     * @param int $offsetY
     */
    private function remove(CharMap $map, array $points, int $offsetX, int $offsetY): void
    {
        foreach ($points as $point) {
            $map->replace($offsetY + $point->y, $offsetX + $point->x, false);
        }
    }

    private function parseInput(string $input): array
    {
        $parts = explode(PHP_EOL.PHP_EOL, $input);
        $shapes = [];
        $regions = [];
        $regionInput = array_pop($parts);

        foreach($parts as $p) {
            $partLines = explode(PHP_EOL, trim($p));
            array_shift($partLines);
            $map = new CharMap(collect($partLines)->map(fn (string $line) => str_split($line))->toArray());
            $points = [];
            for($y = 0; $y < $map->height; $y++) {
                for($x = 0; $x < $map->width; $x++) {
                    if ($map->get($x, $y) === '#') {
                        $points[] = new Vector2($x, $y);
                    }
                }
            }
            $shapes[] = new Shape($points);
        }

        foreach(array_filter(explode(PHP_EOL, $regionInput)) as $regionLine) {
            [$size, $requirements] = explode(': ', $regionLine);
            [$width, $height] = explode('x', $size);
            $shapeCounts = array_map(fn (string $l) => (int) $l, explode(' ', $requirements));

            $regions[] = new Region(
                $width,
                $height,
                $shapeCounts,
            );
        }

        // Parse shapes
        return [$shapes, $regions];
    }
}
