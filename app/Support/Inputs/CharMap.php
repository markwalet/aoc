<?php

namespace App\Support\Inputs;

class CharMap
{
    public readonly int $height;
    public readonly int $width;

    public function __construct(public array $lines)
    {
        $this->height = count($this->lines);
        $this->width = strlen($this->lines[0]);
    }

    public function get(int $row, int $column): string|null
    {
        return $this->lines[$row][$column];
    }

    public function getCell(int $row, int $column): CharCell|null
    {
        return new CharCell($this->lines[$row][$column], $row, $column);
    }

    public function replace(int $row, int $column, string $replacement): void
    {
        $this->lines[$row][$column] = $replacement;
        if (is_array($this->lines[$row]))
            dd($row, $column);
    }

    /**
     * @param int $row
     * @param int $start
     * @param int $length
     * @return array<int, CharCell>
     */
    public function getSurrounding(int $row, int $start, int $length = 1): array
    {
        $adjacent = [];
        $minRow = max(0, $row - 1);
        $maxRow = min($this->height - 1, $row + 1);
        $minColumn = max(0, $start - 1);
        $maxColumn = min($this->width - 1, $start + $length);
        for ($i = $minRow; $i <= $maxRow; $i++) {
            for ($j = $minColumn; $j <= $maxColumn; $j++) {
                if ($i !== $row || $j < $start || $j >= $start + $length) {
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
        dd(implode(PHP_EOL, $this->lines));
    }

    public function dump(): void
    {
        dump(implode(PHP_EOL, $this->lines));
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

