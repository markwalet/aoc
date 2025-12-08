<?php

namespace App\Support;

class UnionFind
{
    private array $parent = [];
    private array $size = [];

    public function makeSet(string $item): void
    {
        if (!isset($this->parent[$item])) {
            $this->parent[$item] = $item;
            $this->size[$item] = 1;
        }
    }

    public function find(string $item): string
    {
        if ($this->parent[$item] === $item) {
            return $item;
        }
        // Path compression
        return $this->parent[$item] = $this->find($this->parent[$item]);
    }

    public function union(string $item1, string $item2): void
    {
        $root1 = $this->find($item1);
        $root2 = $this->find($item2);

        if ($root1 !== $root2) {
            // Union by size
            if ($this->size[$root1] < $this->size[$root2]) {
                [$root1, $root2] = [$root2, $root1]; // swap
            }
            $this->parent[$root2] = $root1;
            $this->size[$root1] += $this->size[$root2];
        }
    }

    public function getSizes(): array
    {
        $sizes = [];
        foreach ($this->parent as $item => $parent) {
            if ($item === $parent) {
                $sizes[] = $this->size[$item];
            }
        }
        return $sizes;
    }

    public function getSetCount(): int
    {
        return count($this->getSizes());
    }
}
