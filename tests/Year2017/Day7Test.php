<?php

namespace Tests\Year2017;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day7Test extends TestCase
{
    #[Test]
    public function it_can_solve_the_example(): void
    {
        $result = $this->getBottom($this->lines('example'));
        $imbalanced = $this->fixInbalance($this->lines('example'));

        $this->assertEquals('tknk', $result);
        $this->assertequals(60, $imbalanced);
    }

    #[Test]
    public function it_can_solve_day_7a(): void
    {
        $result = $this->getBottom($this->lines());

        $this->assertEquals('bsfpjtc', $result);
    }

    #[Test]
    public function it_can_solve_day_7b(): void
    {
        $result = $this->fixInbalance($this->lines());

        $this->assertEquals(529, $result);
    }

    private function getBottom(Collection $lines): string
    {
        $nodes = $lines->map(fn (string $line) => explode(' ', $line, 2)[0]);
        $holds = $lines->flatMap(fn (string $line) => str_contains($line, '->') ? explode(', ', explode(' -> ', $line, 2)[1]) : []);

        return $nodes->diff($holds)->first();
    }

    private function fixInbalance(Collection $lines): int
    {
        $nodes = $lines->keyBy(fn (string $line) => explode(' ', $line, 2)[0]);

        $weights = collect();
        while ($nodes->isNotEmpty()) {
            foreach ($nodes as $node => $line) {
                [$other, $children] = str_contains($line, '->') ? explode(' -> ', $line, 2) : [$line, ''];
                $children = array_filter(explode(', ', $children));
                [, $weight] = explode(' ', $other);
                $weight = (int) Str::trim($weight, '()');

                foreach ($children as $child) {
                    if ($weights->has($child) === false) {
                        continue(2);
                    }
                }
                $w = array_map(fn (string $c) => $weights[$c]['sum'], $children);
                $weights[$node] = [
                    'weight' => $weight,
                    'sum' => array_sum($w) + $weight,
                    'children' => array_combine($children, $w),
                ];
                unset($nodes[$node]);
            }
        }
        $unbalanced = $weights->filter(function (array $node) use ($weights) {
            return count(array_unique($node['children'])) > 1;
        });

        return $unbalanced->map(function (array $node) use ($unbalanced, $weights) {
            $groups = collect($node['children'])
                ->mapToGroups(fn (int $weight, string $key) => [$weight => $key]);

            [$common, $invalid] = $groups->partition(fn (Collection $group) => $group->count() > 1);
            $common = $common->first()->first();
            $invalid = $invalid->first()->first();
            $diff = $weights[$common]['sum'] - $weights[$invalid]['sum'];
            $originalWeight = $weights[$invalid]['weight'];

            return $unbalanced->has($invalid) ? null : $originalWeight + $diff;
        })->first();
    }
}
