<?php

namespace Tests\Year2016;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day6Test extends TestCase
{
    #[Test]
    public function it_can_solve_day_6a(): void
    {
        $lines = $this->lines();
        $columnCount = strlen($lines[0]);
        $result = '';

        for ($i = 0; $i < $columnCount; ++$i) {
            $result .= $lines->map(fn (string $line) => $line[$i])->groupBy(fn (string $char) => $char)->map(fn ($chars) => count($chars))->sortDesc()->keys()->first();
        }
        $this->assertSame('qzedlxso', $result);
    }
    #[Test]
    public function it_can_solve_day_6b(): void
    {
        $lines = $this->lines();
        $columnCount = strlen($lines[0]);
        $result = '';

        for ($i = 0; $i < $columnCount; ++$i) {
            $result .= $lines->map(fn (string $line) => $line[$i])->groupBy(fn (string $char) => $char)->map(fn ($chars) => count($chars))->sort()->keys()->first();
        }
        $this->assertSame('ucmifjae', $result);
    }
}
