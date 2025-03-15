<?php

namespace Tests\Year2015;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day12Test extends TestCase
{
    #[Test]
    public function it_can_sum_up_numbers(): void
    {
        $this->assertEquals(6, $this->sum('[1,2,3]'));
        $this->assertEquals(6, $this->sum('{"a":2,"b":4}'));
        $this->assertEquals(3, $this->sum('[[[3]]]'));
        $this->assertEquals(3, $this->sum('{"a":{"b":4},"c":-1}'));
        $this->assertEquals(0, $this->sum('{"a":[-1,1]}'));
        $this->assertEquals(0, $this->sum('[-1,{"a":1}]'));
        $this->assertEquals(0, $this->sum('[]'));
        $this->assertEquals(0, $this->sum('{}'));
        $this->assertEquals(6, $this->sum('[1,2,3]', 'red'));
        $this->assertEquals(4, $this->sum('[1,{"c":"red","b":2},3]', 'red'));
        $this->assertEquals(0, $this->sum('{"d":"red","e":[1,2,3,4],"f":5}', 'red'));
        $this->assertEquals(6, $this->sum('[1,"red",5]', 'red'));
    }

    #[Test]
    public function it_can_solve_day_12a(): void
    {
        $result = $this->sum($this->lines()[0]);

        $this->assertEquals(119433, $result);
    }

    #[Test]
    public function it_can_solve_day_12b(): void
    {
        $result = $this->sum($this->lines()[0], 'red');

        $this->assertEquals(68466, $result);
    }

    private function sum(string|array $input, string|null $ignore = null): int
    {
        $document = is_array($input) ? $input : json_decode($input, true);
        if ($ignore !== null && array_is_list($document) === false && in_array($ignore, $document)) {
            return 0;
        }

        return array_reduce($document, function ($sum, $value) use ($ignore) {
            if (is_int($value)) {
                return $sum + $value;
            }
            if (is_array($value)) {
                if ($ignore !== null && array_is_list($value) === false && in_array($ignore, $value)) {

                    return $sum;
                }
                return $sum + $this->sum($value, $ignore);
            }

            return $sum;
        }, 0);
    }
}
