<?php

namespace Tests\Year2024;

use App\Support\Inputs\CharCell;
use App\Support\Inputs\CharMap;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day11Test extends TestCase
{
    #[Test]
    public function it_can_blink(): void
    {
        $data = [125, 17];

        $result = $this->blink($data);
        $this->assertEquals([253000, 1, 7], $result);

        $result = $this->blink($result);
        $this->assertEquals([253, 0, 2024, 14168], $result);
    }

    #[Test]
    public function it_can_solve_day_10a(): void
    {
        $data = collect([890, 0, 1, 935698, 68001, 3441397, 7221, 27]);

        for($i =0; $i < 25; $i++) {
            $data = $this->blink($data);
        }

        $this->assertCount(194782, $data->toArray());
    }

    #[Test]
    public function it_can_solve_day_10b(): void
    {
        ini_set('memory_limit', '-1');
        $cache = [];
        $data = collect([890, 0, 1, 935698, 68001, 3441397, 7221, 27]);

        for($i =0; $i < 75; $i++) {
            dump($i);
            $data = $this->blink($data);
        }

        $this->assertCount(12, $data);
    }

    private function blink(Collection $data): Collection
    {
        $result = collect();
        foreach($data as $number) {
            if ($number === 0) {
                $result[] = 1;
            } elseif (strlen($number) % 2 === 0) {
                [$first, $second] = str_split($number, strlen($number) / 2);
                $result[] = (int)$first;
                $result[] = (int)$second;
            } else {
                $result[] = $number * 2024;
            }
        }

        return $result;
    }
}
