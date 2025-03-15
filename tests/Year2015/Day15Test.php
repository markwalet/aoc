<?php

namespace Tests\Year2015;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day15Test extends TestCase
{
    private array $exampleData = [
        'Butterscotch' => ['capacity' => -1, 'durability' => -2, 'flavor' => 6, 'texture' => 3, 'calories' => 8],
        'Cinnamon' => ['capacity' => 2, 'durability' => 3, 'flavor' => -2, 'texture' => -1, 'calories' => 3],
    ];
    private array $data = [
        'Sprinkles' => ['capacity' => 2, 'durability' => 0, 'flavor' => -2, 'texture' => 0, 'calories' => 3],
        'Butterscotch' => ['capacity' => 0, 'durability' => 5, 'flavor' => -3, 'texture' => 0, 'calories' => 3],
        'Chocolate' => ['capacity' => 0, 'durability' => 0, 'flavor' => 5, 'texture' => -1, 'calories' => 8],
        'Candy' => ['capacity' => 0, 'durability' => -1, 'flavor' => 0, 'texture' => 5, 'calories' => 8],
    ];

    #[Test]
    public function it_can_assign_a_score_to_a_recipe(): void
    {
        $result = $this->score(['Butterscotch' => 44, 'Cinnamon' => 56], $this->exampleData);

        $this->assertEquals(62842880, $result);
    }

    #[Test]
    public function it_can_count_calories(): void
    {
        $result = $this->calories(['Butterscotch' => 40, 'Cinnamon' => 60], $this->exampleData);

        $this->assertEquals(500, $result);
    }

    #[Test]
    public function it_can_solve_day_15_example(): void
    {
        $result = $this->getMaximumScore($this->exampleData);

        $this->assertEquals(62842880, $result);
    }

    #[Test]
    public function it_can_solve_day_15a(): void
    {
        $result = $this->getMaximumScore($this->data);

        $this->assertEquals(21367368, $result);
    }

    #[Test]
    public function it_can_solve_day_15b(): void
    {
        $result = $this->getMaximumScore($this->data, 500);

        $this->assertEquals(1766400, $result);
    }

    private function getMaximumScore(array $data, int|null $calories = null): int
    {
        $score = 0;
        foreach ($this->recipeGenerator(array_keys($data)) as $recipe) {
            if ($calories === null || $this->calories($recipe, $data) === $calories) {
                $score = max($score, $this->score($recipe, $data));
            }
        }

        return $score;
    }

    private function recipeGenerator(array $ingredients, array $currentRecipe = []): \Generator
    {
        $leftover = 100 - array_sum($currentRecipe);
        if (count($ingredients) === 1) {
            $currentRecipe[$ingredients[0]] = $leftover;

            yield $currentRecipe;
        } else {
            $ingredient = array_pop($ingredients);

            $start = 0;
            $end = $leftover + 1;
            for ($i = $start; $i <= $end; $i++) {
                $currentRecipe[$ingredient] = $i;
                yield from $this->recipeGenerator($ingredients, $currentRecipe);
            }
        }
    }

    private function score(array $recipe, array $data): int
    {
        $score = 1;

        foreach (['capacity', 'durability', 'flavor', 'texture'] as $key) {
            $sum = 0;
            foreach ($recipe as $ingredient => $amount) {
                $sum += $data[$ingredient][$key] * $amount;
            }
            if ($sum <= 0) {
                return 0;
            }
            $score *= $sum;
        }

        return $score;
    }

    private function calories(array $recipe, array $data): int
    {
        $sum = 0;
        foreach ($recipe as $ingredient => $amount) {
            $sum += $data[$ingredient]['calories'] * $amount;
        }

        return $sum;
    }
}
