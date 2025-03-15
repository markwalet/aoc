<?php

namespace Tests\Year2015;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day3Test extends TestCase
{

    #[Test]
    public function it_can_simulate_a_simple_route(): void
    {
        $this->assertEquals(2, $this->numberOfHouses('>'));
        $this->assertEquals(4, $this->numberOfHouses('^>v<'));
        $this->assertEquals(2, $this->numberOfHouses('^v^v^v^v^v'));
        $this->assertEquals(3, $this->numberOfHouses('^v', 2), 3);
    }
    #[Test]
    public function it_can_solve_day_3a(): void
    {
        $result = $this->numberOfHouses($this->lines()[0]);

        $this->assertEquals(2592, $result);
    }

    #[Test]
    public function it_can_solve_day_3b(): void
    {
        $result = $this->numberOfHouses($this->lines()[0], 2);

        $this->assertEquals(2360, $result);
    }

    private function numberOfHouses(string $input, int $numberOfAgents = 1): int
    {
        $path = str_split($input);

        $agents = array_fill(0, $numberOfAgents, ['x' => 0, 'y' => 0]);
        $currentAgent = 0;
        $houses = [
            '0-0' => 1,
        ];

        foreach($path as $step) {
            $currentAgent++;

            if ($currentAgent >= $numberOfAgents) {
                $currentAgent = 0;
            }
            switch($step) {
                case '>': $agents[$currentAgent]['x']++; break;
                case '<': $agents[$currentAgent]['x']--; break;
                case '^': $agents[$currentAgent]['y']--; break;
                case 'v': $agents[$currentAgent]['y']++; break;
            }
            $key = $agents[$currentAgent]['x'].'-'.$agents[$currentAgent]['y'];
            array_key_exists($key, $houses) ? $houses[$key]++ : $houses[$key] = 1;
        }

        return count($houses);
    }
}
