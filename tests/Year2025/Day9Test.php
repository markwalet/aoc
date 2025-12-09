<?php

namespace Tests\Year2025;

use App\Support\Vectors\Vector2;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day9Test extends TestCase
{
    #[Test]
    public function it_can_solve_day_9(): void
    {
        $points = $this->lines()->map(function (string $line) {
            return new Vector2(...explode(',', $line));
        });

        $squares = $this->generateSquares($points);
        $this->assertEquals(4782896435, $squares->first()['area']);

        $lines = $this->generateLines($points);

        $area = 0;
        foreach ($squares as $square) {
            foreach ($lines as $line) {
                if ($this->checkIntersection($square['points'], $line)) {
                    continue 2;
                }
            }
            $area = $square['area'];
            break;
        }

        $this->assertEquals(1540060480, $area);
    }

    private function generateSquares(Collection $points): Collection
    {
        $squares = collect();
        for ($i = 0; $i < $points->count() - 1; $i++) {
            for ($j = $i + 1; $j < $points->count(); $j++) {
                $a = $points[$i];
                $b = $points[$j];

                $squares->add([
                    'points' => [$a, $b],
                    'area' => (int) (abs($a->x - $b->x) + 1) * (abs($a->y - $b->y) + 1),
                ]);
            }
        }

        return $squares->sortByDesc('area');
    }

    private function generateLines(Collection $points): Collection
    {
        $lines = collect();
        for ($i = 0; $i < $points->count() - 1; $i++) {
            $a = $points[$i];
            $b = $points[$i + 1];
            $lines->add([$a, $b]);
        }

        return $lines;
    }

    private function checkIntersection(array $square, array $line): bool
    {
        return $this->isPointInSquare($square, $line[0])
            || $this->isPointInSquare($square, $line[1])
            || $this->isLineOverSquare($square, $line);
    }

    private function isPointInSquare(array $square, Vector2 $point): bool
    {
        return $point->x > min($square[0]->x, $square[1]->x)
            && $point->x < max($square[0]->x, $square[1]->x)
            && $point->y > min($square[0]->y, $square[1]->y)
            && $point->y < max($square[0]->y, $square[1]->y);
    }

    private function isLineOverSquare(array $square, array $line): bool
    {
        $vertical = $line[0]->x === $line[1]->x;

        $a = $vertical === true
            && $line[0]->x > min($square[0]->x, $square[1]->x)
            && $line[0]->x < max($square[0]->x, $square[1]->x)
            && min($line[0]->y, $line[1]->y) <= min($square[0]->y, $square[1]->y)
            && max($line[0]->y, $line[1]->y) >= max($square[0]->y, $square[1]->y);

        $b = $vertical === false
            && $line[0]->y > min($square[0]->y, $square[1]->y)
            && $line[0]->y < max($square[0]->y, $square[1]->y)
            && min($line[0]->x, $line[1]->x) <= min($square[0]->x, $square[1]->x)
            && max($line[0]->x, $line[1]->x) >= max($square[0]->x, $square[1]->x);

        return $a || $b;
    }
}
