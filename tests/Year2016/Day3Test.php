<?php

namespace Tests\Year2016;

use App\Support\Vectors\Vector3;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day3Test extends TestCase
{
    #[Test]
    public function it_can_validate_a_triangle(): void
    {
        $this->assertFalse($this->validate(new Vector3(5, 15, 25)));
    }

    #[Test]
    public function it_can_solve_day_3a(): void
    {
        $result = $this->lines()
            ->map(fn (string $line) => new Vector3(...array_map('intval', array_filter(explode(' ', $line)))))
            ->filter(fn (Vector3 $v) => $this->validate($v))
            ->count();

        $this->assertEquals(869, $result);
    }

    #[Test]
    public function it_can_solve_day_3b(): void
    {
        $result = $this->lines()
            ->chunk(3)
            ->flatMap(function (Collection $chunk) {
                $numbers = $chunk->map(fn (string $line) => array_values(array_map('intval', array_filter(explode(' ', $line)))))->values();

                return [
                    new Vector3($numbers[0][0], $numbers[1][0], $numbers[2][0]),
                    new Vector3($numbers[0][1], $numbers[1][1], $numbers[2][1]),
                    new Vector3($numbers[0][2], $numbers[1][2], $numbers[2][2]),
                ];
            })
            ->filter(fn (Vector3 $v) => $this->validate($v))
            ->count();

        $this->assertEquals(1544, $result);
    }

    private function validate(Vector3 $triangle): bool
    {
        return $triangle->x + $triangle->y > $triangle->z
            && $triangle->x + $triangle->z > $triangle->y
            && $triangle->z + $triangle->y > $triangle->x;
    }
}
