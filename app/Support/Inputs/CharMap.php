<?php

namespace App\Support\Inputs;

use RuntimeException;

class CharMap
{
    public readonly int $height;
    public readonly int $width;

    public function __construct(public array $lines)
    {
        $this->height = count($this->lines);
        $this->width = count($this->lines[0]);
    }

    public static function fromSize(int $width, int $height, string|int|bool $fill): static
    {
        $row = array_fill(0, $width, $fill);

        return new self(array_fill(0, $height, $row));
    }

    public function get(int $row, int $column): string|int|bool|null
    {
        return $this->lines[$row][$column];
    }

    public function has(int $row, int $column): bool
    {
        return array_key_exists($row, $this->lines) && array_key_exists($column, $this->lines[$row]);
    }

    public function getCell(int $row, int $column): CharCell|null
    {
        return new CharCell($this->lines[$row][$column], $row, $column);
    }

    public function replace(int $row, int $column, string|int|bool $replacement): void
    {
        $this->lines[$row][$column] = $replacement;
    }

    public function count(string|int|bool $search): int
    {
        return array_sum(array_map(fn (array $row) => count(array_filter($row, fn (string|int|bool $char) => $char === $search)), $this->lines));
    }

    public function sum(): int
    {
        return array_sum(array_map(fn (array $row) => array_sum($row), $this->lines));
    }

    /**
     * @param int $row
     * @param int $column
     * @return array<int, CharCell>
     */
    public function getSurrounding(int $row, int $column): array
    {
        $adjacent = [];
        $minRow = max(0, $row - 1);
        $maxRow = min($this->height - 1, $row + 1);
        $minColumn = max(0, $column - 1);
        $maxColumn = min($this->width - 1, $column + 1);
        for ($i = $minRow; $i <= $maxRow; $i++) {
            for ($j = $minColumn; $j <= $maxColumn; $j++) {
                if ($i !== $row || $j < $column || $j >= $column + 1) {
                    $adjacent[] = $this->getCell($i, $j);
                }
            }
        }

        return $adjacent;
    }

    public function fill(CharCell|null $cursor, string $empty = '.', string $wall = '#'): void
    {
        if ($cursor === null || $this->get($cursor->x, $cursor->y) !== $empty) {
            return;
        }

        $this->replace($cursor->x, $cursor->y, $wall);
        foreach ($this->getSurrounding($cursor->x, $cursor->y) as $neighbour) {
            $this->fill($neighbour, $empty, $wall);
        }
    }

    public function dd(): void
    {
        dump("Height: $this->height");
        dump("Width: $this->width");
        dd(implode(PHP_EOL, array_map(fn ($line) => implode('', $line), $this->lines)));
    }

    public function dump(): void
    {

        dump(implode(PHP_EOL, array_map(fn ($line) => implode('', $line), $this->lines)));
    }

    public function echo()
    {
        for ($y = 0; $y < $this->height; $y++) {
            for ($x = 0; $x < $this->width; $x++) {
                $char = match ($this->get($y, $x)) {
                    'L' => '╚',
                    'F' => '╔',
                    '|' => '║',
                    '-' => '─',
                    '7' => '╗',
                    'J' => '╝',
                    default => $this->get($y, $x),
                };
                echo $char;
            }
            echo PHP_EOL;
        }
    }
}

