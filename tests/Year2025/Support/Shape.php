<?php

namespace Tests\Year2025\Support;

use App\Support\Vectors\Vector2;

class Shape
{
    public readonly int $size;
    public readonly int $width;
    public readonly int $height;
    private array $transformations;

    public function __construct(public array $points)
    {
        $this->size = count($this->points);

        if (empty($this->points)) {
            $this->width = 0;
            $this->height = 0;
        } else {
            $minX = min(array_map(fn (Vector2 $p) => $p->x, $this->points));
            $minY = min(array_map(fn (Vector2 $p) => $p->y, $this->points));
            $maxX = max(array_map(fn (Vector2 $p) => $p->x, $this->points));
            $maxY = max(array_map(fn (Vector2 $p) => $p->y, $this->points));
            $this->width = $maxX - $minX + 1;
            $this->height = $maxY - $minY + 1;
        }

        $this->generateTransformations();
    }

    public function getTransformations(): array
    {
        return $this->transformations;
    }

    private function generateTransformations(): void
    {
        $points = $this->points;
        $variants = [];

        for ($i = 0; $i < 8; $i++) {
            // Normalize points to top-left corner
            $minX = min(array_map(fn (Vector2 $p) => $p->x, $points));
            $minY = min(array_map(fn (Vector2 $p) => $p->y, $points));
            $normalized = array_map(fn (Vector2 $p) => new Vector2($p->x - $minX, $p->y - $minY), $points);

            // Sort for consistent representation
            usort($normalized, fn (Vector2 $a, Vector2 $b) => [$a->y, $a->x] <=> [$b->y, $b->x]);

            $key = implode(',', array_map(fn (Vector2 $p) => "{$p->x},{$p->y}", $normalized));
            if (!isset($variants[$key])) {
                $variants[$key] = $normalized;
            }


            // Rotate 90 degrees
            $points = array_map(fn (Vector2 $p) => new Vector2(-$p->y, $p->x), $points);

            if ($i === 3) {
                // Flip horizontally
                $points = array_map(fn (Vector2 $p) => new Vector2(-$p->x, $p->y), $this->points);
            }
        }

        $this->transformations = array_values($variants);
    }
}
