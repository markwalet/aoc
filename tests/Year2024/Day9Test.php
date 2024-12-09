<?php

namespace Tests\Year2024;

use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day9Test extends TestCase
{
    #[Test]
    public function it_can_solve_the_example(): void
    {
        [$expanded, $spaces] = $this->expand($this->lines('example')[0]);

        $expandedA = $this->reduceA($expanded)->toArray();
        $checkSumA = $this->checkSum($expandedA);
        $this->assertEquals('0099811188827773336446555566', implode('', $expandedA));
        $this->assertEquals(1928, $checkSumA);

        $expandedB = $this->reduceB($expanded, $spaces);
        $checkSumB = $this->checkSum($expandedB->toArray());
        $this->assertEquals('00992111777.44.333....5555.6666.....8888..', $expandedB->map(fn (string|null $n) => $n ?? '.')->implode(''));
        $this->assertEquals(2858, $checkSumB);
    }

    #[Test]
    public function it_can_solve_day_9a(): void
    {
        [$expanded,] = $this->expand($this->lines()[0]);
        $expanded = $this->reduceA($expanded)->toArray();
        $checksum = $this->checkSum($expanded);

        $this->assertEquals(6432869891895, $checksum);
    }

    #[Test]
    public function it_can_solve_day_9b(): void
    {
        [$expanded, $spaces] = $this->expand($this->lines()[0]);
        $expanded = $this->reduceB($expanded, $spaces)->toArray();
        $checksum = $this->checkSum($expanded);

        $this->assertEquals(6467290479134, $checksum);
    }

    private function expand(string $input): array
    {
        $input = str_split($input);
        $output = collect();
        $spaces = collect();
        for ($i = 0; $i < count($input); $i++) {
            if ($i % 2 === 0) {
                $output->push(...array_fill(0, $input[$i], $i / 2));
            } else {
                $spaces[count($output)] = $input[$i];
                $output->push(...array_fill(0, $input[$i], null));
            }
        }

        return [$output, collect($spaces)];
    }

    private function checkSum(array $expanded): int
    {
        $sum = 0;
        foreach ($expanded as $i => $number) {
            $sum += $i * $number;
        }

        return $sum;
    }

    private function reduceA(Collection $expanded): Collection
    {
        $expanded = collect($expanded);
        $left = 0;
        $right = count($expanded) - 1;
        while (true) {
            while ($expanded[$left] !== null) {
                $left++;
            }

            while ($expanded[$right] === null) {
                $right--;
            }

            if ($left > $right) {
                return $expanded;
            }

            [$expanded[$left], $expanded[$right]] = [$expanded[$right], $expanded[$left]];
        }
    }

    private function reduceB(Collection $expanded, Collection $spaces): Collection
    {
        $expanded = collect($expanded);
        $right = count($expanded) - 1;
        while (true) {
            if ($right <= 0) {
                return $expanded;
            }
            $blockSize = 0;
            $moving = $expanded[$right];
            if ($moving === null) {
                $right--;
                continue;
            }

            while ($right >= 0 && $moving === $expanded[$right]) {
                $blockSize++;
                $right--;
            }

            $index = $spaces->search(fn (int $size) => $size >= $blockSize);
            if ($index === false || $index > $right) {
                continue;
            }

            $first = 0;
            for ($i = 0; $i < $blockSize; $i++) {
                $first = $index + $i;
                $second = $right + $i + 1;

                [$expanded[$first], $expanded[$second]] = [$expanded[$second], $expanded[$first]];
            }
            $sizeLeft = $spaces[$index] - $blockSize;
            if ($sizeLeft > 0) {
                $spaces[$first + 1] = $sizeLeft;
                $spaces = $spaces->sortKeys();
            }
            unset($spaces[$index]);
        }
    }
}
