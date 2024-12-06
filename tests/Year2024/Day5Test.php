<?php

namespace Tests\Year2024;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day5Test extends TestCase
{
    #[Test]
    public function it_can_solve_day_5a(): void
    {
        [$rules, $updates] = $this->lines()->partition(fn (string $line) => Str::contains($line, '|'));
        $lookup = $this->createLookup($rules);

        $result = $updates
            ->map(fn (string $line) => explode(',', $line))
            ->values()
            ->filter(fn (array $update) => $this->validateUpdate($update, $lookup))
            ->map(fn (array $update) => (int) $update[floor(count($update) / 2)])
            ->sum();

        $this->assertEquals(5991, $result);
    }
    #[Test]
    public function it_can_solve_day_5b(): void
    {
        [$rules, $updates] = $this->lines()->partition(fn (string $line) => Str::contains($line, '|'));
        $lookup = $this->createLookup($rules);

        $result = $updates
            ->map(fn (string $line) => explode(',', $line))
            ->values()
            ->reject(fn (array $update) => $this->validateUpdate($update, $lookup))
            ->map(function (array $update) use($lookup) {

                usort($update, function ($left, $right) use ($lookup) {
                    if (Arr::get($lookup, "$left.$right") === true) {
                        return -1;
                    }
                    if (Arr::get($lookup, "$right.$left") === true) {
                        return 1;
                    }

                    return 0;
                });

                return $update;
            })
            ->map(fn (array $update) => (int) $update[floor(count($update) / 2)])
            ->sum();

        $this->assertEquals(5479, $result);
    }

    private function createLookup(Collection $rules): array
    {
        $rules = $rules->map(fn (string $line) => explode('|', $line));
        $lookup = $rules->flatten()->unique()->mapWithKeys(fn ($page) => [$page => []])->toArray();

        foreach ($rules as $rule) {
            [$left, $right] = $rule;
            $lookup[$left][$right] = true;
        }

        return $lookup;
    }

    private function validateUpdate(array $update, array $lookup): bool
    {
        for ($left = 0; $left < count($update) - 1; $left++) {
            for ($right = $left + 1; $right < count($update); $right++) {
                $leftNumber = $update[$left];
                $rightNumber = $update[$right];
                if (Arr::get($lookup, "$leftNumber.$rightNumber") !== true) {
                    return false;
                }
            }
        }

        return true;
    }
}
