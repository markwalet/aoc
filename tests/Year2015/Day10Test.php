<?php

namespace Tests\Year2015;

use App\Support\Graph\Graph;
use App\Support\Graph\Node;
use App\Support\Graph\TravellingSalesmanSolver;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day10Test extends TestCase
{
    #[Test]
    public function it_can_solve_day_10a_example(): void
    {
        $this->assertEquals('11', $this->step('1'));
        $this->assertEquals('21', $this->step('11'));
        $this->assertEquals('1211', $this->step('21'));
        $this->assertEquals('111221', $this->step('1211'));
        $this->assertEquals('312211', $this->step('111221'));
        $this->assertEquals('312211', $this->game('1', 5));
    }

    #[Test]
    public function it_can_solve_day_10a(): void
    {
        $result = strlen($this->game('1113122113', 40));

        $this->assertEquals(360154, $result);
    }

    #[Test]
    public function it_can_solve_day_10b(): void
    {
        $result = strlen($this->game('1113122113', 50));

        $this->assertEquals(5103798, $result);
    }

    private function game(string $input, int $iterations): string
    {
        for($i = 0; $i < $iterations; $i++) {
            $input = $this->step($input);
        }

        return $input;
    }

    public function step(string $input): string
    {
        $output = '';
        $previous = $input[0];
        $cursorCount = 0;
        for($i = 0; $i < strlen($input); $i++) {
            $current = $input[$i];

            if ($current === $previous) {
                $cursorCount++;
            } else {
                $output .= $cursorCount.$previous;
                $cursorCount = 1;
                $previous = $current;
            }
        }

        $output .= $cursorCount.$previous;
        return $output;
    }
}
