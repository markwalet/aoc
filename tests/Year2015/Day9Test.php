<?php

namespace Tests\Year2015;

use App\Support\Graph\Graph;
use App\Support\Graph\Node;
use App\Support\Graph\TravellingSalesmanSolver;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day9Test extends TestCase
{
    #[Test]
    public function it_can_solve_day_9a_example(): void
    {
        $graph = $this->graph('example');
        $solver = new TravellingSalesmanSolver($graph);
        $score = $solver->shortestRoute();

        $this->assertEquals(605, $score);
    }
    #[Test]
    public function it_can_solve_day_9b_example(): void
    {
        $graph = $this->graph('example');
        $solver = new TravellingSalesmanSolver($graph);
        $score = $solver->longestRoute();

        $this->assertEquals(982, $score);
    }

    #[Test]
    public function it_can_solve_day_9a(): void
    {
        $graph = $this->graph();
        $solver = new TravellingSalesmanSolver($graph);
        $score = $solver->shortestRoute();

        $this->assertEquals(251, $score);
    }

    #[Test]
    public function it_can_solve_day_9b(): void
    {
        $graph = $this->graph();
        $solver = new TravellingSalesmanSolver($graph);
        $score = $solver->longestRoute();

        $this->assertEquals(898, $score);
    }

    private function graph(string|null $variant = null): Graph
    {
        $graph = new Graph();
        $lines = $this->lines($variant);
        $this->addLocations($graph, $lines);
        $this->addDistances($graph, $lines);

        return $graph;
    }

    private function addLocations(Graph $graph, Collection $lines): void
    {
        $lines
            ->flatMap(function (string $line) {
                [$places,] = explode(' = ', $line);

                return explode(' to ', $places);
            })
            ->unique()
            ->each(fn (string $location) => $graph->addNode(new Node($location)));
    }

    private function addDistances(Graph $graph, Collection $lines): void
    {
        $lines->each(function (string $line) use ($graph) {
            [$places, $distance] = explode(' = ', $line);
            [$from, $to] = array_map(fn (string $loc) => $graph->get($loc), explode(' to ', $places));

            $from->addNeighbour($to, $distance);
            $to->addNeighbour($from, $distance);
        });
    }
}
