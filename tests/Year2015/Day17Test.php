<?php

namespace Tests\Year2015;

use App\Support\Generators\SumCombinationGenerator;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day17Test extends TestCase
{
    #[Test]
    public function it_can_solve_day_17a(): void
    {
        $containers = $this->lines()->map(fn (string $c) => intval($c));

        $result = SumCombinationGenerator::for($containers, 150)->generate()->count();

        $this->assertEquals(4372, $result);
    }

    #[Test]
    public function it_can_solve_day_17b(): void
    {
        $containers = $this->lines()->map(fn (string $c) => intval($c));

        $result = SumCombinationGenerator::for($containers, 150)
            ->generate()
            ->groupBy(fn (array $combination) => count($combination))
            ->sortKeys()
            ->first()
            ->count();

        $this->assertEquals(4, $result);
    }
}
