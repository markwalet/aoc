<?php

namespace Tests\Year2015;

use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day11Test extends TestCase
{
    /**
     * @var string[]
     */
    private array $requiredParts;

    protected function setUp(): void
    {
        parent::setUp();

        $this->requiredParts = array_map(fn (int $i) => chr($i).chr($i + 1).chr($i + 2), range(97, 120));
    }

    #[Test]
    public function it_can_validate_passwords(): void
    {
        $this->assertFalse($this->validate('hijklmmn'));
        $this->assertFalse($this->validate('abbceffg'));
        $this->assertFalse($this->validate('abbcegjk'));
        $this->assertTrue($this->validate('abcdffaa'));
        $this->assertTrue($this->validate('ghjaabcc'));
    }

    #[Test]
    public function it_can_solve_day_11a_example(): void
    {
        $this->assertEquals('abcdffaa', $this->solve('abcdefgh'));
    }

    #[Test]
    public function it_can_solve_day_11a(): void
    {
        $this->assertEquals('hepxxyzz', $this->solve('hepxcrrq'));
    }

    #[Test]
    public function it_can_solve_day_11b(): void
    {
        $this->assertEquals('heqaabcc', $this->solve('hepxxyzz'));
    }

    private function solve(string $current)
    {
        foreach($this->all($current) as $password) {
            if ($this->validate($password)) {
                return $password;
            }
        }
    }

    private function validate(string $password): bool
    {
        return preg_match('/[iol]/m', $password) === 0
            && preg_match_all('/(.)\1+/m', $password) > 1
            && Str::contains($password, $this->requiredParts) === true;
    }

    private function all(string $min)
    {
        $cursor = [
            ord($min[0]),
            ord($min[1]),
            ord($min[2]),
            ord($min[3]),
            ord($min[4]),
            ord($min[5]),
            ord($min[6]),
            ord($min[7]),
        ];

        $tries = 0;
        while(true) {
            $index = 7;
            $cursor[$index]++;
            while ($cursor[$index] > 122) {
                $cursor[$index] = 97;
                $index--;
                $cursor[$index]++;
            }

            $password = chr($cursor[0]).chr($cursor[1]).chr($cursor[2]).chr($cursor[3]).chr($cursor[4]).chr($cursor[5]).chr($cursor[6]).chr($cursor[7]);

            if ($password > $min) {
                yield $password;
            }
        }
    }
}
