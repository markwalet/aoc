<?php

namespace Tests\Year2015;

use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day5Test extends TestCase
{
    #[Test]
    public function it_can_do_an_example(): void
    {
        $this->assertTrue($this->stringIsNice('ugknbfddgicrmopn'));
        $this->assertTrue($this->stringIsNice('aaa'));
        $this->assertFalse($this->stringIsNice('jchzalrnumimnmhp'));
        $this->assertFalse($this->stringIsNice('haegwjzuvuyypxyu'));
        $this->assertFalse($this->stringIsNice('dvszwmarrgswjxmb'));
        $this->assertTrue($this->stringIsReallyNice('qjhvhtzxzqqjkmpb'));
        $this->assertTrue($this->stringIsReallyNice('xxyxx'));
        $this->assertFalse($this->stringIsReallyNice('uurcxstgmygtbstg'));
        $this->assertFalse($this->stringIsReallyNice('ieodomkazucvgmuy'));
    }

    #[Test]
    public function it_can_solve_day_5a(): void
    {
        $result = $this->lines()->sum(fn (string $line) => $this->stringIsNice($line) ? 1 : 0);

        $this->assertEquals(238, $result);
    }

    #[Test]
    public function it_can_solve_day_5b(): void
    {
        $result = $this->lines()->sum(fn (string $line) => $this->stringIsReallyNice($line) ? 1 : 0);

        $this->assertEquals(69, $result);
    }

    private function stringIsNice(string $input): bool
    {
        return preg_match_all('/[aeiou]/m', $input) >= 3
            && preg_match_all('/(.)\1+/m', $input) > 0
            && Str::contains($input, ['ab', 'cd', 'pq', 'xy']) === false;
    }

    private function stringIsReallyNice(string $input)
    {
        $containsPair = false;
        $containsSingleRepeat = false;

        for ($i = 0; $i < strlen($input) - 1; ++$i) {
            if ($containsPair === false) {
                $pair = $input[$i].$input[$i + 1];
                $count = substr_count($input, $pair, $i + 2);
                $containsPair = $count > 0;
            }

            if ($containsSingleRepeat === false) {
                $second = $input[$i + 2] ?? '';
                $containsSingleRepeat = $input[$i] === $second;
            }

            if ($containsPair && $containsSingleRepeat) {
                return true;
            }
        }

        return false;
    }
}
