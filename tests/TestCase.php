<?php

namespace Tests;

use App\Support\Inputs\CharMap;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

abstract class TestCase extends BaseTestCase
{
    public function lines(string|null $variant = null, string $split = PHP_EOL): Collection
    {
        return collect(explode($split, $this->puzzleInput($variant)))
            ->map(fn (string $line) => trim($line))
            ->reject(fn (string $line) => $line === '');
    }

    public function map(string|null $variant = null, string $split = PHP_EOL): CharMap
    {
        return new CharMap($this->lines($variant, $split)->map(fn (string $line) => str_split($line))->toArray());
    }

    private function puzzleInput(string|null $variant = null): string
    {
        return file_get_contents($this->puzzleInputPath($variant));
    }

    private function puzzleInputPath(string|null $variant = null): string
    {
        [, $year, $day] = explode('\\', $this::class);
        $day = Str::replace(['Day', 'Test'], '', $day);
        $variant = $variant === null ? '' : "-$variant";

        return __DIR__."/$year/input/$day$variant.txt";
    }
}
