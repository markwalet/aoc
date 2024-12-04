<?php

namespace Tests\Support\Generators;

use App\Support\Generators\PermutationGenerator;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PermutationGeneratorTest extends TestCase
{
    #[Test]
    public function it_generate_all_permutation_for_an_array_of_strings(): void
    {
        $generator = PermutationGenerator::for(['a', 'b', 'c']);

        $result = $generator->generate()->all();

        $this->assertEquals([
            ['a', 'b', 'c'],
            ['a', 'c', 'b'],
            ['b', 'a', 'c'],
            ['b', 'c', 'a'],
            ['c', 'a', 'b'],
            ['c', 'b', 'a'],
        ], $result);
    }

    #[Test]
    public function it_ignores_keys(): void
    {
        $generator = PermutationGenerator::for([23 => 'a', 'test' => 'b', 0 => 'c']);

        $result = $generator->generate()->all();

        $this->assertEquals([
            ['a', 'b', 'c'],
            ['a', 'c', 'b'],
            ['b', 'a', 'c'],
            ['b', 'c', 'a'],
            ['c', 'a', 'b'],
            ['c', 'b', 'a'],
        ], $result);
    }

    #[Test]
    public function it_can_limit_the_permutation_size(): void
    {
        $generator = PermutationGenerator::for(['a', 'b', 'c', 'd'])->length(2);

        $result = $generator->generate()->all();

        $this->assertEquals([
            ['a', 'b'],
            ['a', 'c'],
            ['a', 'd'],
            ['b', 'a'],
            ['b', 'c'],
            ['b', 'd'],
            ['c', 'a'],
            ['c', 'b'],
            ['c', 'd'],
            ['d', 'a'],
            ['d', 'b'],
            ['d', 'c'],
        ], $result);
    }

    #[Test]
    public function it_can_handle_multiple_items_with_the_same_value(): void
    {
        $generator = PermutationGenerator::for([true, false, false, false]);

        $result = $generator->generate()->all();

        $this->assertEquals([
            [true, false, false, false],
            [true, false, false, false],
            [true, false, false, false],
            [true, false, false, false],
            [true, false, false, false],
            [true, false, false, false],

            [false, true, false, false],
            [false, true, false, false],
            [false, false, true, false],
            [false, false, false, true],
            [false, false, true, false],
            [false, false, false, true],

            [false, true, false, false],
            [false, true, false, false],
            [false, false, true, false],
            [false, false, false, true],
            [false, false, true, false],
            [false, false, false, true],

            [false, true, false, false],
            [false, true, false, false],
            [false, false, true, false],
            [false, false, false, true],
            [false, false, true, false],
            [false, false, false, true],
        ], $result);
    }
}
