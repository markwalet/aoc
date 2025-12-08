<?php

namespace App\Support\Vectors;

class Vector3
{
    public function __construct(public float $x, public float $y, public float $z)
    {
    }

    public function subtract(Vector3 $other): Vector3
    {
        return new Vector3($this->x - $other->x, $this->y - $other->y, $this->z - $other->z);
    }

    public function add(Vector3 $other): Vector3
    {
        return new Vector3($this->x + $other->x, $this->y + $other->y, $this->z + $other->z);
    }

    public function multiply(int $factor): Vector3
    {
        return new Vector3($this->x * $factor, $this->y * $factor, $this->z * $factor);
    }

    public function dump(): void
    {
        dump("($this->x, $this->y, $this->z)");
    }

    public function is(Vector3|int $other, int|null $y = null, int|null $z = null): bool
    {
        $other = $other instanceof Vector3 ? $other : new Vector3($other, $y, $z);

        return $this->x === $other->x && $this->y === $other->y && $this->z === $other->z;
    }

    public function euclideanDistance(Vector3 $other): float
    {
        return sqrt(
            ($this->x - $other->x) ** 2 +
            ($this->y - $other->y) ** 2 +
            ($this->z - $other->z) ** 2
        );
    }
}
