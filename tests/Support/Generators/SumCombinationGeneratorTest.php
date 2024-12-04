<?php

namespace Tests\Support\Generators;

use App\Support\Generators\SumCombinationGenerator;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SumCombinationGeneratorTest extends TestCase
{
    #[Test]
    public function it_generate_all_combinations_for_an_array_of_strings(): void
    {
        $generator = SumCombinationGenerator::for([20, 15, 10, 5, 5], 25);

        $result = $generator->generate()->all();

        $this->assertEquals([
            [15, 5, 5],
            [15, 10],
            [20, 5],
            [20, 5],
        ], $result);
    }
}
