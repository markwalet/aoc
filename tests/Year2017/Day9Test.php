<?php

namespace Tests\Year2017;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day9Test extends TestCase
{
    #[Test]
    public function it_can_count_groups(): void
    {
        $this->assertEquals(1, $this->scoreGroup('{}')[0]);
        $this->assertEquals(6, $this->scoreGroup('{{{}}}')[0]);
        $this->assertEquals(5, $this->scoreGroup('{{},{}}')[0]);
        $this->assertEquals(16, $this->scoreGroup('{{{},{},{{}}}}')[0]);
        $this->assertEquals(1, $this->scoreGroup('{<a>,<a>,<a>,<a>}')[0]);
        $this->assertEquals(9, $this->scoreGroup('{{<ab>},{<ab>},{<ab>},{<ab>}}')[0]);
        $this->assertEquals(9, $this->scoreGroup('{{<!!>},{<!!>},{<!!>},{<!!>}}')[0]);
        $this->assertEquals(9, $this->scoreGroup('{{<!!>},{<!!>},{<!!>},{<!!>}}')[0]);
        $this->assertEquals(3, $this->scoreGroup('{{<a!>},{<a!>},{<a!>},{<ab>}}')[0]);
        $this->assertEquals(10, $this->scoreGroup('{<{o"i!a,<{i<a>}')[1]);
    }

    #[Test]
    public function it_can_solve_day_9(): void
    {
        [$result, $garbageCount] = $this->scoreGroup($this->lines()[0]);

        $this->assertEquals(12396, $result);
        $this->assertEquals(6346, $garbageCount);
    }

    private function scoreGroup(string $stream): array
    {
        $content = substr($stream, 1, strlen($stream) - 2);
        $depth = 1;
        $score = 1;
        $inGarbage = false;
        $garbageCount = 0;

        for ($i = 0; $i < strlen($content); $i++) {
            if ($content[$i] === '!') {
                $i++;
                continue;
            }
            if ($inGarbage) {
                if ($content[$i] === '>') {
                    $inGarbage = false;
                } else {
                    $garbageCount++;
                }
            } else {
                switch ($content[$i]) {
                    case '<':
                        $inGarbage = true;
                        break;
                    case '{':
                        $depth++;
                        break;
                    case '}':
                        $score += $depth;
                        $depth--;
                        break;
                    case ',':
                        break;
                    default:
                        throw new \RuntimeException('Unexpected character: ' . $content[$i]);
                }
            }
        }

        return [$score, $garbageCount];
    }
}
