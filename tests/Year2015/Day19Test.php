<?php

namespace Tests\Year2015;

use App\Support\Inputs\CharCell;
use App\Support\Inputs\CharMap;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day19Test extends TestCase
{
    #[Test]
    public function it_can_solve_day_19a_example(): void
    {
        $count = $this->getNumberOfPossibilities(
            $this->lines('example')
        );

        $this->assertEquals(4, $count);
    }

    #[Test]
    public function it_can_solve_day_19a(): void
    {
        $count = $this->getNumberOfPossibilities(
            $this->lines()
        );

        $this->assertEquals(576, $count);
    }

    private function getNumberOfPossibilities(Collection $lines): int
    {
        $replacements = $lines->toArray();
        $start = array_pop($replacements);

        $possibilities = [];
        $replacements = collect($replacements)->map(fn (string $line) => explode(' => ', $line));

        foreach($replacements as $replacement) {
            $offset = 0;
            while(($pos = strpos($start, $replacement[0], $offset)) !== false) {
                $possibilities[] = substr_replace($start, $replacement[1], $pos, strlen($replacement[0]));
                $offset = $pos + 1;
            }
        }

        return count(array_unique($possibilities));
    }
}
