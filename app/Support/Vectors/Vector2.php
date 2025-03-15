<?php

namespace App\Support\Vectors;

class Vector2
{
    public function __construct(public float $x, public float $y)
    {
    }

    public function subtract(Vector2 $other): Vector2
    {
        return new Vector2($this->x - $other->x, $this->y - $other->y);
    }

    public function add(Vector2 $other): Vector2
    {
        return new Vector2($this->x + $other->x, $this->y + $other->y);
    }

    public function move(Vector2 $other): Vector2
    {
        $this->x += $other->x;
        $this->y += $other->y;

        return $this;
    }

    public function multiply(int $factor): Vector2
    {
        return new Vector2($this->x * $factor, $this->y * $factor);
    }

    public function dump(): void
    {
        dump("($this->x, $this->y)");
    }

    public function is(Vector2|int $other, int|null $y = null): bool
    {
        $other = $other instanceof Vector2 ? $other : new Vector2($other, $y);

        return $this->x === $other->x && $this->y === $other->y;
    }

    public function manhattan(): int
    {
        return abs($this->x) + abs($this->y);
    }
}
