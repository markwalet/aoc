<?php

namespace Tests\Year2024;

use App\Support\Collections\ArrayWithDefault;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day1Test extends TestCase
{
    #[Test]
    public function it_can_solve_day_1a(): void
    {
        $result = $this->calculateDiff($this->lines());

        $this->assertEquals(2742123, $result);
    }

    #[Test]
    public function it_can_solve_day_1b(): void
    {
        $result = $this->getSimilarityScore($this->lines());

        $this->assertEquals(21328497, $result);
    }

    private function mapLines(Collection $lines): array
    {
        $first = [];
        $second = [];
        $lines->map(fn (string $line) => array_values(array_map('intval', array_filter(explode(' ', $line)))))
            ->each(function (array $data) use (&$first, &$second) {
                $first[] = $data[0];
                $second[] = $data[1];
            });

        return [$first, $second];
    }

    private function calculateDiff(Collection $lines): int
    {
        [$first, $second] = $this->mapLines($lines);
        sort($first);
        sort($second, SORT_DESC);
        $diff = 0;
        for ($i = 0; $i < count($first); $i++) {
            $diff += abs($first[$i] - $second[$i]);
        }

        return $diff;
    }

    private function getSimilarityScore(Collection $lines)
    {
        [$first, $second] = $this->mapLines($lines);

        $second = collect($second)->groupBy(fn (int $number) => $number)
            ->map(fn (Collection $numbers) => $numbers->count());
        $map = new ArrayWithDefault(0, $second);

        return collect($first)->map(fn (int $number) => $number * $map[$number])->sum();
    }

}
