<?php

namespace App\Support\Collections;

use ArrayAccess;
use ArrayObject;

class ArrayWithDefault extends ArrayObject implements ArrayAccess
{
    public function __construct(private readonly int|string|bool|array $default, private object|array $items = [], int $flags = 0, string $iteratorClass = "ArrayIterator")
    {
        parent::__construct($items, $flags, $iteratorClass);
    }

    public function offsetExists(mixed $offset): bool
    {
        return true;
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->items[$offset] ?? $this->default;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->items[$offset] = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->items[$offset]);
    }

    public function toArray()
    {
        return $this->items;
    }

    public function count(): int {
        return count($this->items);
    }
}
