<?php

namespace Tests\Year2016;

use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day4Test extends TestCase
{
    private array $rotateMap = [
        'a' => 'b',
        'b' => 'c',
        'c' => 'd',
        'd' => 'e',
        'e' => 'f',
        'f' => 'g',
        'g' => 'h',
        'h' => 'i',
        'i' => 'j',
        'j' => 'k',
        'k' => 'l',
        'l' => 'm',
        'm' => 'n',
        'n' => 'o',
        'o' => 'p',
        'p' => 'q',
        'q' => 'r',
        'r' => 's',
        's' => 't',
        't' => 'u',
        'u' => 'v',
        'v' => 'w',
        'w' => 'x',
        'x' => 'y',
        'y' => 'z',
        'z' => 'a',
        ' ' => ' ',
    ];

    #[Test]
    public function it_can_validate_a_room(): void
    {
        $this->assertTrue($this->validate('aaaaa-bbb-z-y-x-123[abxyz]'));
        $this->assertTrue($this->validate('a-b-c-d-e-f-g-h-987[abcde]'));
        $this->assertTrue($this->validate('not-a-real-room-404[oarel]'));
        $this->assertFalse($this->validate('totally-real-room-200[decoy]'));
    }

    #[Test]
    public function it_can_rotate_a_room(): void
    {
        $this->assertEquals('very encrypted name', $this->rotate('qzmt-zixmtkozy-ivhz-343[12]'));
    }

    #[Test]
    public function it_can_solve_day_4a(): void
    {
        $sum = $this->lines()
            ->filter($this->validate(...))
            ->sum(function (string $line) {
                [, $room,] = $this->parse($line);

                return $room;
            });

        $this->assertEquals(278221, $sum);
    }

    #[Test]
    public function it_can_solve_day_4b(): void
    {
        $line = $this->lines()
            ->filter($this->validate(...))
            ->firstWhere(fn (string $line) => $this->rotate($line) === 'northpole object storage');
        [,$room,] = $this->parse($line);

        $this->assertEquals(267, $room);
    }

    private function validate(string $room): bool
    {
        [$contents, , $checksum] = $this->parse($room);

        return collect(str_split(implode('', $contents)))
                ->groupBy(fn (string $char) => $char)
                ->map(fn (Collection $chars) => count($chars))
                ->sortKeys()
                ->sortDesc()
                ->take(5)
                ->keys()
                ->join('') === $checksum;
    }

    private function rotate(string $room): string
    {
        [$contents, $room,] = $this->parse($room);

        $shift = $room % 26;

        $contents = join(' ', $contents);
        for ($i = 0; $i < $shift; $i++) {

            for($j = 0; $j < strlen($contents); $j++) {
                $contents[$j] = $this->rotateMap[$contents[$j]];
            }
        }

        return $contents;
    }

    private function parse(string $room): array
    {
        [$contents, $checksum] = explode('[', $room);
        $checksum = substr($checksum, 0, -1);
        $contents = explode('-', $contents);
        $room = (int) array_pop($contents);

        return [$contents, $room, $checksum];
    }
}
