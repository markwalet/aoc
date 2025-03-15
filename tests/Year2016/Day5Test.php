<?php

namespace Tests\Year2016;

use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day5Test extends TestCase
{
    #[Test]
    public function it_can_solve_day_5a(): void
    {
        $door = 'ffykfhsq';
        $password = '';
        $i = 0;

        while(strlen($password) < 8) {
            $hash = md5($door.$i);

            if (str_starts_with($hash, '00000')) {
                $password .= $hash[5];
            }

            $i++;
        }

        $this->assertEquals('c6697b55', $password);
    }

    #[Test]
    public function it_can_solve_day_5b(): void
    {
        $door = 'ffykfhsq';
        $password = '________';
        $found = 0;
        $i = 0;

        while($found < 8) {
            $hash = md5($door.$i);

            if (str_starts_with($hash, '00000') && $hash[5] < strlen($password) && $password[$hash[5]] === '_') {
                $password[$hash[5]] = $hash[6];
                $found++;
            }

            $i++;
        }

        $this->assertEquals('8c35d1ab', $password);
    }
}
