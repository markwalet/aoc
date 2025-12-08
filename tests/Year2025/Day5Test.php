<?php

namespace Tests\Year2025;

use App\Support\Inputs\CharMap;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Year2025\Support\IngredientList;

class Day5Test extends TestCase
{
    #[Test]
    public function it_can_solve_day_5_example(): void
    {
        $parsed = $this->parse($this->puzzleInput('example'));
        $resultA = $parsed->countFreshIngredients();
        $resultB = $parsed->countAvailableFresh();

        $this->assertEquals(3, $resultA);
        $this->assertEquals(14, $resultB);
    }

    #[Test]
    public function it_can_solve_day_5(): void
    {
        $parsed = $this->parse($this->puzzleInput());
        $resultA = $parsed->countFreshIngredients();
        $resultB = $parsed->countAvailableFresh();

        $this->assertEquals(674, $resultA);
        $this->assertEquals(352509891817881, $resultB);
    }

    private function parse(string $input): IngredientList
    {
        $list = new IngredientList();
        [$rawFreshness, $rawIngredients] = explode(PHP_EOL.PHP_EOL, $input);

        foreach (explode(PHP_EOL, $rawFreshness) as $line) {
            [$start, $end] = explode('-', $line);
            $list->addFreshness((int) $start, (int) $end);
        }

        foreach (explode(PHP_EOL, $rawIngredients) as $line) {
            $list->addIngredient((int) $line);
        }

        return $list;
    }

}
