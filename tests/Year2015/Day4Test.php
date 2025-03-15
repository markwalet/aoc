<?php

namespace Tests\Year2015;

use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day4Test extends TestCase
{
    #[Test]
    public function it_can_do_an_example(): void
    {
        $this->assertEquals(1048970, $this->firstOccurrence('00000', 'pqrstuv'));
    }

    #[Test]
    public function it_can_solve_day_4a(): void
    {
        $result = $this->firstOccurrence('00000', 'iwrupvqb');

        $this->assertEquals(346386, $result);
    }

    #[Test]
    #[Group('slow')]
    public function it_can_solve_day_4b(): void
    {
        $result = $this->firstOccurrence('000000', 'iwrupvqb');

        $this->assertEquals(9958218, $result);
    }

    private function firstOccurrence(string $search, string $prefix): int
    {
        $i = 0;
        while (true) {
            $hash = md5($prefix.$i);

            if (Str::startsWith($hash, $search)) {
                return $i;
            }
            $i++;
        }
    }
}
